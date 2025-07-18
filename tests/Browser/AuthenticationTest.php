<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test admin login with valid credentials.
     */
    public function test_admin_login_with_valid_credentials()
    {
        $admin = $this->createAdmin([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/admin/login')
                    ->assertSee('Login')
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->assertSee($admin->name);
        });
    }

    /**
     * Test admin login with invalid credentials.
     */
    public function test_admin_login_with_invalid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'wrong@email.com')
                    ->type('password', 'wrongpassword')
                    ->press('Login')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('These credentials do not match our records')
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test admin logout functionality.
     */
    public function test_admin_logout()
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->assertPathIs('/admin/dashboard')
                    ->click('[data-toggle="dropdown"]') // User dropdown
                    ->waitFor('.dropdown-menu')
                    ->click('a[href*="logout"]')
                    ->waitForLocation('/')
                    ->assertPathIs('/')
                    ->visit('/admin/dashboard')
                    ->waitForLocation('/admin/login')
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test super admin role access.
     */
    public function test_super_admin_role_access()
    {
        $superAdmin = $this->createSuperAdmin([
            'name' => 'Super Admin',
        ]);

        $this->browse(function (Browser $browser) use ($superAdmin) {
            $this->loginAs($superAdmin, $browser);
            
            // Super admin should access all admin routes
            $browser->visit('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->visit('/admin/users')
                    ->assertDontSee('403')
                    ->assertDontSee('Unauthorized')
                    ->visit('/admin/audit-logs')
                    ->assertDontSee('403')
                    ->assertDontSee('Unauthorized')
                    ->visit('/admin/system-configuration')
                    ->assertDontSee('403')
                    ->assertDontSee('Unauthorized');
        });
    }

    /**
     * Test content manager role access restrictions.
     */
    public function test_content_manager_role_restrictions()
    {
        $contentManager = $this->createContentManager([
            'name' => 'Content Manager',
        ]);

        $this->browse(function (Browser $browser) use ($contentManager) {
            $this->loginAs($contentManager, $browser);
            
            // Content manager should access content-related routes
            $browser->visit('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->visit('/admin/portal-papua-tengah')
                    ->assertDontSee('403')
                    ->visit('/admin/galeri')
                    ->assertDontSee('403')
                    ->visit('/admin/dokumen')
                    ->assertDontSee('403');
                    
            // But not user management
            $browser->visit('/admin/users')
                    ->assertSee('403')
                    ->orAssertSee('Unauthorized');
        });
    }

    /**
     * Test OPD manager role access restrictions.
     */
    public function test_opd_manager_role_restrictions()
    {
        $opdManager = $this->createOpdManager([
            'name' => 'OPD Manager',
        ]);

        $this->browse(function (Browser $browser) use ($opdManager) {
            $this->loginAs($opdManager, $browser);
            
            // OPD manager should access OPD-related routes
            $browser->visit('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->visit('/admin/portal-opd')
                    ->assertDontSee('403');
                    
            // But not user management
            $browser->visit('/admin/users')
                    ->assertSee('403')
                    ->orAssertSee('Unauthorized');
        });
    }

    /**
     * Test WBS manager role access restrictions.
     */
    public function test_wbs_manager_role_restrictions()
    {
        $wbsManager = $this->createWbsManager([
            'name' => 'WBS Manager',
        ]);

        $this->browse(function (Browser $browser) use ($wbsManager) {
            $this->loginAs($wbsManager, $browser);
            
            // WBS manager should access WBS-related routes
            $browser->visit('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->visit('/admin/wbs')
                    ->assertDontSee('403');
                    
            // But not user management
            $browser->visit('/admin/users')
                    ->assertSee('403')
                    ->orAssertSee('Unauthorized');
        });
    }

    /**
     * Test middleware protection on admin routes.
     */
    public function test_middleware_protection_on_admin_routes()
    {
        $this->browse(function (Browser $browser) {
            // Test accessing admin routes without authentication
            $adminRoutes = [
                '/admin/dashboard',
                '/admin/users',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/portal-opd',
            ];

            foreach ($adminRoutes as $route) {
                $browser->visit($route)
                        ->waitForLocation('/admin/login')
                        ->assertPathIs('/admin/login');
            }
        });
    }

    /**
     * Test remember me functionality.
     */
    public function test_remember_me_functionality()
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/admin/login')
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->check('remember')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard');
                    
            // Close browser session and reopen
            $browser->quit();
            
            // Visit admin area again - should still be logged in due to remember me
            $browser->visit('/admin/dashboard')
                    ->assertDontSee('Login')
                    ->assertSee('Dashboard');
        });
    }

    /**
     * Test session timeout handling.
     */
    public function test_session_timeout_handling()
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            // Simulate session timeout by clearing browser storage
            $browser->script('localStorage.clear(); sessionStorage.clear();');
            
            // Try to access admin area
            $browser->refresh()
                    ->waitForLocation('/admin/login')
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test password validation on login form.
     */
    public function test_password_validation_on_login_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->press('Login') // Submit without password
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The password field is required');
        });
    }

    /**
     * Test email validation on login form.
     */
    public function test_email_validation_on_login_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'invalid-email')
                    ->type('password', 'password')
                    ->press('Login')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The email field must be a valid email address');
        });
    }

    /**
     * Test account lockout after multiple failed attempts.
     */
    public function test_account_lockout_after_failed_attempts()
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            // Attempt multiple failed logins
            for ($i = 0; $i < 6; $i++) {
                $browser->visit('/admin/login')
                        ->type('email', $admin->email)
                        ->type('password', 'wrongpassword')
                        ->press('Login')
                        ->waitFor('.alert-danger');
            }
            
            // Now try with correct password - should be locked out
            $browser->visit('/admin/login')
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->waitFor('.alert-danger')
                    ->assertSee('Too many login attempts');
        });
    }

    /**
     * Test role-based navigation menu display.
     */
    public function test_role_based_navigation_menu_display()
    {
        $this->browse(function (Browser $browser) {
            // Test super admin navigation
            $superAdmin = $this->createSuperAdmin();
            $this->loginAs($superAdmin, $browser);
            
            $browser->assertSee('User Management')
                    ->assertSee('System Configuration')
                    ->assertSee('Audit Logs');
                    
            $this->logout($browser);
            
            // Test content manager navigation
            $contentManager = $this->createContentManager();
            $this->loginAs($contentManager, $browser);
            
            $browser->assertDontSee('User Management')
                    ->assertDontSee('System Configuration')
                    ->assertSee('Content Management');
        });
    }
}