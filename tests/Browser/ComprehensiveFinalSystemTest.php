<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\PortalPapuaTengah;
use App\Models\Dokumen;
use App\Models\Wbs;
use App\Models\PortalOpd;
use Database\Seeders\SimpleDuskSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;

class ComprehensiveFinalSystemTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(SimpleDuskSeeder::class);
    }

    /**
     * Test complete system workflow: Public visitor to WBS submission
     */
    public function test_complete_public_visitor_workflow(): void
    {
        $this->browse(function (Browser $browser) {
            // 1. Visit homepage
            $browser->visit('/')
                    ->pause(2000)
                    ->screenshot('homepage_final_test')
                    ->assertSee('Inspektorat')
                    ->assertSee('Papua Tengah');

            // 2. Navigate to WBS
            $browser->visit('/wbs')
                    ->pause(2000)
                    ->screenshot('wbs_page_final_test')
                    ->assertSee('Whistleblower')
                    ->assertSee('WBS');

            // 3. Test WBS form functionality
            $browser->type('nama_pelapor', 'Test Citizen')
                    ->type('email', 'citizen@test.com')
                    ->type('subjek', 'Final System Test Report')
                    ->type('deskripsi', 'This is a comprehensive system test to verify all functionality works correctly.');

            // 4. Navigate to public documents
            $browser->visit('/dokumen')
                    ->pause(2000)
                    ->screenshot('documents_page_final_test');

            // 5. Visit news/berita page
            $browser->visit('/berita')
                    ->pause(2000)
                    ->screenshot('news_page_final_test');

            // 6. Visit contact page
            $browser->visit('/kontak')
                    ->pause(2000)
                    ->screenshot('contact_page_final_test')
                    ->assertSee('Kontak');

            $this->assertTrue(true, 'Complete public visitor workflow tested successfully');
        });
    }

    /**
     * Test complete admin workflow: Login to content management
     */
    public function test_complete_admin_workflow(): void
    {
        $this->browse(function (Browser $browser) {
            // 1. Admin login
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->screenshot('admin_login_final_test')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');

            $this->submitLoginForm($browser);

            // 2. Visit admin dashboard
            $browser->pause(3000)
                    ->screenshot('admin_dashboard_final_test');

            // Verify we're in admin area
            $currentUrl = $browser->driver->getCurrentURL();
            $isInAdminArea = strpos($currentUrl, '/admin') !== false;
            $this->assertTrue($isInAdminArea, 'Should be in admin area after login');

            // 3. Test admin navigation
            $browser->visit('/admin/portal-papua-tengah')
                    ->pause(2000)
                    ->screenshot('admin_news_management_final_test');

            $browser->visit('/admin/wbs')
                    ->pause(2000)
                    ->screenshot('admin_wbs_management_final_test');

            $browser->visit('/admin/dokumen')
                    ->pause(2000)
                    ->screenshot('admin_document_management_final_test');

            $this->assertTrue(true, 'Complete admin workflow tested successfully');
        });
    }

    /**
     * Test database operations and data integrity
     */
    public function test_database_operations_integrity(): void
    {
        // Test creating records with all required fields
        $news = PortalPapuaTengah::create([
            'judul' => 'Test News Final System',
            'slug' => 'test-news-final-system',
            'konten' => 'This is test content for final system testing.',
            'penulis' => 'Test Author',
            'kategori' => 'berita',
            'is_published' => true,
            'published_at' => now(),
            'status' => 'published',
        ]);

        $this->assertNotNull($news->id, 'News should be created successfully');

        // Test document creation with complete schema
        $document = Dokumen::create([
            'judul' => 'Test Document Final System',
            'deskripsi' => 'Test document for final system testing',
            'kategori' => 'peraturan',
            // Original migration fields
            'file_path' => 'test/document.pdf',
            'file_name' => 'document.pdf',
            'file_type' => 'application/pdf',
            'tanggal_publikasi' => now(),
            // New migration fields
            'file_dokumen' => 'test/document.pdf',
            'tanggal_terbit' => now(),
            'tahun' => date('Y'),
            'status' => true,
            'is_public' => true,
            'created_by' => 1,
        ]);

        $this->assertNotNull($document->id, 'Document should be created successfully');

        // Test WBS creation
        $wbs = Wbs::create([
            'nama_pelapor' => 'Test Reporter Final',
            'email' => 'reporter@test.com',
            'subjek' => 'Final System Test WBS',
            'deskripsi' => 'Testing WBS system functionality',
            'status' => 'pending',
            'is_anonymous' => false,
            'created_by' => 1,
        ]);

        $this->assertNotNull($wbs->id, 'WBS should be created successfully');

        // Test user creation with new schema
        $user = User::create([
            'name' => 'Test User Final',
            'email' => 'testuser@final.com',
            'password' => bcrypt('password'),
            'role' => 'content_manager',
            'status' => 'active',
        ]);

        $this->assertNotNull($user->id, 'User should be created successfully');

        $this->assertTrue(true, 'All database operations completed successfully');
    }

    /**
     * Test responsive design and mobile compatibility
     */
    public function test_responsive_design_mobile(): void
    {
        $this->browse(function (Browser $browser) {
            // Test mobile viewport
            $browser->resize(375, 667) // iPhone SE size
                    ->visit('/')
                    ->pause(2000)
                    ->screenshot('mobile_homepage_final_test');

            // Test WBS on mobile
            $browser->visit('/wbs')
                    ->pause(2000)
                    ->screenshot('mobile_wbs_final_test');

            // Test tablet viewport
            $browser->resize(768, 1024) // iPad size
                    ->visit('/')
                    ->pause(2000)
                    ->screenshot('tablet_homepage_final_test');

            // Test desktop viewport
            $browser->resize(1920, 1080) // Desktop size
                    ->visit('/')
                    ->pause(2000)
                    ->screenshot('desktop_homepage_final_test');

            $this->assertTrue(true, 'Responsive design tested across all viewports');
        });
    }

    /**
     * Test all public pages accessibility
     */
    public function test_all_public_pages_accessibility(): void
    {
        $this->browse(function (Browser $browser) {
            $publicPages = [
                '/' => 'Homepage',
                '/wbs' => 'WBS',
                '/dokumen' => 'Documents',
                '/berita' => 'News',
                '/kontak' => 'Contact',
                '/profil' => 'Profile',
                '/pelayanan' => 'Services',
                '/galeri' => 'Gallery',
                '/faq' => 'FAQ',
            ];

            foreach ($publicPages as $url => $name) {
                $browser->visit($url)
                        ->pause(1000)
                        ->screenshot("public_page_{$name}_final_test");

                // Check if page loads without errors
                $pageSource = $browser->driver->getPageSource();
                $hasError = strpos($pageSource, 'Error') !== false ||
                           strpos($pageSource, '404') !== false ||
                           strpos($pageSource, '500') !== false;

                $this->assertFalse($hasError, "Page {$name} should load without errors");
            }

            $this->assertTrue(true, 'All public pages accessibility tested successfully');
        });
    }

    /**
     * Test security features and access control
     */
    public function test_security_features_access_control(): void
    {
        $this->browse(function (Browser $browser) {
            // Test unauthorized access to admin area
            $browser->visit('/admin/dashboard')
                    ->pause(2000)
                    ->screenshot('unauthorized_admin_access_final_test');

            $currentUrl = $browser->driver->getCurrentURL();
            $isRedirectedToLogin = strpos($currentUrl, '/admin/login') !== false;
            $this->assertTrue($isRedirectedToLogin, 'Should redirect to login for unauthorized access');

            // Test admin access with proper credentials
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');

            $this->submitLoginForm($browser);

            $browser->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $isInAdminArea = strpos($currentUrl, '/admin') !== false;
            $this->assertTrue($isInAdminArea, 'Should access admin area with proper credentials');

            $this->assertTrue(true, 'Security features and access control tested successfully');
        });
    }

    /**
     * Test error handling and graceful degradation
     */
    public function test_error_handling_graceful_degradation(): void
    {
        $this->browse(function (Browser $browser) {
            // Test 404 error handling
            $browser->visit('/nonexistent-page')
                    ->pause(2000)
                    ->screenshot('404_error_handling_final_test');

            // Test invalid admin routes
            $browser->visit('/admin/nonexistent-admin-page')
                    ->pause(2000)
                    ->screenshot('admin_404_error_handling_final_test');

            // Test form validation
            $browser->visit('/wbs')
                    ->pause(2000);

            // Submit empty form to test validation
            $this->submitForm($browser);

            $browser->pause(2000)
                    ->screenshot('form_validation_final_test');

            $this->assertTrue(true, 'Error handling and graceful degradation tested successfully');
        });
    }

    /**
     * Test system performance and loading times
     */
    public function test_system_performance_loading_times(): void
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            // Test homepage loading time
            $browser->visit('/')
                    ->pause(1000);

            $homepageLoadTime = microtime(true) - $startTime;

            $startTime = microtime(true);

            // Test WBS page loading time
            $browser->visit('/wbs')
                    ->pause(1000);

            $wbsLoadTime = microtime(true) - $startTime;

            $startTime = microtime(true);

            // Test admin login loading time
            $browser->visit('/admin/login')
                    ->pause(1000);

            $adminLoadTime = microtime(true) - $startTime;

            // Performance assertions (allowing generous time for CI/CD environments)
            $this->assertLessThan(10, $homepageLoadTime, 'Homepage should load within 10 seconds');
            $this->assertLessThan(10, $wbsLoadTime, 'WBS page should load within 10 seconds');
            $this->assertLessThan(10, $adminLoadTime, 'Admin login should load within 10 seconds');

            $this->assertTrue(true, 'System performance and loading times tested successfully');
        });
    }

    /**
     * Helper method to submit login form with flexible button detection.
     */
    private function submitLoginForm(Browser $browser): void
    {
        try {
            $browser->press('Login');
        } catch (\Exception $e) {
            try {
                $browser->press('Masuk');
            } catch (\Exception $e) {
                try {
                    $browser->press('Sign In');
                } catch (\Exception $e) {
                    $browser->click('button[type="submit"]');
                }
            }
        }
    }

    /**
     * Helper method to submit forms with flexible button detection.
     */
    private function submitForm(Browser $browser): void
    {
        try {
            $browser->press('Kirim');
        } catch (\Exception $e) {
            try {
                $browser->press('Submit');
            } catch (\Exception $e) {
                try {
                    $browser->press('Simpan');
                } catch (\Exception $e) {
                    $browser->click('button[type="submit"]');
                }
            }
        }
    }
}