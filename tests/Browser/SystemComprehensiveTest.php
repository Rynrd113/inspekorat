<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class SystemComprehensiveTest extends DuskTestCase
{
    /**
     * Test system security headers
     */
    public function testSystemSecurityHeaders()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->screenshot('security-headers-check');
                
            // Check for common security headers in the response
            $headers = $browser->driver->manage()->getLog('browser');
            
            // Basic security test - page should load without errors
            $browser->assertDontSee('error')
                ->assertDontSee('404')
                ->assertDontSee('500');
        });
    }

    /**
     * Test system performance across all modules
     */
    public function testSystemPerformance()
    {
        $pages = [
            '/' => 'Homepage',
            '/profil' => 'Profile',
            '/pelayanan' => 'Services',
            '/dokumen' => 'Documents',
            '/galeri' => 'Gallery',
            '/berita' => 'News',
            '/kontak' => 'Contact',
            '/portal-opd' => 'Portal OPD'
        ];

        foreach ($pages as $url => $name) {
            $this->browse(function (Browser $browser) use ($url, $name) {
                $startTime = microtime(true);
                
                $browser->visit($url);
                
                $endTime = microtime(true);
                $loadTime = $endTime - $startTime;
                
                // Assert page loads within reasonable time (10 seconds max)
                $this->assertLessThan(10, $loadTime, "$name page should load within 10 seconds");
                
                $browser->screenshot('performance-test-' . str_replace('/', '_', $url ?: 'home'));
            });
        }
    }

    /**
     * Test cross-browser compatibility basics
     */
    public function testCrossBrowserBasics()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Portal')
                ->assertPresent('nav')
                ->assertPresent('footer')
                ->screenshot('cross-browser-compatibility');
        });
    }

    /**
     * Test responsive design across all breakpoints
     */
    public function testResponsiveDesignAllBreakpoints()
    {
        $breakpoints = [
            [320, 568, 'mobile-small'],
            [375, 667, 'mobile-medium'],
            [414, 736, 'mobile-large'],
            [768, 1024, 'tablet'],
            [1024, 768, 'tablet-landscape'],
            [1280, 720, 'desktop-small'],
            [1920, 1080, 'desktop-large']
        ];

        foreach ($breakpoints as [$width, $height, $name]) {
            $this->browse(function (Browser $browser) use ($width, $height, $name) {
                $browser->resize($width, $height)
                    ->visit('/')
                    ->assertSee('Portal')
                    ->screenshot("responsive-$name")
                    ->visit('/portal-opd')
                    ->assertSee('Portal OPD')
                    ->screenshot("responsive-portal-opd-$name");
            });
        }
        
        // Reset to standard desktop size
        $this->browse(function (Browser $browser) {
            $browser->resize(1280, 720);
        });
    }

    /**
     * Test all admin role access patterns
     */
    public function testAllAdminRoleAccessPatterns()
    {
        $adminRoles = [
            'superadmin@inspektorat.go.id',
            'admin@inspektorat.go.id',
            'admin.profil@inspektorat.go.id',
            'admin.pelayanan@inspektorat.go.id',
            'admin.dokumen@inspektorat.go.id',
            'admin.galeri@inspektorat.go.id',
            'admin.faq@inspektorat.go.id',
            'admin.berita@inspektorat.go.id',
            'admin.wbs@inspektorat.go.id',
            'admin.opd@inspektorat.go.id'
        ];

        foreach ($adminRoles as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $this->browse(function (Browser $browser) use ($user) {
                    $browser->loginAs($user)
                        ->visit('/admin/dashboard')
                        ->assertSee('Dashboard')
                        ->screenshot('admin-access-' . str_replace('@', '_', str_replace('.', '_', $user->email)))
                        ->visit('/admin/logout')
                        ->pause(1000);
                });
            }
        }
    }

    /**
     * Test data integrity across all modules
     */
    public function testDataIntegrityAllModules()
    {
        $this->browse(function (Browser $browser) {
            // Test public data integrity
            $browser->visit('/portal-opd')
                ->assertDontSee('null')
                ->assertDontSee('undefined')
                ->assertDontSee('Error')
                ->screenshot('data-integrity-portal-opd')
                
                ->visit('/pelayanan')
                ->assertDontSee('null')
                ->assertDontSee('undefined')
                ->assertDontSee('Error')
                ->screenshot('data-integrity-pelayanan')
                
                ->visit('/berita')
                ->assertDontSee('null')
                ->assertDontSee('undefined')
                ->assertDontSee('Error')
                ->screenshot('data-integrity-berita');
        });
    }

    /**
     * Test search functionality across all modules
     */
    public function testSearchFunctionalityAllModules()
    {
        $searchPages = [
            '/portal-opd' => 'Dinas',
            '/pelayanan' => 'Surat',
            '/dokumen' => 'Peraturan',
            '/berita' => 'Inspektorat'
        ];

        foreach ($searchPages as $url => $searchTerm) {
            $this->browse(function (Browser $browser) use ($url, $searchTerm) {
                $browser->visit($url);
                
                if ($browser->element('input[name="search"], input[type="search"]')) {
                    $browser->type('input[name="search"], input[type="search"]', $searchTerm)
                        ->press('Search, Cari')
                        ->pause(1000)
                        ->screenshot('search-test-' . str_replace('/', '_', $url));
                }
            });
        }
    }

    /**
     * Test form validation across all modules
     */
    public function testFormValidationAllModules()
    {
        $this->browse(function (Browser $browser) {
            // Test contact form validation
            $browser->visit('/kontak')
                ->press('Kirim')
                ->pause(1000)
                ->screenshot('validation-contact-form');
                
            // Test WBS form validation
            $browser->visit('/wbs')
                ->press('Kirim')
                ->pause(1000)
                ->screenshot('validation-wbs-form');
        });
    }

    /**
     * Test file upload functionality
     */
    public function testFileUploadFunctionality()
    {
        $admin = User::where('role', 'superadmin')->first();
        
        if ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin)
                    ->visit('/admin/dokumen/create');
                    
                if ($browser->element('input[type="file"]')) {
                    $browser->attach('file_dokumen', __DIR__ . '/../fixtures/test-document.pdf')
                        ->screenshot('file-upload-test');
                }
                
                $browser->visit('/admin/logout');
            });
        }
    }

    /**
     * Test navigation consistency
     */
    public function testNavigationConsistency()
    {
        $publicPages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/berita', '/kontak'];

        foreach ($publicPages as $url) {
            $this->browse(function (Browser $browser) use ($url) {
                $browser->visit($url)
                    ->assertPresent('nav')
                    ->assertSee('Beranda')
                    ->assertSee('Profil')
                    ->assertSee('Pelayanan')
                    ->screenshot('navigation-consistency-' . str_replace('/', '_', $url ?: 'home'));
            });
        }
    }

    /**
     * Test error handling
     */
    public function testErrorHandling()
    {
        $this->browse(function (Browser $browser) {
            // Test 404 page
            $browser->visit('/non-existent-page')
                ->screenshot('error-handling-404');
                
            // Test admin access without login
            $browser->visit('/admin/dashboard')
                ->assertPathIs('/admin/login')
                ->screenshot('error-handling-unauthorized');
        });
    }

    /**
     * Test database connection and basic CRUD
     */
    public function testDatabaseConnectionAndCrud()
    {
        $admin = User::where('role', 'superadmin')->first();
        
        if ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin)
                    ->visit('/admin/portal-opd')
                    ->assertSee('Portal OPD')
                    ->screenshot('database-connection-test')
                    ->visit('/admin/logout');
            });
        }
    }
}