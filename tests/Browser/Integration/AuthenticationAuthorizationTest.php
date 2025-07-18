<?php

namespace Tests\Browser\Integration;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Authentication and Authorization Integration Test
 * Tests for Portal Inspektorat Papua Tengah authentication system
 */
class AuthenticationAuthorizationTest extends DuskTestCase
{
    /**
     * Test complete authentication workflow
     */
    public function testCompleteAuthenticationWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Test login page access
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->assertSee('Login')
                ->screenshot('auth-login-page');

            // Test successful login
            $browser->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('auth-successful-login');

            // Test authenticated dashboard access
            $browser->assertSee('Dashboard')
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('auth-dashboard-access');

            // Test logout
            $browser->click('.logout-btn')
                ->waitFor('.homepage-content', 10)
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('auth-logout-success');
        });
    }

    /**
     * Test authentication with invalid credentials
     */
    public function testInvalidCredentialsAuthentication()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'invalid@email.com')
                ->type('password', 'wrongpassword')
                ->press('Login')
                ->waitForText('Invalid credentials', 10)
                ->screenshot('auth-invalid-credentials');

            // Test still on login page
            $browser->assertSee('Login')
                ->screenshot('auth-login-page-after-invalid');
        });
    }

    /**
     * Test authentication for all 11 user roles
     */
    public function testAllUserRolesAuthentication()
    {
        $roles = [
            ['email' => 'superadmin@inspektorat.id', 'password' => 'superadmin123', 'role' => 'SuperAdmin'],
            ['email' => 'admin@inspektorat.id', 'password' => 'admin123', 'role' => 'Admin'],
            ['email' => 'admin.profil@inspektorat.id', 'password' => 'adminprofil123', 'role' => 'Admin Profil'],
            ['email' => 'admin.pelayanan@inspektorat.id', 'password' => 'adminpelayanan123', 'role' => 'Admin Pelayanan'],
            ['email' => 'admin.dokumen@inspektorat.id', 'password' => 'admindokumen123', 'role' => 'Admin Dokumen'],
            ['email' => 'admin.galeri@inspektorat.id', 'password' => 'admingaleri123', 'role' => 'Admin Galeri'],
            ['email' => 'admin.faq@inspektorat.id', 'password' => 'adminfaq123', 'role' => 'Admin FAQ'],
            ['email' => 'admin.berita@inspektorat.id', 'password' => 'adminberita123', 'role' => 'Admin Berita'],
            ['email' => 'admin.wbs@inspektorat.id', 'password' => 'adminwbs123', 'role' => 'Admin WBS'],
            ['email' => 'admin.opd@inspektorat.id', 'password' => 'adminopd123', 'role' => 'Admin Portal OPD'],
        ];

        $this->browse(function (Browser $browser) use ($roles) {
            foreach ($roles as $role) {
                $browser->visit('/login')
                    ->waitFor('input[name="email"]', 10)
                    ->type('email', $role['email'])
                    ->type('password', $role['password'])
                    ->press('Login')
                    ->waitForText('Dashboard', 10)
                    ->screenshot("auth-{$role['role']}-login");

                // Verify dashboard access for each role
                $browser->assertSee('Dashboard')
                    ->screenshot("auth-{$role['role']}-dashboard");

                // Logout
                $browser->click('.logout-btn')
                    ->waitFor('.homepage-content', 10);
            }
        });
    }

    /**
     * Test role-based access control - SuperAdmin
     */
    public function testSuperAdminAccessControl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test access to all modules
            $modules = [
                '/admin/portal-opd' => 'Portal OPD',
                '/admin/pelayanan' => 'Manajemen Pelayanan',
                '/admin/dokumen' => 'Manajemen Dokumen',
                '/admin/galeri' => 'Manajemen Galeri',
                '/admin/faq' => 'Manajemen FAQ',
                '/admin/portal-papua-tengah' => 'Manajemen Berita',
                '/admin/wbs' => 'Manajemen WBS',
                '/admin/users' => 'Manajemen User',
                '/admin/profil' => 'Profil Organisasi',
                '/admin/kontak' => 'Kontak'
            ];

            foreach ($modules as $route => $expectedText) {
                $browser->visit($route)
                    ->waitForText($expectedText, 10)
                    ->screenshot("auth-superadmin-access-{$route}");
            }
        });
    }

    /**
     * Test role-based access control - Admin Pelayanan
     */
    public function testAdminPelayananAccessControl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.pelayanan@inspektorat.id')
                ->type('password', 'adminpelayanan123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test allowed access
            $browser->visit('/admin/pelayanan')
                ->waitForText('Manajemen Pelayanan', 10)
                ->screenshot('auth-admin-pelayanan-allowed');

            // Test restricted access
            $browser->visit('/admin/users')
                ->waitForText('Unauthorized', 10)
                ->screenshot('auth-admin-pelayanan-restricted');
        });
    }

    /**
     * Test role-based access control - Admin Dokumen
     */
    public function testAdminDokumenAccessControl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.dokumen@inspektorat.id')
                ->type('password', 'admindokumen123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test allowed access
            $browser->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->screenshot('auth-admin-dokumen-allowed');

            // Test restricted access
            $browser->visit('/admin/pelayanan')
                ->waitForText('Unauthorized', 10)
                ->screenshot('auth-admin-dokumen-restricted');
        });
    }

    /**
     * Test role-based access control - Admin Galeri
     */
    public function testAdminGaleriAccessControl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.galeri@inspektorat.id')
                ->type('password', 'admingaleri123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test allowed access
            $browser->visit('/admin/galeri')
                ->waitForText('Manajemen Galeri', 10)
                ->screenshot('auth-admin-galeri-allowed');

            // Test restricted access
            $browser->visit('/admin/dokumen')
                ->waitForText('Unauthorized', 10)
                ->screenshot('auth-admin-galeri-restricted');
        });
    }

    /**
     * Test session management and security
     */
    public function testSessionManagementSecurity()
    {
        $this->browse(function (Browser $browser) {
            // Test session creation
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('auth-session-created');

            // Test session persistence
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->refresh()
                ->waitForText('Portal OPD', 10)
                ->screenshot('auth-session-persistence');

            // Test session timeout simulation
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10)
                ->visit('/admin/portal-opd')
                ->waitForText('Login', 10)
                ->screenshot('auth-session-timeout');
        });
    }

    /**
     * Test authentication remember me functionality
     */
    public function testRememberMeFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->check('remember')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('auth-remember-me-login');

            // Test persistent login
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10)
                ->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->screenshot('auth-remember-me-check');
        });
    }

    /**
     * Test password reset functionality
     */
    public function testPasswordResetFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->click('a[href="/password/reset"]')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->press('Send Password Reset Link')
                ->waitForText('reset link sent', 10)
                ->screenshot('auth-password-reset');
        });
    }

    /**
     * Test user profile authentication
     */
    public function testUserProfileAuthentication()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test profile access
            $browser->click('.profile-menu')
                ->waitFor('.profile-dropdown', 10)
                ->click('a[href="/profile"]')
                ->waitForText('Profile', 10)
                ->screenshot('auth-profile-access');

            // Test profile update
            $browser->type('name', 'Updated Super Admin')
                ->press('Update Profile')
                ->waitForText('Profile updated', 10)
                ->screenshot('auth-profile-update');
        });
    }

    /**
     * Test concurrent user sessions
     */
    public function testConcurrentUserSessions()
    {
        $this->browse(function (Browser $browser1, Browser $browser2) {
            // First user login
            $browser1->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('auth-concurrent-user1');

            // Second user login
            $browser2->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin@inspektorat.id')
                ->type('password', 'admin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('auth-concurrent-user2');

            // Test both users can access their allowed modules
            $browser1->visit('/admin/users')
                ->waitForText('Manajemen User', 10)
                ->screenshot('auth-concurrent-user1-access');

            $browser2->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->screenshot('auth-concurrent-user2-access');
        });
    }

    /**
     * Test authentication API integration
     */
    public function testAuthenticationApiIntegration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test API authentication
            $browser->visit('/api/user')
                ->waitFor('body', 10)
                ->screenshot('auth-api-integration');

            // Test API logout
            $browser->visit('/api/logout')
                ->waitFor('body', 10)
                ->visit('/admin/dashboard')
                ->waitForText('Login', 10)
                ->screenshot('auth-api-logout');
        });
    }

    /**
     * Test authentication security headers
     */
    public function testAuthenticationSecurityHeaders()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('auth-security-headers');

            // Test CSRF protection
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->assertPresent('meta[name="csrf-token"]')
                ->screenshot('auth-csrf-protection');
        });
    }

    /**
     * Test authentication rate limiting
     */
    public function testAuthenticationRateLimiting()
    {
        $this->browse(function (Browser $browser) {
            // Test multiple failed login attempts
            for ($i = 0; $i < 5; $i++) {
                $browser->visit('/login')
                    ->waitFor('input[name="email"]', 10)
                    ->type('email', 'invalid@email.com')
                    ->type('password', 'wrongpassword')
                    ->press('Login')
                    ->waitForText('Invalid credentials', 10);
            }

            // Test rate limiting activation
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'invalid@email.com')
                ->type('password', 'wrongpassword')
                ->press('Login')
                ->waitForText('Too many login attempts', 10)
                ->screenshot('auth-rate-limiting');
        });
    }

    /**
     * Test authentication with public access
     */
    public function testPublicAccessAuthentication()
    {
        $this->browse(function (Browser $browser) {
            // Test public pages without authentication
            $publicPages = [
                '/' => 'Portal Inspektorat Papua Tengah',
                '/profil' => 'Profil Organisasi',
                '/pelayanan' => 'Layanan Kami',
                '/dokumen' => 'Dokumen',
                '/galeri' => 'Galeri',
                '/berita' => 'Berita',
                '/faq' => 'FAQ',
                '/kontak' => 'Kontak',
                '/wbs' => 'WBS',
                '/portal-opd' => 'Portal OPD'
            ];

            foreach ($publicPages as $route => $expectedText) {
                $browser->visit($route)
                    ->waitForText($expectedText, 10)
                    ->screenshot("auth-public-access-{$route}");
            }

            // Test admin pages redirect to login
            $browser->visit('/admin/dashboard')
                ->waitForText('Login', 10)
                ->screenshot('auth-admin-redirect');
        });
    }
}
