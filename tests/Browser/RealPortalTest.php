<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;

class RealPortalTest extends BasicDuskTestCase
{
    /**
     * Test real homepage content and structure.
     */
    public function test_real_homepage_content_and_structure(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->screenshot('real_homepage_test')
                    ->pause(3000); // Wait for full page load
            
            $pageSource = $browser->driver->getPageSource();
            
            // Basic HTML structure
            $this->assertStringContainsString('<html', $pageSource);
            $this->assertStringContainsString('<head>', $pageSource);
            $this->assertStringContainsString('<body', $pageSource);
            
            // Check for Inspektorat content
            $hasInspektorat = strpos($pageSource, 'Inspektorat') !== false ||
                             strpos($pageSource, 'inspektorat') !== false;
            $hasPapuaTengah = strpos($pageSource, 'Papua Tengah') !== false ||
                             strpos($pageSource, 'papua tengah') !== false;
            
            $this->assertTrue($hasInspektorat, 'Homepage should mention Inspektorat');
            $this->assertTrue($hasPapuaTengah, 'Homepage should mention Papua Tengah');
        });
    }

    /**
     * Test news/berita page exists and is accessible.
     */
    public function test_news_page_exists_and_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                    ->screenshot('real_berita_page')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            
            // Should not be 404
            $is404 = strpos($pageSource, '404') !== false ||
                    strpos($pageSource, 'Not Found') !== false;
            
            $this->assertFalse($is404, 'Berita page should not be 404');
            
            // Should have news-related content
            $hasNewsContent = strpos($pageSource, 'berita') !== false ||
                             strpos($pageSource, 'news') !== false ||
                             strpos($pageSource, 'artikel') !== false;
            
            $this->assertTrue($hasNewsContent, 'Berita page should have news-related content');
        });
    }

    /**
     * Test WBS page structure and form.
     */
    public function test_wbs_page_structure_and_form(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->screenshot('real_wbs_page')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            
            // Should not be 404
            $is404 = strpos($pageSource, '404') !== false;
            $this->assertFalse($is404, 'WBS page should exist');
            
            // Should have WBS-related content
            $hasWbsContent = strpos($pageSource, 'WBS') !== false ||
                            strpos($pageSource, 'Whistleblowing') !== false ||
                            strpos($pageSource, 'pengaduan') !== false ||
                            strpos($pageSource, 'lapor') !== false;
            
            $this->assertTrue($hasWbsContent, 'WBS page should have whistleblowing-related content');
        });
    }

    /**
     * Test contact page accessibility.
     */
    public function test_contact_page_accessibility(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                    ->screenshot('real_contact_page')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            
            // Should not be 404
            $is404 = strpos($pageSource, '404') !== false;
            $this->assertFalse($is404, 'Contact page should exist');
            
            // Should have contact-related content
            $hasContactContent = strpos($pageSource, 'kontak') !== false ||
                                strpos($pageSource, 'contact') !== false ||
                                strpos($pageSource, 'alamat') !== false ||
                                strpos($pageSource, 'telepon') !== false;
            
            $this->assertTrue($hasContactContent, 'Contact page should have contact information');
        });
    }

    /**
     * Test admin login page functionality.
     */
    public function test_admin_login_page_functionality(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->screenshot('real_admin_login')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            $currentUrl = $browser->driver->getCurrentURL();
            
            // Should reach login page
            $isLoginPage = strpos($currentUrl, 'login') !== false ||
                          strpos($pageSource, 'login') !== false ||
                          strpos($pageSource, 'masuk') !== false;
            
            $this->assertTrue($isLoginPage, 'Should reach admin login page');
            
            // Should have form elements
            $hasForm = strpos($pageSource, '<form') !== false;
            $hasInputs = strpos($pageSource, '<input') !== false;
            
            $this->assertTrue($hasForm, 'Login page should have a form');
            $this->assertTrue($hasInputs, 'Login page should have input fields');
        });
    }

    /**
     * Test admin dashboard requires authentication.
     */
    public function test_admin_dashboard_requires_authentication(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/dashboard')
                    ->screenshot('real_admin_dashboard_unauth')
                    ->pause(2000);
            
            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();
            
            // Should be redirected to login or show auth requirement
            $isRedirected = strpos($currentUrl, 'login') !== false;
            $hasAuthError = strpos($pageSource, 'login') !== false ||
                           strpos($pageSource, 'unauthorized') !== false ||
                           strpos($pageSource, '401') !== false ||
                           strpos($pageSource, '403') !== false;
            
            $this->assertTrue(
                $isRedirected || $hasAuthError, 
                'Admin dashboard should require authentication'
            );
        });
    }

    /**
     * Test page performance and loading times.
     */
    public function test_page_performance_and_loading(): void
    {
        $pages = [
            '/' => 'homepage',
            '/berita' => 'news',
            '/kontak' => 'contact',
        ];

        $this->browse(function (Browser $browser) use ($pages) {
            foreach ($pages as $url => $pageName) {
                $startTime = microtime(true);
                
                $browser->visit($url)
                        ->pause(1000);
                
                $loadTime = microtime(true) - $startTime;
                
                // Assert reasonable loading time (under 5 seconds)
                $this->assertLessThan(5, $loadTime, "Page $pageName should load within 5 seconds");
                
                // Take performance screenshot
                $browser->screenshot("performance_" . str_replace('/', '_', $url));
            }
        });
    }

    /**
     * Test responsive design basic functionality.
     */
    public function test_responsive_design_basic(): void
    {
        $this->browse(function (Browser $browser) {
            // Test desktop size
            $browser->resize(1920, 1080)
                    ->visit('/')
                    ->screenshot('responsive_desktop')
                    ->pause(1000);
            
            // Test tablet size
            $browser->resize(768, 1024)
                    ->visit('/')
                    ->screenshot('responsive_tablet')
                    ->pause(1000);
            
            // Test mobile size
            $browser->resize(375, 667)
                    ->visit('/')
                    ->screenshot('responsive_mobile')
                    ->pause(1000);
            
            // Basic assertion - page should still load on all sizes
            $pageSource = $browser->driver->getPageSource();
            $this->assertStringContainsString('<html', $pageSource);
        });
    }
}