<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\DashboardPage;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use InteractsWithAuthentication;

    /**
     * Test login dengan credentials yang valid untuk setiap role
     */
    public function test_super_admin_dapat_login_dengan_credentials_valid()
    {
        $user = $this->createUserWithRole('super_admin');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->assertLoginSuccess();
        });
    }

    public function test_admin_dapat_login_dengan_credentials_valid()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->assertLoginSuccess();
        });
    }

    public function test_content_manager_dapat_login_dengan_credentials_valid()
    {
        $user = $this->createUserWithRole('content_manager');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->assertLoginSuccess();
        });
    }

    public function test_service_manager_dapat_login_dengan_credentials_valid()
    {
        $user = $this->createUserWithRole('service_manager');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->assertLoginSuccess();
        });
    }

    public function test_wbs_manager_dapat_login_dengan_credentials_valid()
    {
        $user = $this->createUserWithRole('wbs_manager');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->assertLoginSuccess();
        });
    }

    public function test_admin_berita_dapat_login_dengan_credentials_valid()
    {
        $user = $this->createUserWithRole('admin_berita');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->assertLoginSuccess();
        });
    }

    /**
     * Test login dengan credentials yang invalid
     */
    public function test_user_tidak_dapat_login_dengan_email_tidak_terdaftar()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->loginAndExpectFailure('notregistered@example.com', 'password')
                    ->assertLoginFailed();
        });
    }

    public function test_user_tidak_dapat_login_dengan_password_salah()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->loginAndExpectFailure($user->email, 'wrongpassword')
                    ->assertLoginFailed();
        });
    }

    public function test_user_tidak_dapat_login_dengan_email_dan_password_kosong()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->loginAndExpectFailure('', '')
                    ->assertLoginFailed();
        });
    }

    /**
     * Test form validation
     */
    public function test_login_form_validasi_email_format()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->fillEmail('invalid-email')
                    ->fillPassword('password')
                    ->submitLogin()
                    ->assertErrorMessage('Format email tidak valid');
        });
    }

    public function test_login_form_validasi_password_minimum_length()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->fillEmail('test@example.com')
                    ->fillPassword('123')
                    ->submitLogin()
                    ->assertErrorMessage('Password minimal 6 karakter');
        });
    }

    public function test_login_form_validasi_field_required()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->testFormValidation();
        });
    }

    /**
     * Test role-based access control
     */
    public function test_user_biasa_tidak_dapat_akses_admin_panel()
    {
        $user = $this->createUserWithRole('user');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->loginAndExpectFailure($user->email, 'password', 'Akses ditolak. Hanya admin yang bisa login.');
        });
    }

    public function test_inactive_user_tidak_dapat_login()
    {
        $user = $this->createUserWithRole('admin', ['is_active' => false]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->loginAndExpectFailure($user->email, 'password', 'Akun tidak aktif');
        });
    }

    /**
     * Test logout functionality
     */
    public function test_user_dapat_logout()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            $this->login($browser, $user);
            
            $browser->visit(new DashboardPage)
                    ->logout()
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test session management
     */
    public function test_session_timeout_redirect_ke_login()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            $this->login($browser, $user);
            
            // Simulate session timeout
            $browser->script('document.cookie = "laravel_session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"');
            
            $browser->visit('/admin/dashboard')
                    ->waitForLocation('/admin/login', 30)
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test remember me functionality
     */
    public function test_remember_me_functionality()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                    ->fillEmail($user->email)
                    ->fillPassword('password')
                    ->check('input[name="remember"]')
                    ->submitLogin()
                    ->waitForLocation('/admin/dashboard', 30);

            // Verify remember token is set
            $browser->assertCookieExists('remember_web_' . sha1(User::class));
        });
    }

    /**
     * Test security features
     */
    public function test_login_csrf_protection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->assertPresent('input[name="_token"]');
        });
    }

    public function test_password_field_is_masked()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->type('input[name="password"]', 'testpassword')
                    ->assertAttribute('input[name="password"]', 'type', 'password');
        });
    }

    /**
     * Test responsive design
     */
    public function test_login_form_responsive_design()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage);
            
            $this->testResponsiveDesign($browser, function ($browser, $device) {
                $browser->assertVisible('input[name="email"]')
                        ->assertVisible('input[name="password"]')
                        ->assertVisible('button[type="submit"]');
            });
        });
    }

    /**
     * Test accessibility
     */
    public function test_login_form_accessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->keys('input[name="email"]', ['{tab}'])
                    ->assertFocused('input[name="password"]')
                    ->keys('input[name="password"]', ['{tab}'])
                    ->assertFocused('button[type="submit"]');
        });
    }

    /**
     * Test concurrent sessions
     */
    public function test_multiple_concurrent_sessions()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user) {
            // Login in first browser
            $this->login($browser1, $user);
            
            // Login in second browser with same user
            $this->login($browser2, $user);
            
            // Both sessions should be valid
            $browser1->visit('/admin/dashboard')
                     ->assertPathIs('/admin/dashboard');
            
            $browser2->visit('/admin/dashboard')
                     ->assertPathIs('/admin/dashboard');
        });
    }

    /**
     * Test SQL injection protection
     */
    public function test_sql_injection_protection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->loginAndExpectFailure("admin@admin.com' OR '1'='1", "password' OR '1'='1")
                    ->assertLoginFailed();
        });
    }

    /**
     * Test XSS protection
     */
    public function test_xss_protection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->fillEmail('<script>alert("XSS")</script>')
                    ->fillPassword('password')
                    ->submitLogin()
                    ->assertDontSee('<script>alert("XSS")</script>');
        });
    }

    /**
     * Test brute force protection
     */
    public function test_brute_force_protection()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            // Multiple failed attempts
            for ($i = 0; $i < 6; $i++) {
                $browser->visit(new LoginPage)
                        ->loginAndExpectFailure($user->email, 'wrongpassword');
            }

            // Should show rate limit message
            $browser->assertSee('Too many login attempts')
                    ->orWhere('assertSee', 'Rate limit exceeded');
        });
    }

    /**
     * Test login redirect after authentication
     */
    public function test_login_redirect_intended_page()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            // Try to access protected page
            $browser->visit('/admin/wbs')
                    ->assertPathIs('/admin/login');

            // Login
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertPathIs('/admin/wbs');
        });
    }

    /**
     * Test performance - login process should be fast
     */
    public function test_login_performance()
    {
        $user = $this->createUserWithRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            $startTime = microtime(true);
            
            $browser->visit(new LoginPage)
                    ->login($user->email, 'password')
                    ->assertLoginSuccess();
            
            $endTime = microtime(true);
            $loginTime = $endTime - $startTime;
            
            // Login should complete within 5 seconds
            $this->assertLessThan(5, $loginTime, 'Login process took too long: ' . $loginTime . ' seconds');
        });
    }
}
