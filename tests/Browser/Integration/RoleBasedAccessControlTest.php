<?php

namespace Tests\Browser\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Role-Based Access Control Integration Test
 * Tests for comprehensive role-based access control in Portal Inspektorat Papua Tengah
 */
class RoleBasedAccessControlTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
        // Create test users for all roles
        $users = [
            ['name' => 'SuperAdmin User', 'email' => 'superadmin@inspektorat.id', 'password' => bcrypt('superadmin123'), 'role' => 'superadmin', 'is_active' => true],
            ['name' => 'Admin User', 'email' => 'admin@inspektorat.id', 'password' => bcrypt('admin123'), 'role' => 'admin', 'is_active' => true],
            ['name' => 'Admin Profil', 'email' => 'admin.profil@inspektorat.id', 'password' => bcrypt('adminprofil123'), 'role' => 'admin_profil', 'is_active' => true],
            ['name' => 'Admin Pelayanan', 'email' => 'admin.pelayanan@inspektorat.id', 'password' => bcrypt('adminpelayanan123'), 'role' => 'admin_pelayanan', 'is_active' => true],
            ['name' => 'Admin Dokumen', 'email' => 'admin.dokumen@inspektorat.id', 'password' => bcrypt('admindokumen123'), 'role' => 'admin_dokumen', 'is_active' => true],
            ['name' => 'Admin Galeri', 'email' => 'admin.galeri@inspektorat.id', 'password' => bcrypt('admingaleri123'), 'role' => 'admin_galeri', 'is_active' => true],
            ['name' => 'Admin FAQ', 'email' => 'admin.faq@inspektorat.id', 'password' => bcrypt('adminfaq123'), 'role' => 'admin_faq', 'is_active' => true],
            ['name' => 'Admin Berita', 'email' => 'admin.berita@inspektorat.id', 'password' => bcrypt('adminberita123'), 'role' => 'admin_berita', 'is_active' => true],
            ['name' => 'Admin WBS', 'email' => 'admin.wbs@inspektorat.id', 'password' => bcrypt('adminwbs123'), 'role' => 'admin_wbs', 'is_active' => true],
            ['name' => 'Admin Portal OPD', 'email' => 'admin.opd@inspektorat.id', 'password' => bcrypt('adminopd123'), 'role' => 'admin_opd', 'is_active' => true],
            ['name' => 'Public User', 'email' => 'public@inspektorat.id', 'password' => bcrypt('public123'), 'role' => 'public', 'is_active' => true],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }

    /**
     * Test SuperAdmin can access all admin modules
     */
    public function testSuperAdminFullAccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('rbac-superadmin-dashboard');

            // Test access to all modules
            $modules = [
                '/admin/portal-opd' => 'Portal OPD',
                '/admin/pelayanan' => 'Pelayanan',
                '/admin/dokumen' => 'Dokumen',
                '/admin/galeri' => 'Galeri',
                '/admin/faq' => 'FAQ',
                '/admin/portal-papua-tengah' => 'Berita',
                '/admin/wbs' => 'WBS',
                '/admin/profil' => 'Profil',
                '/admin/users' => 'Users'
            ];

            foreach ($modules as $route => $title) {
                $browser->visit($route)
                    ->waitFor('.content', 10)
                    ->assertSee($title)
                    ->screenshot('rbac-superadmin-' . str_replace(['/', '-'], ['', '_'], $route));
            }
        });
    }

    /**
     * Test Admin Profil role access restriction
     */
    public function testAdminProfilAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.profil@inspektorat.id')
                ->type('password', 'adminprofil123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('rbac-admin-profil-dashboard');

            // Should have access to profil module
            $browser->visit('/admin/profil')
                ->waitFor('.content', 10)
                ->assertSee('Profil')
                ->screenshot('rbac-admin-profil-allowed');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/users'
            ];

            foreach ($restrictedModules as $route) {
                $browser->visit($route)
                    ->waitFor('.error-message', 10)
                    ->assertSee('Access Denied')
                    ->screenshot('rbac-admin-profil-denied-' . str_replace(['/', '-'], ['', '_'], $route));
            }
        });
    }

    /**
     * Test Admin Pelayanan role access restriction
     */
    public function testAdminPelayananAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.pelayanan@inspektorat.id')
                ->type('password', 'adminpelayanan123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('rbac-admin-pelayanan-dashboard');

            // Should have access to pelayanan module
            $browser->visit('/admin/pelayanan')
                ->waitFor('.content', 10)
                ->assertSee('Pelayanan')
                ->screenshot('rbac-admin-pelayanan-allowed');

            // Test restricted access
            $restrictedModules = ['/admin/portal-opd', '/admin/users'];
            foreach ($restrictedModules as $route) {
                $browser->visit($route)
                    ->waitFor('.error-message', 10)
                    ->assertSee('Access Denied')
                    ->screenshot('rbac-admin-pelayanan-denied');
            }
        });
    }

    /**
     * Test cross-role authentication security
     */
    public function testCrossRoleAuthenticationSecurity()
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin Dokumen
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.dokumen@inspektorat.id')
                ->type('password', 'admindokumen123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Try to access other role's module
            $browser->visit('/admin/pelayanan')
                ->waitFor('.error-message', 10)
                ->assertSee('Access Denied')
                ->screenshot('rbac-cross-role-security');

            // Logout and login as different role
            $browser->click('.logout-btn')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.galeri@inspektorat.id')
                ->type('password', 'admingaleri123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Should have access to galeri module
            $browser->visit('/admin/galeri')
                ->waitFor('.content', 10)
                ->assertSee('Galeri')
                ->screenshot('rbac-galeri-access-allowed');
        });
    }

    /**
     * Test session isolation between roles
     */
    public function testSessionIsolationBetweenRoles()
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin FAQ
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.faq@inspektorat.id')
                ->type('password', 'adminfaq123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Access FAQ module
            $browser->visit('/admin/faq')
                ->waitFor('.content', 10)
                ->assertSee('FAQ')
                ->screenshot('rbac-faq-session-valid');

            // Simulate session hijacking attempt
            $browser->visit('/admin/users')
                ->waitFor('.error-message', 10)
                ->assertSee('Access Denied')
                ->screenshot('rbac-session-security');
        });
    }

    /**
     * Test public user cannot access admin panel
     */
    public function testPublicUserAdminPanelRestriction()
    {
        $this->browse(function (Browser $browser) {
            // Try to access admin panel without login
            $browser->visit('/admin/dashboard')
                ->waitFor('.login-form', 10)
                ->assertSee('Login')
                ->screenshot('rbac-public-admin-redirect');

            // Login as public user
            $browser->type('email', 'public@inspektorat.id')
                ->type('password', 'public123')
                ->press('Login')
                ->waitFor('.error-message', 10)
                ->assertSee('Access Denied')
                ->screenshot('rbac-public-user-denied');
        });
    }

    /**
     * Test role-based menu visibility
     */
    public function testRoleBasedMenuVisibility()
    {
        $this->browse(function (Browser $browser) {
            // Login as Admin WBS
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.wbs@inspektorat.id')
                ->type('password', 'adminwbs123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Check sidebar menu visibility
            $browser->assertSee('WBS') // Should see WBS menu
                ->assertDontSee('Users') // Should not see Users menu
                ->assertDontSee('Portal OPD') // Should not see Portal OPD menu
                ->screenshot('rbac-wbs-menu-visibility');
        });
    }

    /**
     * Test bulk role permission validation
     */
    public function testBulkRolePermissionValidation()
    {
        $roleTests = [
            'admin.opd@inspektorat.id' => ['allowed' => '/admin/portal-opd', 'denied' => '/admin/users'],
            'admin.berita@inspektorat.id' => ['allowed' => '/admin/portal-papua-tengah', 'denied' => '/admin/portal-opd'],
            'admin.dokumen@inspektorat.id' => ['allowed' => '/admin/dokumen', 'denied' => '/admin/galeri'],
            'admin.galeri@inspektorat.id' => ['allowed' => '/admin/galeri', 'denied' => '/admin/faq'],
        ];

        foreach ($roleTests as $email => $tests) {
            $this->browse(function (Browser $browser) use ($email, $tests) {
                $password = str_replace(['admin.', '@inspektorat.id'], ['admin', '123'], $email);
                
                $browser->visit('/login')
                    ->waitFor('input[name="email"]', 10)
                    ->type('email', $email)
                    ->type('password', $password)
                    ->press('Login')
                    ->waitForText('Dashboard', 10);

                // Test allowed access
                $browser->visit($tests['allowed'])
                    ->waitFor('.content', 10)
                    ->screenshot('rbac-bulk-test-allowed-' . str_replace(['@', '.'], ['', '_'], $email));

                // Test denied access
                $browser->visit($tests['denied'])
                    ->waitFor('.error-message', 10)
                    ->assertSee('Access Denied')
                    ->screenshot('rbac-bulk-test-denied-' . str_replace(['@', '.'], ['', '_'], $email));

                // Logout
                $browser->click('.logout-btn')
                    ->waitFor('input[name="email"]', 10);
            });
        }
    }
}