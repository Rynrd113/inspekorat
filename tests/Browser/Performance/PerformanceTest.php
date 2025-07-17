<?php

namespace Tests\Browser\Performance;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\DuskTestCase;

class PerformanceTest extends DuskTestCase
{
    use InteractsWithAuthentication;

    /**
     * Test page load performance
     */
    public function test_page_load_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $pages = [
                '/admin/dashboard',
                '/admin/berita',
                '/admin/berita/create',
                '/admin/wbs',
                '/admin/documents',
                '/admin/users',
            ];
            
            foreach ($pages as $page) {
                $startTime = microtime(true);
                
                $browser->visit($page)
                        ->waitForLoadingToFinish($browser);
                
                $endTime = microtime(true);
                $loadTime = $endTime - $startTime;
                
                // Page should load within 2 seconds
                $this->assertLessThan(2, $loadTime, 
                    "Page $page took too long to load: " . $loadTime . ' seconds');
            }
        });
    }

    /**
     * Test database query performance
     */
    public function test_database_query_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test large dataset loading
            $startTime = microtime(true);
            
            $browser->visit('/admin/berita')
                    ->waitForLoadingToFinish($browser);
            
            $endTime = microtime(true);
            $queryTime = $endTime - $startTime;
            
            // Database queries should complete within 1 second
            $this->assertLessThan(1, $queryTime, 
                'Database queries took too long: ' . $queryTime . ' seconds');
        });
    }

    /**
     * Test search performance
     */
    public function test_search_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $searchQueries = [
                'simple search',
                'complex search with multiple terms',
                'special characters !@#$%^&*()',
                'very long search query that might stress the system',
                'unicode characters: αβγδεζηθικλμνξοπρστυφχψω',
            ];
            
            foreach ($searchQueries as $query) {
                $startTime = microtime(true);
                
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $query)
                        ->press('button[type="submit"]')
                        ->waitForReload();
                
                $endTime = microtime(true);
                $searchTime = $endTime - $startTime;
                
                // Search should complete within 1.5 seconds
                $this->assertLessThan(1.5, $searchTime, 
                    "Search for '$query' took too long: " . $searchTime . ' seconds');
            }
        });
    }

    /**
     * Test file upload performance
     */
    public function test_file_upload_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $fileSizes = [
                'small' => 1024 * 10,      // 10KB
                'medium' => 1024 * 100,    // 100KB
                'large' => 1024 * 1024,    // 1MB
            ];
            
            foreach ($fileSizes as $sizeName => $sizeBytes) {
                $content = str_repeat('a', $sizeBytes);
                $filePath = $this->createTestFile("performance_$sizeName.jpg", $content, 'image/jpeg');
                
                $startTime = microtime(true);
                
                $browser->visit('/admin/berita/create')
                        ->fillForm([
                            'judul' => "Performance Test $sizeName",
                            'konten' => 'Performance test content',
                            'status' => 'draft',
                            'kategori' => 'umum',
                        ])
                        ->attach('input[name="gambar"]', $filePath)
                        ->press('button[type="submit"]')
                        ->waitForLocation('/admin/berita', 30);
                
                $endTime = microtime(true);
                $uploadTime = $endTime - $startTime;
                
                // Upload should complete within reasonable time based on size
                $maxTime = $sizeName === 'large' ? 10 : 5;
                $this->assertLessThan($maxTime, $uploadTime, 
                    "Upload of $sizeName file took too long: " . $uploadTime . ' seconds');
            }
        });
    }

    /**
     * Test pagination performance
     */
    public function test_pagination_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita');
            
            // Test multiple page loads
            for ($i = 1; $i <= 5; $i++) {
                $startTime = microtime(true);
                
                $browser->clickLink("$i")
                        ->waitForReload();
                
                $endTime = microtime(true);
                $pageTime = $endTime - $startTime;
                
                // Pagination should be fast
                $this->assertLessThan(1, $pageTime, 
                    "Pagination to page $i took too long: " . $pageTime . ' seconds');
            }
        });
    }

    /**
     * Test concurrent user simulation
     */
    public function test_concurrent_user_simulation()
    {
        $this->browse(function (Browser $browser1, Browser $browser2, Browser $browser3) {
            // Simulate multiple users
            $this->loginAsAdmin($browser1);
            $this->loginAsContentManager($browser2);
            $this->loginAsWbsManager($browser3);
            
            $startTime = microtime(true);
            
            // All users perform actions simultaneously
            $browser1->visit('/admin/berita')
                    ->type('input[name="search"]', 'concurrent test 1')
                    ->press('button[type="submit"]')
                    ->waitForReload();
            
            $browser2->visit('/admin/berita/create')
                    ->fillForm([
                        'judul' => 'Concurrent Test 2',
                        'konten' => 'Concurrent test content',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            $browser3->visit('/admin/wbs')
                    ->type('input[name="search"]', 'concurrent test 3')
                    ->press('button[type="submit"]')
                    ->waitForReload();
            
            $endTime = microtime(true);
            $concurrentTime = $endTime - $startTime;
            
            // Concurrent operations should complete within 5 seconds
            $this->assertLessThan(5, $concurrentTime, 
                'Concurrent operations took too long: ' . $concurrentTime . ' seconds');
        });
    }

    /**
     * Test memory usage performance
     */
    public function test_memory_usage_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $initialMemory = memory_get_usage();
            
            // Perform memory-intensive operations
            for ($i = 0; $i < 10; $i++) {
                $browser->visit('/admin/berita')
                        ->visit('/admin/berita/create')
                        ->visit('/admin/wbs')
                        ->visit('/admin/documents')
                        ->visit('/admin/users');
            }
            
            $finalMemory = memory_get_usage();
            $memoryIncrease = $finalMemory - $initialMemory;
            
            // Memory increase should be reasonable (less than 50MB)
            $this->assertLessThan(50 * 1024 * 1024, $memoryIncrease, 
                'Memory usage increased too much: ' . ($memoryIncrease / 1024 / 1024) . ' MB');
        });
    }

    /**
     * Test JavaScript performance
     */
    public function test_javascript_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita/create')
                    ->script('
                        window.performanceTest = {
                            start: performance.now(),
                            end: null,
                            duration: null
                        };
                        
                        // Simulate heavy JavaScript operations
                        for (let i = 0; i < 10000; i++) {
                            const div = document.createElement("div");
                            div.innerHTML = "Test " + i;
                            document.body.appendChild(div);
                            document.body.removeChild(div);
                        }
                        
                        window.performanceTest.end = performance.now();
                        window.performanceTest.duration = window.performanceTest.end - window.performanceTest.start;
                    ');
            
            $duration = $browser->script('return window.performanceTest.duration')[0];
            
            // JavaScript operations should complete within 1 second
            $this->assertLessThan(1000, $duration, 
                'JavaScript operations took too long: ' . $duration . ' ms');
        });
    }

    /**
     * Test CSS rendering performance
     */
    public function test_css_rendering_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/dashboard')
                    ->script('
                        const startTime = performance.now();
                        
                        // Force reflow by accessing layout properties
                        const elements = document.querySelectorAll("*");
                        elements.forEach(el => {
                            const rect = el.getBoundingClientRect();
                            const style = window.getComputedStyle(el);
                        });
                        
                        const endTime = performance.now();
                        window.cssRenderTime = endTime - startTime;
                    ');
            
            $renderTime = $browser->script('return window.cssRenderTime')[0];
            
            // CSS rendering should be fast
            $this->assertLessThan(500, $renderTime, 
                'CSS rendering took too long: ' . $renderTime . ' ms');
        });
    }

    /**
     * Test AJAX request performance
     */
    public function test_ajax_request_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->script('
                        window.ajaxPerformanceTest = {
                            results: [],
                            testAjax: function(url, index) {
                                const startTime = performance.now();
                                
                                fetch(url)
                                    .then(response => response.json())
                                    .then(data => {
                                        const endTime = performance.now();
                                        const duration = endTime - startTime;
                                        window.ajaxPerformanceTest.results.push({
                                            url: url,
                                            duration: duration,
                                            index: index
                                        });
                                    });
                            }
                        };
                        
                        // Test multiple AJAX requests
                        for (let i = 0; i < 5; i++) {
                            window.ajaxPerformanceTest.testAjax("/admin/api/berita", i);
                        }
                    ');
            
            // Wait for AJAX requests to complete
            $browser->pause(3000);
            
            $results = $browser->script('return window.ajaxPerformanceTest.results')[0];
            
            foreach ($results as $result) {
                // Each AJAX request should complete within 800ms
                $this->assertLessThan(800, $result['duration'], 
                    'AJAX request took too long: ' . $result['duration'] . ' ms');
            }
        });
    }

    /**
     * Test form submission performance
     */
    public function test_form_submission_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $formData = [
                'judul' => 'Performance Test Form',
                'konten' => str_repeat('Content for performance testing. ', 100),
                'status' => 'draft',
                'kategori' => 'umum',
            ];
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/berita/create')
                    ->fillForm($formData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            $endTime = microtime(true);
            $submitTime = $endTime - $startTime;
            
            // Form submission should complete within 3 seconds
            $this->assertLessThan(3, $submitTime, 
                'Form submission took too long: ' . $submitTime . ' seconds');
        });
    }

    /**
     * Test image loading performance
     */
    public function test_image_loading_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->script('
                        window.imageLoadTest = {
                            loadedImages: 0,
                            totalImages: 0,
                            startTime: performance.now(),
                            endTime: null
                        };
                        
                        const images = document.querySelectorAll("img");
                        window.imageLoadTest.totalImages = images.length;
                        
                        images.forEach(img => {
                            if (img.complete) {
                                window.imageLoadTest.loadedImages++;
                            } else {
                                img.addEventListener("load", () => {
                                    window.imageLoadTest.loadedImages++;
                                    if (window.imageLoadTest.loadedImages === window.imageLoadTest.totalImages) {
                                        window.imageLoadTest.endTime = performance.now();
                                    }
                                });
                            }
                        });
                        
                        if (window.imageLoadTest.loadedImages === window.imageLoadTest.totalImages) {
                            window.imageLoadTest.endTime = performance.now();
                        }
                    ');
            
            // Wait for images to load
            $browser->pause(5000);
            
            $loadTime = $browser->script('
                return window.imageLoadTest.endTime ? 
                    window.imageLoadTest.endTime - window.imageLoadTest.startTime : 
                    null
            ')[0];
            
            if ($loadTime) {
                // Images should load within 3 seconds
                $this->assertLessThan(3000, $loadTime, 
                    'Images took too long to load: ' . $loadTime . ' ms');
            }
        });
    }

    /**
     * Test responsive design performance
     */
    public function test_responsive_design_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $viewports = [
                ['width' => 1920, 'height' => 1080],
                ['width' => 1366, 'height' => 768],
                ['width' => 768, 'height' => 1024],
                ['width' => 375, 'height' => 667],
            ];
            
            foreach ($viewports as $viewport) {
                $startTime = microtime(true);
                
                $browser->resize($viewport['width'], $viewport['height'])
                        ->visit('/admin/dashboard')
                        ->waitForLoadingToFinish($browser);
                
                $endTime = microtime(true);
                $resizeTime = $endTime - $startTime;
                
                // Responsive layout should adjust quickly
                $this->assertLessThan(2, $resizeTime, 
                    'Responsive design took too long at ' . $viewport['width'] . 'x' . $viewport['height'] . ': ' . $resizeTime . ' seconds');
            }
        });
    }

    /**
     * Test cache performance
     */
    public function test_cache_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // First load (cache miss)
            $startTime1 = microtime(true);
            $browser->visit('/admin/berita')
                    ->waitForLoadingToFinish($browser);
            $endTime1 = microtime(true);
            $firstLoadTime = $endTime1 - $startTime1;
            
            // Second load (cache hit)
            $startTime2 = microtime(true);
            $browser->visit('/admin/berita')
                    ->waitForLoadingToFinish($browser);
            $endTime2 = microtime(true);
            $secondLoadTime = $endTime2 - $startTime2;
            
            // Cached load should be significantly faster
            $this->assertLessThan($firstLoadTime * 0.8, $secondLoadTime, 
                'Cache does not seem to be working effectively. First load: ' . $firstLoadTime . 's, Second load: ' . $secondLoadTime . 's');
        });
    }

    /**
     * Test bulk operations performance
     */
    public function test_bulk_operations_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/berita')
                    ->check('input[name="select_all"]')
                    ->select('select[name="bulk_action"]', 'change_status')
                    ->press('Apply')
                    ->waitForReload();
            
            $endTime = microtime(true);
            $bulkTime = $endTime - $startTime;
            
            // Bulk operations should complete within 5 seconds
            $this->assertLessThan(5, $bulkTime, 
                'Bulk operations took too long: ' . $bulkTime . ' seconds');
        });
    }

    /**
     * Test export performance
     */
    public function test_export_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="export"]')
                    ->pause(5000); // Wait for export to complete
            
            $endTime = microtime(true);
            $exportTime = $endTime - $startTime;
            
            // Export should complete within 10 seconds
            $this->assertLessThan(10, $exportTime, 
                'Export took too long: ' . $exportTime . ' seconds');
        });
    }

    /**
     * Test WebSocket performance (if applicable)
     */
    public function test_websocket_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/dashboard')
                    ->script('
                        window.wsPerformanceTest = {
                            startTime: performance.now(),
                            endTime: null,
                            messageCount: 0,
                            latencies: []
                        };
                        
                        if (window.WebSocket) {
                            const ws = new WebSocket("ws://localhost:8080");
                            
                            ws.onopen = function() {
                                for (let i = 0; i < 10; i++) {
                                    const msgStartTime = performance.now();
                                    ws.send("ping " + i);
                                    
                                    ws.onmessage = function(event) {
                                        const msgEndTime = performance.now();
                                        const latency = msgEndTime - msgStartTime;
                                        window.wsPerformanceTest.latencies.push(latency);
                                        window.wsPerformanceTest.messageCount++;
                                        
                                        if (window.wsPerformanceTest.messageCount === 10) {
                                            window.wsPerformanceTest.endTime = performance.now();
                                        }
                                    };
                                }
                            };
                        }
                    ');
            
            // Wait for WebSocket test to complete
            $browser->pause(5000);
            
            $latencies = $browser->script('return window.wsPerformanceTest.latencies')[0];
            
            if ($latencies && count($latencies) > 0) {
                $avgLatency = array_sum($latencies) / count($latencies);
                
                // WebSocket messages should have low latency
                $this->assertLessThan(100, $avgLatency, 
                    'WebSocket latency too high: ' . $avgLatency . ' ms');
            }
        });
    }

    /**
     * Test database connection pool performance
     */
    public function test_database_connection_pool_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $startTime = microtime(true);
            
            // Simulate multiple concurrent database operations
            for ($i = 0; $i < 5; $i++) {
                $browser->visit('/admin/berita')
                        ->visit('/admin/wbs')
                        ->visit('/admin/documents')
                        ->visit('/admin/users');
            }
            
            $endTime = microtime(true);
            $poolTime = $endTime - $startTime;
            
            // Database pool should handle concurrent connections efficiently
            $this->assertLessThan(10, $poolTime, 
                'Database connection pool performance is poor: ' . $poolTime . ' seconds');
        });
    }

    /**
     * Test API endpoint performance
     */
    public function test_api_endpoint_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $endpoints = [
                '/admin/api/berita',
                '/admin/api/wbs',
                '/admin/api/documents',
                '/admin/api/users',
            ];
            
            foreach ($endpoints as $endpoint) {
                $startTime = microtime(true);
                
                $browser->script('
                    fetch("' . $endpoint . '")
                        .then(response => response.json())
                        .then(data => {
                            window.apiResponseTime = performance.now() - window.apiStartTime;
                        });
                    window.apiStartTime = performance.now();
                ');
                
                $browser->pause(2000);
                
                $responseTime = $browser->script('return window.apiResponseTime')[0];
                
                if ($responseTime) {
                    // API endpoints should respond within 500ms
                    $this->assertLessThan(500, $responseTime, 
                        "API endpoint $endpoint took too long: " . $responseTime . ' ms');
                }
            }
        });
    }
}
