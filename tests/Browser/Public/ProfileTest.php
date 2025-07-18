<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProfileTest extends DuskTestCase
{
    /**
     * Test profile page loads successfully
     */
    public function test_profile_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Visi')
                ->assertSee('Misi')
                ->assertSee('Sejarah')
                ->screenshot('profile_page_main');
        });
    }

    /**
     * Test profile page displays organization information
     */
    public function test_profile_page_displays_organization_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Terwujudnya Pengawasan Internal yang Profesional')
                ->assertSee('Melaksanakan pengawasan internal yang berkualitas')
                ->assertSee('Memberikan assurance dan consulting yang optimal')
                ->assertSee('Meningkatkan kapasitas pengawasan internal')
                ->assertSee('Memperkuat sistem pengendalian internal pemerintah')
                ->screenshot('profile_organization_info');
        });
    }

    /**
     * Test profile page mission items display
     */
    public function test_profile_page_mission_items()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Melaksanakan pengawasan internal yang berkualitas')
                ->assertSee('Memberikan assurance dan consulting yang optimal')
                ->assertSee('Meningkatkan kapasitas pengawasan internal')
                ->assertSee('Memperkuat sistem pengendalian internal pemerintah')
                ->screenshot('profile_mission_items');
        });
    }

    /**
     * Test profile page quick links section
     */
    public function test_profile_page_quick_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Akses Cepat')
                ->assertSee('Portal Berita')
                ->assertSee('Whistleblowing System')
                ->assertSee('Dokumen Publik')
                ->assertSee('Portal OPD')
                ->screenshot('profile_quick_links');
        });
    }

    /**
     * Test profile page navigation breadcrumb
     */
    public function test_profile_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->screenshot('profile_breadcrumb');
        });
    }

    /**
     * Test profile page responsive design on mobile
     */
    public function test_profile_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Visi')
                ->assertSee('Misi')
                ->screenshot('profile_mobile_responsive');
        });
    }

    /**
     * Test profile page responsive design on tablet
     */
    public function test_profile_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Visi')
                ->assertSee('Misi')
                ->screenshot('profile_tablet_responsive');
        });
    }

    /**
     * Test profile page links to other sections
     */
    public function test_profile_page_internal_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->click('a[href*="berita"]')
                ->waitForText('Berita')
                ->assertPathIs('/berita')
                ->screenshot('profile_link_to_news');
        });
    }

    /**
     * Test profile page WBS link
     */
    public function test_profile_page_wbs_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->click('a[href*="wbs"]')
                ->waitForText('Whistleblowing System')
                ->assertPathIs('/wbs')
                ->screenshot('profile_link_to_wbs');
        });
    }

    /**
     * Test profile page dokumen link
     */
    public function test_profile_page_dokumen_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->click('a[href*="dokumen"]')
                ->waitForText('Dokumen')
                ->assertPathIs('/dokumen')
                ->screenshot('profile_link_to_documents');
        });
    }

    /**
     * Test profile page portal OPD link
     */
    public function test_profile_page_portal_opd_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->click('a[href*="portal-opd"]')
                ->waitForText('Portal OPD')
                ->assertPathIs('/portal-opd')
                ->screenshot('profile_link_to_portal_opd');
        });
    }

    /**
     * Test profile page SEO elements
     */
    public function test_profile_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertTitleContains('Profil')
                ->assertTitleContains('Papua Tengah')
                ->screenshot('profile_seo_check');
        });
    }

    /**
     * Test profile page performance
     */
    public function test_profile_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'Profile page should load within 5 seconds');
    }

    /**
     * Test profile page back to homepage
     */
    public function test_profile_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('profile_back_to_homepage');
        });
    }

    /**
     * Test profile page displays contact information
     */
    public function test_profile_page_contact_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Jl. Raya Nabire No. 123')
                ->assertSee('(0984) 21234')
                ->assertSee('inspektorat@paputengah.go.id')
                ->screenshot('profile_contact_info');
        });
    }

    /**
     * Test profile page scroll functionality
     */
    public function test_profile_page_scroll()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->screenshot('profile_page_footer');
        });
    }
}
