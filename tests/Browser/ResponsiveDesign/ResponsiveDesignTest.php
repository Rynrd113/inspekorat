<?php

namespace Tests\Browser\ResponsiveDesign;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\DuskTestCase;

class ResponsiveDesignTest extends DuskTestCase
{
    use InteractsWithAuthentication;

    protected $devices = [
        'desktop' => ['width' => 1920, 'height' => 1080],
        'laptop' => ['width' => 1366, 'height' => 768],
        'tablet' => ['width' => 768, 'height' => 1024],
        'mobile' => ['width' => 375, 'height' => 667],
    ];

    /**
     * Test login page responsive design
     */
    public function test_login_page_responsive()
    {
        $this->browse(function (Browser $browser) {
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/login')
                        ->assertVisible('form')
                        ->assertVisible('input[name="email"]')
                        ->assertVisible('input[name="password"]')
                        ->assertVisible('button[type="submit"]');
                
                // Test mobile-specific elements
                if ($device === 'mobile') {
                    $browser->assertVisible('.mobile-login-header')
                            ->assertMissing('.desktop-sidebar');
                } else {
                    $browser->assertVisible('.desktop-login-header');
                }
            }
        });
    }

    /**
     * Test dashboard responsive design
     */
    public function test_dashboard_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/dashboard')
                        ->assertVisible('.dashboard-content');
                
                if ($device === 'mobile' || $device === 'tablet') {
                    // Test mobile navigation
                    $browser->assertVisible('.mobile-nav-toggle')
                            ->click('.mobile-nav-toggle')
                            ->waitFor('.mobile-nav-menu', 5)
                            ->assertVisible('.mobile-nav-menu')
                            ->click('.mobile-nav-toggle')
                            ->waitUntilMissing('.mobile-nav-menu', 5);
                } else {
                    // Test desktop navigation
                    $browser->assertVisible('.desktop-sidebar')
                            ->assertVisible('.desktop-nav-menu');
                }
            }
        });
    }

    /**
     * Test data table responsive design
     */
    public function test_data_table_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita')
                        ->assertVisible('.data-table');
                
                if ($device === 'mobile') {
                    // Test mobile table layout
                    $browser->assertVisible('.mobile-table-cards')
                            ->assertMissing('.desktop-table-header');
                } else {
                    // Test desktop table layout
                    $browser->assertVisible('.desktop-table-header')
                            ->assertVisible('thead')
                            ->assertVisible('tbody');
                }
            }
        });
    }

    /**
     * Test form responsive design
     */
    public function test_form_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita/create')
                        ->assertVisible('form')
                        ->assertVisible('input[name="judul"]')
                        ->assertVisible('textarea[name="konten"]')
                        ->assertVisible('select[name="kategori"]')
                        ->assertVisible('button[type="submit"]');
                
                // Test form layout adjustments
                if ($device === 'mobile') {
                    $browser->assertVisible('.mobile-form-layout')
                            ->assertMissing('.desktop-form-sidebar');
                } else {
                    $browser->assertVisible('.desktop-form-layout');
                }
            }
        });
    }

    /**
     * Test navigation menu responsive behavior
     */
    public function test_navigation_menu_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test mobile navigation
            $browser->resize(375, 667)
                    ->visit('/admin/dashboard')
                    ->assertVisible('.mobile-nav-toggle')
                    ->assertMissing('.desktop-nav-menu')
                    ->click('.mobile-nav-toggle')
                    ->waitFor('.mobile-nav-menu', 5)
                    ->assertVisible('.mobile-nav-menu')
                    ->assertSee('Dashboard')
                    ->assertSee('Berita')
                    ->assertSee('WBS')
                    ->assertSee('Documents');
            
            // Test tablet navigation
            $browser->resize(768, 1024)
                    ->visit('/admin/dashboard')
                    ->assertVisible('.tablet-nav-menu')
                    ->assertVisible('.nav-item');
            
            // Test desktop navigation
            $browser->resize(1920, 1080)
                    ->visit('/admin/dashboard')
                    ->assertVisible('.desktop-nav-menu')
                    ->assertVisible('.sidebar-nav')
                    ->assertMissing('.mobile-nav-toggle');
        });
    }

    /**
     * Test card layout responsive design
     */
    public function test_card_layout_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/dashboard')
                        ->assertVisible('.stats-cards');
                
                // Check card layout based on device
                switch ($device) {
                    case 'mobile':
                        $browser->assertVisible('.stats-cards.mobile-layout')
                                ->assertPresent('.stats-card:nth-child(1)')
                                ->assertPresent('.stats-card:nth-child(2)');
                        break;
                    case 'tablet':
                        $browser->assertVisible('.stats-cards.tablet-layout')
                                ->assertPresent('.stats-card:nth-child(1)')
                                ->assertPresent('.stats-card:nth-child(2)')
                                ->assertPresent('.stats-card:nth-child(3)');
                        break;
                    case 'desktop':
                        $browser->assertVisible('.stats-cards.desktop-layout')
                                ->assertPresent('.stats-card:nth-child(1)')
                                ->assertPresent('.stats-card:nth-child(2)')
                                ->assertPresent('.stats-card:nth-child(3)')
                                ->assertPresent('.stats-card:nth-child(4)');
                        break;
                }
            }
        });
    }

    /**
     * Test modal responsive design
     */
    public function test_modal_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita')
                        ->click('button[data-action="delete"]:first')
                        ->waitFor('.modal', 10)
                        ->assertVisible('.modal');
                
                // Test modal sizing for different devices
                if ($device === 'mobile') {
                    $browser->assertVisible('.modal.mobile-modal')
                            ->assertMissing('.modal-lg');
                } else {
                    $browser->assertVisible('.modal.desktop-modal');
                }
                
                $browser->press('Batal')
                        ->waitUntilMissing('.modal', 10);
            }
        });
    }

    /**
     * Test image gallery responsive design
     */
    public function test_image_gallery_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita/create')
                        ->assertVisible('.image-gallery');
                
                // Test gallery layout
                switch ($device) {
                    case 'mobile':
                        $browser->assertVisible('.gallery-mobile')
                                ->assertMissing('.gallery-desktop');
                        break;
                    case 'tablet':
                        $browser->assertVisible('.gallery-tablet');
                        break;
                    case 'desktop':
                        $browser->assertVisible('.gallery-desktop');
                        break;
                }
            }
        });
    }

    /**
     * Test search filters responsive design
     */
    public function test_search_filters_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita')
                        ->assertVisible('.search-filters');
                
                if ($device === 'mobile') {
                    // Test mobile filters - collapsed by default
                    $browser->assertVisible('.mobile-filter-toggle')
                            ->click('.mobile-filter-toggle')
                            ->waitFor('.mobile-filter-panel', 5)
                            ->assertVisible('.mobile-filter-panel')
                            ->assertVisible('input[name="search"]')
                            ->assertVisible('select[name="kategori"]');
                } else {
                    // Test desktop filters - always visible
                    $browser->assertVisible('.desktop-filter-panel')
                            ->assertVisible('input[name="search"]')
                            ->assertVisible('select[name="kategori"]')
                            ->assertVisible('select[name="status"]');
                }
            }
        });
    }

    /**
     * Test text editor responsive design
     */
    public function test_text_editor_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita/create')
                        ->assertVisible('.text-editor');
                
                if ($device === 'mobile') {
                    // Test mobile editor
                    $browser->assertVisible('.editor-mobile')
                            ->assertVisible('.mobile-toolbar')
                            ->assertMissing('.desktop-toolbar');
                } else {
                    // Test desktop editor
                    $browser->assertVisible('.editor-desktop')
                            ->assertVisible('.desktop-toolbar');
                }
            }
        });
    }

    /**
     * Test pagination responsive design
     */
    public function test_pagination_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita')
                        ->assertVisible('.pagination');
                
                if ($device === 'mobile') {
                    // Test mobile pagination
                    $browser->assertVisible('.pagination-mobile')
                            ->assertVisible('.page-prev')
                            ->assertVisible('.page-next')
                            ->assertMissing('.page-numbers');
                } else {
                    // Test desktop pagination
                    $browser->assertVisible('.pagination-desktop')
                            ->assertVisible('.page-numbers')
                            ->assertVisible('.page-prev')
                            ->assertVisible('.page-next');
                }
            }
        });
    }

    /**
     * Test breadcrumb responsive design
     */
    public function test_breadcrumb_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita/create')
                        ->assertVisible('.breadcrumb');
                
                if ($device === 'mobile') {
                    // Test mobile breadcrumb
                    $browser->assertVisible('.breadcrumb-mobile')
                            ->assertVisible('.breadcrumb-back')
                            ->assertMissing('.breadcrumb-full');
                } else {
                    // Test desktop breadcrumb
                    $browser->assertVisible('.breadcrumb-desktop')
                            ->assertVisible('.breadcrumb-full')
                            ->assertSee('Dashboard')
                            ->assertSee('Berita')
                            ->assertSee('Create');
                }
            }
        });
    }

    /**
     * Test alert messages responsive design
     */
    public function test_alert_messages_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita/create')
                        ->press('button[type="submit"]') // Trigger validation errors
                        ->waitFor('.alert-danger', 10)
                        ->assertVisible('.alert-danger');
                
                if ($device === 'mobile') {
                    // Test mobile alert
                    $browser->assertVisible('.alert-mobile')
                            ->assertMissing('.alert-desktop');
                } else {
                    // Test desktop alert
                    $browser->assertVisible('.alert-desktop');
                }
            }
        });
    }

    /**
     * Test footer responsive design
     */
    public function test_footer_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/dashboard')
                        ->assertVisible('.footer');
                
                if ($device === 'mobile') {
                    // Test mobile footer
                    $browser->assertVisible('.footer-mobile')
                            ->assertMissing('.footer-desktop');
                } else {
                    // Test desktop footer
                    $browser->assertVisible('.footer-desktop');
                }
            }
        });
    }

    /**
     * Test touch gestures for mobile
     */
    public function test_touch_gestures_mobile()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->resize(375, 667)
                    ->visit('/admin/berita')
                    ->assertVisible('.data-table');
            
            // Test swipe gestures
            $browser->script('
                const table = document.querySelector(".data-table");
                if (table) {
                    const touchStart = new TouchEvent("touchstart", {
                        touches: [{ clientX: 100, clientY: 100 }]
                    });
                    const touchMove = new TouchEvent("touchmove", {
                        touches: [{ clientX: 200, clientY: 100 }]
                    });
                    const touchEnd = new TouchEvent("touchend", {
                        touches: []
                    });
                    
                    table.dispatchEvent(touchStart);
                    table.dispatchEvent(touchMove);
                    table.dispatchEvent(touchEnd);
                }
            ');
        });
    }

    /**
     * Test orientation change
     */
    public function test_orientation_change()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Portrait mode
            $browser->resize(375, 667)
                    ->visit('/admin/dashboard')
                    ->assertVisible('.dashboard-content');
            
            // Landscape mode
            $browser->resize(667, 375)
                    ->visit('/admin/dashboard')
                    ->assertVisible('.dashboard-content')
                    ->assertVisible('.landscape-layout');
        });
    }

    /**
     * Test responsive images
     */
    public function test_responsive_images()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita')
                        ->assertVisible('.responsive-image');
                
                // Check if proper image sizes are loaded
                $browser->script('
                    const images = document.querySelectorAll(".responsive-image img");
                    images.forEach(img => {
                        if (img.srcset) {
                            console.log("Image has srcset:", img.srcset);
                        }
                    });
                ');
            }
        });
    }

    /**
     * Test responsive typography
     */
    public function test_responsive_typography()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/dashboard')
                        ->assertVisible('h1')
                        ->assertVisible('h2')
                        ->assertVisible('p');
                
                // Check font sizes based on device
                $browser->script('
                    const h1 = document.querySelector("h1");
                    const computedStyle = window.getComputedStyle(h1);
                    const fontSize = computedStyle.fontSize;
                    console.log("H1 font size on ' . $device . ':", fontSize);
                ');
            }
        });
    }

    /**
     * Test responsive spacing
     */
    public function test_responsive_spacing()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/dashboard')
                        ->assertVisible('.container');
                
                // Check spacing based on device
                $browser->script('
                    const container = document.querySelector(".container");
                    const computedStyle = window.getComputedStyle(container);
                    const padding = computedStyle.padding;
                    const margin = computedStyle.margin;
                    console.log("Container spacing on ' . $device . ':", {padding, margin});
                ');
            }
        });
    }

    /**
     * Test accessibility on different screen sizes
     */
    public function test_accessibility_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita')
                        ->assertVisible('[aria-label]')
                        ->assertVisible('[role="button"]')
                        ->assertVisible('[tabindex]');
                
                // Test keyboard navigation
                $browser->keys('body', ['{tab}', '{tab}', '{enter}']);
            }
        });
    }

    /**
     * Test performance on different screen sizes
     */
    public function test_performance_responsive()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            foreach ($this->devices as $device => $dimensions) {
                $startTime = microtime(true);
                
                $browser->resize($dimensions['width'], $dimensions['height'])
                        ->visit('/admin/berita')
                        ->waitForLoadingToFinish($browser);
                
                $endTime = microtime(true);
                $loadTime = $endTime - $startTime;
                
                // Page should load within 3 seconds on all devices
                $this->assertLessThan(3, $loadTime, 
                    "Page took too long to load on $device: " . $loadTime . ' seconds');
            }
        });
    }
}
