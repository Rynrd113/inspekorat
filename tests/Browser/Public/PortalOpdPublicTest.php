<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PortalOpdPublicTest extends DuskTestCase
{
    /**
     * Test Portal OPD page loads successfully
     */
    public function test_portal_opd_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->assertSee('Portal OPD Papua Tengah')
                ->assertSee('Organisasi Perangkat Daerah')
                ->screenshot('portal_opd_page_main');
        });
    }

    /**
     * Test Portal OPD page displays OPD list
     */
    public function test_portal_opd_page_displays_opd_list()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->assertPresent('.opd-item, .card, .opd-card')
                ->screenshot('portal_opd_list_display');
        });
    }

    /**
     * Test Portal OPD page search functionality
     */
    public function test_portal_opd_page_search_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->type('search', 'inspektorat')
                ->press('Cari')
                ->waitFor('body', 3)
                ->screenshot('portal_opd_search_functionality');
        });
    }

    /**
     * Test Portal OPD page OPD card display
     */
    public function test_portal_opd_page_opd_card_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->whenAvailable('.opd-item:first-child, .card:first-child', function ($card) {
                    $card->assertPresent('.opd-name, .nama-opd, h3, h4')
                        ->assertPresent('.opd-logo, .logo, img')
                        ->assertPresent('.opd-description, .deskripsi');
                })
                ->screenshot('portal_opd_card_display');
        });
    }

    /**
     * Test Portal OPD page OPD detail click
     */
    public function test_portal_opd_page_opd_detail_click()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->whenAvailable('a[href*="portal-opd/"]', function ($link) {
                    $link->click();
                })
                ->waitFor('body', 3)
                ->screenshot('portal_opd_detail_click');
        });
    }

    /**
     * Test Portal OPD page responsive design on mobile
     */
    public function test_portal_opd_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->assertSee('Portal OPD')
                ->screenshot('portal_opd_mobile_responsive');
        });
    }

    /**
     * Test Portal OPD page responsive design on tablet
     */
    public function test_portal_opd_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->assertSee('Portal OPD')
                ->screenshot('portal_opd_tablet_responsive');
        });
    }

    /**
     * Test Portal OPD page grid layout
     */
    public function test_portal_opd_page_grid_layout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->assertPresent('.grid, .opd-grid, .card-grid')
                ->screenshot('portal_opd_grid_layout');
        });
    }

    /**
     * Test Portal OPD page empty state
     */
    public function test_portal_opd_page_empty_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd?search=nonexistentopd')
                ->waitForText('Portal OPD')
                ->whenAvailable('.empty-state, .no-results', function ($empty) {
                    $empty->assertSee('Tidak ada OPD');
                })
                ->screenshot('portal_opd_empty_state');
        });
    }

    /**
     * Test Portal OPD page pagination
     */
    public function test_portal_opd_page_pagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->whenAvailable('.pagination', function ($pagination) {
                    $pagination->assertPresent('a, button');
                })
                ->screenshot('portal_opd_pagination');
        });
    }

    /**
     * Test Portal OPD detail page loads successfully
     */
    public function test_portal_opd_detail_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->screenshot('portal_opd_detail_page_main');
        });
    }

    /**
     * Test Portal OPD detail page displays OPD information
     */
    public function test_portal_opd_detail_page_displays_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('.opd-name, .nama-opd, h1', function ($name) {
                    $name->assertPresent('span, h1, h2');
                })
                ->screenshot('portal_opd_detail_info');
        });
    }

    /**
     * Test Portal OPD detail page vision mission display
     */
    public function test_portal_opd_detail_page_vision_mission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('.vision, .visi', function ($vision) {
                    $vision->assertSee('Visi');
                })
                ->whenAvailable('.mission, .misi', function ($mission) {
                    $mission->assertSee('Misi');
                })
                ->screenshot('portal_opd_detail_vision_mission');
        });
    }

    /**
     * Test Portal OPD detail page leadership information
     */
    public function test_portal_opd_detail_page_leadership()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('.leadership, .pimpinan, .kepala-opd', function ($leadership) {
                    $leadership->assertSee('Kepala');
                })
                ->screenshot('portal_opd_detail_leadership');
        });
    }

    /**
     * Test Portal OPD detail page contact information
     */
    public function test_portal_opd_detail_page_contact_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('.contact-info, .informasi-kontak', function ($contact) {
                    $contact->assertSee('Kontak');
                })
                ->screenshot('portal_opd_detail_contact_info');
        });
    }

    /**
     * Test Portal OPD detail page address display
     */
    public function test_portal_opd_detail_page_address()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('.address, .alamat', function ($address) {
                    $address->assertSee('Alamat');
                })
                ->screenshot('portal_opd_detail_address');
        });
    }

    /**
     * Test Portal OPD detail page website link
     */
    public function test_portal_opd_detail_page_website_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('a[href*="http"]', function ($link) {
                    $link->assertSee('Website');
                })
                ->screenshot('portal_opd_detail_website_link');
        });
    }

    /**
     * Test Portal OPD detail page phone number clickable
     */
    public function test_portal_opd_detail_page_phone_clickable()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('a[href*="tel:"]', function ($phone) {
                    $phone->assertPresent('a');
                })
                ->screenshot('portal_opd_detail_phone_clickable');
        });
    }

    /**
     * Test Portal OPD detail page email clickable
     */
    public function test_portal_opd_detail_page_email_clickable()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('a[href*="mailto:"]', function ($email) {
                    $email->assertPresent('a');
                })
                ->screenshot('portal_opd_detail_email_clickable');
        });
    }

    /**
     * Test Portal OPD detail page breadcrumb
     */
    public function test_portal_opd_detail_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->assertSee('Beranda')
                ->assertSee('Portal OPD')
                ->screenshot('portal_opd_detail_breadcrumb');
        });
    }

    /**
     * Test Portal OPD detail page back to list
     */
    public function test_portal_opd_detail_page_back_to_list()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->click('a[href="/portal-opd"]')
                ->waitForText('Portal OPD')
                ->assertPathIs('/portal-opd')
                ->screenshot('portal_opd_detail_back_to_list');
        });
    }

    /**
     * Test Portal OPD page SEO elements
     */
    public function test_portal_opd_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->assertTitleContains('Portal OPD')
                ->screenshot('portal_opd_seo_check');
        });
    }

    /**
     * Test Portal OPD page performance
     */
    public function test_portal_opd_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'Portal OPD page should load within 5 seconds');
    }

    /**
     * Test Portal OPD page back to homepage
     */
    public function test_portal_opd_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('portal_opd_back_to_homepage');
        });
    }

    /**
     * Test Portal OPD page footer links
     */
    public function test_portal_opd_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('portal_opd_footer_links');
        });
    }

    /**
     * Test Portal OPD page navigation menu consistency
     */
    public function test_portal_opd_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('Kontak')
                ->assertSee('WBS')
                ->screenshot('portal_opd_navigation_consistency');
        });
    }

    /**
     * Test Portal OPD page search clear functionality
     */
    public function test_portal_opd_page_search_clear()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd?search=test')
                ->waitForText('Portal OPD')
                ->click('a[href="/portal-opd"]:not([href*="search"])')
                ->waitFor('body', 2)
                ->screenshot('portal_opd_search_clear');
        });
    }

    /**
     * Test Portal OPD page address toggle functionality
     */
    public function test_portal_opd_page_address_toggle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->whenAvailable('button[onclick*="toggleAddress"]', function ($button) {
                    $button->click();
                })
                ->waitFor('.address-display', 2)
                ->screenshot('portal_opd_address_toggle');
        });
    }

    /**
     * Test Portal OPD detail page quick actions
     */
    public function test_portal_opd_detail_page_quick_actions()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('.quick-actions, .aksi-cepat', function ($actions) {
                    $actions->assertSee('Aksi');
                })
                ->screenshot('portal_opd_detail_quick_actions');
        });
    }

    /**
     * Test Portal OPD detail page related links
     */
    public function test_portal_opd_detail_page_related_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->whenAvailable('.related-links, .link-terkait', function ($links) {
                    $links->assertSee('Link');
                })
                ->screenshot('portal_opd_detail_related_links');
        });
    }

    /**
     * Test Portal OPD detail page responsive design on mobile
     */
    public function test_portal_opd_detail_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->screenshot('portal_opd_detail_mobile_responsive');
        });
    }

    /**
     * Test Portal OPD detail page responsive design on tablet
     */
    public function test_portal_opd_detail_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/portal-opd/1')
                ->waitFor('body', 5)
                ->screenshot('portal_opd_detail_tablet_responsive');
        });
    }
}
