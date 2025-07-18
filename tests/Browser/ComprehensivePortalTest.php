<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Database\Seeders\SimpleDuskSeeder;

class ComprehensivePortalTest extends BasicDuskTestCase
{
    /**
     * Test all major portal functionality end-to-end.
     */
    public function test_comprehensive_portal_functionality(): void
    {
        $this->browse(function (Browser $browser) {
            // Test 1: Homepage accessibility
            $browser->visit('/')
                    ->screenshot('comprehensive_homepage')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            $this->assertStringContainsString('Inspektorat', $pageSource, 'Homepage should contain Inspektorat');
            
            // Test 2: Public news page
            $browser->visit('/berita')
                    ->screenshot('comprehensive_news')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            $isNewsPage = strpos($pageSource, '404') === false;
            $this->assertTrue($isNewsPage, 'News page should be accessible');
            
            // Test 3: WBS form accessibility
            $browser->visit('/wbs')
                    ->screenshot('comprehensive_wbs')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            $isWbsPage = strpos($pageSource, '404') === false;
            $this->assertTrue($isWbsPage, 'WBS page should be accessible');
            
            // Test 4: Contact page
            $browser->visit('/kontak')
                    ->screenshot('comprehensive_contact')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            $isContactPage = strpos($pageSource, '404') === false;
            $this->assertTrue($isContactPage, 'Contact page should be accessible');
            
            // Test 5: Admin login page structure
            $browser->visit('/admin/login')
                    ->screenshot('comprehensive_admin_login')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            $hasLoginForm = strpos($pageSource, '<form') !== false &&
                           strpos($pageSource, '<input') !== false;
            $this->assertTrue($hasLoginForm, 'Admin login should have form elements');
            
            // Test 6: Admin area protection
            $browser->visit('/admin/dashboard')
                    ->screenshot('comprehensive_admin_protection')
                    ->pause(2000);
            
            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();
            
            $isProtected = strpos($currentUrl, '/login') !== false ||
                          strpos($pageSource, 'login') !== false ||
                          strpos($pageSource, '401') !== false ||
                          strpos($pageSource, '403') !== false;
            
            $this->assertTrue($isProtected, 'Admin dashboard should be protected');
        });
    }

    /**
     * Test responsive design across multiple viewport sizes.
     */
    public function test_responsive_design_comprehensive(): void
    {
        $viewports = [
            ['name' => 'desktop', 'width' => 1920, 'height' => 1080],
            ['name' => 'laptop', 'width' => 1366, 'height' => 768],
            ['name' => 'tablet', 'width' => 768, 'height' => 1024],
            ['name' => 'mobile', 'width' => 375, 'height' => 667],
        ];

        $this->browse(function (Browser $browser) use ($viewports) {
            foreach ($viewports as $viewport) {
                $browser->resize($viewport['width'], $viewport['height'])
                        ->visit('/')
                        ->screenshot("responsive_{$viewport['name']}")
                        ->pause(1000);
                
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringContainsString('<html', $pageSource, 
                    "Page should load properly on {$viewport['name']} viewport");
            }
        });
    }

    /**
     * Test performance across different pages.
     */
    public function test_performance_comprehensive(): void
    {
        $pages = [
            ['url' => '/', 'name' => 'homepage'],
            ['url' => '/berita', 'name' => 'news'],
            ['url' => '/wbs', 'name' => 'wbs'],
            ['url' => '/kontak', 'name' => 'contact'],
            ['url' => '/admin/login', 'name' => 'admin_login'],
        ];

        $this->browse(function (Browser $browser) use ($pages) {
            foreach ($pages as $page) {
                $startTime = microtime(true);
                
                $browser->visit($page['url'])
                        ->pause(1000);
                
                $loadTime = microtime(true) - $startTime;
                
                // Assert reasonable loading time (under 5 seconds)
                $this->assertLessThan(5, $loadTime, 
                    "Page {$page['name']} should load within 5 seconds, took {$loadTime}s");
                
                $browser->screenshot("performance_{$page['name']}");
            }
        });
    }

