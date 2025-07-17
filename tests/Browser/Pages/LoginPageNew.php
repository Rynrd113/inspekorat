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
