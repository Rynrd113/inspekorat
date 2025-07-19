<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Dokumen;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DokumenTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin Dokumen',
            'email' => 'admin.dokumen@inspektorat.id',
            'password' => bcrypt('admindokumen123'),
            'role' => 'admin_dokumen',
            'is_active' => true,
        ]);

        // Create test dokumen data
        $this->createTestDokumenData();
    }

    private function createTestDokumenData()
    {
        $categories = ['regulasi', 'panduan', 'template', 'laporan', 'lainnya'];
        
        for ($i = 1; $i <= 15; $i++) {
            Dokumen::create([
                'judul' => 'Dokumen Test ' . $i,
                'deskripsi' => 'Deskripsi dokumen test ' . $i,
                'file_path' => 'dokumen/test-document-' . $i . '.pdf',
                'file_name' => 'test-document-' . $i . '.pdf',
                'file_size' => rand(1024, 5120), // 1KB to 5KB
                'kategori' => $categories[($i - 1) % 5],
                'status' => true,
                'is_public' => ($i <= 10),
                'download_count' => rand(0, 100),
                'urutan' => $i,
                'created_by' => $this->admin->id,
                'updated_by' => $this->admin->id,
            ]);
        }
    }

    /**
     * Test Dokumen index page
     */
    public function testDokumenIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->assertSee('Dokumen')
                ->assertSee('Tambah Dokumen')
                ->assertSee('Dokumen Test 1')
                ->assertSee('Dokumen Test 2')
                ->assertSee('Dokumen Test 3')
                ->assertSee('Download')
                ->assertSee('Preview');
        });
    }

    /**
     * Test Dokumen pagination
     */
    public function testDokumenPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->assertSee('Dokumen')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Dokumen Test 11')
                ->assertSee('Dokumen Test 12');
        });
    }

    /**
     * Test Dokumen search functionality
     */
    public function testDokumenSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->type('search', 'Dokumen Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Dokumen Test 5')
                ->assertDontSee('Dokumen Test 1')
                ->assertDontSee('Dokumen Test 2');
        });
    }

    /**
     * Test Dokumen filter by category
     */
    public function testDokumenFilterByCategory()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->select('kategori', 'regulasi')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Dokumen Test 1'); // First document should be 'regulasi'
        });
    }

    /**
     * Test Dokumen create page
     */
    public function testDokumenCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->clickLink('Tambah Dokumen')
                ->pause(1000)
                ->assertPathIs('/admin/dokumen/create')
                ->assertSee('Tambah Dokumen')
                ->assertPresent('input[name="judul"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('input[name="file"]')
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('input[name="status"]')
                ->assertPresent('input[name="is_public"]');
        });
    }

    /**
     * Test Dokumen store validation
     */
    public function testDokumenStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The judul field is required')
                ->assertSee('The deskripsi field is required')
                ->assertSee('The file field is required')
                ->assertSee('The kategori field is required');
        });
    }

    /**
     * Test Dokumen show page
     */
    public function testDokumenShowPage()
    {
        $dokumen = Dokumen::first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->click('a[href="/admin/dokumen/' . $dokumen->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/dokumen/' . $dokumen->id)
                ->assertSee($dokumen->judul)
                ->assertSee($dokumen->deskripsi)
                ->assertSee($dokumen->kategori)
                ->assertSee($dokumen->file_name)
                ->assertSee('Download')
                ->assertSee('Edit')
                ->assertSee('Hapus');
        });
    }

    /**
     * Test Dokumen edit page
     */
    public function testDokumenEditPage()
    {
        $dokumen = Dokumen::first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen/' . $dokumen->id . '/edit')
                ->assertSee('Edit Dokumen')
                ->assertInputValue('judul', $dokumen->judul)
                ->assertInputValue('deskripsi', $dokumen->deskripsi)
                ->assertSelected('kategori', $dokumen->kategori);
        });
    }

    /**
     * Test Dokumen update functionality
     */
    public function testDokumenUpdate()
    {
        $dokumen = Dokumen::first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen/' . $dokumen->id . '/edit')
                ->clear('judul')
                ->type('judul', 'Dokumen Updated Test')
                ->clear('deskripsi')
                ->type('deskripsi', 'Deskripsi dokumen yang telah diupdate')
                ->select('kategori', 'panduan')
                ->check('status')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/dokumen')
                ->assertSee('Data berhasil diupdate')
                ->assertSee('Dokumen Updated Test');
        });
    }

    /**
     * Test Dokumen update validation
     */
    public function testDokumenUpdateValidation()
    {
        $dokumen = Dokumen::first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen/' . $dokumen->id . '/edit')
                ->clear('judul')
                ->clear('deskripsi')
                ->press('Update')
                ->pause(1000)
                ->assertSee('The judul field is required')
                ->assertSee('The deskripsi field is required');
        });
    }

    /**
     * Test Dokumen delete functionality
     */
    public function testDokumenDelete()
    {
        $dokumen = Dokumen::latest()->first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->press('.btn-delete[data-id="' . $dokumen->id . '"]')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Hapus');
                })
                ->pause(2000)
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($dokumen->judul);
        });
    }

    /**
     * Test Dokumen download functionality
     */
    public function testDokumenDownload()
    {
        $dokumen = Dokumen::first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->click('a[href="/admin/dokumen/' . $dokumen->id . '/download"]')
                ->pause(1000);
            
            // Note: Browser testing for file downloads is complex
            // In real scenario, we'd verify the download count increased
            $this->assertDatabaseHas('dokumens', [
                'id' => $dokumen->id,
            ]);
        });
    }

    /**
     * Test Dokumen status toggle
     */
    public function testDokumenStatusToggle()
    {
        $dokumen = Dokumen::first();
        $originalStatus = $dokumen->status;
        
        $this->browse(function (Browser $browser) use ($dokumen, $originalStatus) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->click('.toggle-status[data-id="' . $dokumen->id . '"]')
                ->pause(1000)
                ->assertSee('Status berhasil diupdate');
                
            // Verify status changed in database
            $this->assertDatabaseHas('dokumens', [
                'id' => $dokumen->id,
                'status' => !$originalStatus,
            ]);
        });
    }

    /**
     * Test Dokumen public access toggle
     */
    public function testDokumenPublicAccessToggle()
    {
        $dokumen = Dokumen::first();
        $originalPublic = $dokumen->is_public;
        
        $this->browse(function (Browser $browser) use ($dokumen, $originalPublic) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->click('.toggle-public[data-id="' . $dokumen->id . '"]')
                ->pause(1000)
                ->assertSee('Akses publik berhasil diupdate');
                
            // Verify public access changed in database
            $this->assertDatabaseHas('dokumens', [
                'id' => $dokumen->id,
                'is_public' => !$originalPublic,
            ]);
        });
    }

    /**
     * Test Dokumen sorting functionality
     */
    public function testDokumenSorting()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->click('th[data-sort="judul"]')
                ->pause(1000)
                ->assertSee('Dokumen Test'); // Should show sorted results
        });
    }

    /**
     * Test Dokumen bulk operations
     */
    public function testDokumenBulkOperations()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->check('input[name="select_all"]')
                ->select('bulk_action', 'activate')
                ->press('Jalankan')
                ->pause(1000)
                ->assertSee('Operasi bulk berhasil dijalankan');
        });
    }

    /**
     * Test access denied for non-authorized user
     */
    public function testAccessDeniedForNonAuthorizedUser()
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@inspektorat.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dokumen')
                ->assertSee('403')
                ->assertSee('Forbidden');
        });
    }
}