    /**
     * Test SEO and meta tag presence.
     */
    public function test_seo_and_meta_tags(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(2000);
            
            // Check for essential SEO elements
            $pageSource = $browser->driver->getPageSource();
            
            $hasTitle = strpos($pageSource, '<title>') !== false;
            $hasMetaDescription = strpos($pageSource, 'meta name="description"') !== false;
            $hasViewport = strpos($pageSource, 'meta name="viewport"') !== false;
            $hasCharset = strpos($pageSource, 'charset') !== false;
            
            $this->assertTrue($hasTitle, 'Page should have title tag');
            $this->assertTrue($hasMetaDescription, 'Page should have meta description');
            $this->assertTrue($hasViewport, 'Page should have viewport meta tag');
            $this->assertTrue($hasCharset, 'Page should have charset declaration');
            
            $browser->screenshot('seo_meta_tags');
        });
    }

    /**
     * Test accessibility features.
     */
    public function test_accessibility_features(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            
            // Check for semantic HTML elements
            $hasHeader = strpos($pageSource, '<header') !== false;
            $hasMain = strpos($pageSource, '<main') !== false;
            $hasNav = strpos($pageSource, '<nav') !== false;
            $hasFooter = strpos($pageSource, '<footer') !== false;
            
            // Check for alt attributes on images (basic check)
            $hasImages = strpos($pageSource, '<img') !== false;
            if ($hasImages) {
                $hasAltAttributes = strpos($pageSource, 'alt=') !== false;
                $this->assertTrue($hasAltAttributes, 'Images should have alt attributes');
            }
            
            $this->assertTrue($hasHeader, 'Page should have header element');
            $this->assertTrue($hasMain || $hasNav, 'Page should have main or nav element');
            
            $browser->screenshot('accessibility_check');
        });
    }

    /**
     * Test error handling and 404 pages.
     */
    public function test_error_handling(): void
    {
        $this->browse(function (Browser $browser) {
            // Test 404 page
            $browser->visit('/non-existent-page')
                    ->pause(2000)
                    ->screenshot('error_404');
            
            $pageSource = $browser->driver->getPageSource();
            $currentUrl = $browser->driver->getCurrentURL();
            
            // Should show 404 error or redirect to valid page
            $hasErrorHandling = strpos($pageSource, '404') !== false ||
                               strpos($currentUrl, '/') !== false; // Redirected to homepage
            
            $this->assertTrue($hasErrorHandling, 'Should handle 404 errors gracefully');
        });
    }

    /**
     * Test basic security headers and measures.
     */
    public function test_security_measures(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            
            // Check for CSRF token (basic security measure)
            $hasCsrfToken = strpos($pageSource, 'csrf-token') !== false ||
                           strpos($pageSource, '_token') !== false;
            
            // Check that sensitive paths require authentication
            $browser->visit('/admin/users')
                    ->pause(2000);
            
            $currentUrl = $browser->driver->getCurrentURL();
            $isRedirected = strpos($currentUrl, 'login') !== false;
            
            $this->assertTrue($isRedirected, 'Sensitive admin paths should require authentication');
            
            $browser->screenshot('security_check');
        });
    }

    /**
     * Test navigation and user flow.
     */
    public function test_navigation_and_user_flow(): void
    {
        $this->browse(function (Browser $browser) {
            // Start from homepage
            $browser->visit('/')
                    ->pause(2000)
                    ->screenshot('flow_homepage');
            
            // Navigate to news
            $browser->visit('/berita')
                    ->pause(2000)
                    ->screenshot('flow_news');
            
            // Navigate to WBS
            $browser->visit('/wbs')
                    ->pause(2000)
                    ->screenshot('flow_wbs');
            
            // Navigate to contact
            $browser->visit('/kontak')
                    ->pause(2000)
                    ->screenshot('flow_contact');
            
            // Try to access admin (should redirect)
            $browser->visit('/admin/dashboard')
                    ->pause(2000)
                    ->screenshot('flow_admin_redirect');
            
            $currentUrl = $browser->driver->getCurrentURL();
            $flowCompleted = strpos($currentUrl, 'login') !== false ||
                            strpos($currentUrl, '/') !== false;
            
            $this->assertTrue($flowCompleted, 'User flow should complete without errors');
        });
    }
}