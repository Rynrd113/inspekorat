<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RoleAccessTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users for different roles
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Profil',
            'email' => 'admin.profil@inspektorat.id',
            'password' => bcrypt('adminprofil123'),
            'role' => 'admin_profil',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Pelayanan',
            'email' => 'admin.pelayanan@inspektorat.id',
            'password' => bcrypt('adminpelayanan123'),
            'role' => 'admin_pelayanan',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Dokumen',
            'email' => 'admin.dokumen@inspektorat.id',
            'password' => bcrypt('admindokumen123'),
            'role' => 'admin_dokumen',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Galeri',
            'email' => 'admin.galeri@inspektorat.id',
            'password' => bcrypt('admingaleri123'),
            'role' => 'admin_galeri',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin FAQ',
            'email' => 'admin.faq@inspektorat.id',
            'password' => bcrypt('adminfaq123'),
            'role' => 'admin_faq',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Berita',
            'email' => 'admin.berita@inspektorat.id',
            'password' => bcrypt('adminberita123'),
            'role' => 'admin_berita',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin WBS',
            'email' => 'admin.wbs@inspektorat.id',
            'password' => bcrypt('adminwbs123'),
            'role' => 'admin_wbs',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Portal OPD',
            'email' => 'admin.opd@inspektorat.id',
            'password' => bcrypt('adminopd123'),
            'role' => 'admin_opd',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'User Public',
            'email' => 'user.public@inspektorat.id',
            'password' => bcrypt('userpublic123'),
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    /**
     * Test SuperAdmin can access all modules
     */
    public function testSuperAdminCanAccessAllModules()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Test access to all modules
            $modules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/profil',
                '/admin/configurations',
                '/admin/content-approvals'
            ];

            foreach ($modules as $module) {
                $browser->visit($module)
                    ->assertDontSee('403')
                    ->assertDontSee('Unauthorized');
            }
        });
    }

    /**
     * Test Admin Profil can only access profile management
     */
    public function testAdminProfilAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.profil@inspektorat.id')
                ->type('password', 'adminprofil123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to profile module
            $browser->visit('/admin/profil')
                ->assertDontSee('403')
                ->assertSee('Profil');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/wbs'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test Admin Pelayanan can only access service management
     */
    public function testAdminPelayananAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.pelayanan@inspektorat.id')
                ->type('password', 'adminpelayanan123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to pelayanan module
            $browser->visit('/admin/pelayanan')
                ->assertDontSee('403')
                ->assertSee('Pelayanan');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/profil'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test Admin Dokumen can only access document management
     */
    public function testAdminDokumenAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.dokumen@inspektorat.id')
                ->type('password', 'admindokumen123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to dokumen module
            $browser->visit('/admin/dokumen')
                ->assertDontSee('403')
                ->assertSee('Dokumen');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/galeri',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/profil'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test Admin Galeri can only access gallery management
     */
    public function testAdminGaleriAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.galeri@inspektorat.id')
                ->type('password', 'admingaleri123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to galeri module
            $browser->visit('/admin/galeri')
                ->assertDontSee('403')
                ->assertSee('Galeri');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/profil'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test Admin FAQ can only access FAQ management
     */
    public function testAdminFaqAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.faq@inspektorat.id')
                ->type('password', 'adminfaq123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to faq module
            $browser->visit('/admin/faq')
                ->assertDontSee('403')
                ->assertSee('FAQ');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/profil'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test Admin Berita can only access news management
     */
    public function testAdminBeritaAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.berita@inspektorat.id')
                ->type('password', 'adminberita123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to berita module
            $browser->visit('/admin/portal-papua-tengah')
                ->assertDontSee('403')
                ->assertSee('Berita');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq',
                '/admin/wbs',
                '/admin/profil'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test Admin WBS can only access WBS management
     */
    public function testAdminWbsAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.wbs@inspektorat.id')
                ->type('password', 'adminwbs123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to wbs module
            $browser->visit('/admin/wbs')
                ->assertDontSee('403')
                ->assertSee('WBS');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/profil'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test Admin Portal OPD can only access Portal OPD management
     */
    public function testAdminPortalOpdAccessRestriction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin.opd@inspektorat.id')
                ->type('password', 'adminopd123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to portal-opd module
            $browser->visit('/admin/portal-opd')
                ->assertDontSee('403')
                ->assertSee('Portal OPD');

            // Should not have access to other modules
            $restrictedModules = [
                '/admin/users',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq',
                '/admin/portal-papua-tengah',
                '/admin/wbs',
                '/admin/profil'
            ];

            foreach ($restrictedModules as $module) {
                $browser->visit($module)
                    ->assertSee('403');
            }
        });
    }

    /**
     * Test User Public cannot access admin panel
     */
    public function testUserPublicCannotAccessAdminPanel()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'user.public@inspektorat.id')
                ->type('password', 'userpublic123')
                ->press('Login')
                ->pause(1000)
                ->assertSee('You do not have permission to access admin panel');
        });
    }

    /**
     * Test dashboard access based on role
     */
    public function testDashboardAccessBasedOnRole()
    {
        $roles = [
            'superadmin@inspektorat.id' => 'superadmin123',
            'admin.profil@inspektorat.id' => 'adminprofil123',
            'admin.pelayanan@inspektorat.id' => 'adminpelayanan123',
            'admin.dokumen@inspektorat.id' => 'admindokumen123',
            'admin.galeri@inspektorat.id' => 'admingaleri123',
            'admin.faq@inspektorat.id' => 'adminfaq123',
            'admin.berita@inspektorat.id' => 'adminberita123',
            'admin.wbs@inspektorat.id' => 'adminwbs123',
            'admin.opd@inspektorat.id' => 'adminopd123',
        ];

        foreach ($roles as $email => $password) {
            $this->browse(function (Browser $browser) use ($email, $password) {
                $browser->visit('/admin/login')
                    ->type('email', $email)
                    ->type('password', $password)
                    ->press('Login')
                    ->pause(1000)
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->click('button[onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();"]')
                    ->pause(1000);
            });
        }
    }

    /**
     * Test sidebar menu visibility based on role
     */
    public function testSidebarMenuVisibilityBasedOnRole()
    {
        $this->browse(function (Browser $browser) {
            // Test SuperAdmin - should see all menu items
            $browser->visit('/admin/login')
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->pause(1000)
                ->assertSee('Dashboard')
                ->assertSee('Users')
                ->assertSee('Portal OPD')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('FAQ')
                ->assertSee('Berita')
                ->assertSee('WBS')
                ->assertSee('Profil')
                ->click('button[onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();"]')
                ->pause(1000);

            // Test Admin Profil - should only see profile menu
            $browser->visit('/admin/login')
                ->type('email', 'admin.profil@inspektorat.id')
                ->type('password', 'adminprofil123')
                ->press('Login')
                ->pause(1000)
                ->assertSee('Dashboard')
                ->assertSee('Profil')
                ->assertDontSee('Users')
                ->assertDontSee('Portal OPD')
                ->assertDontSee('Pelayanan')
                ->assertDontSee('Dokumen')
                ->assertDontSee('Galeri')
                ->assertDontSee('FAQ')
                ->assertDontSee('Berita')
                ->assertDontSee('WBS');
        });
    }
}
