<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ServicesTest extends DuskTestCase
{
    /**
     * Test services page loads successfully
     */
    public function test_services_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->assertSee('Pelayanan')
                ->assertSee('Inspektorat Papua Tengah')
                ->screenshot('services_page_main');
        });
    }

    /**
     * Test services page displays service list
     */
    public function test_services_page_displays_service_list()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->assertPresent('.service-item, .pelayanan-item, .card')
                ->screenshot('services_list_display');
        });
    }

    /**
     * Test services page navigation breadcrumb
     */
    public function test_services_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->assertSee('Beranda')
                ->assertSee('Pelayanan')
                ->screenshot('services_breadcrumb');
        });
    }

    /**
     * Test services page responsive design on mobile
     */
    public function test_services_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->assertSee('Pelayanan')
                ->screenshot('services_mobile_responsive');
        });
    }

    /**
     * Test services page responsive design on tablet
     */
    public function test_services_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->assertSee('Pelayanan')
                ->screenshot('services_tablet_responsive');
        });
    }

    /**
     * Test services page service details link
     */
    public function test_services_page_service_details_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.service-item:first-child a, .pelayanan-item:first-child a, .card:first-child a', function ($link) {
                    $link->click();
                })
                ->waitFor('body', 5)
                ->screenshot('services_detail_click');
        });
    }

    /**
     * Test services page search functionality (if available)
     */
    public function test_services_page_search_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('input[name="search"]', function ($input) {
                    $input->type('layanan')
                        ->press('Enter');
                })
                ->waitFor('body', 3)
                ->screenshot('services_search_functionality');
        });
    }

    /**
     * Test services page categories (if available)
     */
    public function test_services_page_categories()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.category-filter, .kategori-filter', function ($category) {
                    $category->click();
                })
                ->waitFor('body', 2)
                ->screenshot('services_categories');
        });
    }

    /**
     * Test services page back to homepage
     */
    public function test_services_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('services_back_to_homepage');
        });
    }

    /**
     * Test services page contact information
     */
    public function test_services_page_contact_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->scrollIntoView('footer')
                ->assertSee('Kontak')
                ->screenshot('services_contact_info');
        });
    }

    /**
     * Test services page SEO elements
     */
    public function test_services_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->assertTitleContains('Pelayanan')
                ->screenshot('services_seo_check');
        });
    }

    /**
     * Test services page performance
     */
    public function test_services_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'Services page should load within 5 seconds');
    }

    /**
     * Test services page service card information
     */
    public function test_services_page_service_card_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.service-item, .pelayanan-item, .card', function ($card) {
                    $card->assertSee('Layanan')
                        ->assertPresent('.service-title, .pelayanan-title, .card-title, h3, h4');
                })
                ->screenshot('services_card_info');
        });
    }

    /**
     * Test services page service requirements (if available)
     */
    public function test_services_page_service_requirements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.requirements, .persyaratan', function ($req) {
                    $req->assertSee('Persyaratan');
                })
                ->screenshot('services_requirements');
        });
    }

    /**
     * Test services page service cost information (if available)
     */
    public function test_services_page_service_cost()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.cost, .biaya, .tarif', function ($cost) {
                    $cost->assertSee('Biaya');
                })
                ->screenshot('services_cost_info');
        });
    }

    /**
     * Test services page service duration (if available)
     */
    public function test_services_page_service_duration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.duration, .waktu', function ($duration) {
                    $duration->assertSee('Waktu');
                })
                ->screenshot('services_duration_info');
        });
    }

    /**
     * Test services page empty state (if no services)
     */
    public function test_services_page_empty_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.empty-state', function ($empty) {
                    $empty->assertSee('Tidak ada layanan');
                })
                ->screenshot('services_empty_state');
        });
    }

    /**
     * Test services page pagination (if available)
     */
    public function test_services_page_pagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->whenAvailable('.pagination', function ($pagination) {
                    $pagination->assertPresent('a, button');
                })
                ->screenshot('services_pagination');
        });
    }

    /**
     * Test services page footer links
     */
    public function test_services_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('services_footer_links');
        });
    }

    /**
     * Test services page navigation menu consistency
     */
    public function test_services_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->waitForText('Pelayanan')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('Kontak')
                ->assertSee('WBS')
                ->assertSee('Portal OPD')
                ->screenshot('services_navigation_consistency');
        });
    }
}
