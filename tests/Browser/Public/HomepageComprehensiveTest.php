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
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Inspektorat Provinsi')
                ->assertSee('Papua Tengah')
                ->assertSee('Portal Informasi Pemerintahan')
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
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Portal OPD')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('FAQ')
                ->assertSee('WBS')
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
                ->waitForText('Inspektorat Provinsi')
                ->assertPresent('.hero-slider')
                ->assertSee('Pengawasan yang')
                ->assertSee('Akuntabel & Transparan')
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
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('#layanan')
                ->assertSee('Berita Inspektorat')
                ->assertPresent('#berita-list')
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
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('#pintasan-layanan')
                ->assertSee('Pintasan Layanan')
                ->assertSee('Profil Inspektorat')
                ->assertSee('Layanan Publik')
                ->assertSee('Dokumen Publik')
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
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('footer')
                ->assertPresent('footer')
                ->assertSee('Inspektorat Provinsi Papua Tengah')
                ->assertSee('Tautan Cepat')
                ->assertSee('Layanan')
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
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('#informasi')
                ->assertSee('Informasi Kontak')
                ->assertSee('Alamat Kantor')
                ->assertSee('Telepon')
                ->assertSee('Email')
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
                ->waitForText('Inspektorat Provinsi')
                ->scrollIntoView('.bg-gray-50')
                ->assertSee('Portal Organisasi Perangkat Daerah')
                ->assertSee('Informasi OPD Terlengkap')
                ->assertSee('Mudah Diakses')
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
                ->waitForText('Inspektorat Provinsi')
                ->assertSee('Inspektorat Provinsi')
                ->screenshot('homepage-mobile-responsive')
                ->resize(768, 1024) // iPad size
                ->assertSee('Inspektorat Provinsi')
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
                ->waitForText('Inspektorat Provinsi')
                ->assertTitleContains('Portal Informasi Pemerintahan')
                ->assertTitleContains('Inspektorat Papua Tengah')
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
            
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi');
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Assert page loads within reasonable time (10 seconds for complex page)
            $this->assertLessThan(10, $loadTime, 'Homepage should load within 10 seconds');
            
            $browser->screenshot('homepage-performance-check');
        });
    }
}