<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Database\Seeders\RolePermissionSeeder;

abstract class DuskTestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Setup database for testing
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->seed([
            UserSeeder::class,
            RolePermissionSeeder::class
        ]);
    }

    /**
     * Helper method untuk menunggu loading selesai
     */
    protected function waitForLoadingToFinish($browser)
    {
        $browser->waitUntil('document.readyState === "complete"', 30);
        $browser->waitUntil('!document.querySelector(".loading, .spinner, .loader")', 10);
        $browser->pause(500); // Additional pause for stability
        return $browser;
    }

    /**
     * Helper method untuk screenshot dengan nama deskriptif
     */
    protected function takeScreenshot($browser, $name)
    {
        $browser->screenshot("debug-{$name}-" . now()->format('Y-m-d-H-i-s'));
        return $browser;
    }

    /**
     * Helper method untuk scroll ke element
     */
    protected function scrollToElement($browser, $selector)
    {
        $browser->script("document.querySelector('{$selector}').scrollIntoView({ behavior: 'smooth', block: 'center' });");
        $browser->pause(1000); // Increased pause for smooth scrolling
        return $browser;
    }

    /**
     * Helper method untuk clear dan type text
     */
    protected function clearAndType($browser, $selector, $text)
    {
        $browser->clear($selector)->type($selector, $text);
        $browser->pause(200); // Small pause for stability
        return $browser;
    }

    /**
     * Helper method untuk upload file dengan validasi
     */
    protected function uploadFile($browser, $inputSelector, $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File tidak ditemukan: {$filePath}");
        }
        
        $browser->attach($inputSelector, $filePath);
        $browser->pause(2000); // Wait untuk file processing
        return $browser;
    }

    /**
     * Helper method untuk handle modal confirmation
     */
    protected function confirmModal($browser, $modalSelector = '.modal')
    {
        $browser->waitFor($modalSelector, 10);
        $browser->whenAvailable($modalSelector, function ($modal) {
            $modal->press('Ya')
                  ->orWhere('Confirm')
                  ->orWhere('OK')
                  ->orWhere('Hapus')
                  ->orWhere('Delete');
        });
        return $browser;
    }

    /**
     * Helper method untuk handle alert confirmation
     */
    protected function acceptAlert($browser)
    {
        $browser->acceptDialog();
        return $browser;
    }

    /**
     * Helper method untuk wait sampai URL berubah
     */
    protected function waitForUrlChange($browser, $expectedUrl = null)
    {
        if ($expectedUrl) {
            $browser->waitForLocation($expectedUrl, 30);
        } else {
            $browser->pause(2000);
        }
        return $browser;
    }

    /**
     * Helper method untuk assert success notification
     */
    protected function assertSuccessNotification($browser, $message = null)
    {
        $browser->waitFor('.alert-success, .notification-success, .toast-success, .swal2-success', 10);
        
        if ($message) {
            $browser->assertSee($message);
        }
        
        return $browser;
    }

    /**
     * Helper method untuk assert error notification
     */
    protected function assertErrorNotification($browser, $message = null)
    {
        $browser->waitFor('.alert-danger, .alert-error, .notification-error, .toast-error, .swal2-error', 10);
        
        if ($message) {
            $browser->assertSee($message);
        }
        
        return $browser;
    }

    /**
     * Helper method untuk resize window ke ukuran tertentu
     */
    protected function resizeWindow($browser, $width, $height)
    {
        $browser->resize($width, $height);
        $browser->pause(1000);
        return $browser;
    }

    /**
     * Helper method untuk test responsive design
     */
    protected function testResponsiveDesign($browser, $callback)
    {
        $sizes = [
            'desktop' => [1920, 1080],
            'tablet_landscape' => [1024, 768],
            'tablet_portrait' => [768, 1024],
            'mobile_large' => [414, 736],
            'mobile_medium' => [375, 667],
            'mobile_small' => [320, 568],
        ];

        foreach ($sizes as $device => $dimensions) {
            $this->resizeWindow($browser, $dimensions[0], $dimensions[1]);
            $callback($browser, $device);
        }

        return $browser;
    }

    /**
     * Helper method untuk debug - print current URL dan page title
     */
    protected function debugCurrentState($browser)
    {
        $url = $browser->driver->getCurrentURL();
        $title = $browser->driver->getTitle();
        
        echo "\n=== DEBUG INFO ===\n";
        echo "Current URL: {$url}\n";
        echo "Page Title: {$title}\n";
        echo "=================\n";
        
        return $browser;
    }

    /**
     * Helper method untuk create user dengan role tertentu
     */
    protected function createUserWithRole($role, $attributes = [])
    {
        return User::factory()->create(array_merge([
            'role' => $role,
            'password' => bcrypt('password'),
            'is_active' => true,
        ], $attributes));
    }

    /**
     * Helper method untuk assert page has no errors
     */
    protected function assertPageHasNoErrors($browser)
    {
        $browser->assertDontSee('Error')
                ->assertDontSee('Exception')
                ->assertDontSee('Fatal error')
                ->assertDontSee('500 Internal Server Error')
                ->assertDontSee('404 Not Found');
        
        return $browser;
    }

    /**
     * Helper method untuk handle file download
     */
    protected function downloadFile($browser, $linkSelector, $expectedFileName = null)
    {
        $browser->click($linkSelector);
        
        if ($expectedFileName) {
            $browser->pause(2000);
            // Verify file exists in download directory
            $downloadPath = storage_path('app/downloads/' . $expectedFileName);
            $this->assertTrue(file_exists($downloadPath), "Downloaded file not found: {$expectedFileName}");
        }
        
        return $browser;
    }

    /**
     * Helper method untuk wait untuk element visible
     */
    protected function waitForElementVisible($browser, $selector, $timeout = 10)
    {
        $browser->waitFor($selector, $timeout);
        return $browser;
    }

    /**
     * Helper method untuk wait untuk element invisible
     */
    protected function waitForElementInvisible($browser, $selector, $timeout = 10)
    {
        $browser->waitUntilMissing($selector, $timeout);
        return $browser;
    }

    /**
     * Helper method untuk handle rich text editor
     */
    protected function fillRichTextEditor($browser, $selector, $content)
    {
        $browser->script("
            var editor = document.querySelector('{$selector}');
            if (editor) {
                if (editor.tagName === 'TEXTAREA') {
                    editor.value = '{$content}';
                } else {
                    editor.innerHTML = '{$content}';
                }
            }
        ");
        
        return $browser;
    }

    /**
     * Helper method untuk assert table row exists
     */
    protected function assertTableRowExists($browser, $rowData)
    {
        foreach ($rowData as $cellData) {
            $browser->assertSee($cellData);
        }
        
        return $browser;
    }

    /**
     * Helper method untuk click dengan retry jika gagal
     */
    protected function clickWithRetry($browser, $selector, $retries = 3)
    {
        for ($i = 0; $i < $retries; $i++) {
            try {
                $browser->click($selector);
                return $browser;
            } catch (\Exception $e) {
                if ($i === $retries - 1) {
                    throw $e;
                }
                $browser->pause(1000);
            }
        }
        
        return $browser;
    }

    /**
     * Helper method untuk measure page load time
     */
    protected function measurePageLoadTime($browser, $url)
    {
        $startTime = microtime(true);
        $browser->visit($url);
        $this->waitForLoadingToFinish($browser);
        $endTime = microtime(true);
        
        $loadTime = $endTime - $startTime;
        
        echo "\n=== PERFORMANCE INFO ===\n";
        echo "Page: {$url}\n";
        echo "Load Time: " . number_format($loadTime, 2) . " seconds\n";
        echo "======================\n";
        
        return $browser;
    }

    /**
     * Helper method untuk create test files
     */
    protected function createTestFile($filename, $content = null, $mimeType = 'text/plain')
    {
        $testFilesDir = storage_path('app/test-files');
        
        if (!is_dir($testFilesDir)) {
            mkdir($testFilesDir, 0755, true);
        }
        
        $filePath = $testFilesDir . '/' . $filename;
        
        if ($content === null) {
            $content = "Test content for {$filename}";
        }
        
        file_put_contents($filePath, $content);
        
        return $filePath;
    }

    /**
     * Helper method untuk cleanup test files
     */
    protected function cleanupTestFiles()
    {
        $testFilesDir = storage_path('app/test-files');
        
        if (is_dir($testFilesDir)) {
            array_map('unlink', glob($testFilesDir . '/*'));
        }
    }

    /**
     * Teardown method untuk cleanup
     */
    protected function tearDown(): void
    {
        $this->cleanupTestFiles();
        parent::tearDown();
    }
}
