<?php

namespace Tests\Browser\Integration;

use App\Models\User;
use App\Models\InfoKantor;
use App\Models\WebPortal;
use App\Models\ContentApproval;
use App\Models\SystemConfiguration;
use App\Models\AuditLog;
use App\Models\Pengaduan;
use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\Wbs;
use App\Models\Faq;
use App\Models\Galeri;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Complete System Test Suite
 * Comprehensive testing for all modules in Portal Inspektorat Papua Tengah
 */
class CompleteSystemTestSuite extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;
    protected $admin;
    protected $contentManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'superadmin@inspektorat.id'
        ]);
        
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@inspektorat.id'
        ]);
        
        $this->contentManager = User::factory()->create([
            'role' => 'content_manager',
            'email' => 'content@inspektorat.id'
        ]);
        
        $this->createComprehensiveTestData();
    }

    private function createComprehensiveTestData()
    {
        // System Configuration
        SystemConfiguration::create([
            'key' => 'app_name',
            'value' => 'Portal Inspektorat Papua Tengah - Test Suite',
            'type' => 'text',
            'group' => 'general',
            'description' => 'Application name',
            'is_public' => true
        ]);

        // Info Kantor
        InfoKantor::create([
            'judul' => 'Complete Test Info Kantor',
            'konten' => 'Comprehensive test content for info kantor',
            'kategori' => 'pengumuman',
            'status' => true,
            'created_by' => $this->admin->id
        ]);

        // Web Portal
        WebPortal::create([
            'nama_portal' => 'Complete Test Portal',
            'deskripsi' => 'Comprehensive test portal',
            'url' => 'https://test.complete.id',
            'kategori' => 'portal',
            'status' => 'active',
            'created_by' => $this->admin->id
        ]);

        // Portal Papua Tengah (News)
        PortalPapuaTengah::create([
            'judul' => 'Complete Test News',
            'slug' => 'complete-test-news',
            'konten' => 'Comprehensive test news content',
            'penulis' => $this->admin->name,
            'kategori' => 'berita',
            'is_published' => true,
            'created_by' => $this->admin->id
        ]);

        // Portal OPD
        PortalOpd::create([
            'nama_opd' => 'Complete Test OPD',
            'singkatan' => 'CTO',
            'deskripsi' => 'Comprehensive test OPD',
            'alamat' => 'Test Address',
            'telepon' => '081234567890',
            'email' => 'test@opd.id',
            'website' => 'https://test.opd.id',
            'status' => 'active',
            'created_by' => $this->admin->id
        ]);

        // WBS
        Wbs::create([
            'nama_pelapor' => 'Complete Test Reporter',
            'email' => 'reporter@test.id',
            'subjek' => 'Complete Test WBS Report',
            'deskripsi' => 'Comprehensive test WBS description',
            'status' => 'pending'
        ]);

        // FAQ
        Faq::create([
            'pertanyaan' => 'Complete Test FAQ Question?',
            'jawaban' => 'Complete test FAQ answer for comprehensive testing',
            'kategori' => 'umum',
            'status' => true,
            'urutan' => 1
        ]);

        // Gallery
        Galeri::create([
            'judul' => 'Complete Test Gallery',
            'deskripsi' => 'Comprehensive test gallery item',
            'kategori' => 'foto',
            'file_media' => 'galeri/complete-test.jpg',
            'status' => true,
            'created_by' => $this->admin->id
        ]);

        // Pelayanan
        Pelayanan::create([
            'nama' => 'Complete Test Service',
            'deskripsi' => 'Comprehensive test service description',
            'prosedur' => 'Test service procedure',
            'persyaratan' => 'Test service requirements',
            'waktu_penyelesaian' => '7 hari kerja',
            'biaya' => 'Gratis',
            'kategori' => 'Audit',
            'status' => true,
            'created_by' => $this->admin->id
        ]);

        // Dokumen
        Dokumen::create([
            'judul' => 'Complete Test Document',
            'deskripsi' => 'Comprehensive test document',
            'kategori' => 'peraturan',
            'tahun' => date('Y'),
            'file_dokumen' => 'dokumen/complete-test.pdf',
            'status' => true,
            'created_by' => $this->admin->id
        ]);

        // Pengaduan
        Pengaduan::create([
            'nama_pengadu' => 'Complete Test Complainant',
            'email' => 'complainant@test.id',
            'subjek' => 'Complete Test Complaint',
            'isi_pengaduan' => 'Comprehensive test complaint content',
            'kategori' => 'pelayanan',
            'status' => 'pending'
        ]);
    }

    /**
     * Test complete system functionality across all modules
     */
    public function testCompleteSystemFunctionality()
    {
        $this->browse(function (Browser $browser) {
            // Test homepage
            $browser->visit('/')
                ->assertSee('Portal Inspektorat')
                ->assertSee('Berita Terbaru')
                ->assertSee('Layanan')
                ->screenshot('complete_system_homepage');

            // Test all public pages
            $publicPages = [
                '/profil' => 'Profil',
                '/berita' => 'Berita',
                '/pelayanan' => 'Layanan',
                '/dokumen' => 'Dokumen',
                '/galeri' => 'Galeri',
                '/faq' => 'FAQ',
                '/kontak' => 'Kontak',
                '/wbs' => 'WBS',
                '/portal-opd' => 'Portal OPD',
                '/info-kantor' => 'Informasi Kantor',
                '/web-portal' => 'Portal Web'
            ];

            foreach ($publicPages as $url => $expectedText) {
                $browser->visit($url)
                    ->assertSee($expectedText)
                    ->pause(500);
            }

            $browser->screenshot('complete_system_public_pages');
        });
    }

    /**
     * Test all admin modules functionality
     */
    public function testCompleteAdminFunctionality()
    {
        $this->browse(function (Browser $browser) {
            // Login as admin
            $browser->loginAs($this->admin)
                ->visit('/admin/dashboard')
                ->assertSee('Dashboard')
                ->screenshot('complete_admin_dashboard');

            // Test all admin modules
            $adminModules = [
                '/admin/portal-papua-tengah' => 'Portal Papua Tengah',
                '/admin/portal-opd' => 'Portal OPD',
                '/admin/wbs' => 'WBS',
                '/admin/pelayanan' => 'Pelayanan',
                '/admin/dokumen' => 'Dokumen',
                '/admin/galeri' => 'Galeri',
                '/admin/faq' => 'FAQ',
                '/admin/info-kantor' => 'Info Kantor',
                '/admin/web-portal' => 'Web Portal',
                '/admin/pengaduan' => 'Pengaduan',
                '/admin/approvals' => 'Persetujuan'
            ];

            foreach ($adminModules as $url => $expectedText) {
                $browser->visit($url)
                    ->assertSee($expectedText)
                    ->pause(500);
            }

            $browser->screenshot('complete_admin_modules');
        });
    }

    /**
     * Test super admin exclusive functionality
     */
    public function testSuperAdminFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->assertSee('Manajemen User')
                ->visit('/admin/configurations')
                ->assertSee('Konfigurasi Sistem')
                ->visit('/admin/audit-logs')
                ->assertSee('Log Audit')
                ->screenshot('complete_super_admin_functionality');
        });
    }

    /**
     * Test CRUD operations across all modules
     */
    public function testCompleteCrudOperations()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin);

            // Test InfoKantor CRUD
            $browser->visit('/admin/info-kantor/create')
                ->type('judul', 'CRUD Test Info Kantor')
                ->type('konten', 'Testing CRUD operations')
                ->select('kategori', 'informasi')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Info Kantor berhasil ditambahkan')
                ->screenshot('complete_crud_info_kantor');

            // Test WebPortal CRUD
            $browser->visit('/admin/web-portal/create')
                ->type('nama_portal', 'CRUD Test Portal')
                ->type('deskripsi', 'Testing CRUD operations')
                ->type('url', 'https://crud.test.id')
                ->select('kategori', 'portal')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Web Portal berhasil ditambahkan')
                ->screenshot('complete_crud_web_portal');

            // Test FAQ CRUD
            $browser->visit('/admin/faq/create')
                ->type('pertanyaan', 'CRUD Test FAQ Question?')
                ->type('jawaban', 'CRUD test FAQ answer')
                ->select('kategori', 'umum')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('FAQ berhasil ditambahkan')
                ->screenshot('complete_crud_faq');
        });
    }

    /**
     * Test search functionality across all modules
     */
    public function testCompleteSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            // Test search in different modules
            $searchTests = [
                '/info-kantor' => ['search' => 'Complete', 'expected' => 'Complete Test Info Kantor'],
                '/web-portal' => ['search' => 'Complete', 'expected' => 'Complete Test Portal'],
                '/berita' => ['search' => 'Complete', 'expected' => 'Complete Test News'],
                '/portal-opd' => ['search' => 'Complete', 'expected' => 'Complete Test OPD'],
                '/faq' => ['search' => 'Complete', 'expected' => 'Complete Test FAQ'],
                '/pelayanan' => ['search' => 'Complete', 'expected' => 'Complete Test Service'],
                '/dokumen' => ['search' => 'Complete', 'expected' => 'Complete Test Document']
            ];

            foreach ($searchTests as $url => $testData) {
                $browser->visit($url)
                    ->type('search', $testData['search'])
                    ->press('Cari')
                    ->pause(1000)
                    ->assertSee($testData['expected']);
            }

            $browser->screenshot('complete_search_functionality');
        });
    }

    /**
     * Test user role access control across all modules
     */
    public function testCompleteRoleAccessControl()
    {
        $this->browse(function (Browser $browser) {
            // Test Content Manager access
            $browser->loginAs($this->contentManager)
                ->visit('/admin/info-kantor')
                ->assertSee('Info Kantor') // Should have access
                ->visit('/admin/users')
                ->assertSee('403') // Should not have access
                ->screenshot('complete_role_content_manager');

            // Test Admin access
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->assertSee('Info Kantor') // Should have access
                ->visit('/admin/audit-logs')
                ->assertSee('403') // Should not have access
                ->screenshot('complete_role_admin');

            // Test Super Admin access
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->assertSee('Manajemen User') // Should have access
                ->visit('/admin/audit-logs')
                ->assertSee('Log Audit') // Should have access
                ->screenshot('complete_role_super_admin');
        });
    }

    /**
     * Test responsive design across all modules
     */
    public function testCompleteResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/berita', '/pelayanan', '/info-kantor', '/web-portal'];

            foreach ($pages as $page) {
                // Test tablet view
                $browser->resize(768, 1024)
                    ->visit($page)
                    ->pause(500);

                // Test mobile view
                $browser->resize(375, 667)
                    ->visit($page)
                    ->pause(500);
            }

            $browser->screenshot('complete_responsive_design');
        });
    }

    /**
     * Test performance across all modules
     */
    public function testCompleteSystemPerformance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            // Load all main pages
            $pages = [
                '/', '/profil', '/berita', '/pelayanan', '/dokumen',
                '/galeri', '/faq', '/kontak', '/wbs', '/portal-opd',
                '/info-kantor', '/web-portal'
            ];

            foreach ($pages as $page) {
                $browser->visit($page)->pause(200);
            }

            $endTime = microtime(true);
            $totalLoadTime = $endTime - $startTime;

            // Assert total load time is reasonable (less than 30 seconds for all pages)
            $this->assertLessThan(30, $totalLoadTime);

            $browser->screenshot('complete_system_performance');
        });
    }

    /**
     * Test data integrity across all modules
     */
    public function testCompleteDataIntegrity()
    {
        $this->browse(function (Browser $browser) {
            // Verify all test data exists and is accessible
            $browser->visit('/info-kantor')
                ->assertSee('Complete Test Info Kantor')
                ->visit('/web-portal')
                ->assertSee('Complete Test Portal')
                ->visit('/berita')
                ->assertSee('Complete Test News')
                ->visit('/portal-opd')
                ->assertSee('Complete Test OPD')
                ->visit('/faq')
                ->assertSee('Complete Test FAQ')
                ->visit('/pelayanan')
                ->assertSee('Complete Test Service')
                ->visit('/dokumen')
                ->assertSee('Complete Test Document');

            // Verify admin can access all data
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->assertSee('Complete Test WBS Report')
                ->visit('/admin/pengaduan')
                ->assertSee('Complete Test Complaint');

            $browser->screenshot('complete_data_integrity');
        });
    }

    /**
     * Test complete system workflow end-to-end
     */
    public function testCompleteSystemWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Public user views content
            $browser->visit('/')
                ->assertSee('Portal Inspektorat')
                ->click('a[href*="info-kantor"]')
                ->assertSee('Complete Test Info Kantor')
                ->screenshot('complete_workflow_step1');

            // Step 2: Admin creates new content
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Workflow Test Content')
                ->type('konten', 'Testing complete workflow')
                ->select('kategori', 'pengumuman')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Info Kantor berhasil ditambahkan')
                ->screenshot('complete_workflow_step2');

            // Step 3: Super Admin monitors via audit logs
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('InfoKantor')
                ->assertSee('created')
                ->screenshot('complete_workflow_step3');

            // Step 4: Public sees updated content
            $browser->visit('/info-kantor')
                ->assertSee('Workflow Test Content')
                ->screenshot('complete_workflow_step4');

            // Step 5: Content approval workflow
            $browser->loginAs($this->contentManager)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Approval Workflow Test')
                ->type('konten', 'Testing approval workflow')
                ->select('kategori', 'informasi')
                ->press('Submit untuk Approval')
                ->pause(2000)
                ->assertSee('disubmit untuk approval')
                ->screenshot('complete_workflow_step5');

            // Step 6: Admin approves content
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->assertSee('Approval Workflow Test')
                ->screenshot('complete_workflow_step6');
        });
    }
}
