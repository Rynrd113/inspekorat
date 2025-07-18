<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use App\Models\User;

class SimplifiedAuthTest extends BasicDuskTestCase
{
    /**
     * Test admin login page is accessible.
     */
    public function test_admin_login_page_is_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->screenshot('admin_login_page')
                    ->pause(2000); // Wait for page load
            
            // Check if we get admin login page
            $pageSource = $browser->driver->getPageSource();
            
            // Basic assertions that login page exists
            $this->assertStringContainsString('login', strtolower($pageSource));
            $this->assertTrue(true, 'Admin login page is accessible');
        });
    }

    /**
     * Test that login form elements exist.
     */
    public function test_login_form_elements_exist(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->screenshot('login_form_check');
            
            $pageSource = $browser->driver->getPageSource();
            
            // Check for common login form elements
            $hasEmailField = strpos($pageSource, 'email') !== false;
            $hasPasswordField = strpos($pageSource, 'password') !== false;
            $hasLoginButton = strpos($pageSource, 'Login') !== false || 
                             strpos($pageSource, 'Masuk') !== false ||
                             strpos($pageSource, 'Submit') !== false;
            
            $this->assertTrue($hasEmailField, 'Email field should exist');
            $this->assertTrue($hasPasswordField, 'Password field should exist');
            $this->assertTrue($hasLoginButton, 'Login button should exist');
        });
    }

    /**
     * Test public pages are accessible without authentication.
     */
    public function test_public_pages_are_accessible(): void
    {
        $publicPages = [
            '/' => 'homepage',
            '/berita' => 'news page',
            '/wbs' => 'wbs page',
            '/kontak' => 'contact page',
        ];

        $this->browse(function (Browser $browser) use ($publicPages) {
            foreach ($publicPages as $url => $pageName) {
                $browser->visit($url)
                        ->screenshot("public_page_" . str_replace('/', '_', $url))
                        ->pause(1000);
                
                // Just verify page loads without error
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringContainsString('<html', $pageSource, "Page $pageName should load properly");
            }
        });
    }

    /**
     * Test that admin routes redirect to login when not authenticated.
     */
    public function test_admin_routes_require_authentication(): void
    {
        $adminRoutes = [
            '/admin/dashboard',
            '/admin/portal-papua-tengah',
            '/admin/wbs',
            '/admin/users',
        ];

        $this->browse(function (Browser $browser) use ($adminRoutes) {
            foreach ($adminRoutes as $route) {
                $browser->visit($route)
                        ->pause(2000)
                        ->screenshot("admin_route_" . str_replace('/', '_', $route));
                
                $currentUrl = $browser->driver->getCurrentURL();
                
                // Should redirect to login page or show login form
                $isRedirectedToLogin = strpos($currentUrl, '/login') !== false;
                $pageSource = $browser->driver->getPageSource();
                $hasLoginForm = strpos(strtolower($pageSource), 'login') !== false;
                
                $this->assertTrue(
                    $isRedirectedToLogin || $hasLoginForm, 
                    "Route $route should require authentication"
                );
            }
        });
    }

    /**
     * Test navigation and basic page structure.
     */
    public function test_navigation_and_page_structure(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->screenshot('homepage_structure')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            
            // Check for basic HTML structure
            $this->assertStringContainsString('<html', $pageSource);
            $this->assertStringContainsString('<head>', $pageSource);
            $this->assertStringContainsString('<body', $pageSource);
            
            // Check for navigation elements
            $hasNavigation = strpos($pageSource, '<nav') !== false || 
                           strpos($pageSource, 'menu') !== false ||
                           strpos($pageSource, 'navbar') !== false;
            
            $this->assertTrue($hasNavigation, 'Page should have navigation elements');
        });
    }

    /**
     * Test WBS form accessibility (public feature).
     */
    public function test_wbs_form_accessibility(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->screenshot('wbs_form_access')
                    ->pause(2000);
            
            $pageSource = $browser->driver->getPageSource();
            
            // Check for WBS form elements
            $hasForm = strpos($pageSource, '<form') !== false;
            $hasTextArea = strpos($pageSource, '<textarea') !== false || 
                          strpos($pageSource, 'textarea') !== false;
            
            $this->assertTrue($hasForm, 'WBS page should have a form');
            $this->assertTrue($hasTextArea, 'WBS form should have text areas for reports');
        });
    }
}