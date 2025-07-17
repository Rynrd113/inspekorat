<?php

namespace Tests\Browser\Traits;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;

trait InteractsWithAuthentication
{
    /**
     * Login dengan user yang sudah ada
     */
    protected function login(Browser $browser, User $user)
    {
        $browser->visit(new LoginPage)
                ->login($user->email, 'password')
                ->assertLoginSuccess();

        return $browser;
    }

    /**
     * Login dengan role tertentu
     */
    protected function loginAs(Browser $browser, string $role)
    {
        $user = $this->createUserWithRole($role);
        return $this->login($browser, $user);
    }

    /**
     * Login sebagai super admin
     */
    protected function loginAsSuperAdmin(Browser $browser)
    {
        return $this->loginAs($browser, 'super_admin');
    }

    /**
     * Login sebagai admin
     */
    protected function loginAsAdmin(Browser $browser)
    {
        return $this->loginAs($browser, 'admin');
    }

    /**
     * Login sebagai content manager
     */
    protected function loginAsContentManager(Browser $browser)
    {
        return $this->loginAs($browser, 'content_manager');
    }

    /**
     * Login sebagai service manager
     */
    protected function loginAsServiceManager(Browser $browser)
    {
        return $this->loginAs($browser, 'service_manager');
    }

    /**
     * Login sebagai OPD manager
     */
    protected function loginAsOpdManager(Browser $browser)
    {
        return $this->loginAs($browser, 'opd_manager');
    }

    /**
     * Login sebagai WBS manager
     */
    protected function loginAsWbsManager(Browser $browser)
    {
        return $this->loginAs($browser, 'wbs_manager');
    }

    /**
     * Login dengan credentials invalid
     */
    protected function attemptLoginWithInvalidCredentials(Browser $browser, string $email = 'invalid@example.com', string $password = 'wrongpassword')
    {
        $browser->visit(new LoginPage)
                ->loginAndExpectFailure($email, $password, 'These credentials do not match our records.');

        return $browser;
    }

    /**
     * Logout user
     */
    protected function logout(Browser $browser)
    {
        $browser->visit('/admin/dashboard')
                ->click('a[href*="logout"]')
                ->waitForLocation('/admin/login', 30)
                ->assertPathIs('/admin/login');

        return $browser;
    }

    /**
     * Assert user is logged in
     */
    protected function assertLoggedIn(Browser $browser, string $expectedName = null)
    {
        $browser->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');

        if ($expectedName) {
            $browser->assertSee($expectedName);
        }

        return $browser;
    }

    /**
     * Assert user is not logged in
     */
    protected function assertNotLoggedIn(Browser $browser)
    {
        $browser->assertPathIs('/admin/login')
                ->assertSee('Login');

        return $browser;
    }

    /**
     * Test role-based access control
     */
    protected function testRoleBasedAccess(Browser $browser, string $role, string $url, bool $shouldHaveAccess)
    {
        $user = $this->createUserWithRole($role);
        $this->login($browser, $user);

        $browser->visit($url);

        if ($shouldHaveAccess) {
            $browser->assertDontSee('403')
                    ->assertDontSee('Forbidden')
                    ->assertDontSee('Unauthorized');
        } else {
            $browser->assertStatus(403)
                    ->orWhere('assertSee', 'Forbidden')
                    ->orWhere('assertSee', 'Unauthorized');
        }

        return $browser;
    }
}
