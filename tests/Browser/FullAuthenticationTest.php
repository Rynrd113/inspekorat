<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Database\Seeders\SimpleDuskSeeder;

class FullAuthenticationTest extends DuskTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SimpleDuskSeeder::class);
    }

    /**
     * Test successful admin login with real credentials.
     */
    public function test_successful_admin_login_with_real_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->screenshot('admin_login_form')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');
                    
            // Try different button texts
            try {
                $browser->press('Login');
            } catch (\Exception $e) {
                try {
                    $browser->press('Masuk');
                } catch (\Exception $e) {
                    try {
                        $browser->press('Sign In');
                    } catch (\Exception $e) {
                        $browser->click('button[type="submit"]');
                    }
                }
            }
            
            $browser->pause(3000)
                    ->screenshot('after_admin_login');
            
            $currentUrl = $browser->driver->getCurrentURL();
            
            // Should be redirected to dashboard or not on login page
            $isLoggedIn = strpos($currentUrl, '/admin/dashboard') !== false ||
                         strpos($currentUrl, '/admin/login') === false;
            
            $this->assertTrue($isLoggedIn, 'Admin should be logged in successfully');
        });
    }

    /**
     * Test content manager login and access.
     */
    public function test_content_manager_login_and_access(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'content@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('content_manager_logged_in');
            
            // Try to access content management pages
            $browser->visit('/admin/portal-papua-tengah')
                    ->pause(2000)
                    ->screenshot('content_manager_news_access');
            
            $pageSource = $browser->driver->getPageSource();
            $hasAccess = strpos($pageSource, '403') === false &&
                        strpos($pageSource, 'Unauthorized') === false;
            
            $this->assertTrue($hasAccess, 'Content manager should access news management');
        });
    }

    /**
     * Test WBS manager login and WBS access.
     */
    public function test_wbs_manager_login_and_access(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'wbs@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000);
            
            // Try to access WBS management
            $browser->visit('/admin/wbs')
                    ->pause(2000)
                    ->screenshot('wbs_manager_access');
            
            $pageSource = $browser->driver->getPageSource();
            $hasAccess = strpos($pageSource, '403') === false &&
                        strpos($pageSource, 'Unauthorized') === false;
            
            $this->assertTrue($hasAccess, 'WBS manager should access WBS management');
        });
    }

    /**
     * Test invalid login credentials.
     */
    public function test_invalid_login_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'wrong@email.com')
                    ->type('password', 'wrongpassword');
                    
            // Try different button texts
            try {
                $browser->press('Login');
            } catch (\Exception $e) {
                try {
                    $browser->press('Masuk');
                } catch (\Exception $e) {
                    try {
                        $browser->press('Sign In');
                    } catch (\Exception $e) {
                        $browser->click('button[type="submit"]');
                    }
                }
            }
            
            $browser->pause(3000)
                    ->screenshot('invalid_login_attempt');
            
            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();
            
            // Should stay on login page or show error
            $loginFailed = strpos($currentUrl, '/admin/login') !== false ||
                          strpos($pageSource, 'invalid') !== false ||
                          strpos($pageSource, 'error') !== false ||
                          strpos($pageSource, 'credentials') !== false;
            
            $this->assertTrue($loginFailed, 'Invalid credentials should not allow login');
        });
    }

    /**
     * Test super admin has access to user management.
     */
    public function test_super_admin_user_management_access(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'superadmin@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000);
            
            // Try to access user management
            $browser->visit('/admin/users')
                    ->pause(2000)
                    ->screenshot('super_admin_user_management');
            
            $pageSource = $browser->driver->getPageSource();
            $hasAccess = strpos($pageSource, '403') === false &&
                        strpos($pageSource, 'Unauthorized') === false;
            
            $this->assertTrue($hasAccess, 'Super admin should access user management');
        });
    }

    /**
     * Test content manager cannot access user management.
     */
    public function test_content_manager_cannot_access_user_management(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'content@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000);
            
            // Try to access user management (should be forbidden)
            $browser->visit('/admin/users')
                    ->pause(2000)
                    ->screenshot('content_manager_user_access_denied');
            
            $pageSource = $browser->driver->getPageSource();
            $accessDenied = strpos($pageSource, '403') !== false ||
                           strpos($pageSource, 'Unauthorized') !== false ||
                           strpos($pageSource, 'Forbidden') !== false;
            
            $this->assertTrue($accessDenied, 'Content manager should not access user management');
        });
    }

    /**
     * Test logout functionality.
     */
    public function test_logout_functionality(): void
    {
        $this->browse(function (Browser $browser) {
            // First login
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000);
            
            // Then logout
            $browser->visit('/admin/logout')
                    ->pause(2000)
                    ->screenshot('after_logout');
            
            // Try to access admin area
            $browser->visit('/admin/dashboard')
                    ->pause(2000)
                    ->screenshot('dashboard_after_logout');
            
            $currentUrl = $browser->driver->getCurrentURL();
            
            // Should be redirected to login
            $isLoggedOut = strpos($currentUrl, '/admin/login') !== false;
            
            $this->assertTrue($isLoggedOut, 'User should be logged out and redirected to login');
        });
    }

    /**
     * Test news visibility based on status.
     */
    public function test_news_visibility_based_on_status(): void
    {
        $this->browse(function (Browser $browser) {
            // Check public news page
            $browser->visit('/berita')
                    ->pause(2000)
                    ->screenshot('public_news_page');
            
            $pageSource = $browser->driver->getPageSource();
            
            // Should see published news but not draft
            $hasPublishedNews = strpos($pageSource, 'Berita Test Published') !== false;
            $hasDraftNews = strpos($pageSource, 'Berita Test Draft') !== false;
            
            $this->assertTrue($hasPublishedNews, 'Published news should be visible');
            $this->assertFalse($hasDraftNews, 'Draft news should not be visible');
        });
    }

    /**
     * Test WBS report tracking functionality.
     */
    public function test_wbs_report_tracking(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs/tracking')
                    ->pause(2000)
                    ->type('nomor_tiket', 'WBS-TEST-001')
                    ->press('Cari')
                    ->pause(2000)
                    ->screenshot('wbs_tracking_result');
            
            $pageSource = $browser->driver->getPageSource();
            
            // Should find the test report
            $hasReport = strpos($pageSource, 'Test WBS Report') !== false;
            
            $this->assertTrue($hasReport, 'Should be able to track WBS report');
        });
    }
}