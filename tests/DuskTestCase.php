<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * Prepare for Dusk test execution.
     */
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-gpu',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-extensions',
            '--disable-web-security',
            '--disable-features=VizDisplayCompositor',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if the browser window should start maximized.
     */
    protected function shouldStartMaximized(): bool
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
               isset($_ENV['DUSK_START_MAXIMIZED']);
    }

    /**
     * Create a user with specific role for testing.
     */
    protected function createUserWithRole(string $role = 'user', array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => $role,
            'email_verified_at' => now(),
        ], $attributes));
    }

    /**
     * Create super admin user for testing.
     */
    protected function createSuperAdmin(array $attributes = []): User
    {
        return $this->createUserWithRole('super_admin', $attributes);
    }

    /**
     * Create admin user for testing.
     */
    protected function createAdmin(array $attributes = []): User
    {
        return $this->createUserWithRole('admin', $attributes);
    }

    /**
     * Create content manager user for testing.
     */
    protected function createContentManager(array $attributes = []): User
    {
        return $this->createUserWithRole('content_manager', $attributes);
    }

    /**
     * Create OPD manager user for testing.
     */
    protected function createOpdManager(array $attributes = []): User
    {
        return $this->createUserWithRole('opd_manager', $attributes);
    }

    /**
     * Create WBS manager user for testing.
     */
    protected function createWbsManager(array $attributes = []): User
    {
        return $this->createUserWithRole('wbs_manager', $attributes);
    }

    /**
     * Login as user via browser.
     */
    protected function loginAs(User $user, $browser)
    {
        return $browser->visit('/admin/login')
                      ->type('email', $user->email)
                      ->type('password', 'password')
                      ->press('Login')
                      ->waitForLocation('/admin/dashboard');
    }

    /**
     * Logout from browser.
     */
    protected function logout($browser)
    {
        return $browser->visit('/admin/logout')
                      ->waitForLocation('/');
    }

    /**
     * Wait for success notification.
     */
    protected function waitForSuccessNotification($browser, string $message = null)
    {
        if ($message) {
            return $browser->waitForText($message, 10);
        }
        
        return $browser->waitFor('.alert-success', 10)
                      ->orWaitFor('.toast-success', 10)
                      ->orWaitFor('[data-notify="container"]', 10);
    }

    /**
     * Wait for error notification.
     */
    protected function waitForErrorNotification($browser, string $message = null)
    {
        if ($message) {
            return $browser->waitForText($message, 10);
        }
        
        return $browser->waitFor('.alert-danger', 10)
                      ->orWaitFor('.toast-error', 10)
                      ->orWaitFor('[data-notify="container"]', 10);
    }

    /**
     * Upload file in browser.
     */
    protected function uploadFile($browser, string $selector, string $filename, string $content = 'test content')
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'dusk_test_');
        file_put_contents($tempFile, $content);
        
        $browser->attach($selector, $tempFile);
        
        register_shutdown_function(function () use ($tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        });
        
        return $browser;
    }

    /**
     * Check if element exists without throwing exception.
     */
    protected function elementExists($browser, string $selector): bool
    {
        try {
            $browser->element($selector);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Take screenshot with custom name for debugging.
     */
    protected function takeScreenshot($browser, string $name = null)
    {
        $name = $name ?: 'debug_' . date('Y-m-d_H-i-s');
        $browser->screenshot($name);
        return $browser;
    }

    /**
     * Clear form fields.
     */
    protected function clearForm($browser, array $fields)
    {
        foreach ($fields as $field) {
            $browser->clear($field);
        }
        return $browser;
    }

    /**
     * Fill form with data.
     */
    protected function fillForm($browser, array $data)
    {
        foreach ($data as $field => $value) {
            if (is_array($value)) {
                // Handle select dropdown
                $browser->select($field, $value['value']);
            } else {
                $browser->type($field, $value);
            }
        }
        return $browser;
    }

    /**
     * Wait for page to load completely.
     */
    protected function waitForPageLoad($browser, int $timeout = 30)
    {
        return $browser->waitUntil('document.readyState === "complete"', $timeout);
    }

    /**
     * Wait for AJAX requests to complete.
     */
    protected function waitForAjax($browser, int $timeout = 30)
    {
        return $browser->waitUntil('typeof jQuery !== "undefined" && jQuery.active == 0', $timeout);
    }

    /**
     * Scroll to element.
     */
    protected function scrollTo($browser, string $selector)
    {
        $browser->script("document.querySelector('$selector').scrollIntoView({behavior: 'smooth'});");
        $browser->pause(500); // Wait for scroll animation
        return $browser;
    }

    /**
     * Check database has record after action.
     */
    protected function assertDatabaseHasAfterAction($browser, string $table, array $data, callable $action = null)
    {
        if ($action) {
            $action($browser);
        }
        
        $this->assertDatabaseHas($table, $data);
        return $browser;
    }

    /**
     * Verify frontend-backend sync by checking both UI and database.
     */
    protected function verifyFrontendBackendSync($browser, string $table, array $dbData, string $uiSelector, string $expectedText = null)
    {
        // Check database
        $this->assertDatabaseHas($table, $dbData);
        
        // Check UI
        if ($expectedText) {
            $browser->waitForText($expectedText, 10);
        } else {
            $browser->waitFor($uiSelector, 10);
        }
        
        return $browser;
    }
}