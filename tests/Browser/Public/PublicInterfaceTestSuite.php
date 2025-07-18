<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Public Interface Test Suite
 * Comprehensive test suite for all public-facing pages of Portal Inspektorat Papua Tengah
 */
class PublicInterfaceTestSuite extends DuskTestCase
{
    /**
     * Test complete public interface navigation flow
     */
    public function testCompletePublicInterfaceFlow()
    {
        $this->browse(function (Browser $browser) {
            // Start from homepage
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('public-suite-homepage');

            // Navigate through all main sections
            $this->navigateToAllPublicPages($browser);
            
            // Test interactive features
            $this->testInteractiveFeatures($browser);
            
            // Test responsive design
            $this->testResponsiveDesign($browser);
            
            // Test accessibility
            $this->testAccessibility($browser);
        });
    }

    /**
     * Navigate to all public pages
     */
    private function navigateToAllPublicPages(Browser $browser)
    {
        $pages = [
            '/profil' => 'Profile',
            '/pelayanan' => 'Services',
            '/dokumen' => 'Documents',
            '/galeri' => 'Gallery',
            '/faq' => 'FAQ',
            '/berita' => 'News',
            '/kontak' => 'Contact',
            '/wbs' => 'WBS',
            '/portal-opd' => 'Portal OPD'
        ];

        foreach ($pages as $route => $pageName) {
            $browser->visit($route)
                ->waitFor('body', 10)
                ->assertSee($pageName)
                ->screenshot('public-suite-' . strtolower(str_replace(' ', '-', $pageName)));
        }
    }

    /**
     * Test interactive features across all pages
     */
    private function testInteractiveFeatures(Browser $browser)
    {
        // Test search functionality
        $browser->visit('/pelayanan')
            ->waitFor('.search-form', 10)
            ->type('search', 'test')
            ->press('Search')
            ->waitFor('.search-results', 10)
            ->screenshot('public-suite-search');

        // Test form submissions
        $browser->visit('/kontak')
            ->waitFor('.contact-form', 10)
            ->type('nama', 'Test User')
            ->type('email', 'test@example.com')
            ->type('subjek', 'Test Subject')
            ->type('pesan', 'Test message')
            ->press('Send')
            ->waitFor('.success-message', 10)
            ->screenshot('public-suite-form-submit');

        // Test WBS form
        $browser->visit('/wbs')
            ->waitFor('.wbs-form', 10)
            ->type('judul', 'Test WBS Report')
            ->type('isi', 'Test content')
            ->check('anonim')
            ->press('Submit')
            ->waitFor('.success-message', 10)
            ->screenshot('public-suite-wbs-submit');
    }

    /**
     * Test responsive design
     */
    private function testResponsiveDesign(Browser $browser)
    {
        $viewports = [
            ['width' => 375, 'height' => 667, 'name' => 'mobile'],
            ['width' => 768, 'height' => 1024, 'name' => 'tablet'],
            ['width' => 1920, 'height' => 1080, 'name' => 'desktop']
        ];

        foreach ($viewports as $viewport) {
            $browser->resize($viewport['width'], $viewport['height'])
                ->visit('/')
                ->waitFor('body', 10)
                ->screenshot('public-suite-responsive-' . $viewport['name']);
        }
    }

    /**
     * Test accessibility features
     */
    private function testAccessibility(Browser $browser)
    {
        $browser->visit('/')
            ->waitFor('body', 10)
            ->assertSourceHas('alt=')
            ->assertSourceHas('aria-label')
            ->assertSourceHas('role=')
            ->screenshot('public-suite-accessibility');
    }

    /**
     * Test all public pages load successfully
     */
    public function testAllPublicPagesLoad()
    {
        $pages = [
            '/' => 'Homepage',
            '/profil' => 'Profile',
            '/pelayanan' => 'Services',
            '/dokumen' => 'Documents',
            '/galeri' => 'Gallery',
            '/faq' => 'FAQ',
            '/berita' => 'News',
            '/kontak' => 'Contact',
            '/wbs' => 'WBS',
            '/portal-opd' => 'Portal OPD'
        ];

        foreach ($pages as $route => $pageName) {
            $this->browse(function (Browser $browser) use ($route, $pageName) {
                $browser->visit($route)
                    ->waitFor('body', 10)
                    ->assertSee($pageName)
                    ->screenshot('public-suite-load-' . strtolower(str_replace(' ', '-', $pageName)));
            });
        }
    }

