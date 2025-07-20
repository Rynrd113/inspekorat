<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RoleAccessTest extends DuskTestCase
{

    /**
     * Test SuperAdmin can access all modules
     */
    public function testSuperAdminCanAccessAllModules()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'superadmin@inspektorat.go.id')
                ->type('password', 'superadmin123')
                ->press('Masuk')
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
                '/admin/wbs'
            ];

            foreach ($modules as $module) {
                $browser->visit($module)
                    ->assertDontSee('Anda tidak memiliki akses ke halaman ini')
                    ->assertDontSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.profil@inspektorat.go.id')
                ->type('password', 'adminprofil123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to profil module (route exists but might redirect)
            $browser->visit('/admin/profil')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini');

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.pelayanan@inspektorat.go.id')
                ->type('password', 'adminpelayanan123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to pelayanan module
            $browser->visit('/admin/pelayanan')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini');

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.dokumen@inspektorat.go.id')
                ->type('password', 'admindokumen123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to dokumen module
            $browser->visit('/admin/dokumen')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini')
;

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.galeri@inspektorat.go.id')
                ->type('password', 'admingaleri123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to galeri module
            $browser->visit('/admin/galeri')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini')
;

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.faq@inspektorat.go.id')
                ->type('password', 'adminfaq123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to faq module
            $browser->visit('/admin/faq')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini')
;

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.berita@inspektorat.go.id')
                ->type('password', 'adminberita123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to berita module
            $browser->visit('/admin/portal-papua-tengah')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini')
;

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.wbs@inspektorat.go.id')
                ->type('password', 'adminwbs123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to wbs module
            $browser->visit('/admin/wbs')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini')
;

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'admin.opd@inspektorat.go.id')
                ->type('password', 'adminopd123')
                ->press('Masuk')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard');

            // Should have access to portal-opd module
            $browser->visit('/admin/portal-opd')
                ->assertDontSee('Anda tidak memiliki akses ke halaman ini')
;

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
                    ->assertSee('Anda tidak memiliki akses ke halaman ini');
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
                ->type('email', 'user.public@inspektorat.go.id')
                ->type('password', 'userpublic123')
                ->press('Masuk')
                ->pause(1000)
                ->assertSee('Akses ditolak. Hanya admin yang bisa login.');
        });
    }

    /**
     * Test dashboard access based on role
     */
    public function testDashboardAccessBasedOnRole()
    {
        $roles = [
            'superadmin@inspektorat.go.id' => 'superadmin123',
            'admin.profil@inspektorat.go.id' => 'adminprofil123',
            'admin.pelayanan@inspektorat.go.id' => 'adminpelayanan123',
            'admin.dokumen@inspektorat.go.id' => 'admindokumen123',
            'admin.galeri@inspektorat.go.id' => 'admingaleri123',
            'admin.faq@inspektorat.go.id' => 'adminfaq123',
            'admin.berita@inspektorat.go.id' => 'adminberita123',
            'admin.wbs@inspektorat.go.id' => 'adminwbs123',
            'admin.opd@inspektorat.go.id' => 'adminopd123',
        ];

        foreach ($roles as $email => $password) {
            $this->browse(function (Browser $browser) use ($email, $password) {
                $browser->visit('/admin/login')
                    ->type('email', $email)
                    ->type('password', $password)
                    ->press('Masuk')
                    ->pause(1000)
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->element('form[action*="logout"] button[type="submit"]')->click()
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
                ->type('email', 'superadmin@inspektorat.go.id')
                ->type('password', 'superadmin123')
                ->press('Masuk')
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
                ->element('form[action*="logout"] button[type="submit"]')->click()
                ->pause(1000);

            // Test Admin Profil - should only see profile menu
            $browser->visit('/admin/login')
                ->type('email', 'admin.profil@inspektorat.go.id')
                ->type('password', 'adminprofil123')
                ->press('Masuk')
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
