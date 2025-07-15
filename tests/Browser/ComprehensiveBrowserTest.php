<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

class ComprehensiveBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;
    protected $contentManager;
    protected $regularUser;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        $this->contentManager = User::factory()->create([
            'name' => 'Content Manager',
            'email' => 'content@example.com',
            'password' => Hash::make('password'),
            'role' => 'content_manager'
        ]);

        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
    }

    /**
     * Test complete user journey - Public to Admin
     */
    public function testCompleteUserJourney()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Visit homepage
            $browser->visit('/')
                    ->assertSee('Inspektorat')
                    ->assertSee('Beranda');

            // Step 2: Navigate through public pages
            $browser->clickLink('Berita')
                    ->assertPathIs('/berita')
                    ->assertSee('Berita');

            $browser->clickLink('WBS')
                    ->assertPathIs('/wbs')
                    ->assertSee('Whistleblowing System');

            $browser->clickLink('Profil')
                    ->assertPathIs('/profil')
                    ->assertSee('Profil');

            $browser->clickLink('Pelayanan')
                    ->assertPathIs('/pelayanan')
                    ->assertSee('Pelayanan');

            $browser->clickLink('Dokumen')
                    ->assertPathIs('/dokumen')
                    ->assertSee('Dokumen');

            $browser->clickLink('Galeri')
                    ->assertPathIs('/galeri')
                    ->assertSee('Galeri');

            $browser->clickLink('FAQ')
                    ->assertPathIs('/faq')
                    ->assertSee('FAQ');

            $browser->clickLink('Kontak')
                    ->assertPathIs('/kontak')
                    ->assertSee('Kontak');

            // Step 3: Submit WBS form
            $browser->visit('/wbs')
                    ->type('nama', 'Test User')
                    ->type('email', 'test@example.com')
                    ->type('telepon', '081234567890')
                    ->type('pesan', 'Test WBS message')
                    ->press('Kirim')
                    ->assertSee('berhasil');

            // Step 4: Login as admin
            $browser->visit('/admin/login')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard');

            // Step 5: Test admin navigation
            $browser->clickLink('WBS')
                    ->assertPathIs('/admin/wbs')
                    ->assertSee('Whistleblowing System');

            $browser->clickLink('Berita')
                    ->assertPathIs('/admin/portal-papua-tengah')
                    ->assertSee('Portal Papua Tengah');

            $browser->clickLink('Portal OPD')
                    ->assertPathIs('/admin/portal-opd')
                    ->assertSee('Portal OPD');

            // Step 6: Test CRUD operations
            $browser->visit('/admin/wbs')
                    ->clickLink('Tambah')
                    ->type('nama', 'Admin Test WBS')
                    ->type('email', 'admin@example.com')
                    ->type('telepon', '081234567891')
                    ->type('pesan', 'Admin test message')
                    ->press('Simpan')
                    ->assertSee('berhasil');

            // Step 7: Logout
            $browser->clickLink('Logout')
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test all public pages accessibility
     */
    public function testPublicPagesAccessibility()
    {
        $this->browse(function (Browser $browser) {
            $publicPages = [
                '/' => 'Beranda',
                '/berita' => 'Berita',
                '/wbs' => 'WBS',
                '/profil' => 'Profil',
                '/pelayanan' => 'Pelayanan',
                '/dokumen' => 'Dokumen',
                '/galeri' => 'Galeri',
                '/faq' => 'FAQ',
                '/kontak' => 'Kontak',
                '/portal-opd' => 'Portal OPD'
            ];

            foreach ($publicPages as $url => $title) {
                $browser->visit($url)
                        ->assertDontSee('404')
                        ->assertDontSee('500')
                        ->assertDontSee('Error');
            }
        });
    }

    /**
     * Test authentication flow
     */
    public function testAuthenticationFlow()
    {
        $this->browse(function (Browser $browser) {
            // Test login page
            $browser->visit('/admin/login')
                    ->assertSee('Login')
                    ->assertSee('Email')
                    ->assertSee('Password');

            // Test failed login
            $browser->type('email', 'wrong@example.com')
                    ->type('password', 'wrongpassword')
                    ->press('Login')
                    ->assertSee('credentials');

            // Test successful login
            $browser->type('email', 'admin@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard');

            // Test logout
            $browser->clickLink('Logout')
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test role-based access control
     */
    public function testRoleBasedAccess()
    {
        $this->browse(function (Browser $browser) {
            // Login as content manager
            $browser->visit('/admin/login')
                    ->type('email', 'content@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard');

            // Should be able to access content pages
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertDontSee('403')
                    ->assertDontSee('Forbidden');

            // Should not be able to access WBS (depends on role configuration)
            $browser->visit('/admin/wbs')
                    ->assertSee('403');

            $browser->clickLink('Logout');
        });
    }

    /**
     * Test form validations
     */
    public function testFormValidations()
    {
        $this->browse(function (Browser $browser) {
            // Test WBS form validation
            $browser->visit('/wbs')
                    ->press('Kirim')
                    ->assertSee('required');

            // Test invalid email
            $browser->type('nama', 'Test User')
                    ->type('email', 'invalid-email')
                    ->type('pesan', 'Test message')
                    ->press('Kirim')
                    ->assertSee('valid email');

            // Test valid form
            $browser->type('email', 'valid@example.com')
                    ->type('telepon', '081234567890')
                    ->press('Kirim')
                    ->assertSee('berhasil');
        });
    }

    /**
     * Test responsive design
     */
    public function testResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            // Test mobile view
            $browser->resize(375, 667)
                    ->visit('/')
                    ->assertPresent('.navbar-toggler')
                    ->click('.navbar-toggler')
                    ->assertVisible('.navbar-collapse');

            // Test tablet view
            $browser->resize(768, 1024)
                    ->visit('/')
                    ->assertDontSee('.navbar-toggler');

            // Test desktop view
            $browser->resize(1920, 1080)
                    ->visit('/')
                    ->assertDontSee('.navbar-toggler');
        });
    }

    /**
     * Test search functionality
     */
    public function testSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            // Test news search
            $browser->visit('/berita')
                    ->type('search', 'test')
                    ->press('Cari')
                    ->assertQueryStringHas('search', 'test');

            // Test document search
            $browser->visit('/dokumen')
                    ->type('search', 'test')
                    ->press('Cari')
                    ->assertQueryStringHas('search', 'test');
        });
    }

    /**
     * Test file uploads
     */
    public function testFileUploads()
    {
        $this->browse(function (Browser $browser) {
            // Login as admin
            $browser->loginAs($this->admin)
                    ->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'Test News with Image')
                    ->type('konten', 'Test content')
                    ->select('kategori', 'berita')
                    ->attach('gambar', __DIR__.'/fixtures/test-image.jpg')
                    ->press('Simpan')
                    ->assertSee('berhasil');
        });
    }

    /**
     * Test pagination
     */
    public function testPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                    ->assertPresent('.pagination')
                    ->clickLink('2')
                    ->assertQueryStringHas('page', '2');
        });
    }

    /**
     * Test error pages
     */
    public function testErrorPages()
    {
        $this->browse(function (Browser $browser) {
            // Test 404 page
            $browser->visit('/non-existent-page')
                    ->assertSee('404');

            // Test 403 page (access denied)
            $browser->loginAs($this->regularUser)
                    ->visit('/admin/wbs')
                    ->assertSee('403');
        });
    }

    /**
     * Test admin CRUD operations
     */
    public function testAdminCRUDOperations()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/wbs');

            // Create
            $browser->clickLink('Tambah')
                    ->type('nama', 'Browser Test WBS')
                    ->type('email', 'browsertest@example.com')
                    ->type('telepon', '081234567890')
                    ->type('pesan', 'Browser test message')
                    ->press('Simpan')
                    ->assertSee('berhasil');

            // Read (already tested in list view)
            $browser->assertSee('Browser Test WBS');

            // Update
            $browser->clickLink('Edit')
                    ->type('nama', 'Updated Browser Test WBS')
                    ->press('Update')
                    ->assertSee('berhasil')
                    ->assertSee('Updated Browser Test WBS');

            // Delete
            $browser->click('[data-confirm="delete"]')
                    ->acceptDialog()
                    ->assertDontSee('Updated Browser Test WBS');
        });
    }

    /**
     * Test JavaScript functionality
     */
    public function testJavaScriptFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertScript('typeof jQuery !== "undefined"');

            // Test modal functionality
            $browser->visit('/admin/login')
                    ->loginAs($this->admin)
                    ->visit('/admin/wbs')
                    ->click('[data-toggle="modal"]')
                    ->assertVisible('.modal');
        });
    }

    /**
     * Test performance (basic)
     */
    public function testBasicPerformance()
    {
        $this->browse(function (Browser $browser) {
            $start = microtime(true);
            
            $browser->visit('/');
            
            $end = microtime(true);
            $loadTime = $end - $start;
            
            $this->assertLessThan(5, $loadTime, 'Page load time should be under 5 seconds');
        });
    }

    /**
     * Test all admin modules accessibility
     */
    public function testAdminModulesAccessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin);

            $adminPages = [
                '/admin/dashboard' => 'Dashboard',
                '/admin/wbs' => 'WBS',
                '/admin/portal-papua-tengah' => 'Portal Papua Tengah',
                '/admin/portal-opd' => 'Portal OPD',
                '/admin/profil' => 'Profil',
                '/admin/pelayanan' => 'Pelayanan',
                '/admin/dokumen' => 'Dokumen',
                '/admin/galeri' => 'Galeri',
                '/admin/faq' => 'FAQ'
            ];

            foreach ($adminPages as $url => $title) {
                $browser->visit($url)
                        ->assertDontSee('404')
                        ->assertDontSee('500')
                        ->assertDontSee('Error');
            }
        });
    }
}
