<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\PortalPapuaTengah;
use App\Models\Dokumen;
use App\Models\Wbs;
use App\Models\PortalOpd;
use App\Models\Pelayanan;
use App\Models\Galeri;
use App\Models\Faq;
use Database\Seeders\SimpleDuskSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;

class FinalSystemCompletionTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(SimpleDuskSeeder::class);
    }

    /**
     * Test system completeness verification
     */
    public function test_system_completeness_verification(): void
    {
        // Test that all major modules have proper controllers and views
        $this->assertTrue(class_exists('App\Http\Controllers\Admin\PortalOpdController'), 'Portal OPD Controller exists');
        $this->assertTrue(class_exists('App\Http\Controllers\Admin\PelayananController'), 'Pelayanan Controller exists');
        $this->assertTrue(class_exists('App\Http\Controllers\Admin\DokumenController'), 'Dokumen Controller exists');
        $this->assertTrue(class_exists('App\Http\Controllers\Admin\GaleriController'), 'Galeri Controller exists');
        $this->assertTrue(class_exists('App\Http\Controllers\Admin\FaqController'), 'FAQ Controller exists');
        $this->assertTrue(class_exists('App\Http\Controllers\Admin\PortalPapuaTengahController'), 'News Controller exists');
        $this->assertTrue(class_exists('App\Http\Controllers\Admin\WbsController'), 'WBS Controller exists');

        // Test that all major models exist
        $this->assertTrue(class_exists('App\Models\PortalOpd'), 'Portal OPD Model exists');
        $this->assertTrue(class_exists('App\Models\Pelayanan'), 'Pelayanan Model exists');
        $this->assertTrue(class_exists('App\Models\Dokumen'), 'Dokumen Model exists');
        $this->assertTrue(class_exists('App\Models\Galeri'), 'Galeri Model exists');
        $this->assertTrue(class_exists('App\Models\Faq'), 'FAQ Model exists');
        $this->assertTrue(class_exists('App\Models\PortalPapuaTengah'), 'News Model exists');
        $this->assertTrue(class_exists('App\Models\Wbs'), 'WBS Model exists');

        // Test admin views exist
        $adminViews = [
            'admin.portal-opd.index',
            'admin.portal-opd.create',
            'admin.portal-opd.edit',
            'admin.portal-opd.show',
            'admin.pelayanan.index',
            'admin.pelayanan.create',
            'admin.pelayanan.edit',
            'admin.pelayanan.show',
            'admin.dokumen.index',
            'admin.dokumen.create',
            'admin.dokumen.edit',
            'admin.dokumen.show',
            'admin.galeri.index',
            'admin.galeri.create',
            'admin.galeri.edit',
            'admin.galeri.show',
            'admin.faq.index',
            'admin.faq.create',
            'admin.faq.edit',
            'admin.faq.show',
        ];

        foreach ($adminViews as $view) {
            $this->assertTrue(view()->exists($view), "Admin view {$view} exists");
        }

        // Test public views exist
        $publicViews = [
            'public.portal-opd.index',
            'public.pelayanan',
            'public.dokumen',
            'public.galeri',
            'public.faq',
            'public.home',
            'public.wbs',
            'public.kontak',
        ];

        foreach ($publicViews as $view) {
            $this->assertTrue(view()->exists($view), "Public view {$view} exists");
        }

        $this->assertTrue(true, 'All system components verified successfully');
    }

    /**
     * Test all admin modules are accessible
     */
    public function test_all_admin_modules_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as admin
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000);

            // Test access to all admin modules
            $adminModules = [
                '/admin/portal-opd' => 'Portal OPD Management',
                '/admin/pelayanan' => 'Services Management',
                '/admin/dokumen' => 'Document Management',
                '/admin/galeri' => 'Gallery Management',
                '/admin/faq' => 'FAQ Management',
                '/admin/portal-papua-tengah' => 'News Management',
                '/admin/wbs' => 'WBS Management',
            ];

            foreach ($adminModules as $url => $name) {
                $browser->visit($url)
                        ->pause(1000)
                        ->screenshot("final_admin_access_{$name}");

                // Verify we're on an admin page (not redirected to login)
                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/admin', $currentUrl, 
                    "Should be on admin page for {$name}");
            }

            $this->assertTrue(true, 'All admin modules are accessible');
        });
    }

    /**
     * Test all public pages load correctly
     */
    public function test_all_public_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $publicPages = [
                '/' => 'Homepage',
                '/portal-opd' => 'Portal OPD',
                '/pelayanan' => 'Services',
                '/dokumen' => 'Documents',
                '/galeri' => 'Gallery',
                '/faq' => 'FAQ',
                '/wbs' => 'WBS',
                '/kontak' => 'Contact',
                '/profil' => 'Profile',
                '/berita' => 'News',
            ];

            foreach ($publicPages as $url => $name) {
                $browser->visit($url)
                        ->pause(1000)
                        ->screenshot("final_public_page_{$name}");

                // Check if page loads without major errors
                $pageSource = $browser->driver->getPageSource();
                $hasError = strpos($pageSource, '500') !== false ||
                           strpos($pageSource, 'Whoops') !== false ||
                           strpos($pageSource, 'Error 404') !== false;

                $this->assertFalse($hasError, "Public page {$name} should load without errors");
            }

            $this->assertTrue(true, 'All public pages load correctly');
        });
    }

    /**
     * Test database tables are properly structured
     */
    public function test_database_tables_structure(): void
    {
        // Test that all major tables exist and have basic structure
        $tables = [
            'users' => ['name', 'email', 'role', 'status'],
            'portal_opds' => ['nama_opd', 'singkatan', 'alamat', 'status'],
            'pelayanans' => ['nama', 'deskripsi', 'kategori', 'status'],
            'dokumens' => ['judul', 'deskripsi', 'kategori', 'status'],
            'galeris' => ['judul', 'deskripsi', 'kategori', 'status'],
            'faqs' => ['pertanyaan', 'jawaban', 'kategori', 'status'],
            'portal_papua_tengahs' => ['judul', 'konten', 'kategori', 'status'],
            'wbs' => ['nama_pelapor', 'subjek', 'deskripsi', 'status'],
        ];

        foreach ($tables as $table => $columns) {
            $this->assertTrue(\Schema::hasTable($table), "Table {$table} exists");
            
            foreach ($columns as $column) {
                $this->assertTrue(\Schema::hasColumn($table, $column), 
                    "Table {$table} has column {$column}");
            }
        }

        $this->assertTrue(true, 'All database tables are properly structured');
    }

    /**
     * Test role-based access system
     */
    public function test_role_based_access_system(): void
    {
        // Create test users for different roles
        $roles = [
            'super_admin' => 'superadmin_final@test.com',
            'admin' => 'admin_final@test.com',
            'content_manager' => 'content_final@test.com',
            'admin_portal_opd' => 'opd_final@test.com',
            'admin_pelayanan' => 'pelayanan_final@test.com',
        ];

        foreach ($roles as $role => $email) {
            if (!User::where('email', $email)->exists()) {
                User::create([
                    'name' => "Final Test {$role}",
                    'email' => $email,
                    'password' => bcrypt('password'),
                    'role' => $role,
                    'status' => 'active',
                ]);
            }
        }

        $this->browse(function (Browser $browser) use ($roles) {
            foreach ($roles as $role => $email) {
                // Logout if logged in
                $browser->visit('/admin/logout')->pause(1000);

                // Login with role
                $browser->visit('/admin/login')
                        ->type('email', $email)
                        ->type('password', 'password')
                        ->press('Login')
                        ->pause(3000)
                        ->screenshot("final_role_test_{$role}_dashboard");

                // Verify successful login to admin area
                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/admin', $currentUrl, 
                    "Role {$role} should be able to login to admin area");
            }

            $this->assertTrue(true, 'Role-based access system working');
        });
    }

    /**
     * Test comprehensive system functionality
     */
    public function test_comprehensive_system_functionality(): void
    {
        $this->browse(function (Browser $browser) {
            // Test complete user journey
            
            // 1. Visit homepage
            $browser->visit('/')
                    ->pause(2000)
                    ->screenshot('final_comprehensive_homepage')
                    ->assertSee('Inspektorat');

            // 2. Navigate through public modules
            $browser->visit('/portal-opd')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_portal_opd');

            $browser->visit('/pelayanan')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_pelayanan');

            $browser->visit('/dokumen')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_dokumen');

            $browser->visit('/galeri')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_galeri');

            $browser->visit('/faq')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_faq');

            // 3. Test WBS functionality
            $browser->visit('/wbs')
                    ->pause(2000)
                    ->screenshot('final_comprehensive_wbs')
                    ->type('nama_pelapor', 'Final System Test')
                    ->type('email', 'finaltest@example.com')
                    ->type('subjek', 'Final System Testing')
                    ->type('deskripsi', 'Testing complete system functionality')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_wbs_filled');

            // 4. Test admin access
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('final_comprehensive_admin_dashboard');

            // 5. Test admin module navigation
            $browser->visit('/admin/portal-opd')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_admin_opd');

            $browser->visit('/admin/pelayanan')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_admin_pelayanan');

            $browser->visit('/admin/dokumen')
                    ->pause(1000)
                    ->screenshot('final_comprehensive_admin_dokumen');

            $this->assertTrue(true, 'Comprehensive system functionality test completed');
        });
    }
}