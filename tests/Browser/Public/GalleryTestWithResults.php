<?php

namespace Tests\Browser\Public;

use App\Models\Galeri;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Gallery Test With Results
 * Test gallery functionality with database result verification
 */
class GalleryTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestGalleryItems();
    }

    private function createTestGalleryItems()
    {
        // Create test gallery items
        Galeri::create([
            'judul' => 'Kegiatan Sosialisasi 2024',
            'deskripsi' => 'Dokumentasi kegiatan sosialisasi program inspektorat tahun 2024',
            'kategori' => 'kegiatan',
            'album' => 'Sosialisasi 2024',
            'file_path' => 'gallery/kegiatan-sosialisasi-2024.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => 2048000,
            'tipe' => 'image',
            'status' => 'active',
            'view_count' => 0,
            'created_at' => now()->subDays(1)
        ]);

        Galeri::create([
            'judul' => 'Rapat Koordinasi Bulanan',
            'deskripsi' => 'Dokumentasi rapat koordinasi bulanan dengan OPD',
            'kategori' => 'rapat',
            'album' => 'Koordinasi 2024',
            'file_path' => 'gallery/rapat-koordinasi-bulanan.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => 1536000,
            'tipe' => 'image',
            'status' => 'active',
            'view_count' => 5,
            'created_at' => now()->subDays(2)
        ]);

        Galeri::create([
            'judul' => 'Workshop Pengawasan Internal',
            'deskripsi' => 'Video workshop pengawasan internal untuk peningkatan kapasitas',
            'kategori' => 'workshop',
            'album' => 'Workshop 2024',
            'file_path' => 'gallery/workshop-pengawasan-internal.mp4',
            'file_type' => 'video/mp4',
            'file_size' => 52428800,
            'tipe' => 'video',
            'status' => 'active',
            'view_count' => 10,
            'created_at' => now()->subDays(3)
        ]);

        Galeri::create([
            'judul' => 'Pelantikan Pejabat Baru',
            'deskripsi' => 'Dokumentasi pelantikan pejabat baru di lingkungan inspektorat',
            'kategori' => 'acara',
            'album' => 'Pelantikan 2024',
            'file_path' => 'gallery/pelantikan-pejabat-baru.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => 3072000,
            'tipe' => 'image',
            'status' => 'active',
            'view_count' => 15,
            'created_at' => now()->subDays(4)
        ]);

        Galeri::create([
            'judul' => 'Pelatihan Teknologi Informasi',
            'deskripsi' => 'Dokumentasi pelatihan teknologi informasi untuk pegawai',
            'kategori' => 'pelatihan',
            'album' => 'Pelatihan 2024',
            'file_path' => 'gallery/pelatihan-teknologi-informasi.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => 2560000,
            'tipe' => 'image',
            'status' => 'active',
            'view_count' => 8,
            'created_at' => now()->subDays(5)
        ]);

        Galeri::create([
            'judul' => 'Kunjungan Kerja Ke Daerah',
            'deskripsi' => 'Video dokumentasi kunjungan kerja ke daerah-daerah di Papua Tengah',
            'kategori' => 'kunjungan',
            'album' => 'Kunjungan 2024',
            'file_path' => 'gallery/kunjungan-kerja-daerah.mp4',
            'file_type' => 'video/mp4',
            'file_size' => 78643200,
            'tipe' => 'video',
            'status' => 'active',
            'view_count' => 12,
            'created_at' => now()->subDays(6)
        ]);
    }

    /**
     * Test gallery view count increment
     */
    public function testGalleryViewCountIncrement()
    {
        $this->browse(function (Browser $browser) {
            $galleryItem = Galeri::first();
            $initialViewCount = $galleryItem->view_count;

            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.gallery-item:first-child')
                ->waitFor('.gallery-lightbox', 10)
                ->assertSee($galleryItem->judul)
                ->screenshot('gallery-view-count-increment');

            // Verify view count increased
            $galleryItem->refresh();
            $this->assertEquals($initialViewCount + 1, $galleryItem->view_count);
        });
    }

    /**
     * Test gallery search functionality with results
     */
    public function testGallerySearchWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.search-form', 10)
                ->type('search', 'Kegiatan')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Kegiatan Sosialisasi 2024')
                ->assertDontSee('Rapat Koordinasi Bulanan')
                ->screenshot('gallery-search-results');

            // Verify search results count
            $browser->assertSee('1 item ditemukan');
        });
    }

    /**
     * Test gallery category filter with results
     */
    public function testGalleryCategoryFilterWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.category-filter', 10)
                ->select('kategori', 'kegiatan')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Kegiatan Sosialisasi 2024')
                ->assertDontSee('Rapat Koordinasi Bulanan')
                ->screenshot('gallery-category-filter');

            // Verify category filter results count
            $browser->assertSee('1 item kategori kegiatan');
        });
    }

    /**
     * Test gallery album filter with results
     */
    public function testGalleryAlbumFilterWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.album-filter', 10)
                ->select('album', 'Sosialisasi 2024')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Kegiatan Sosialisasi 2024')
                ->assertDontSee('Rapat Koordinasi Bulanan')
                ->screenshot('gallery-album-filter');

            // Verify album filter results count
            $browser->assertSee('1 item album Sosialisasi 2024');
        });
    }

    /**
     * Test gallery media type filter with results
     */
    public function testGalleryMediaTypeFilterWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.media-type-filter', 10)
                ->select('tipe', 'video')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Workshop Pengawasan Internal')
                ->assertSee('Kunjungan Kerja Ke Daerah')
                ->assertDontSee('Kegiatan Sosialisasi 2024')
                ->screenshot('gallery-media-type-filter');

            // Verify media type filter results count
            $browser->assertSee('2 video ditemukan');
        });
    }

    /**
     * Test gallery lightbox functionality with tracking
     */
    public function testGalleryLightboxWithTracking()
    {
        $this->browse(function (Browser $browser) {
            $galleryItem = Galeri::where('tipe', 'image')->first();
            $initialViewCount = $galleryItem->view_count;

            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.gallery-item[data-id="' . $galleryItem->id . '"]')
                ->waitFor('.gallery-lightbox', 10)
                ->assertSee($galleryItem->judul)
                ->assertSee($galleryItem->deskripsi)
                ->screenshot('gallery-lightbox-tracking');

            // Test navigation in lightbox
            $browser->click('.lightbox-next')
                ->waitFor('.lightbox-content', 10)
                ->screenshot('gallery-lightbox-navigation');

            // Close lightbox
            $browser->click('.lightbox-close')
                ->waitUntilMissing('.gallery-lightbox', 10)
                ->screenshot('gallery-lightbox-close');

            // Verify view count increased
            $galleryItem->refresh();
            $this->assertEquals($initialViewCount + 1, $galleryItem->view_count);
        });
    }

    /**
     * Test gallery video playback tracking
     */
    public function testGalleryVideoPlaybackTracking()
    {
        $this->browse(function (Browser $browser) {
            $videoItem = Galeri::where('tipe', 'video')->first();
            $initialViewCount = $videoItem->view_count;

            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.gallery-item[data-id="' . $videoItem->id . '"]')
                ->waitFor('.video-player', 10)
                ->click('.video-play-btn')
                ->waitFor('.video-playing', 10)
                ->screenshot('gallery-video-playback');

            // Verify video view count increased
            $videoItem->refresh();
            $this->assertEquals($initialViewCount + 1, $videoItem->view_count);
        });
    }

    /**
     * Test gallery download functionality with tracking
     */
    public function testGalleryDownloadWithTracking()
    {
        $this->browse(function (Browser $browser) {
            $galleryItem = Galeri::first();
            $initialDownloadCount = $galleryItem->download_count ?? 0;

            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.gallery-item:first-child')
                ->waitFor('.gallery-lightbox', 10)
                ->click('.download-btn')
                ->waitFor('.download-started', 10)
                ->screenshot('gallery-download-tracking');

            // Verify download count increased
            $galleryItem->refresh();
            $this->assertEquals($initialDownloadCount + 1, $galleryItem->download_count);
        });
    }

    /**
     * Test gallery statistics display
     */
    public function testGalleryStatisticsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.gallery-statistics', 10)
                ->assertSee('Total Media: 6')
                ->assertSee('Total Foto: 4')
                ->assertSee('Total Video: 2')
                ->assertSee('Total Dilihat: 50')
                ->screenshot('gallery-statistics-display');

            // Test statistics after interaction
            $browser->click('.gallery-item:first-child')
                ->waitFor('.gallery-lightbox', 10)
                ->click('.lightbox-close')
                ->waitUntilMissing('.gallery-lightbox', 10)
                ->refresh()
                ->waitFor('.gallery-statistics', 10)
                ->assertSee('Total Dilihat: 51')
                ->screenshot('gallery-statistics-after-interaction');
        });
    }

    /**
     * Test gallery album grouping with results
     */
    public function testGalleryAlbumGroupingWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.album-view', 10)
                ->click('.album-view-btn')
                ->waitFor('.album-groups', 10)
                ->assertSee('Sosialisasi 2024 (1)')
                ->assertSee('Koordinasi 2024 (1)')
                ->assertSee('Workshop 2024 (1)')
                ->assertSee('Pelantikan 2024 (1)')
                ->screenshot('gallery-album-grouping');

            // Test album click
            $browser->click('.album-sosialisasi-2024')
                ->waitFor('.album-items', 10)
                ->assertSee('Kegiatan Sosialisasi 2024')
                ->assertDontSee('Rapat Koordinasi Bulanan')
                ->screenshot('gallery-album-click');
        });
    }

    /**
     * Test gallery pagination with results
     */
    public function testGalleryPaginationWithResults()
    {
        // Create additional gallery items for pagination testing
        for ($i = 7; $i <= 20; $i++) {
            Galeri::create([
                'judul' => "Test Gallery Item $i",
                'deskripsi' => "Test description $i",
                'kategori' => 'test',
                'album' => 'Test Album',
                'file_path' => "gallery/test-$i.jpg",
                'file_type' => 'image/jpeg',
                'file_size' => 1024000,
                'tipe' => 'image',
                'status' => 'active',
                'view_count' => 0
            ]);
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->assertSee('Test Gallery Item 7')
                ->assertSee('Test Gallery Item 12')
                ->screenshot('gallery-pagination-page-1');

            // Test pagination
            $browser->click('.pagination .next')
                ->waitFor('.gallery-grid', 10)
                ->assertSee('Test Gallery Item 13')
                ->assertSee('Test Gallery Item 20')
                ->screenshot('gallery-pagination-page-2');

            // Verify pagination info
            $browser->assertSee('Halaman 2 dari 2');
        });
    }

    /**
     * Test gallery sorting with results
     */
    public function testGallerySortingWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.sort-options', 10)
                ->select('sort', 'newest')
                ->press('Sort')
                ->waitFor('.sorted-results', 10)
                ->screenshot('gallery-sort-newest');

            // Verify newest item appears first
            $browser->assertSeeIn('.gallery-item:first-child', 'Kegiatan Sosialisasi 2024');

            // Test sort by most viewed
            $browser->select('sort', 'most_viewed')
                ->press('Sort')
                ->waitFor('.sorted-results', 10)
                ->screenshot('gallery-sort-most-viewed');

            // Verify most viewed item appears first
            $browser->assertSeeIn('.gallery-item:first-child', 'Pelantikan Pejabat Baru');
        });
    }

    /**
     * Test gallery sharing functionality
     */
    public function testGallerySharingFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.gallery-item:first-child')
                ->waitFor('.gallery-lightbox', 10)
                ->click('.share-btn')
                ->waitFor('.share-options', 10)
                ->assertSee('Bagikan')
                ->assertPresent('.share-facebook')
                ->assertPresent('.share-twitter')
                ->assertPresent('.share-whatsapp')
                ->screenshot('gallery-sharing-functionality');

            // Test copy link functionality
            $browser->click('.copy-link-btn')
                ->waitForText('Link copied to clipboard', 10)
                ->screenshot('gallery-copy-link');
        });
    }

    /**
     * Test gallery responsive design with results
     */
    public function testGalleryResponsiveDesignWithResults()
    {
        $this->browse(function (Browser $browser) {
            // Test mobile view
            $browser->resize(375, 667)
                ->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->assertPresent('.mobile-gallery-layout')
                ->screenshot('gallery-mobile-responsive');

            // Test tablet view
            $browser->resize(768, 1024)
                ->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->assertPresent('.tablet-gallery-layout')
                ->screenshot('gallery-tablet-responsive');

            // Test desktop view
            $browser->resize(1920, 1080)
                ->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->assertPresent('.desktop-gallery-layout')
                ->screenshot('gallery-desktop-responsive');
        });
    }

    /**
     * Test gallery metadata display
     */
    public function testGalleryMetadataDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.gallery-item:first-child')
                ->waitFor('.gallery-lightbox', 10)
                ->assertSee('2.00 MB') // File size
                ->assertSee('JPEG') // File type
                ->assertSee('Sosialisasi 2024') // Album
                ->assertSee('kegiatan') // Category
                ->assertPresent('.metadata-info')
                ->screenshot('gallery-metadata-display');
        });
    }

    /**
     * Test gallery search with no results
     */
    public function testGallerySearchWithNoResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.search-form', 10)
                ->type('search', 'NonExistentGalleryItem')
                ->press('Search')
                ->waitFor('.no-results', 10)
                ->assertSee('Tidak ada item galeri yang ditemukan')
                ->assertSee('Coba kata kunci yang berbeda')
                ->screenshot('gallery-search-no-results');
        });
    }

    /**
     * Test gallery access tracking
     */
    public function testGalleryAccessTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.gallery-item:first-child')
                ->waitFor('.gallery-lightbox', 10)
                ->screenshot('gallery-access-tracking');

            // Verify gallery access was tracked
            $this->assertDatabaseHas('gallery_access_logs', [
                'gallery_id' => 1,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Dusk'
            ]);
        });
    }
}