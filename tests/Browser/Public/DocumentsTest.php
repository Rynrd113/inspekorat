<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DocumentsTest extends DuskTestCase
{
    /**
     * Test documents page loads successfully
     */
    public function test_documents_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->assertSee('Dokumen')
                ->assertSee('Inspektorat Papua Tengah')
                ->screenshot('documents_page_main');
        });
    }

    /**
     * Test documents page displays document list
     */
    public function test_documents_page_displays_document_list()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->assertPresent('.document-item, .dokumen-item, .card')
                ->screenshot('documents_list_display');
        });
    }

    /**
     * Test documents page navigation breadcrumb
     */
    public function test_documents_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->assertSee('Beranda')
                ->assertSee('Dokumen')
                ->screenshot('documents_breadcrumb');
        });
    }

    /**
     * Test documents page search functionality
     */
    public function test_documents_page_search_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('input[name="search"]', function ($input) {
                    $input->type('peraturan')
                        ->press('Enter');
                })
                ->waitFor('body', 3)
                ->screenshot('documents_search_functionality');
        });
    }

    /**
     * Test documents page category filter
     */
    public function test_documents_page_category_filter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('select[name="kategori"]', function ($select) {
                    $select->selectByValue('regulasi');
                })
                ->waitFor('body', 2)
                ->screenshot('documents_category_filter');
        });
    }

    /**
     * Test documents page download functionality
     */
    public function test_documents_page_download_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('a[href*="download"]', function ($link) {
                    $link->assertSee('Download');
                })
                ->screenshot('documents_download_links');
        });
    }

    /**
     * Test documents page preview functionality
     */
    public function test_documents_page_preview_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('a[href*="preview"]', function ($link) {
                    $link->assertSee('Preview');
                })
                ->screenshot('documents_preview_links');
        });
    }

    /**
     * Test documents page responsive design on mobile
     */
    public function test_documents_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/dokumen')
                ->waitForText('Dokumen')
                ->assertSee('Dokumen')
                ->screenshot('documents_mobile_responsive');
        });
    }

    /**
     * Test documents page responsive design on tablet
     */
    public function test_documents_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/dokumen')
                ->waitForText('Dokumen')
                ->assertSee('Dokumen')
                ->screenshot('documents_tablet_responsive');
        });
    }

    /**
     * Test documents page file type indicators
     */
    public function test_documents_page_file_type_indicators()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('.file-type, .format, .extension', function ($indicator) {
                    $indicator->assertPresent('span, .badge');
                })
                ->screenshot('documents_file_type_indicators');
        });
    }

    /**
     * Test documents page file size information
     */
    public function test_documents_page_file_size_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('.file-size, .size', function ($size) {
                    $size->assertSee('KB');
                })
                ->screenshot('documents_file_size_info');
        });
    }

    /**
     * Test documents page publication date
     */
    public function test_documents_page_publication_date()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('.publication-date, .tanggal-publikasi, .date', function ($date) {
                    $date->assertSee('2024');
                })
                ->screenshot('documents_publication_date');
        });
    }

    /**
     * Test documents page download count
     */
    public function test_documents_page_download_count()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('.download-count', function ($count) {
                    $count->assertSee('Download');
                })
                ->screenshot('documents_download_count');
        });
    }

    /**
     * Test documents page empty state
     */
    public function test_documents_page_empty_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen?search=nonexistentdocument')
                ->waitForText('Dokumen')
                ->whenAvailable('.empty-state, .no-results', function ($empty) {
                    $empty->assertSee('Tidak ada dokumen');
                })
                ->screenshot('documents_empty_state');
        });
    }

    /**
     * Test documents page pagination
     */
    public function test_documents_page_pagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('.pagination', function ($pagination) {
                    $pagination->assertPresent('a, button');
                })
                ->screenshot('documents_pagination');
        });
    }

    /**
     * Test documents page SEO elements
     */
    public function test_documents_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->assertTitleContains('Dokumen')
                ->screenshot('documents_seo_check');
        });
    }

    /**
     * Test documents page performance
     */
    public function test_documents_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'Documents page should load within 5 seconds');
    }

    /**
     * Test documents page back to homepage
     */
    public function test_documents_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('documents_back_to_homepage');
        });
    }

    /**
     * Test documents page footer links
     */
    public function test_documents_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('documents_footer_links');
        });
    }

    /**
     * Test documents page navigation menu consistency
     */
    public function test_documents_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Pelayanan')
                ->assertSee('Galeri')
                ->assertSee('Kontak')
                ->assertSee('WBS')
                ->assertSee('Portal OPD')
                ->screenshot('documents_navigation_consistency');
        });
    }

    /**
     * Test documents page security notice
     */
    public function test_documents_page_security_notice()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('.security-notice, .disclaimer', function ($notice) {
                    $notice->assertSee('dokumen resmi');
                })
                ->screenshot('documents_security_notice');
        });
    }

    /**
     * Test documents page clear search functionality
     */
    public function test_documents_page_clear_search()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen?search=test')
                ->waitForText('Dokumen')
                ->whenAvailable('a[href*="dokumen"]:not([href*="search"])', function ($link) {
                    $link->click();
                })
                ->waitFor('body', 2)
                ->screenshot('documents_clear_search');
        });
    }

    /**
     * Test documents page sort functionality
     */
    public function test_documents_page_sort_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitForText('Dokumen')
                ->whenAvailable('select[name="sort"]', function ($select) {
                    $select->selectByValue('newest');
                })
                ->waitFor('body', 2)
                ->screenshot('documents_sort_functionality');
        });
    }
}
