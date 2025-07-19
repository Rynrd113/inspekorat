<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GaleriTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin Galeri',
            'email' => 'admin.galeri@inspektorat.id',
            'password' => bcrypt('admingaleri123'),
            'role' => 'admin_galeri',
            'is_active' => true,
        ]);

        // Create test galeri data
        $this->createTestGaleriData();
    }

    private function createTestGaleriData()
    {
        $categories = ['kegiatan', 'rapat', 'acara', 'fasilitas', 'lainnya'];
        
        for ($i = 1; $i <= 15; $i++) {
            Galeri::create([
                'judul' => 'Galeri Test ' . $i,
                'deskripsi' => 'Deskripsi galeri test ' . $i,
                'file_path' => 'galeri/test-image-' . $i . '.jpg',
                'file_name' => 'test-image-' . $i . '.jpg',
                'file_size' => rand(1024, 5120), // 1KB to 5KB
                'kategori' => $categories[($i - 1) % 5],
                'status' => true,
                'urutan' => $i,
                'alt_text' => 'Alt text untuk galeri test ' . $i,
                'created_by' => $this->admin->id,
                'updated_by' => $this->admin->id,
            ]);
        }
    }

    /**
     * Test Galeri index page
     */
    public function testGaleriIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->assertSee('Galeri')
                ->assertSee('Tambah Galeri')
                ->assertSee('Bulk Upload')
                ->assertSee('Galeri Test 1')
                ->assertSee('Galeri Test 2')
                ->assertSee('Galeri Test 3');
        });
    }

    /**
     * Test Galeri pagination
     */
    public function testGaleriPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->assertSee('Galeri')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Galeri Test 11')
                ->assertSee('Galeri Test 12');
        });
    }

    /**
     * Test Galeri search functionality
     */
    public function testGaleriSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->type('search', 'Galeri Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Galeri Test 5')
                ->assertDontSee('Galeri Test 1')
                ->assertDontSee('Galeri Test 2');
        });
    }

    /**
     * Test Galeri filter by category
     */
    public function testGaleriFilterByCategory()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->select('kategori', 'kegiatan')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Galeri Test 1'); // First gallery should be 'kegiatan'
        });
    }

    /**
     * Test Galeri create page
     */
    public function testGaleriCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->clickLink('Tambah Galeri')
                ->pause(1000)
                ->assertPathIs('/admin/galeri/create')
                ->assertSee('Tambah Galeri')
                ->assertPresent('input[name="judul"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('input[name="file"]')
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('input[name="alt_text"]')
                ->assertPresent('input[name="status"]');
        });
    }

    /**
     * Test Galeri store validation
     */
    public function testGaleriStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The judul field is required')
                ->assertSee('The deskripsi field is required')
                ->assertSee('The file field is required')
                ->assertSee('The kategori field is required');
        });
    }

    /**
     * Test Galeri show page
     */
    public function testGaleriShowPage()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('a[href="/admin/galeri/' . $galeri->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/galeri/' . $galeri->id)
                ->assertSee($galeri->judul)
                ->assertSee($galeri->deskripsi)
                ->assertSee($galeri->kategori)
                ->assertSee('Edit')
                ->assertSee('Hapus');
        });
    }

    /**
     * Test Galeri edit page
     */
    public function testGaleriEditPage()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/' . $galeri->id . '/edit')
                ->assertSee('Edit Galeri')
                ->assertInputValue('judul', $galeri->judul)
                ->assertInputValue('deskripsi', $galeri->deskripsi)
                ->assertSelected('kategori', $galeri->kategori)
                ->assertInputValue('alt_text', $galeri->alt_text);
        });
    }

    /**
     * Test Galeri update functionality
     */
    public function testGaleriUpdate()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/' . $galeri->id . '/edit')
                ->clear('judul')
                ->type('judul', 'Galeri Updated Test')
                ->clear('deskripsi')
                ->type('deskripsi', 'Deskripsi galeri yang telah diupdate')
                ->select('kategori', 'rapat')
                ->clear('alt_text')
                ->type('alt_text', 'Alt text yang diupdate')
                ->check('status')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/galeri')
                ->assertSee('Data berhasil diupdate')
                ->assertSee('Galeri Updated Test');
        });
    }

    /**
     * Test Galeri update validation
     */
    public function testGaleriUpdateValidation()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/' . $galeri->id . '/edit')
                ->clear('judul')
                ->clear('deskripsi')
                ->press('Update')
                ->pause(1000)
                ->assertSee('The judul field is required')
                ->assertSee('The deskripsi field is required');
        });
    }

    /**
     * Test Galeri delete functionality
     */
    public function testGaleriDelete()
    {
        $galeri = Galeri::latest()->first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->press('.btn-delete[data-id="' . $galeri->id . '"]')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Hapus');
                })
                ->pause(2000)
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($galeri->judul);
        });
    }

    /**
     * Test Galeri bulk upload page
     */
    public function testGaleriBulkUploadPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->clickLink('Bulk Upload')
                ->pause(1000)
                ->assertSee('Bulk Upload Galeri')
                ->assertPresent('input[name="files[]"]')
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('textarea[name="deskripsi"]');
        });
    }

    /**
     * Test Galeri bulk upload validation
     */
    public function testGaleriBulkUploadValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->clickLink('Bulk Upload')
                ->pause(1000)
                ->press('Upload')
                ->pause(1000)
                ->assertSee('The files field is required')
                ->assertSee('The kategori field is required');
        });
    }

    /**
     * Test Galeri status toggle
     */
    public function testGaleriStatusToggle()
    {
        $galeri = Galeri::first();
        $originalStatus = $galeri->status;
        
        $this->browse(function (Browser $browser) use ($galeri, $originalStatus) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('.toggle-status[data-id="' . $galeri->id . '"]')
                ->pause(1000)
                ->assertSee('Status berhasil diupdate');
                
            // Verify status changed in database
            $this->assertDatabaseHas('galeris', [
                'id' => $galeri->id,
                'status' => !$originalStatus,
            ]);
        });
    }

    /**
     * Test Galeri grid view
     */
    public function testGaleriGridView()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('.view-grid')
                ->pause(1000)
                ->assertPresent('.gallery-grid')
                ->assertPresent('.gallery-item');
        });
    }

    /**
     * Test Galeri list view
     */
    public function testGaleriListView()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('.view-list')
                ->pause(1000)
                ->assertPresent('.gallery-list')
                ->assertPresent('table');
        });
    }

    /**
     * Test Galeri sorting functionality
     */
    public function testGaleriSorting()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('th[data-sort="judul"]')
                ->pause(1000)
                ->assertSee('Galeri Test'); // Should show sorted results
        });
    }

    /**
     * Test Galeri bulk operations
     */
    public function testGaleriBulkOperations()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->check('input[name="select_all"]')
                ->select('bulk_action', 'activate')
                ->press('Jalankan')
                ->pause(1000)
                ->assertSee('Operasi bulk berhasil dijalankan');
        });
    }

    /**
     * Test Galeri image preview
     */
    public function testGaleriImagePreview()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('.img-preview[data-id="' . $galeri->id . '"]')
                ->pause(1000)
                ->whenAvailable('.modal', function ($modal) use ($galeri) {
                    $modal->assertSee($galeri->judul)
                          ->assertPresent('img');
                });
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
                ->visit('/admin/galeri')
                ->assertSee('403')
                ->assertSee('Forbidden');
        });
    }
}

