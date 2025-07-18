<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        for ($i = 1; $i <= 15; $i++) {
            Galeri::create([
                'judul' => 'Galeri Test ' . $i,
                'deskripsi' => 'Deskripsi galeri test ' . $i,
                'kategori' => 'Kegiatan',
                'album' => 'Album ' . ceil($i / 3),
                'tanggal_kegiatan' => now()->subDays($i),
                'lokasi_kegiatan' => 'Lokasi Kegiatan ' . $i,
                'file_media' => 'galeri/test-' . $i . '.jpg',
                'thumbnail' => 'galeri/thumbnails/test-' . $i . '.jpg',
                'status' => 'published',
                'is_featured' => $i <= 5,
                'view_count' => rand(10, 100),
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
                ->assertSee('Tambah Media')
                ->assertSee('Galeri Test 1')
                ->assertSee('Galeri Test 2')
                ->assertSee('Galeri Test 3');
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
                ->click('button[data-view="grid"]')
                ->pause(1000)
                ->assertSee('Galeri Test 1')
                ->assertPresent('.galeri-grid')
                ->assertPresent('.galeri-card');
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
     * Test Galeri create page
     */
    public function testGaleriCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->clickLink('Tambah Media')
                ->pause(1000)
                ->assertPathIs('/admin/galeri/create')
                ->assertSee('Tambah Media Galeri')
                ->assertPresent('input[name="judul"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('input[name="album"]')
                ->assertPresent('input[name="tanggal_kegiatan"]')
                ->assertPresent('input[name="lokasi_kegiatan"]')
                ->assertPresent('input[name="file_media"]')
                ->assertPresent('select[name="status"]')
                ->assertPresent('input[name="is_featured"]');
        });
    }

    /**
     * Test Galeri store image functionality
     */
    public function testGaleriStoreImage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/create')
                ->type('judul', 'Rapat Koordinasi Audit Internal')
                ->type('deskripsi', 'Dokumentasi rapat koordinasi audit internal dengan seluruh OPD')
                ->select('kategori', 'Kegiatan')
                ->type('album', 'Rapat Koordinasi 2024')
                ->type('tanggal_kegiatan', '2024-01-15')
                ->type('lokasi_kegiatan', 'Kantor Inspektorat Papua Tengah')
                ->attach('file_media', __DIR__ . '/../../fixtures/test-image.jpg')
                ->select('status', 'published')
                ->check('is_featured')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/galeri')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Rapat Koordinasi Audit Internal');
        });
    }

    /**
     * Test Galeri store video functionality
     */
    public function testGaleriStoreVideo()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/create')
                ->type('judul', 'Video Sosialisasi WBS')
                ->type('deskripsi', 'Video sosialisasi sistem whistleblowing untuk masyarakat')
                ->select('kategori', 'Sosialisasi')
                ->type('album', 'Video Sosialisasi 2024')
                ->type('tanggal_kegiatan', '2024-01-20')
                ->type('lokasi_kegiatan', 'Balai Desa Nabire')
                ->attach('file_media', __DIR__ . '/../../fixtures/test-video.mp4')
                ->select('status', 'published')
                ->press('Simpan')
                ->pause(3000)
                ->assertPathIs('/admin/galeri')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Video Sosialisasi WBS');
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
                ->assertSee('The kategori field is required')
                ->assertSee('The album field is required')
                ->assertSee('The tanggal kegiatan field is required')
                ->assertSee('The lokasi kegiatan field is required')
                ->assertSee('The file media field is required');
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
                ->assertSee($galeri->album)
                ->assertSee($galeri->lokasi_kegiatan)
                ->assertPresent('img, video');
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
                ->visit('/admin/galeri')
                ->click('a[href="/admin/galeri/' . $galeri->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/galeri/' . $galeri->id . '/edit')
                ->assertSee('Edit Media Galeri')
                ->assertInputValue('judul', $galeri->judul)
                ->assertInputValue('album', $galeri->album)
                ->assertInputValue('lokasi_kegiatan', $galeri->lokasi_kegiatan)
                ->assertSee($galeri->deskripsi);
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
                ->type('judul', 'Galeri Updated')
                ->clear('album')
                ->type('album', 'Album Updated')
                ->clear('lokasi_kegiatan')
                ->type('lokasi_kegiatan', 'Lokasi Updated')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/galeri')
                ->assertSee('Data berhasil diperbarui')
                ->assertSee('Galeri Updated')
                ->assertSee('Album Updated');
        });
    }

    /**
     * Test Galeri delete functionality
     */
    public function testGaleriDelete()
    {
        $galeri = Galeri::first();
        $galeriTitle = $galeri->judul;
        
        $this->browse(function (Browser $browser) use ($galeri, $galeriTitle) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus media ini?\')) { document.getElementById(\'delete-form-' . $galeri->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/galeri')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($galeriTitle);
        });
    }

    /**
     * Test Galeri category filter
     */
    public function testGaleriCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->select('kategori', 'Kegiatan')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Galeri Test 1')
                ->assertSee('Galeri Test 2')
                ->select('kategori', 'Sosialisasi')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Tidak ada data');
        });
    }

    /**
     * Test Galeri album filter
     */
    public function testGaleriAlbumFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->select('album', 'Album 1')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Galeri Test 1')
                ->assertSee('Galeri Test 2')
                ->assertSee('Galeri Test 3');
        });
    }

    /**
     * Test Galeri status filter
     */
    public function testGaleriStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->select('status', 'published')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Galeri Test 1')
                ->assertSee('Galeri Test 2')
                ->select('status', 'draft')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Tidak ada data');
        });
    }

    /**
     * Test Galeri featured toggle
     */
    public function testGaleriFeaturedToggle()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('input[name="is_featured"][data-id="' . $galeri->id . '"]')
                ->pause(1000)
                ->assertSee('Featured status berhasil diubah');
        });
    }

    /**
     * Test Galeri bulk upload
     */
    public function testGaleriBulkUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->clickLink('Bulk Upload')
                ->pause(1000)
                ->assertPathIs('/admin/galeri/bulk-upload')
                ->assertSee('Bulk Upload Media')
                ->attach('files[]', __DIR__ . '/../../fixtures/test-image-1.jpg')
                ->attach('files[]', __DIR__ . '/../../fixtures/test-image-2.jpg')
                ->attach('files[]', __DIR__ . '/../../fixtures/test-image-3.jpg')
                ->type('album', 'Bulk Upload Album')
                ->select('kategori', 'Kegiatan')
                ->type('tanggal_kegiatan', '2024-01-25')
                ->type('lokasi_kegiatan', 'Bulk Upload Location')
                ->press('Upload')
                ->pause(3000)
                ->assertPathIs('/admin/galeri')
                ->assertSee('Bulk upload berhasil')
                ->assertSee('Bulk Upload Album');
        });
    }

    /**
     * Test Galeri image lightbox
     */
    public function testGaleriImageLightbox()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/' . $galeri->id)
                ->click('img[data-lightbox="gallery"]')
                ->pause(1000)
                ->assertPresent('.lightbox-overlay')
                ->assertSee($galeri->judul);
        });
    }

    /**
     * Test Galeri responsive design
     */
    public function testGaleriResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->assertSee('Galeri')
                ->assertSee('Tambah Media')
                ->resize(768, 1024) // iPad size
                ->assertSee('Galeri')
                ->assertSee('Tambah Media')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test Galeri file type validation
     */
    public function testGaleriFileTypeValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/create')
                ->type('judul', 'Test File Type')
                ->type('deskripsi', 'Test file type validation')
                ->select('kategori', 'Kegiatan')
                ->type('album', 'Test Album')
                ->type('tanggal_kegiatan', '2024-01-01')
                ->type('lokasi_kegiatan', 'Test Location')
                ->attach('file_media', __DIR__ . '/../../fixtures/test-document.pdf')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('File media harus berupa gambar atau video');
        });
    }

    /**
     * Test Galeri file size validation
     */
    public function testGaleriFileSizeValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/create')
                ->type('judul', 'Test File Size')
                ->type('deskripsi', 'Test file size validation')
                ->select('kategori', 'Kegiatan')
                ->type('album', 'Test Album')
                ->type('tanggal_kegiatan', '2024-01-01')
                ->type('lokasi_kegiatan', 'Test Location')
                ->attach('file_media', __DIR__ . '/../../fixtures/large-image.jpg')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('File media maksimal 10MB');
        });
    }

    /**
     * Test Galeri album autocomplete
     */
    public function testGaleriAlbumAutocomplete()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri/create')
                ->type('album', 'Alb')
                ->pause(1000)
                ->assertSee('Album 1')
                ->assertSee('Album 2')
                ->click('.album-suggestion:contains("Album 1")')
                ->assertInputValue('album', 'Album 1');
        });
    }

    /**
     * Test Galeri bulk actions
     */
    public function testGaleriBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->check('select-all')
                ->select('bulk-action', 'publish')
                ->press('Apply')
                ->pause(1000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test Galeri statistics
     */
    public function testGaleriStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->assertSee('Total Media')
                ->assertSee('Total Foto')
                ->assertSee('Total Video')
                ->assertSee('Total Album')
                ->assertSee('Total Views');
        });
    }

    /**
     * Test Galeri slideshow
     */
    public function testGaleriSlideshow()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('button[data-action="slideshow"]')
                ->pause(1000)
                ->assertPresent('.slideshow-container')
                ->assertSee('Galeri Test 1')
                ->click('.slideshow-next')
                ->pause(1000)
                ->assertSee('Galeri Test 2');
        });
    }

    /**
     * Test Galeri export functionality
     */
    public function testGaleriExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->click('a[href="/admin/galeri/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test Galeri advanced search
     */
    public function testGaleriAdvancedSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/galeri')
                ->clickLink('Advanced Search')
                ->pause(1000)
                ->type('judul', 'Test')
                ->select('kategori', 'Kegiatan')
                ->select('album', 'Album 1')
                ->type('tanggal_dari', '2024-01-01')
                ->type('tanggal_sampai', '2024-12-31')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Galeri Test 1')
                ->assertSee('Galeri Test 2');
        });
    }
}
