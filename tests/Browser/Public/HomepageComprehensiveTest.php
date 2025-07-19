<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomepageComprehensiveTest extends DuskTestCase
{
    /**
     * Test homepage basic functionality
     */
    public function testHomepageBasicFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Portal')
                ->assertSee('Papua Tengah')
                ->screenshot('homepage-main')
                ->assertPresent('nav')
                ->assertPresent('footer');
        });
    }

    /**
     * Test homepage navigation
     */
    public function testHomepageNavigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('Berita')
                ->assertSee('Kontak')
                ->screenshot('homepage-navigation');
        });
    }

    /**
     * Test homepage hero section
     */
    public function testHomepageHeroSection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('.hero-section, .banner, .jumbotron')
                ->screenshot('homepage-hero-section');
        });
    }

    /**
     * Test homepage news section
     */
    public function testHomepageNewsSection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Berita')
                ->screenshot('homepage-news-section');
        });
    }

    /**
     * Test homepage services section
     */
    public function testHomepageServicesSection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Pelayanan')
                ->screenshot('homepage-services-section');
        });
    }

    /**
     * Test homepage footer
     */
    public function testHomepageFooter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('footer')
                ->screenshot('homepage-footer');
        });
    }

    /**
     * Test homepage contact information
     */
    public function testHomepageContactInfo()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Kontak')
                ->screenshot('homepage-contact-info');
        });
    }

    /**
     * Test homepage portal OPD showcase
     */
    public function testHomepagePortalOpdShowcase()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Portal OPD')
                ->screenshot('homepage-portal-opd-showcase');
        });
    }

    /**
     * Test homepage mobile responsive
     */
    public function testHomepageMobileResponsive()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone size
                ->visit('/')
                ->assertSee('Portal')
                ->screenshot('homepage-mobile-responsive')
                ->resize(768, 1024) // iPad size
                ->assertSee('Portal')
                ->screenshot('homepage-tablet-responsive')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test homepage SEO elements
     */
    public function testHomepageSEO()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('title')
                ->assertPresent('meta[name="description"]')
                ->screenshot('homepage-seo-check');
        });
    }

    /**
     * Test homepage loading performance
     */
    public function testHomepageLoadingPerformance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            $browser->visit('/');
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Assert page loads within reasonable time (5 seconds)
            $this->assertLessThan(5, $loadTime, 'Homepage should load within 5 seconds');
            
            $browser->screenshot('homepage-performance-check');
        });
    }
}