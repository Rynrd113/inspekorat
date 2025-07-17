<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\DashboardPage;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\DuskTestCase;

class AuthorizationTest extends DuskTestCase
{
    use InteractsWithAuthentication;

    /**
     * Test super admin dapat akses semua module
     */
    public function test_super_admin_dapat_akses_semua_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('super_admin');

            // Test akses ke setiap module
            $modules = ['wbs', 'berita', 'pelayanan', 'dokumen', 'galeri', 'faq', 'users', 'portal-opd', 'profil', 'configurations'];
            
            foreach ($modules as $module) {
                $browser->visit(new DashboardPage)
                        ->navigateToModule($module)
                        ->assertPageHasNoErrors($browser);
            }
        });
    }

    /**
     * Test admin dapat akses operational modules
     */
    public function test_admin_dapat_akses_operational_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin');

            // Test akses ke module yang diperbolehkan
            $allowedModules = ['wbs', 'berita', 'pelayanan', 'dokumen', 'galeri', 'faq', 'portal-opd', 'profil'];
            
            foreach ($allowedModules as $module) {
                $browser->visit(new DashboardPage)
                        ->navigateToModule($module)
                        ->assertPageHasNoErrors($browser);
            }
        });
    }

    /**
     * Test admin tidak dapat akses user management
     */
    public function test_admin_tidak_dapat_akses_user_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden')
                    ->orWhere('assertSee', 'Unauthorized');
        });
    }

    /**
     * Test content manager hanya dapat akses content modules
     */
    public function test_content_manager_hanya_dapat_akses_content_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('content_manager');

            // Test akses ke module yang diperbolehkan
            $allowedModules = ['berita', 'galeri', 'faq'];
            
            foreach ($allowedModules as $module) {
                $browser->visit(new DashboardPage)
                        ->navigateToModule($module)
                        ->assertPageHasNoErrors($browser);
            }
        });
    }

    /**
     * Test content manager tidak dapat akses module lain
     */
    public function test_content_manager_tidak_dapat_akses_module_lain()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $restrictedModules = ['wbs', 'pelayanan', 'dokumen', 'users', 'portal-opd', 'configurations'];
            
            foreach ($restrictedModules as $module) {
                $browser->visit(new DashboardPage)
                        ->navigateToModule($module);
                
                $browser->assertSee('403')
                        ->orWhere('assertSee', 'Forbidden')
                        ->orWhere('assertSee', 'Unauthorized');
            }
        });
    }

    /**
     * Test service manager hanya dapat akses service modules
     */
    public function test_service_manager_hanya_dapat_akses_service_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsServiceManager($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('service_manager');

            // Test akses ke module yang diperbolehkan
            $allowedModules = ['pelayanan', 'dokumen'];
            
            foreach ($allowedModules as $module) {
                $browser->visit(new DashboardPage)
                        ->navigateToModule($module)
                        ->assertPageHasNoErrors($browser);
            }
        });
    }

    /**
     * Test WBS manager hanya dapat akses WBS module
     */
    public function test_wbs_manager_hanya_dapat_akses_wbs_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('wbs_manager');

            // Test akses ke WBS module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('wbs')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test WBS manager tidak dapat akses module lain
     */
    public function test_wbs_manager_tidak_dapat_akses_module_lain()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $restrictedModules = ['berita', 'pelayanan', 'dokumen', 'galeri', 'faq', 'users', 'portal-opd', 'configurations'];
            
            foreach ($restrictedModules as $module) {
                $browser->visit(new DashboardPage)
                        ->navigateToModule($module);
                
                $browser->assertSee('403')
                        ->orWhere('assertSee', 'Forbidden')
                        ->orWhere('assertSee', 'Unauthorized');
            }
        });
    }

    /**
     * Test admin berita hanya dapat akses berita module
     */
    public function test_admin_berita_hanya_dapat_akses_berita_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminBerita($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin_berita');

            // Test akses ke berita module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('berita')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test admin pelayanan hanya dapat akses pelayanan module
     */
    public function test_admin_pelayanan_hanya_dapat_akses_pelayanan_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminPelayanan($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin_pelayanan');

            // Test akses ke pelayanan module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('pelayanan')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test admin dokumen hanya dapat akses dokumen module
     */
    public function test_admin_dokumen_hanya_dapat_akses_dokumen_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminDokumen($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin_dokumen');

            // Test akses ke dokumen module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('dokumen')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test admin galeri hanya dapat akses galeri module
     */
    public function test_admin_galeri_hanya_dapat_akses_galeri_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminGaleri($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin_galeri');

            // Test akses ke galeri module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('galeri')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test admin faq hanya dapat akses faq module
     */
    public function test_admin_faq_hanya_dapat_akses_faq_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminFaq($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin_faq');

            // Test akses ke FAQ module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('faq')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test admin WBS hanya dapat akses WBS module
     */
    public function test_admin_wbs_hanya_dapat_akses_wbs_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminWbs($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin_wbs');

            // Test akses ke WBS module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('wbs')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test admin portal opd hanya dapat akses portal opd module
     */
    public function test_admin_portal_opd_hanya_dapat_akses_portal_opd_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminPortalOpd($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('admin_portal_opd');

            // Test akses ke portal OPD module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('portal-opd')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test OPD manager hanya dapat akses portal OPD module
     */
    public function test_opd_manager_hanya_dapat_akses_portal_opd_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsOpdManager($browser);
            
            $browser->visit(new DashboardPage)
                    ->assertMenuItemsForRole('opd_manager');

            // Test akses ke portal OPD module
            $browser->visit(new DashboardPage)
                    ->navigateToModule('portal-opd')
                    ->assertPageHasNoErrors($browser);
        });
    }

    /**
     * Test role-based menu visibility
     */
    public function test_menu_visibility_berdasarkan_role()
    {
        $roles = [
            'super_admin' => ['WBS', 'Berita', 'Pelayanan', 'Dokumen', 'Galeri', 'FAQ', 'Portal OPD', 'Profil', 'Users', 'Configurations'],
            'admin' => ['WBS', 'Berita', 'Pelayanan', 'Dokumen', 'Galeri', 'FAQ', 'Portal OPD', 'Profil'],
            'content_manager' => ['Berita', 'Galeri', 'FAQ'],
            'service_manager' => ['Pelayanan', 'Dokumen'],
            'wbs_manager' => ['WBS'],
            'admin_berita' => ['Berita'],
            'admin_pelayanan' => ['Pelayanan'],
        ];

        foreach ($roles as $role => $expectedMenus) {
            $this->browse(function (Browser $browser) use ($role, $expectedMenus) {
                $this->loginAs($browser, $role);
                
                $browser->visit(new DashboardPage);
                
                foreach ($expectedMenus as $menu) {
                    $browser->assertSee($menu);
                }
            });
        }
    }

    /**
     * Test action button visibility berdasarkan role
     */
    public function test_action_button_visibility_berdasarkan_role()
    {
        $this->browse(function (Browser $browser) {
            // Test super admin dapat melihat semua action button
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/wbs')
                    ->assertVisible('a[href*="create"]') // Create button
                    ->assertVisible('a[href*="edit"]') // Edit button
                    ->assertVisible('button[data-action="delete"]'); // Delete button
        });

        $this->browse(function (Browser $browser) {
            // Test admin berita hanya dapat melihat action button untuk berita
            $this->loginAsAdminBerita($browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertVisible('a[href*="create"]') // Create button
                    ->assertVisible('a[href*="edit"]') // Edit button
                    ->assertVisible('button[data-action="delete"]'); // Delete button
        });
    }

    /**
     * Test direct URL access dengan role yang tidak sesuai
     */
    public function test_direct_url_access_dengan_role_tidak_sesuai()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminBerita($browser);
            
            // Try to access WBS module directly
            $browser->visit('/admin/wbs')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden')
                    ->orWhere('assertSee', 'Unauthorized');
        });
    }

    /**
     * Test API endpoint access dengan role yang tidak sesuai
     */
    public function test_api_endpoint_access_dengan_role_tidak_sesuai()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminBerita($browser);
            
            // Try to access WBS API endpoint
            $browser->visit('/api/wbs')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden')
                    ->orWhere('assertSee', 'Unauthorized');
        });
    }

    /**
     * Test role escalation protection
     */
    public function test_role_escalation_protection()
    {
        $adminUser = $this->createUserWithRole('admin');
        $normalUser = $this->createUserWithRole('user');

        $this->browse(function (Browser $browser) use ($adminUser, $normalUser) {
            $this->login($browser, $adminUser);
            
            // Admin should not be able to access user management
            $browser->visit('/admin/users')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden');
        });
    }

    /**
     * Test middleware protection
     */
    public function test_middleware_protection()
    {
        $this->browse(function (Browser $browser) {
            // Test without authentication
            $browser->visit('/admin/dashboard')
                    ->assertPathIs('/admin/login');
            
            // Test with wrong role
            $this->loginAsUser($browser);
            
            $browser->visit('/admin/dashboard')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden')
                    ->orWhere('assertPathIs', '/admin/login');
        });
    }

    /**
     * Test session-based authorization
     */
    public function test_session_based_authorization()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Valid session should allow access
            $browser->visit('/admin/dashboard')
                    ->assertSee('Dashboard');
            
            // Clear session
            $browser->script('document.cookie = "laravel_session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"');
            
            // Should redirect to login
            $browser->visit('/admin/dashboard')
                    ->waitForLocation('/admin/login', 30)
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test cross-module access control
     */
    public function test_cross_module_access_control()
    {
        $this->browse(function (Browser $browser) {
            // Admin berita should not access pelayanan
            $this->loginAsAdminBerita($browser);
            
            $browser->visit('/admin/pelayanan')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden');
        });

        $this->browse(function (Browser $browser) {
            // Admin pelayanan should not access berita
            $this->loginAsAdminPelayanan($browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden');
        });
    }
}
