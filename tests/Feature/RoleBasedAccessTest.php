<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    private $users = [];

    public function setUp(): void
    {
        parent::setUp();
        
        $roles = [
            'super_admin',
            'admin',
            'content_manager',
            'service_manager',
            'opd_manager',
            'wbs_manager',
            'admin_wbs',
            'admin_berita',
            'admin_portal_opd',
            'admin_pelayanan',
            'admin_dokumen',
            'admin_galeri',
            'admin_faq',
            'user'
        ];

        foreach ($roles as $role) {
            $this->users[$role] = User::factory()->create([
                'name' => "Test {$role}",
                'email' => "test_{$role}@example.com",
                'password' => Hash::make('password'),
                'role' => $role
            ]);
        }
    }

    /** @test */
    public function super_admin_can_access_all_modules()
    {
        $superAdmin = $this->users['super_admin'];
        
        $adminRoutes = [
            '/admin/dashboard',
            '/admin/wbs',
            '/admin/portal-papua-tengah',
            '/admin/portal-opd',
            '/admin/profil',
            '/admin/pelayanan',
            '/admin/dokumen',
            '/admin/galeri',
            '/admin/faq'
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($superAdmin)->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "Super admin should access {$route}, got status {$response->status()}"
            );
        }
    }

    /** @test */
    public function admin_can_access_operational_modules()
    {
        $admin = $this->users['admin'];
        
        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/wbs',
            '/admin/portal-papua-tengah',
            '/admin/portal-opd',
            '/admin/profil',
            '/admin/pelayanan',
            '/admin/dokumen',
            '/admin/galeri',
            '/admin/faq'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "Admin should access {$route}, got status {$response->status()}"
            );
        }
    }

    /** @test */
    public function content_manager_can_access_content_modules()
    {
        $contentManager = $this->users['content_manager'];
        
        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/portal-papua-tengah',
            '/admin/galeri',
            '/admin/faq'
        ];

        $forbiddenRoutes = [
            '/admin/wbs',
            '/admin/portal-opd',
            '/admin/pelayanan',
            '/admin/dokumen'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($contentManager)->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "Content manager should access {$route}, got status {$response->status()}"
            );
        }

        foreach ($forbiddenRoutes as $route) {
            $response = $this->actingAs($contentManager)->get($route);
            $this->assertEquals(
                403,
                $response->status(),
                "Content manager should not access {$route}"
            );
        }
    }

    /** @test */
    public function service_manager_can_access_service_modules()
    {
        $serviceManager = $this->users['service_manager'];
        
        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/pelayanan',
            '/admin/dokumen'
        ];

        $forbiddenRoutes = [
            '/admin/wbs',
            '/admin/portal-papua-tengah',
            '/admin/portal-opd',
            '/admin/galeri'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($serviceManager)->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "Service manager should access {$route}, got status {$response->status()}"
            );
        }

        foreach ($forbiddenRoutes as $route) {
            $response = $this->actingAs($serviceManager)->get($route);
            $this->assertEquals(
                403,
                $response->status(),
                "Service manager should not access {$route}"
            );
        }
    }

    /** @test */
    public function opd_manager_can_access_opd_modules()
    {
        $opdManager = $this->users['opd_manager'];
        
        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/portal-opd'
        ];

        $forbiddenRoutes = [
            '/admin/wbs',
            '/admin/portal-papua-tengah',
            '/admin/pelayanan',
            '/admin/dokumen',
            '/admin/galeri'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($opdManager)->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "OPD manager should access {$route}, got status {$response->status()}"
            );
        }

        foreach ($forbiddenRoutes as $route) {
            $response = $this->actingAs($opdManager)->get($route);
            $this->assertEquals(
                403,
                $response->status(),
                "OPD manager should not access {$route}"
            );
        }
    }

    /** @test */
    public function wbs_manager_can_access_wbs_modules()
    {
        $wbsManager = $this->users['wbs_manager'];
        
        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/wbs'
        ];

        $forbiddenRoutes = [
            '/admin/portal-papua-tengah',
            '/admin/portal-opd',
            '/admin/pelayanan',
            '/admin/dokumen',
            '/admin/galeri'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($wbsManager)->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "WBS manager should access {$route}, got status {$response->status()}"
            );
        }

        foreach ($forbiddenRoutes as $route) {
            $response = $this->actingAs($wbsManager)->get($route);
            $this->assertEquals(
                403,
                $response->status(),
                "WBS manager should not access {$route}"
            );
        }
    }

    /** @test */
    public function specialized_admins_can_access_only_their_modules()
    {
        $specializedRoles = [
            'admin_wbs' => ['/admin/wbs'],
            'admin_berita' => ['/admin/portal-papua-tengah'],
            'admin_portal_opd' => ['/admin/portal-opd'],
            'admin_pelayanan' => ['/admin/pelayanan'],
            'admin_dokumen' => ['/admin/dokumen'],
            'admin_galeri' => ['/admin/galeri'],
            'admin_faq' => ['/admin/faq']
        ];

        foreach ($specializedRoles as $role => $allowedRoutes) {
            $user = $this->users[$role];
            
            // Dashboard should be accessible to all admin roles
            $response = $this->actingAs($user)->get('/admin/dashboard');
            $this->assertTrue(
                $response->isSuccessful(),
                "{$role} should access dashboard"
            );

            // Test allowed routes
            foreach ($allowedRoutes as $route) {
                $response = $this->actingAs($user)->get($route);
                $this->assertTrue(
                    $response->isSuccessful(),
                    "{$role} should access {$route}, got status {$response->status()}"
                );
            }

            // Test forbidden routes
            $allAdminRoutes = [
                '/admin/wbs',
                '/admin/portal-papua-tengah',
                '/admin/portal-opd',
                '/admin/pelayanan',
                '/admin/dokumen',
                '/admin/galeri',
                '/admin/faq'
            ];

            $forbiddenRoutes = array_diff($allAdminRoutes, $allowedRoutes);
            
            foreach ($forbiddenRoutes as $route) {
                $response = $this->actingAs($user)->get($route);
                $this->assertEquals(
                    403,
                    $response->status(),
                    "{$role} should not access {$route}"
                );
            }
        }
    }

    /** @test */
    public function regular_user_cannot_access_admin_modules()
    {
        $user = $this->users['user'];
        
        $adminRoutes = [
            '/admin/dashboard',
            '/admin/wbs',
            '/admin/portal-papua-tengah',
            '/admin/portal-opd',
            '/admin/pelayanan',
            '/admin/dokumen',
            '/admin/galeri',
            '/admin/faq'
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $this->assertEquals(
                403,
                $response->status(),
                "Regular user should not access {$route}"
            );
        }
    }

    /** @test */
    public function user_role_methods_work_correctly()
    {
        // Test isAdmin method
        $this->assertTrue($this->users['super_admin']->isAdmin());
        $this->assertTrue($this->users['admin']->isAdmin());
        $this->assertTrue($this->users['content_manager']->isAdmin());
        $this->assertFalse($this->users['user']->isAdmin());

        // Test isSuperAdmin method
        $this->assertTrue($this->users['super_admin']->isSuperAdmin());
        $this->assertFalse($this->users['admin']->isSuperAdmin());

        // Test hasRole method
        $this->assertTrue($this->users['admin_wbs']->hasRole('admin_wbs'));
        $this->assertFalse($this->users['admin_wbs']->hasRole('admin_berita'));

        // Test hasAnyRole method
        $this->assertTrue($this->users['admin_wbs']->hasAnyRole(['admin_wbs', 'admin_berita']));
        $this->assertFalse($this->users['admin_wbs']->hasAnyRole(['admin_berita', 'admin_galeri']));

        // Test canManageUsers method
        $this->assertTrue($this->users['super_admin']->canManageUsers());
        $this->assertFalse($this->users['admin']->canManageUsers());

        // Test canApproveContent method
        $this->assertTrue($this->users['super_admin']->canApproveContent());
        $this->assertTrue($this->users['admin']->canApproveContent());
        $this->assertTrue($this->users['content_manager']->canApproveContent());
        $this->assertFalse($this->users['admin_wbs']->canApproveContent());
    }

    /** @test */
    public function role_level_hierarchy_works()
    {
        $this->assertEquals(100, $this->users['super_admin']->getRoleLevel());
        $this->assertEquals(90, $this->users['admin']->getRoleLevel());
        $this->assertEquals(80, $this->users['content_manager']->getRoleLevel());
        $this->assertEquals(70, $this->users['admin_wbs']->getRoleLevel());
        $this->assertEquals(10, $this->users['user']->getRoleLevel());
    }

    /** @test */
    public function accessible_modules_method_works()
    {
        $superAdminModules = $this->users['super_admin']->getAccessibleModules();
        $this->assertEquals(['all'], $superAdminModules);

        $adminModules = $this->users['admin']->getAccessibleModules();
        $this->assertContains('beranda', $adminModules);
        $this->assertContains('berita', $adminModules);
        $this->assertContains('wbs', $adminModules);

        $adminWbsModules = $this->users['admin_wbs']->getAccessibleModules();
        $this->assertContains('beranda', $adminWbsModules);
        $this->assertContains('wbs', $adminWbsModules);
        $this->assertNotContains('berita', $adminWbsModules);

        $userModules = $this->users['user']->getAccessibleModules();
        $this->assertEquals(['beranda'], $userModules);
    }
}
