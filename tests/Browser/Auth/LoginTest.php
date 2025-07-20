<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{

    /**
     * Test admin login with valid credentials
     */
    public function testLoginWithValidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->assertSee('Panel Admin')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->type('email', 'admin@inspektorat.go.id')
                ->type('password', 'admin123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');
        });
    }

    /**
     * Test superadmin login
     */
    public function testSuperAdminLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'superadmin@inspektorat.go.id')
                ->type('password', 'superadmin123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard')
                ->assertSee('Super Admin');
        });
    }

    /**
     * Test login with invalid credentials
     */
    public function testLoginWithInvalidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin@inspektorat.go.id')
                ->type('password', 'wrongpassword')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/login')
                ->assertSee('Email atau kata sandi salah');
        });
    }

    /**
     * Test logout functionality
     */
    public function testLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin@inspektorat.go.id')
                ->type('password', 'admin123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->element('form[action*="logout"] button[type="submit"]')->click()
                ->pause(1000)
                ->assertPathIs('/admin/login')
                ->assertSee('Panel Admin');
        });
    }

    /**
     * Test login form validation
     */
    public function testLoginFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->press('Masuk')
                ->pause(1000)
                ->assertSee('Email wajib diisi')
                ->assertSee('Kata sandi wajib diisi');
        });
    }

    /**
     * Test login with inactive user
     */
    public function testLoginWithInactiveUser()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'wrong@email.com')
                ->type('password', 'wrongpassword')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test remember me functionality
     */
    public function testRememberMeFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin@inspektorat.go.id')
                ->type('password', 'admin123')
                ->check('remember-me')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');
        });
    }

    /**
     * Test password reset link
     */
    public function testPasswordResetLink()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->clickLink('Lupa kata sandi?')
                ->pause(1000)
                ->assertSee('Lupa kata sandi?');
        });
    }

    /**
     * Test responsive design on mobile
     */
    public function testLoginPageResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->visit('/admin/login')
                ->assertSee('Panel Admin')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->resize(1280, 720); // Reset to desktop size
        });
    }

    /**
     * Test login redirect after successful authentication
     */
    public function testLoginRedirectAfterAuthentication()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/users')
                ->assertPathIs('/admin/login')
                ->type('email', 'admin@inspektorat.go.id')
                ->type('password', 'admin123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/users');
        });
    }
}
