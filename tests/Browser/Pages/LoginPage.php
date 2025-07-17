<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class LoginPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url()
    {
        return '/admin/login';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertSee('Login')
                ->assertPresent('form');
    }

    /**
     * Get the element shortcuts for the page.
     */
    public function elements()
    {
        return [
            '@email' => 'input[name="email"]',
            '@password' => 'input[name="password"]',
            '@remember' => 'input[name="remember"]',
            '@submit' => 'button[type="submit"]',
            '@forgot-password' => 'a[href*="forgot-password"]',
            '@error-message' => '.alert-danger',
            '@success-message' => '.alert-success',
            '@loading' => '.loading, .spinner',
        ];
    }

    /**
     * Login dengan credentials
     */
    public function login(Browser $browser, string $email, string $password, bool $remember = false)
    {
        $browser->type('@email', $email)
                ->type('@password', $password);

        if ($remember) {
            $browser->check('@remember');
        }

        $browser->press('@submit')
                ->waitForLocation('/admin/dashboard', 30);

        return $browser;
    }

    /**
     * Attempt login and expect success
     */
    public function loginAndExpectSuccess(Browser $browser, string $email, string $password, bool $remember = false)
    {
        $this->login($browser, $email, $password, $remember);
        
        $browser->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');

        return $browser;
    }

    /**
     * Attempt login and expect failure
     */
    public function loginAndExpectFailure(Browser $browser, string $email, string $password, string $expectedError = null)
    {
        $browser->type('@email', $email)
                ->type('@password', $password)
                ->press('@submit')
                ->waitFor('@error-message', 10)
                ->assertPathIs($this->url());

        if ($expectedError) {
            $browser->assertSee($expectedError);
        }

        return $browser;
    }

    /**
     * Assert login form is visible
     */
    public function assertLoginFormVisible(Browser $browser)
    {
        $browser->assertVisible('@email')
                ->assertVisible('@password')
                ->assertVisible('@submit');

        return $browser;
    }

    /**
     * Assert error message is shown
     */
    public function assertErrorMessage(Browser $browser, string $message = null)
    {
        $browser->waitFor('@error-message', 10);
        
        if ($message) {
            $browser->assertSee($message);
        }

        return $browser;
    }

    /**
     * Assert success message is shown
     */
    public function assertSuccessMessage(Browser $browser, string $message = null)
    {
        $browser->waitFor('@success-message', 10);
        
        if ($message) {
            $browser->assertSee($message);
        }

        return $browser;
    }

    /**
     * Fill email field
     */
    public function fillEmail(Browser $browser, string $email)
    {
        $browser->clear('@email')->type('@email', $email);
        return $browser;
    }

    /**
     * Fill password field
     */
    public function fillPassword(Browser $browser, string $password)
    {
        $browser->clear('@password')->type('@password', $password);
        return $browser;
    }

    /**
     * Submit login form
     */
    public function submitLogin(Browser $browser)
    {
        $browser->press('@submit');
        return $browser;
    }

    /**
     * Test various login scenarios
     */
    public function testLoginScenarios(Browser $browser)
    {
        // Test empty fields
        $browser->clear('@email')
                ->clear('@password')
                ->press('@submit')
                ->waitFor('@error-message', 5);

        // Test invalid email format
        $browser->type('@email', 'invalid-email')
                ->type('@password', 'password')
                ->press('@submit')
                ->waitFor('@error-message', 5);

        // Test with SQL injection attempts
        $browser->clear('@email')
                ->clear('@password')
                ->type('@email', "admin@admin.com' OR '1'='1")
                ->type('@password', "password' OR '1'='1")
                ->press('@submit')
                ->waitFor('@error-message', 5);

        return $browser;
    }

    /**
     * Test password field security
     */
    public function testPasswordSecurity(Browser $browser)
    {
        $browser->type('@password', 'testpassword');
        
        // Assert password field is masked
        $browser->assertAttribute('@password', 'type', 'password');
        
        return $browser;
    }

    /**
     * Test remember me functionality
     */
    public function testRememberMe(Browser $browser, string $email, string $password)
    {
        $browser->type('@email', $email)
                ->type('@password', $password)
                ->check('@remember')
                ->press('@submit');

        return $browser;
    }

    /**
     * Test login rate limiting
     */
    public function testRateLimiting(Browser $browser)
    {
        for ($i = 0; $i < 6; $i++) {
            $browser->clear('@email')
                    ->clear('@password')
                    ->type('@email', 'test@example.com')
                    ->type('@password', 'wrongpassword')
                    ->press('@submit')
                    ->pause(1000);
        }

        // Should show rate limit message
        $browser->assertSee('Too many login attempts')
                ->orWhere('Rate limit exceeded');

        return $browser;
    }

    /**
     * Test accessibility features
     */
    public function testAccessibility(Browser $browser)
    {
        // Test keyboard navigation
        $browser->keys('@email', ['{tab}'])
                ->assertFocused('@password')
                ->keys('@password', ['{tab}'])
                ->assertFocused('@submit');

        // Test ARIA labels
        $browser->assertAttribute('@email', 'aria-label', 'Email')
                ->assertAttribute('@password', 'aria-label', 'Password');

        return $browser;
    }

    /**
     * Test form validation
     */
    public function testFormValidation(Browser $browser)
    {
        // Test required fields
        $browser->clear('@email')
                ->clear('@password')
                ->press('@submit')
                ->waitFor('@error-message', 5)
                ->assertSee('Email wajib diisi')
                ->assertSee('Password wajib diisi');

        // Test email format validation
        $browser->type('@email', 'invalid-email')
                ->press('@submit')
                ->waitFor('@error-message', 5)
                ->assertSee('Format email tidak valid');

        // Test password minimum length
        $browser->clear('@password')
                ->type('@password', '123')
                ->press('@submit')
                ->waitFor('@error-message', 5)
                ->assertSee('Password minimal 6 karakter');

        return $browser;
    }

    /**
     * Test CSRF protection
     */
    public function testCSRFProtection(Browser $browser)
    {
        // Assert CSRF token is present
        $browser->assertPresent('input[name="_token"]');

        return $browser;
    }

    /**
     * Test responsive design
     */
    public function testResponsiveDesign(Browser $browser)
    {
        $sizes = [
            'mobile' => [375, 667],
            'tablet' => [768, 1024],
            'desktop' => [1920, 1080],
        ];

        foreach ($sizes as $device => $dimensions) {
            $browser->resize($dimensions[0], $dimensions[1])
                    ->pause(1000)
                    ->assertVisible('@email')
                    ->assertVisible('@password')
                    ->assertVisible('@submit');
        }

        return $browser;
    }

    /**
     * Assert login success
     */
    public function assertLoginSuccess(Browser $browser)
    {
        $browser->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');

        return $browser;
    }

    /**
     * Assert login failed
     */
    public function assertLoginFailed(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->waitFor('@error-message', 10);

        return $browser;
    }
}

        if ($remember) {
            $browser->check('@remember');
        }

        $browser->press('@submit');

        return $browser;
    }

    /**
     * Login dengan user model
     */
    public function loginWithUser(Browser $browser, $user, bool $remember = false)
    {
        return $this->login($browser, $user->email, 'password', $remember);
    }

    /**
     * Assert login berhasil
     */
    public function assertLoginSuccess(Browser $browser)
    {
        $browser->waitForLocation('/admin/dashboard', 30)
                ->assertSee('Dashboard');

        return $browser;
    }

    /**
     * Assert login gagal
     */
    public function assertLoginFailed(Browser $browser, string $expectedError = 'These credentials do not match our records.')
    {
        $browser->assertPathIs($this->url())
                ->assertPresent('@error-message')
                ->assertSee($expectedError);

        return $browser;
    }

    /**
     * Assert form validation errors
     */
    public function assertValidationErrors(Browser $browser, array $expectedErrors)
    {
        $browser->assertPathIs($this->url());

        foreach ($expectedErrors as $field => $message) {
            $browser->assertSee($message);
        }

        return $browser;
    }

    /**
     * Click forgot password link
     */
    public function clickForgotPassword(Browser $browser)
    {
        $browser->click('@forgot-password');

        return $browser;
    }

    /**
     * Test empty form submission
     */
    public function testEmptyFormSubmission(Browser $browser)
    {
        $browser->press('@submit')
                ->assertPathIs($this->url())
                ->assertSee('The email field is required')
                ->assertSee('The password field is required');

        return $browser;
    }

    /**
     * Test invalid email format
     */
    public function testInvalidEmailFormat(Browser $browser)
    {
        $browser->type('@email', 'invalid-email')
                ->type('@password', 'password')
                ->press('@submit')
                ->assertPathIs($this->url())
                ->assertSee('The email field must be a valid email address');

        return $browser;
    }

    /**
     * Test password visibility toggle
     */
    public function testPasswordVisibilityToggle(Browser $browser)
    {
        $browser->type('@password', 'password')
                ->assertAttribute('@password', 'type', 'password')
                ->click('.password-toggle')
                ->assertAttribute('@password', 'type', 'text')
                ->click('.password-toggle')
                ->assertAttribute('@password', 'type', 'password');

        return $browser;
    }

    /**
     * Test remember me functionality
     */
    public function testRememberMe(Browser $browser, $user)
    {
        $browser->type('@email', $user->email)
                ->type('@password', 'password')
                ->check('@remember')
                ->press('@submit')
                ->waitForLocation('/admin/dashboard', 30);

        // Simulate browser restart
        $browser->restart();

        // Visit admin area - should be logged in
        $browser->visit('/admin/dashboard')
                ->assertDontSee('Login')
                ->assertSee('Dashboard');

        return $browser;
    }

    /**
     * Test session timeout
     */
    public function testSessionTimeout(Browser $browser, $user)
    {
        // Login first
        $this->loginWithUser($browser, $user);

        // Clear session
        $browser->script('sessionStorage.clear(); localStorage.clear();');

        // Visit admin area - should redirect to login
        $browser->visit('/admin/dashboard')
                ->waitForLocation($this->url(), 30)
                ->assertSee('Login');

        return $browser;
    }

    /**
     * Test brute force protection
     */
    public function testBruteForceProtection(Browser $browser)
    {
        // Try login multiple times with wrong credentials
        for ($i = 0; $i < 5; $i++) {
            $browser->type('@email', 'test@example.com')
                    ->type('@password', 'wrongpassword')
                    ->press('@submit')
                    ->waitFor('@error-message', 5);
        }

        // Should be blocked after multiple attempts
        $browser->assertSee('Too many login attempts')
                ->assertDisabled('@submit');

        return $browser;
    }

    /**
     * Test login redirect after logout
     */
    public function testLoginRedirectAfterLogout(Browser $browser, $user)
    {
        // Login first
        $this->loginWithUser($browser, $user);

        // Access protected page
        $browser->visit('/admin/users')
                ->assertSee('Users');

        // Logout
        $browser->click('.logout-link')
                ->waitForLocation($this->url(), 30);

        // Try to access protected page - should redirect to login
        $browser->visit('/admin/users')
                ->waitForLocation($this->url(), 30)
                ->assertSee('Login');

        return $browser;
    }

    /**
     * Test CSRF protection
     */
    public function testCSRFProtection(Browser $browser)
    {
        // Remove CSRF token
        $browser->script('document.querySelector("input[name=_token]").remove();');

        $browser->type('@email', 'test@example.com')
                ->type('@password', 'password')
                ->press('@submit')
                ->waitFor('@error-message', 5)
                ->assertSee('CSRF token mismatch');

        return $browser;
    }

    /**
     * Test password reset functionality
     */
    public function testPasswordReset(Browser $browser, string $email)
    {
        $browser->click('@forgot-password')
                ->waitForLocation('/admin/forgot-password', 30)
                ->type('email', $email)
                ->press('Send Reset Link')
                ->waitFor('@success-message', 10)
                ->assertSee('Password reset link sent');

        return $browser;
    }

    /**
     * Test keyboard navigation
     */
    public function testKeyboardNavigation(Browser $browser)
    {
        $browser->type('@email', 'test@example.com')
                ->keys('@email', '{tab}')
                ->assertFocused('@password')
                ->type('@password', 'password')
                ->keys('@password', '{tab}')
                ->assertFocused('@remember')
                ->keys('@remember', '{tab}')
                ->assertFocused('@submit')
                ->keys('@submit', '{enter}');

        return $browser;
    }

    /**
     * Clear login form
     */
    public function clearForm(Browser $browser)
    {
        $browser->clear('@email')
                ->clear('@password')
                ->uncheck('@remember');

        return $browser;
    }

    /**
     * Assert form is empty
     */
    public function assertFormEmpty(Browser $browser)
    {
        $browser->assertInputValue('@email', '')
                ->assertInputValue('@password', '')
                ->assertNotChecked('@remember');

        return $browser;
    }
}