    /**
     * Test navigation consistency
     */
    public function testNavigationConsistency()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('.navbar', 10)
                    ->assertSee('Portal Inspektorat Papua Tengah')
                    ->assertSee('Beranda')
                    ->assertSee('Profil')
                    ->assertSee('Pelayanan')
                    ->assertSee('Dokumen')
                    ->assertSee('Galeri')
                    ->assertSee('FAQ')
                    ->assertSee('Berita')
                    ->assertSee('Kontak')
                    ->assertSee('WBS')
                    ->assertSee('Portal OPD')
                    ->screenshot('public-suite-nav-' . str_replace('/', 'home', $page));
            }
        });
    }

    /**
     * Test footer consistency
     */
    public function testFooterConsistency()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('.footer', 10)
                    ->assertSee('Portal Inspektorat Papua Tengah')
                    ->assertSee('Contact Information')
                    ->assertSee('Quick Links')
                    ->screenshot('public-suite-footer-' . str_replace('/', 'home', $page));
            }
        });
    }

    /**
     * Test breadcrumb navigation
     */
    public function testBreadcrumbNavigation()
    {
        $this->browse(function (Browser $browser) {
            $pages = [
                '/profil' => ['Beranda', 'Profil'],
                '/pelayanan' => ['Beranda', 'Pelayanan'],
                '/dokumen' => ['Beranda', 'Dokumen'],
                '/galeri' => ['Beranda', 'Galeri'],
                '/faq' => ['Beranda', 'FAQ'],
                '/berita' => ['Beranda', 'Berita'],
                '/kontak' => ['Beranda', 'Kontak'],
                '/wbs' => ['Beranda', 'WBS'],
                '/portal-opd' => ['Beranda', 'Portal OPD']
            ];

            foreach ($pages as $route => $breadcrumbs) {
                $browser->visit($route)
                    ->waitFor('.breadcrumb', 10);

                foreach ($breadcrumbs as $breadcrumb) {
                    $browser->assertSee($breadcrumb);
                }

                $browser->screenshot('public-suite-breadcrumb-' . str_replace('/', '', $route));
            }
        });
    }

    /**
     * Test search functionality across modules
     */
    public function testSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $searchPages = [
                '/pelayanan' => 'service',
                '/dokumen' => 'document',
                '/galeri' => 'gallery',
                '/faq' => 'faq',
                '/berita' => 'news',
                '/portal-opd' => 'opd'
            ];

            foreach ($searchPages as $route => $searchTerm) {
                $browser->visit($route)
                    ->waitFor('.search-form', 10)
                    ->type('search', $searchTerm)
                    ->press('Search')
                    ->waitFor('.search-results', 10)
                    ->screenshot('public-suite-search-' . str_replace('/', '', $route));
            }
        });
    }

    /**
     * Test form validation across all forms
     */
    public function testFormValidation()
    {
        $this->browse(function (Browser $browser) {
            // Test contact form validation
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->press('Send')
                ->waitFor('.error-message', 10)
                ->assertSee('validation error')
                ->screenshot('public-suite-validation-contact');

            // Test WBS form validation
            $browser->visit('/wbs')
                ->waitFor('.wbs-form', 10)
                ->press('Submit')
                ->waitFor('.error-message', 10)
                ->assertSee('validation error')
                ->screenshot('public-suite-validation-wbs');
        });
    }

    /**
     * Test performance across all pages
     */
    public function testPerformanceAcrossPages()
    {
        $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

        foreach ($pages as $page) {
            $this->browse(function (Browser $browser) use ($page) {
                $startTime = microtime(true);

                $browser->visit($page)
                    ->waitFor('body', 10);

                $endTime = microtime(true);
                $loadTime = $endTime - $startTime;

                // Page should load within 3 seconds
                $this->assertLessThan(3, $loadTime, "Page {$page} took too long to load: {$loadTime}s");
                $browser->screenshot('public-suite-performance-' . str_replace('/', 'home', $page));
            });
        }
    }

    /**
     * Test SEO elements across all pages
     */
    public function testSeoElements()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->assertSourceHas('<title>')
                    ->assertSourceHas('meta name="description"')
                    ->assertSourceHas('meta name="keywords"')
                    ->assertSourceHas('meta property="og:title"')
                    ->assertSourceHas('meta property="og:description"')
                    ->screenshot('public-suite-seo-' . str_replace('/', 'home', $page));
            }
        });
    }

    /**
     * Test error handling
     */
    public function testErrorHandling()
    {
        $this->browse(function (Browser $browser) {
            // Test 404 error handling
            $browser->visit('/nonexistent-page')
                ->waitFor('.error-page', 10)
                ->assertSee('404')
                ->assertSee('Page Not Found')
                ->screenshot('public-suite-error-404');

            // Test invalid parameters
            $browser->visit('/berita/invalid-slug')
                ->waitFor('.error-page', 10)
                ->assertSee('404')
                ->screenshot('public-suite-error-invalid-slug');
        });
    }

    /**
     * Test mobile-first responsive design
     */
    public function testMobileFirstDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667); // Mobile viewport

            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->assertPresent('.mobile-menu')
                    ->assertPresent('.mobile-navigation')
                    ->screenshot('public-suite-mobile-' . str_replace('/', 'home', $page));
            }
        });
    }
}