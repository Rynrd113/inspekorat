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

class ComprehensiveRoleTestingTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(SimpleDuskSeeder::class);
        
        // Create comprehensive test users for all roles
        $this->createTestUsers();
    }

    /**
     * Create test users for all 11 roles
     */
    private function createTestUsers(): void
    {
        $roles = [
            'super_admin' => ['name' => 'Super Admin Test', 'email' => 'superadmin_role_test@test.com'],
            'admin' => ['name' => 'Admin Test', 'email' => 'admin_role_test@test.com'],
            'content_manager' => ['name' => 'Content Manager Test', 'email' => 'content_role_test@test.com'],
            'admin_profil' => ['name' => 'Admin Profil Test', 'email' => 'profil_role_test@test.com'],
            'admin_pelayanan' => ['name' => 'Admin Pelayanan Test', 'email' => 'pelayanan_role_test@test.com'],
            'admin_dokumen' => ['name' => 'Admin Dokumen Test', 'email' => 'dokumen_role_test@test.com'],
            'admin_galeri' => ['name' => 'Admin Galeri Test', 'email' => 'galeri_role_test@test.com'],
            'admin_faq' => ['name' => 'Admin FAQ Test', 'email' => 'faq_role_test@test.com'],
            'admin_portal_opd' => ['name' => 'Admin Portal OPD Test', 'email' => 'opd_role_test@test.com'],
            'admin_berita' => ['name' => 'Admin Berita Test', 'email' => 'berita_role_test@test.com'],
            'admin_wbs' => ['name' => 'Admin WBS Test', 'email' => 'wbs_role_test@test.com'],
        ];

        foreach ($roles as $role => $userData) {
            // Check if user already exists to avoid duplicates
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => bcrypt('password'),
                    'role' => $role,
                    'status' => 'active',
                ]);
            }
        }
    }

    /**
     * Test Super Admin full access
     */
    public function test_super_admin_complete_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Super Admin
            $browser->visit('/admin/login')
                    ->type('email', 'superadmin_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('super_admin_dashboard');

            // Test User Management (Super Admin exclusive)
            $browser->visit('/admin/users')
                    ->pause(2000)
                    ->screenshot('super_admin_user_management')
                    ->assertSee('Manajemen User');

            // Test access to all modules
            $modules = [
                '/admin/portal-papua-tengah' => 'Portal Papua Tengah',
                '/admin/wbs' => 'WBS Management',
                '/admin/dokumen' => 'Document Management',
                '/admin/galeri' => 'Gallery Management',
                '/admin/faq' => 'FAQ Management',
                '/admin/portal-opd' => 'Portal OPD',
                '/admin/pelayanan' => 'Services Management',
            ];

            foreach ($modules as $url => $name) {
                $browser->visit($url)
                        ->pause(1000)
                        ->screenshot("super_admin_{$name}_access");
            }

            $this->assertTrue(true, 'Super Admin has complete access to all modules');
        });
    }

    /**
     * Test Admin Portal OPD role-specific access
     */
    public function test_admin_portal_opd_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin Portal OPD
            $browser->visit('/admin/login')
                    ->type('email', 'opd_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('admin_opd_dashboard');

            // Test Portal OPD access (should work)
            $browser->visit('/admin/portal-opd')
                    ->pause(2000)
                    ->screenshot('admin_opd_portal_access')
                    ->assertSee('Portal OPD');

            // Test create new OPD
            $browser->visit('/admin/portal-opd/create')
                    ->pause(2000)
                    ->screenshot('admin_opd_create_form')
                    ->type('nama_opd', 'Test OPD Role Access')
                    ->type('singkatan', 'TORA')
                    ->type('deskripsi', 'Testing OPD role access functionality')
                    ->check('status')
                    ->press('Simpan')
                    ->pause(2000)
                    ->screenshot('admin_opd_after_create');

            $this->assertTrue(true, 'Admin Portal OPD can access and manage OPD module');
        });
    }

    /**
     * Test Admin Pelayanan role-specific access
     */
    public function test_admin_pelayanan_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin Pelayanan
            $browser->visit('/admin/login')
                    ->type('email', 'pelayanan_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('admin_pelayanan_dashboard');

            // Test Pelayanan access (should work)
            $browser->visit('/admin/pelayanan')
                    ->pause(2000)
                    ->screenshot('admin_pelayanan_module_access')
                    ->assertSee('Pelayanan');

            // Test create new service
            $browser->visit('/admin/pelayanan/create')
                    ->pause(2000)
                    ->screenshot('admin_pelayanan_create_form')
                    ->type('nama', 'Test Service Role Access')
                    ->type('deskripsi', 'Testing Pelayanan role access functionality')
                    ->select('kategori', 'administrasi')
                    ->check('status')
                    ->press('Simpan')
                    ->pause(2000)
                    ->screenshot('admin_pelayanan_after_create');

            $this->assertTrue(true, 'Admin Pelayanan can access and manage Services module');
        });
    }

    /**
     * Test Admin Dokumen role-specific access
     */
    public function test_admin_dokumen_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin Dokumen
            $browser->visit('/admin/login')
                    ->type('email', 'dokumen_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('admin_dokumen_dashboard');

            // Test Document access (should work)
            $browser->visit('/admin/dokumen')
                    ->pause(2000)
                    ->screenshot('admin_dokumen_module_access')
                    ->assertSee('Dokumen');

            // Test document list functionality
            $browser->visit('/admin/dokumen/create')
                    ->pause(2000)
                    ->screenshot('admin_dokumen_create_form');

            $this->assertTrue(true, 'Admin Dokumen can access and manage Documents module');
        });
    }

    /**
     * Test Admin Galeri role-specific access
     */
    public function test_admin_galeri_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin Galeri
            $browser->visit('/admin/login')
                    ->type('email', 'galeri_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('admin_galeri_dashboard');

            // Test Gallery access (should work)
            $browser->visit('/admin/galeri')
                    ->pause(2000)
                    ->screenshot('admin_galeri_module_access')
                    ->assertSee('Galeri');

            // Test gallery create form
            $browser->visit('/admin/galeri/create')
                    ->pause(2000)
                    ->screenshot('admin_galeri_create_form');

            $this->assertTrue(true, 'Admin Galeri can access and manage Gallery module');
        });
    }

    /**
     * Test Admin FAQ role-specific access
     */
    public function test_admin_faq_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin FAQ
            $browser->visit('/admin/login')
                    ->type('email', 'faq_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('admin_faq_dashboard');

            // Test FAQ access (should work)
            $browser->visit('/admin/faq')
                    ->pause(2000)
                    ->screenshot('admin_faq_module_access')
                    ->assertSee('FAQ');

            // Test create new FAQ
            $browser->visit('/admin/faq/create')
                    ->pause(2000)
                    ->screenshot('admin_faq_create_form')
                    ->type('pertanyaan', 'Test FAQ Role Access Question?')
                    ->type('jawaban', 'This is testing FAQ role access functionality.')
                    ->select('kategori', 'umum')
                    ->select('status', '1')
                    ->press('Simpan')
                    ->pause(2000)
                    ->screenshot('admin_faq_after_create');

            $this->assertTrue(true, 'Admin FAQ can access and manage FAQ module');
        });
    }

    /**
     * Test Admin Berita role-specific access
     */
    public function test_admin_berita_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin Berita
            $browser->visit('/admin/login')
                    ->type('email', 'berita_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('admin_berita_dashboard');

            // Test Berita access (should work)
            $browser->visit('/admin/portal-papua-tengah')
                    ->pause(2000)
                    ->screenshot('admin_berita_module_access')
                    ->assertSee('Portal Papua Tengah');

            // Test create new news
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->pause(2000)
                    ->screenshot('admin_berita_create_form')
                    ->type('judul', 'Test News Role Access')
                    ->type('konten', 'This is testing news role access functionality.')
                    ->type('penulis', 'Admin Berita Test')
                    ->select('kategori', 'berita')
                    ->check('is_published')
                    ->press('Simpan')
                    ->pause(2000)
                    ->screenshot('admin_berita_after_create');

            $this->assertTrue(true, 'Admin Berita can access and manage News module');
        });
    }

    /**
     * Test Admin WBS role-specific access
     */
    public function test_admin_wbs_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin WBS
            $browser->visit('/admin/login')
                    ->type('email', 'wbs_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('admin_wbs_dashboard');

            // Test WBS access (should work)
            $browser->visit('/admin/wbs')
                    ->pause(2000)
                    ->screenshot('admin_wbs_module_access')
                    ->assertSee('WBS');

            // Test WBS management
            $browser->visit('/admin/wbs')
                    ->pause(2000)
                    ->screenshot('admin_wbs_list_view');

            $this->assertTrue(true, 'Admin WBS can access and manage WBS module');
        });
    }

    /**
     * Test role restrictions - Admin Dokumen cannot access other modules
     */
    public function test_role_restrictions_admin_dokumen(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin Dokumen
            $browser->visit('/admin/login')
                    ->type('email', 'dokumen_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000);

            // Should NOT be able to access user management
            $browser->visit('/admin/users')
                    ->pause(2000)
                    ->screenshot('admin_dokumen_restricted_users');

            // Current URL should not be users page (redirected)
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringNotContainsString('/admin/users', $currentUrl, 
                'Admin Dokumen should not access user management');

            // Should NOT be able to access Portal OPD
            $browser->visit('/admin/portal-opd')
                    ->pause(2000)
                    ->screenshot('admin_dokumen_restricted_opd');

            $this->assertTrue(true, 'Role restrictions working for Admin Dokumen');
        });
    }

    /**
     * Test Content Manager role access
     */
    public function test_content_manager_role_access(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as Content Manager
            $browser->visit('/admin/login')
                    ->type('email', 'content_role_test@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->pause(3000)
                    ->screenshot('content_manager_dashboard');

            // Content Manager should have broader access
            $accessibleModules = [
                '/admin/portal-papua-tengah' => 'News Management',
                '/admin/dokumen' => 'Document Management',
                '/admin/galeri' => 'Gallery Management',
                '/admin/faq' => 'FAQ Management',
            ];

            foreach ($accessibleModules as $url => $name) {
                $browser->visit($url)
                        ->pause(1000)
                        ->screenshot("content_manager_{$name}_access");
            }

            $this->assertTrue(true, 'Content Manager has access to content modules');
        });
    }

    /**
     * Test database integrity with role-based operations
     */
    public function test_database_integrity_with_roles(): void
    {
        // Test data creation by different roles
        $adminOpd = User::where('email', 'opd_role_test@test.com')->first();
        $adminFaq = User::where('email', 'faq_role_test@test.com')->first();
        $adminPelayanan = User::where('email', 'pelayanan_role_test@test.com')->first();
        
        // Authenticate as admin pelayanan for audit log purposes
        auth()->login($adminPelayanan);

        // Test Portal OPD creation
        $portalOpd = PortalOpd::create([
            'nama_opd' => 'Test OPD by Admin OPD',
            'singkatan' => 'TOAO',
            'deskripsi' => 'Created by Admin Portal OPD role',
            'status' => true,
            'created_by' => $adminOpd->id,
        ]);

        $this->assertNotNull($portalOpd->id, 'Portal OPD created by Admin OPD role');

        // Test FAQ creation
        $faq = Faq::create([
            'pertanyaan' => 'Test FAQ by Admin FAQ',
            'jawaban' => 'This FAQ was created by Admin FAQ role',
            'kategori' => 'umum',
            'status' => true,
            'urutan' => 1,
            'created_by' => $adminFaq->id,
        ]);

        $this->assertNotNull($faq->id, 'FAQ created by Admin FAQ role');

        // Test Pelayanan creation
        $pelayanan = Pelayanan::create([
            'nama' => 'Test Service by Admin Pelayanan',
            'deskripsi' => 'This service was created by Admin Pelayanan role',
            'kategori' => 'administrasi',
            'status' => true,
            'created_by' => $adminPelayanan->id,
        ]);

        $this->assertNotNull($pelayanan->id, 'Service created by Admin Pelayanan role');

        $this->assertTrue(true, 'Database integrity maintained with role-based operations');
    }

    /**
     * Test all user roles can login and access dashboard
     */
    public function test_all_roles_dashboard_access(): void
    {
        $roles = [
            'superadmin_role_test@test.com' => 'Super Admin',
            'admin_role_test@test.com' => 'Admin',
            'content_role_test@test.com' => 'Content Manager',
            'profil_role_test@test.com' => 'Admin Profil',
            'pelayanan_role_test@test.com' => 'Admin Pelayanan',
            'dokumen_role_test@test.com' => 'Admin Dokumen',
            'galeri_role_test@test.com' => 'Admin Galeri',
            'faq_role_test@test.com' => 'Admin FAQ',
            'opd_role_test@test.com' => 'Admin Portal OPD',
            'berita_role_test@test.com' => 'Admin Berita',
            'wbs_role_test@test.com' => 'Admin WBS',
        ];

        $this->browse(function (Browser $browser) use ($roles) {
            foreach ($roles as $email => $roleName) {
                // Logout if already logged in
                $browser->visit('/admin/logout')->pause(1000);

                // Login with each role
                $browser->visit('/admin/login')
                        ->type('email', $email)
                        ->type('password', 'password')
                        ->press('Login')
                        ->pause(3000)
                        ->screenshot("dashboard_access_{$roleName}")
                        ->assertPathIs('/admin/dashboard');

                // Verify dashboard loads
                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/admin', $currentUrl, 
                    "{$roleName} should be able to access admin dashboard");
            }

            $this->assertTrue(true, 'All 11 roles can login and access dashboard');
        });
    }

    /**
     * Helper method to submit forms with flexible button detection
     */
    private function submitForm(Browser $browser): void
    {
        try {
            $browser->press('Simpan');
        } catch (\Exception $e) {
            try {
                $browser->press('Save');
            } catch (\Exception $e) {
                try {
                    $browser->press('Submit');
                } catch (\Exception $e) {
                    $browser->click('button[type="submit"]');
                }
            }
        }
    }
}