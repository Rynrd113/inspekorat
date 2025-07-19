<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ComprehensiveTestSuite extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Run comprehensive test suite for all modules
     * This test verifies the overall system functionality
     */
    public function testComprehensiveSystemFunctionality()
    {
        $this->browse(function (Browser $browser) {
            // Test that homepage loads correctly
            $browser->visit('/')
                ->assertSee('Inspektorat Papua Tengah')
                ->assertPresent('.navbar')
                ->assertPresent('.footer');
                
            // Test that admin login works
            $browser->visit('/admin/login')
                ->assertSee('Login')
                ->assertPresent('form')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]');
        });
    }

    /**
     * Test all critical admin modules are accessible
     */
    public function testAllAdminModulesAccessible()
    {
        $adminModules = [
            '/admin/dashboard' => 'Dashboard',
            '/admin/wbs' => 'WBS',
            '/admin/portal-papua-tengah' => 'Portal Papua Tengah',
            '/admin/portal-opd' => 'Portal OPD',
            '/admin/pelayanan' => 'Pelayanan',
            '/admin/dokumen' => 'Dokumen',
            '/admin/galeri' => 'Galeri',
            '/admin/faq' => 'FAQ',
            '/admin/users' => 'Users',
            '/admin/configurations' => 'Configurations',
            '/admin/audit-logs' => 'Audit Logs',
            '/admin/approvals' => 'Approvals',
            '/admin/pengaduan' => 'Pengaduan',
            '/admin/web-portal' => 'Web Portal',
        ];

        $this->browse(function (Browser $browser) use ($adminModules) {
            // Create super admin for testing
            $superAdmin = \App\Models\User::create([
                'name' => 'Super Admin Test',
                'email' => 'superadmin.test@inspektorat.id',
                'password' => bcrypt('superadmin123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]);

            $browser->loginAs($superAdmin);
            
            foreach ($adminModules as $url => $expectedText) {
                $browser->visit($url)
                    ->pause(500)
                    ->assertDontSee('404')
                    ->assertDontSee('500')
                    ->assertDontSee('403');
            }
        });
    }

    /**
     * Test all public pages are accessible
     */
    public function testAllPublicPagesAccessible()
    {
        $publicPages = [
            '/' => 'Inspektorat',
            '/berita' => 'Berita',
            '/pelayanan' => 'Pelayanan',
            '/dokumen' => 'Dokumen',
            '/galeri' => 'Galeri',
            '/faq' => 'FAQ',
            '/kontak' => 'Kontak',
            '/wbs' => 'WBS',
            '/profil' => 'Profil',
            '/portal-opd' => 'Portal OPD',
        ];

        $this->browse(function (Browser $browser) use ($publicPages) {
            foreach ($publicPages as $url => $expectedText) {
                $browser->visit($url)
                    ->pause(500)
                    ->assertDontSee('404')
                    ->assertDontSee('500')
                    ->assertStatus(200);
            }
        });
    }

    /**
     * Test form submissions work correctly
     */
    public function testCriticalFormSubmissions()
    {
        $this->browse(function (Browser $browser) {
            // Test WBS form submission
            $browser->visit('/wbs')
                ->type('nama_pelapor', 'Test User')
                ->type('email', 'test@email.com')
                ->type('subjek', 'Test Subject')
                ->type('deskripsi', 'Test Description')
                ->type('tanggal_kejadian', '2024-01-01')
                ->type('lokasi_kejadian', 'Test Location')
                ->press('Kirim Laporan')
                ->pause(2000)
                ->assertSee('berhasil');

            // Test contact form submission
            $browser->visit('/kontak')
                ->type('nama', 'Test User')
                ->type('email', 'test@email.com')
                ->type('pesan', 'Test Message')
                ->press('Kirim Pesan')
                ->pause(2000)
                ->assertSee('berhasil');
        });
    }

    /**
     * Test responsive design works
     */
    public function testResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            // Test desktop view
            $browser->resize(1920, 1080)
                ->visit('/')
                ->assertPresent('.navbar')
                ->assertPresent('.container');

            // Test tablet view
            $browser->resize(768, 1024)
                ->visit('/')
                ->assertPresent('.navbar')
                ->pause(500);

            // Test mobile view
            $browser->resize(375, 667)
                ->visit('/')
                ->assertPresent('.navbar')
                ->pause(500);
        });
    }

    /**
     * Test search functionality
     */
    public function testSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            // Create test data first
            \App\Models\PortalPapuaTengah::create([
                'judul' => 'Search Test Article',
                'konten' => 'Content for search testing',
                'kategori' => 'berita',
                'penulis' => 'Test Author',
                'status' => 'published',
                'is_published' => true,
                'published_at' => now(),
            ]);

            // Test search on berita page
            $browser->visit('/berita')
                ->type('.search-input', 'Search Test')
                ->press('.search-button')
                ->pause(1000)
                ->assertSee('Search Test Article');
        });
    }

    /**
     * Test database connectivity and basic CRUD
     */
    public function testDatabaseConnectivity()
    {
        // Test that we can create, read, update, delete records
        $user = \App\Models\User::create([
            'name' => 'Test User CRUD',
            'email' => 'testcrud@inspektorat.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testcrud@inspektorat.id',
            'name' => 'Test User CRUD',
        ]);

        $user->update(['name' => 'Updated Test User']);
        
        $this->assertDatabaseHas('users', [
            'email' => 'testcrud@inspektorat.id',
            'name' => 'Updated Test User',
        ]);

        $user->delete();
        
        $this->assertDatabaseMissing('users', [
            'email' => 'testcrud@inspektorat.id',
        ]);
    }

    /**
     * Test file operations work
     */
    public function testFileOperations()
    {
        $this->browse(function (Browser $browser) {
            // Create admin user
            $admin = \App\Models\User::create([
                'name' => 'Admin File Test',
                'email' => 'adminfile@inspektorat.id',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'is_active' => true,
            ]);

            // Test file upload in dokumen module
            $browser->loginAs($admin)
                ->visit('/admin/dokumen/create')
                ->type('judul', 'Test File Upload')
                ->type('deskripsi', 'Test file upload description')
                ->select('kategori', 'regulasi')
                ->attach('file', __DIR__.'/../fixtures/test-document.pdf')
                ->press('Simpan')
                ->pause(3000)
                ->assertSee('berhasil');
        });
    }

    /**
     * Test performance and loading times
     */
    public function testPerformanceAndLoadingTimes()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            $browser->visit('/');
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Assert page loads within 5 seconds
            $this->assertLessThan(5, $loadTime, 'Homepage should load within 5 seconds');
        });
    }
}
