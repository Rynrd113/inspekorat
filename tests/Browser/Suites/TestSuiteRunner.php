<?php

namespace Tests\Browser\Suites;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\DuskTestCase;

class TestSuiteRunner extends DuskTestCase
{
    use InteractsWithAuthentication;

    /**
     * Test suite untuk Phase 1 - Foundation
     */
    public function test_phase_1_foundation_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test basic functionality
            $browser->visit('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->assertVisible('.stats-cards');
            
            // Test navigation
            $browser->visit('/admin/berita')
                    ->assertSee('Berita')
                    ->assertVisible('.data-table');
            
            // Test authentication
            $browser->logout()
                    ->visit('/login')
                    ->assertSee('Login')
                    ->type('email', 'admin@inspektorat.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard', 30)
                    ->assertSee('Dashboard');
        });
    }

    /**
     * Test suite untuk Phase 2 - Core Modules
     */
    public function test_phase_2_core_modules_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test CRUD operations
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Suite Test Berita',
                        'konten' => 'Test suite content',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Suite Test Berita');
            
            // Test search
            $browser->type('input[name="search"]', 'Suite Test')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee('Suite Test Berita');
            
            // Test edit
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/berita/*/edit', 30)
                    ->type('input[name="judul"]', 'Updated Suite Test Berita')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Updated Suite Test Berita');
        });
    }

    /**
     * Test suite untuk Phase 3 - Extended Coverage
     */
    public function test_phase_3_extended_coverage_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test file upload
            $filePath = $this->createTestFile('suite.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Suite File Test',
                        'konten' => 'Test file upload',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Suite File Test');
            
            // Test responsive design
            $browser->resize(375, 667)
                    ->visit('/admin/berita')
                    ->assertVisible('.data-table')
                    ->assertVisible('.mobile-nav-toggle');
            
            // Test performance
            $startTime = microtime(true);
            $browser->visit('/admin/dashboard')
                    ->waitForLoadingToFinish($browser);
            $endTime = microtime(true);
            
            $this->assertLessThan(3, $endTime - $startTime, 'Page load too slow');
        });
    }

    /**
     * Test suite untuk Phase 4 - Advanced Features
     */
    public function test_phase_4_advanced_features_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test integration
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Integration Suite Test',
                        'konten' => 'Test integration',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            // Test notification
            $browser->visit('/admin/notifications')
                    ->assertSee('New berita published')
                    ->assertSee('Integration Suite Test');
            
            // Test API
            $browser->script('
                fetch("/admin/api/berita")
                    .then(response => response.json())
                    .then(data => {
                        window.apiTestResult = data;
                    });
            ')
            ->pause(2000);
            
            $apiResult = $browser->script('return window.apiTestResult')[0];
            $this->assertIsArray($apiResult);
            $this->assertArrayHasKey('data', $apiResult);
        });
    }

    /**
     * Smoke test untuk semua functionality utama
     */
    public function test_smoke_test_all_main_features()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $mainPages = [
                '/admin/dashboard',
                '/admin/berita',
                '/admin/wbs',
                '/admin/documents',
                '/admin/users',
                '/admin/profile',
                '/admin/settings',
            ];
            
            foreach ($mainPages as $page) {
                $browser->visit($page)
                        ->assertDontSee('500')
                        ->assertDontSee('404')
                        ->assertDontSee('403')
                        ->assertVisible('body');
            }
        });
    }

    /**
     * Security test suite
     */
    public function test_security_test_suite()
    {
        $this->browse(function (Browser $browser) {
            // Test unauthorized access
            $browser->visit('/admin/dashboard')
                    ->assertSee('Login');
            
            // Test CSRF protection
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/berita/create')
                    ->assertPresent('input[name="_token"]');
            
            // Test XSS protection
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => '<script>alert("XSS")</script>',
                        'konten' => 'Test XSS content',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertDontSee('<script>')
                    ->assertSee('&lt;script&gt;');
        });
    }

    /**
     * Performance test suite
     */
    public function test_performance_test_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $criticalPages = [
                '/admin/dashboard',
                '/admin/berita',
                '/admin/berita/create',
            ];
            
            foreach ($criticalPages as $page) {
                $startTime = microtime(true);
                
                $browser->visit($page)
                        ->waitForLoadingToFinish($browser);
                
                $endTime = microtime(true);
                $loadTime = $endTime - $startTime;
                
                $this->assertLessThan(3, $loadTime, 
                    "Page $page took too long to load: " . $loadTime . ' seconds');
            }
        });
    }

    /**
     * Accessibility test suite
     */
    public function test_accessibility_test_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/dashboard')
                    ->assertVisible('[role="main"]')
                    ->assertVisible('[aria-label]')
                    ->assertPresent('h1');
            
            // Test keyboard navigation
            $browser->visit('/admin/berita')
                    ->keys('body', ['{tab}', '{tab}', '{enter}']);
            
            // Test screen reader compatibility
            $browser->assertPresent('[aria-describedby]')
                    ->assertPresent('[aria-expanded]')
                    ->assertPresent('[aria-hidden]');
        });
    }

    /**
     * Mobile compatibility test suite
     */
    public function test_mobile_compatibility_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->resize(375, 667)
                    ->visit('/admin/dashboard')
                    ->assertVisible('.mobile-nav-toggle')
                    ->click('.mobile-nav-toggle')
                    ->waitFor('.mobile-nav-menu', 5)
                    ->assertVisible('.mobile-nav-menu');
            
            // Test touch interactions
            $browser->visit('/admin/berita')
                    ->assertVisible('.data-table')
                    ->script('
                        const table = document.querySelector(".data-table");
                        if (table) {
                            const touchEvent = new TouchEvent("touchstart", {
                                touches: [{ clientX: 100, clientY: 100 }]
                            });
                            table.dispatchEvent(touchEvent);
                        }
                    ');
        });
    }

    /**
     * Browser compatibility test suite
     */
    public function test_browser_compatibility_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test modern JavaScript features
            $browser->visit('/admin/dashboard')
                    ->script('
                        // Test ES6 features
                        const testArrow = () => "arrow function works";
                        const testTemplate = `template literal works`;
                        const testDestructuring = {a: 1, b: 2};
                        const {a, b} = testDestructuring;
                        
                        window.modernJSTest = {
                            arrow: testArrow(),
                            template: testTemplate,
                            destructuring: a === 1 && b === 2
                        };
                    ');
            
            $jsTest = $browser->script('return window.modernJSTest')[0];
            $this->assertEquals('arrow function works', $jsTest['arrow']);
            $this->assertEquals('template literal works', $jsTest['template']);
            $this->assertTrue($jsTest['destructuring']);
        });
    }

    /**
     * Data integrity test suite
     */
    public function test_data_integrity_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test data creation and validation
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Data Integrity Test',
                        'konten' => 'Test data integrity',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Data Integrity Test');
            
            // Test data persistence
            $browser->visit('/admin/berita')
                    ->click('a[href*="edit"]')
                    ->waitForLocation('/admin/berita/*/edit', 30)
                    ->assertInputValue('input[name="judul"]', 'Data Integrity Test')
                    ->assertInputValue('textarea[name="konten"]', 'Test data integrity')
                    ->assertSelected('select[name="status"]', 'published')
                    ->assertSelected('select[name="kategori"]', 'umum');
        });
    }

    /**
     * Error handling test suite
     */
    public function test_error_handling_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test 404 handling
            $browser->visit('/admin/nonexistent-page')
                    ->assertSee('404');
            
            // Test validation errors
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('judul wajib diisi')
                    ->assertSee('konten wajib diisi');
            
            // Test network error simulation
            $browser->script('
                // Simulate network error
                const originalFetch = window.fetch;
                window.fetch = function() {
                    return Promise.reject(new Error("Network error"));
                };
                
                // Restore after test
                setTimeout(() => {
                    window.fetch = originalFetch;
                }, 5000);
            ');
        });
    }

    /**
     * Complete regression test suite
     */
    public function test_complete_regression_suite()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test all major workflows
            $workflows = [
                'create_berita',
                'edit_berita',
                'delete_berita',
                'search_berita',
                'filter_berita',
                'upload_file',
                'manage_users',
                'manage_wbs',
                'manage_documents',
            ];
            
            foreach ($workflows as $workflow) {
                $this->runWorkflowTest($browser, $workflow);
            }
        });
    }

    /**
     * Helper method untuk menjalankan workflow test
     */
    private function runWorkflowTest(Browser $browser, string $workflow)
    {
        switch ($workflow) {
            case 'create_berita':
                $browser->visit('/admin/berita')
                        ->click('a[href*="create"]')
                        ->waitForLocation('/admin/berita/create', 30)
                        ->fillForm([
                            'judul' => "Test $workflow",
                            'konten' => "Test content for $workflow",
                            'status' => 'draft',
                            'kategori' => 'umum',
                        ])
                        ->press('button[type="submit"]')
                        ->waitForLocation('/admin/berita', 30)
                        ->assertSee("Test $workflow");
                break;
                
            case 'edit_berita':
                $browser->visit('/admin/berita')
                        ->click('a[href*="edit"]:first')
                        ->waitForLocation('/admin/berita/*/edit', 30)
                        ->type('input[name="judul"]', "Updated $workflow")
                        ->press('button[type="submit"]')
                        ->waitForLocation('/admin/berita', 30)
                        ->assertSee("Updated $workflow");
                break;
                
            case 'search_berita':
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', 'Test')
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
                break;
                
            default:
                // Basic page visit test
                $browser->visit('/admin/dashboard')
                        ->assertVisible('body');
                break;
        }
    }
}
