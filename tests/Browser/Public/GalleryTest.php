<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GalleryTest extends DuskTestCase
{
    /**
     * Test gallery page loads successfully
     */
    public function test_gallery_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->assertSee('Galeri')
                ->assertSee('Inspektorat Papua Tengah')
                ->screenshot('gallery_page_main');
        });
    }

    /**
     * Test gallery page displays media items
     */
    public function test_gallery_page_displays_media_items()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->assertPresent('.gallery-item, .galeri-item, .media-item, .card')
                ->screenshot('gallery_media_items');
        });
    }

    /**
     * Test gallery page navigation breadcrumb
     */
    public function test_gallery_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->assertSee('Beranda')
                ->assertSee('Galeri')
                ->screenshot('gallery_breadcrumb');
        });
    }

    /**
     * Test gallery page media type filter
     */
    public function test_gallery_page_media_type_filter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('select[name="type"], .filter-type', function ($filter) {
                    $filter->selectByValue('photo');
                })
                ->waitFor('body', 2)
                ->screenshot('gallery_media_type_filter');
        });
    }

    /**
     * Test gallery page category filter
     */
    public function test_gallery_page_category_filter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('select[name="kategori"], .filter-kategori', function ($filter) {
                    $filter->selectByValue('kegiatan');
                })
                ->waitFor('body', 2)
                ->screenshot('gallery_category_filter');
        });
    }

    /**
     * Test gallery page photo lightbox functionality
     */
    public function test_gallery_page_photo_lightbox()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('.gallery-item img, .galeri-item img', function ($img) {
                    $img->click();
                })
                ->waitFor('.lightbox, .modal', 3)
                ->screenshot('gallery_photo_lightbox');
        });
    }

    /**
     * Test gallery page video playback
     */
    public function test_gallery_page_video_playback()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('video, .video-player', function ($video) {
                    $video->assertPresent('controls');
                })
                ->screenshot('gallery_video_playback');
        });
    }

    /**
     * Test gallery page responsive design on mobile
     */
    public function test_gallery_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/galeri')
                ->waitForText('Galeri')
                ->assertSee('Galeri')
                ->screenshot('gallery_mobile_responsive');
        });
    }

    /**
     * Test gallery page responsive design on tablet
     */
    public function test_gallery_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/galeri')
                ->waitForText('Galeri')
                ->assertSee('Galeri')
                ->screenshot('gallery_tablet_responsive');
        });
    }

    /**
     * Test gallery page grid layout
     */
    public function test_gallery_page_grid_layout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->assertPresent('.grid, .gallery-grid, .media-grid')
                ->screenshot('gallery_grid_layout');
        });
    }

    /**
     * Test gallery page media detail view
     */
    public function test_gallery_page_media_detail_view()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('a[href*="galeri/"]', function ($link) {
                    $link->click();
                })
                ->waitFor('body', 3)
                ->screenshot('gallery_media_detail_view');
        });
    }

    /**
     * Test gallery page media caption display
     */
    public function test_gallery_page_media_caption()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('.caption, .title, .judul', function ($caption) {
                    $caption->assertPresent('h3, h4, .media-title');
                })
                ->screenshot('gallery_media_caption');
        });
    }

    /**
     * Test gallery page media event date
     */
    public function test_gallery_page_media_event_date()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('.event-date, .tanggal-event, .date', function ($date) {
                    $date->assertSee('2024');
                })
                ->screenshot('gallery_media_event_date');
        });
    }

    /**
     * Test gallery page empty state
     */
    public function test_gallery_page_empty_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri?kategori=nonexistent')
                ->waitForText('Galeri')
                ->whenAvailable('.empty-state, .no-results', function ($empty) {
                    $empty->assertSee('Tidak ada media');
                })
                ->screenshot('gallery_empty_state');
        });
    }

    /**
     * Test gallery page pagination
     */
    public function test_gallery_page_pagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('.pagination', function ($pagination) {
                    $pagination->assertPresent('a, button');
                })
                ->screenshot('gallery_pagination');
        });
    }

    /**
     * Test gallery page SEO elements
     */
    public function test_gallery_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->assertTitleContains('Galeri')
                ->screenshot('gallery_seo_check');
        });
    }

    /**
     * Test gallery page performance
     */
    public function test_gallery_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'Gallery page should load within 5 seconds');
    }

    /**
     * Test gallery page back to homepage
     */
    public function test_gallery_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('gallery_back_to_homepage');
        });
    }

    /**
     * Test gallery page footer links
     */
    public function test_gallery_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('gallery_footer_links');
        });
    }

    /**
     * Test gallery page navigation menu consistency
     */
    public function test_gallery_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Kontak')
                ->assertSee('WBS')
                ->assertSee('Portal OPD')
                ->screenshot('gallery_navigation_consistency');
        });
    }

    /**
     * Test gallery page image lazy loading
     */
    public function test_gallery_page_image_lazy_loading()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('img[loading="lazy"]', function ($img) {
                    $img->assertAttribute('loading', 'lazy');
                })
                ->screenshot('gallery_image_lazy_loading');
        });
    }

    /**
     * Test gallery page image alt text
     */
    public function test_gallery_page_image_alt_text()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('img', function ($img) {
                    $img->assertAttribute('alt');
                })
                ->screenshot('gallery_image_alt_text');
        });
    }

    /**
     * Test gallery page media sharing functionality
     */
    public function test_gallery_page_media_sharing()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('.share-button, .social-share', function ($share) {
                    $share->assertPresent('a, button');
                })
                ->screenshot('gallery_media_sharing');
        });
    }

    /**
     * Test gallery page media download functionality
     */
    public function test_gallery_page_media_download()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('.download-button, a[download]', function ($download) {
                    $download->assertPresent('a, button');
                })
                ->screenshot('gallery_media_download');
        });
    }
}
