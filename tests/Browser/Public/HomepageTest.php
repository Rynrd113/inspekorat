<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomepageTest extends DuskTestCase
{
    /**
     * Test homepage loads successfully with all main elements
     */
    public function test_homepage_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Inspektorat Provinsi')
                ->assertSee('Papua Tengah')
                ->assertSee('Statistik Portal Papua Tengah')
                ->screenshot('homepage_main');
        });
    }

    /**
     * Test homepage navigation menu
     */
    public function test_homepage_navigation_menu()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('FAQ')
                ->assertSee('WBS')
                ->assertSee('Portal OPD')
                ->screenshot('homepage_navigation');
        });
    }

    /**
     * Test homepage statistics display
     */
    public function test_homepage_statistics_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertSee('Portal OPD')
                ->assertSee('Berita')
                ->assertSee('WBS')
                ->assertSee('Total Views')
                ->screenshot('homepage_statistics');
        });
    }

    /**
     * Test homepage recent news section
     */
    public function test_homepage_recent_news_section()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Berita Terbaru')
                ->screenshot('homepage_news_section');
        });
    }

    /**
     * Test homepage service cards
     */
    public function test_homepage_service_cards()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Profil Inspektorat')
                ->assertSee('Layanan Publik')
                ->assertSee('Dokumen Publik')
                ->assertSee('Galeri Kegiatan')
                ->assertSee('FAQ')
                ->assertSee('WBS')
                ->assertSee('Portal Berita')
                ->assertSee('Portal OPD')
                ->screenshot('homepage_service_cards');
        });
    }

    /**
     * Test homepage contact information
     */
    public function test_homepage_contact_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('#informasi')
                ->assertSee('Informasi Kontak')
                ->assertSee('Alamat Kantor')
                ->assertSee('Telepon')
                ->assertSee('Email')
                ->assertSee('Jam Operasional')
                ->screenshot('homepage_contact_info');
        });
    }

    /**
     * Test homepage responsive design on mobile
     */
    public function test_homepage_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Inspektorat Provinsi')
                ->assertSee('Papua Tengah')
                ->assertSee('Statistik Portal Papua Tengah')
                ->screenshot('homepage_mobile_responsive');
        });
    }

    /**
     * Test homepage responsive design on tablet
     */
    public function test_homepage_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Inspektorat Provinsi')
                ->assertSee('Papua Tengah')
                ->assertSee('Statistik Portal Papua Tengah')
                ->screenshot('homepage_tablet_responsive');
        });
    }

    /**
     * Test homepage navigation links work
     */
    public function test_homepage_navigation_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->click('a[href="/berita"]')
                ->waitForText('Berita', 10)
                ->assertPathIs('/berita')
                ->screenshot('homepage_navigation_berita');
        });
    }

    /**
     * Test homepage service card links
     */
    public function test_homepage_service_card_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->click('a[href="/portal-opd"]')
                ->waitForText('Portal OPD', 10)
                ->assertPathIs('/portal-opd')
                ->screenshot('homepage_service_link_portal_opd');
        });
    }

    /**
     * Test homepage footer links
     */
    public function test_homepage_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('footer')
                ->assertSee('Tautan Cepat')
                ->assertSee('Layanan')
                ->assertSee('Inspektorat Provinsi Papua Tengah')
                ->screenshot('homepage_footer');
        });
    }

    /**
     * Test homepage portal OPD showcase section
     */
    public function test_homepage_portal_opd_showcase()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('.bg-gray-50')
                ->assertSee('Portal Organisasi Perangkat Daerah')
                ->assertSee('Informasi OPD Terlengkap')
                ->assertSee('Mudah Diakses')
                ->assertSee('Selalu Update')
                ->assertSee('Kontak Langsung')
                ->screenshot('homepage_portal_opd_showcase');
        });
    }

    /**
     * Test homepage performance - page loads within reasonable time
     */
    public function test_homepage_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 10 seconds (more reasonable for complex page)
        $this->assertLessThan(10, $loadTime, 'Homepage should load within 10 seconds');
    }

    /**
     * Test homepage SEO elements
     */
    public function test_homepage_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi')
                ->assertTitleContains('Portal Informasi Pemerintahan')
                ->assertTitleContains('Inspektorat Papua Tengah')
                ->screenshot('homepage_seo_check');
        });
    }
}
