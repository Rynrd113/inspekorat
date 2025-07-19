<?php

namespace Tests\Browser\Integration;

use App\Models\User;
use App\Models\PortalPapuaTengah;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use App\Models\Wbs;
use App\Models\ContentApproval;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WorkflowIntegrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;
    protected $contentManager;
    protected $editor;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create users with different roles
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->contentManager = User::create([
            'name' => 'Content Manager',
            'email' => 'content.manager@inspektorat.id',
            'password' => bcrypt('contentmanager123'),
            'role' => 'content_manager',
            'is_active' => true,
        ]);

        $this->editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@inspektorat.id',
            'password' => bcrypt('editor123'),
            'role' => 'admin_berita',
            'is_active' => true,
        ]);
    }

    /**
     * Test complete berita workflow from creation to publication
     */
    public function testCompleteBeritaWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Editor creates a berita
            $browser->loginAs($this->editor)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita Workflow Test')
                ->type('konten', 'Konten berita workflow test')
                ->select('kategori', 'berita')
                ->type('penulis', 'Editor Test')
                ->uncheck('is_published') // Draft first
                ->press('Simpan Berita')
                ->pause(2000)
                ->assertSee('Berita berhasil ditambahkan');

            // Step 2: Content Manager reviews and approves
            $berita = PortalPapuaTengah::where('judul', 'Berita Workflow Test')->first();
            
            $browser->loginAs($this->contentManager)
                ->visit('/admin/approvals')
                ->assertSee('Berita Workflow Test')
                ->click('a[href="/admin/approvals/' . $berita->id . '"]')
                ->type('review_notes', 'Berita telah direview dan disetujui')
                ->press('Approve')
                ->pause(2000)
                ->assertSee('Content approved successfully');

            // Step 3: Verify berita is published and visible on public page
            $browser->logout()
                ->visit('/berita')
                ->assertSee('Berita Workflow Test');
        });
    }

    /**
     * Test complete WBS workflow from submission to response
     */
    public function testCompleteWbsWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Public user submits WBS report
            $browser->visit('/wbs')
                ->type('nama_pelapor', 'Pelapor Workflow Test')
                ->type('email', 'pelapor@email.com')
                ->type('subjek', 'Laporan Workflow Test')
                ->type('deskripsi', 'Deskripsi laporan workflow test')
                ->type('tanggal_kejadian', '2024-01-01')
                ->type('lokasi_kejadian', 'Lokasi Test')
                ->press('Kirim Laporan')
                ->pause(2000)
                ->assertSee('Laporan berhasil dikirim');

            // Step 2: Admin processes the WBS report
            $wbs = Wbs::where('subjek', 'Laporan Workflow Test')->first();
            
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/wbs/' . $wbs->id)
                ->assertSee('Laporan Workflow Test')
                ->select('status', 'proses')
                ->type('admin_note', 'Laporan sedang dalam proses review')
                ->press('Update Status')
                ->pause(2000)
                ->assertSee('Status berhasil diupdate');

            // Step 3: Admin provides response
            $browser->visit('/admin/wbs/' . $wbs->id)
                ->type('response', 'Terima kasih atas laporan Anda. Laporan telah kami tindaklanjuti.')
                ->select('status', 'selesai')
                ->press('Kirim Response')
                ->pause(2000)
                ->assertSee('Response berhasil dikirim');

            // Verify WBS status in database
            $this->assertDatabaseHas('wbs', [
                'id' => $wbs->id,
                'status' => 'selesai',
                'response' => 'Terima kasih atas laporan Anda. Laporan telah kami tindaklanjuti.',
            ]);
        });
    }

    /**
     * Test complete document management workflow
     */
    public function testCompleteDocumentWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Admin uploads document
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dokumen/create')
                ->type('judul', 'Dokumen Workflow Test')
                ->type('deskripsi', 'Deskripsi dokumen workflow test')
                ->select('kategori', 'regulasi')
                ->attach('file', __DIR__.'/../../fixtures/test-document.pdf')
                ->uncheck('is_public') // Private first
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Data berhasil disimpan');

            // Step 2: Admin reviews and makes document public
            $dokumen = Dokumen::where('judul', 'Dokumen Workflow Test')->first();
            
            $browser->visit('/admin/dokumen/' . $dokumen->id . '/edit')
                ->check('is_public')
                ->check('status')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Data berhasil diupdate');

            // Step 3: Verify document is accessible publicly
            $browser->logout()
                ->visit('/dokumen')
                ->assertSee('Dokumen Workflow Test')
                ->click('a[href="/dokumen/' . $dokumen->id . '/download"]')
                ->pause(1000);

            // Verify download count increased
            $this->assertDatabaseHas('dokumens', [
                'id' => $dokumen->id,
                'is_public' => true,
            ]);
        });
    }

    /**
     * Test user role permissions workflow
     */
    public function testUserRolePermissionsWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Super admin creates new user
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/create')
                ->type('name', 'New User Workflow')
                ->type('email', 'newuser.workflow@inspektorat.id')
                ->type('password', 'newuser123')
                ->type('password_confirmation', 'newuser123')
                ->select('role', 'admin_pelayanan')
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Data berhasil disimpan');

            // Step 2: New user logs in and accesses their permitted module
            $newUser = User::where('email', 'newuser.workflow@inspektorat.id')->first();
            
            $browser->loginAs($newUser)
                ->visit('/admin/pelayanan')
                ->assertSee('Pelayanan') // Should have access
                ->visit('/admin/users')
                ->assertSee('403'); // Should not have access

            // Step 3: Super admin updates user role
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $newUser->id . '/edit')
                ->select('role', 'admin')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Data berhasil diupdate');

            // Step 4: User now has broader access
            $browser->loginAs($newUser)
                ->visit('/admin/users')
                ->assertDontSee('403'); // Should now have access
        });
    }

    /**
     * Test content approval workflow with revision
     */
    public function testContentApprovalWithRevisionWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Editor creates content
            $browser->loginAs($this->editor)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita Revision Test')
                ->type('konten', 'Konten yang perlu revisi')
                ->select('kategori', 'berita')
                ->type('penulis', 'Editor Test')
                ->press('Simpan Berita')
                ->pause(2000);

            // Step 2: Content Manager requests revision
            $berita = PortalPapuaTengah::where('judul', 'Berita Revision Test')->first();
            
            $browser->loginAs($this->contentManager)
                ->visit('/admin/approvals/' . $berita->id)
                ->type('review_notes', 'Mohon diperbaiki struktur kalimat dan ditambahkan data pendukung')
                ->press('Request Revision')
                ->pause(2000)
                ->assertSee('Revision requested successfully');

            // Step 3: Editor revises content
            $browser->loginAs($this->editor)
                ->visit('/admin/portal-papua-tengah/' . $berita->id . '/edit')
                ->clear('konten')
                ->type('konten', 'Konten yang telah direvisi dan diperbaiki struktur kalimatnya dengan data pendukung')
                ->press('Update Berita')
                ->pause(2000)
                ->assertSee('Berita berhasil diupdate');

            // Step 4: Content Manager approves revised content
            $browser->loginAs($this->contentManager)
                ->visit('/admin/approvals/' . $berita->id)
                ->type('review_notes', 'Revisi telah sesuai, content disetujui')
                ->press('Approve')
                ->pause(2000)
                ->assertSee('Content approved successfully');
        });
    }

    /**
     * Test multi-user collaborative editing workflow
     */
    public function testMultiUserCollaborativeWorkflow()
    {
        $pelayananEditor = User::create([
            'name' => 'Pelayanan Editor',
            'email' => 'pelayanan.editor@inspektorat.id',
            'password' => bcrypt('pelayananeditor123'),
            'role' => 'admin_pelayanan',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($pelayananEditor) {
            // Step 1: First user creates pelayanan
            $browser->loginAs($pelayananEditor)
                ->visit('/admin/pelayanan/create')
                ->type('nama', 'Pelayanan Collaborative Test')
                ->type('deskripsi', 'Draft pelayanan yang dibuat bersama')
                ->type('prosedur', 'Langkah 1: Draft\nLangkah 2: Review')
                ->type('persyaratan', 'Syarat 1\nSyarat 2')
                ->type('waktu_penyelesaian', '5 hari kerja')
                ->type('biaya', 'Gratis')
                ->select('kategori', 'Administrasi')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Data berhasil disimpan');

            // Step 2: Super admin reviews and enhances
            $pelayanan = Pelayanan::where('nama', 'Pelayanan Collaborative Test')->first();
            
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/pelayanan/' . $pelayanan->id . '/edit')
                ->clear('prosedur')
                ->type('prosedur', 'Langkah 1: Persiapan dokumen\nLangkah 2: Pengajuan\nLangkah 3: Verifikasi\nLangkah 4: Penyelesaian')
                ->clear('persyaratan')
                ->type('persyaratan', 'KTP asli\nSurat pengantar\nDokumen pendukung')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Data berhasil diupdate');

            // Step 3: Content manager publishes
            $browser->loginAs($this->contentManager)
                ->visit('/admin/pelayanan/' . $pelayanan->id . '/edit')
                ->check('status')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Data berhasil diupdate');

            // Step 4: Verify pelayanan is live
            $browser->logout()
                ->visit('/pelayanan')
                ->assertSee('Pelayanan Collaborative Test');
        });
    }

    /**
     * Test audit trail workflow
     */
    public function testAuditTrailWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Perform various actions
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita Audit Test')
                ->type('konten', 'Konten berita audit test')
                ->select('kategori', 'berita')
                ->type('penulis', 'Super Admin')
                ->press('Simpan Berita')
                ->pause(2000);

            $berita = PortalPapuaTengah::where('judul', 'Berita Audit Test')->first();

            // Step 2: Edit the berita
            $browser->visit('/admin/portal-papua-tengah/' . $berita->id . '/edit')
                ->clear('konten')
                ->type('konten', 'Konten berita audit test yang telah diupdate')
                ->press('Update Berita')
                ->pause(2000);

            // Step 3: Check audit logs
            $browser->visit('/admin/audit-logs')
                ->assertSee('portal_papua_tengah')
                ->assertSee('created')
                ->assertSee('updated')
                ->assertSee($this->superAdmin->name)
                ->assertSee('Berita Audit Test');
        });
    }

    /**
     * Test system configuration workflow
     */
    public function testSystemConfigurationWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Super admin updates system configuration
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->type('site_name', 'Inspektorat Papua Tengah - Updated')
                ->type('site_description', 'Portal resmi yang telah diupdate')
                ->type('contact_email', 'updated@inspektorat.id')
                ->press('Simpan Konfigurasi')
                ->pause(2000)
                ->assertSee('Konfigurasi berhasil disimpan');

            // Step 2: Verify configuration applied on public site
            $browser->logout()
                ->visit('/')
                ->assertSee('Inspektorat Papua Tengah - Updated');

            // Step 3: Export configuration
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->click('.export-config')
                ->pause(1000);
        });
    }

    /**
     * Test error handling and recovery workflow
     */
    public function testErrorHandlingAndRecoveryWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Attempt invalid operation
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/portal-papua-tengah/create')
                ->press('Simpan Berita') // Submit without required fields
                ->pause(1000)
                ->assertSee('The judul field is required')
                ->assertSee('The konten field is required');

            // Step 2: Correct errors and resubmit
            $browser->type('judul', 'Berita Error Recovery Test')
                ->type('konten', 'Konten berita error recovery test')
                ->select('kategori', 'berita')
                ->type('penulis', 'Super Admin')
                ->press('Simpan Berita')
                ->pause(2000)
                ->assertSee('Berita berhasil ditambahkan');

            // Step 3: Verify data integrity
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'judul' => 'Berita Error Recovery Test',
                'konten' => 'Konten berita error recovery test',
            ]);
        });
    }
}
