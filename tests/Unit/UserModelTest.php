<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function is_admin_method_works_correctly()
    {
        $adminRoles = [
            'admin', 'super_admin', 'content_manager', 'service_manager', 
            'opd_manager', 'wbs_manager', 'admin_wbs', 'admin_berita', 
            'admin_portal_opd', 'admin_pelayanan', 'admin_dokumen', 
            'admin_galeri', 'admin_faq'
        ];

        foreach ($adminRoles as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->assertTrue($user->isAdmin(), "User with role {$role} should be admin");
        }

        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($user->isAdmin(), "User with role 'user' should not be admin");
    }

    /** @test */
    public function is_super_admin_method_works_correctly()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->assertTrue($superAdmin->isSuperAdmin());

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertFalse($admin->isSuperAdmin());

        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($user->isSuperAdmin());
    }

    /** @test */
    public function has_role_method_works_correctly()
    {
        $user = User::factory()->create(['role' => 'admin_wbs']);
        
        $this->assertTrue($user->hasRole('admin_wbs'));
        $this->assertFalse($user->hasRole('admin_berita'));
        $this->assertFalse($user->hasRole('admin'));
    }

    /** @test */
    public function has_any_role_method_works_correctly()
    {
        $user = User::factory()->create(['role' => 'admin_wbs']);
        
        $this->assertTrue($user->hasAnyRole(['admin_wbs', 'admin_berita']));
        $this->assertTrue($user->hasAnyRole(['admin_wbs']));
        $this->assertFalse($user->hasAnyRole(['admin_berita', 'admin_galeri']));
    }

    /** @test */
    public function get_role_level_method_works_correctly()
    {
        $roleLevels = [
            'super_admin' => 100,
            'admin' => 90,
            'content_manager' => 80,
            'service_manager' => 80,
            'opd_manager' => 80,
            'wbs_manager' => 80,
            'admin_wbs' => 70,
            'admin_berita' => 70,
            'admin_portal_opd' => 70,
            'admin_pelayanan' => 70,
            'admin_dokumen' => 70,
            'admin_galeri' => 70,
            'admin_faq' => 70,
            'user' => 10
        ];

        foreach ($roleLevels as $role => $expectedLevel) {
            $user = User::factory()->create(['role' => $role]);
            $this->assertEquals($expectedLevel, $user->getRoleLevel(), "Role {$role} should have level {$expectedLevel}");
        }

        // Test unknown role
        $user = User::factory()->create(['role' => 'unknown_role']);
        $this->assertEquals(0, $user->getRoleLevel());
    }

    /** @test */
    public function can_manage_users_method_works_correctly()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->assertTrue($superAdmin->canManageUsers());

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertFalse($admin->canManageUsers());

        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($user->canManageUsers());
    }

    /** @test */
    public function can_approve_content_method_works_correctly()
    {
        $rolesCanApprove = ['super_admin', 'admin', 'content_manager'];
        $rolesCannotApprove = ['admin_wbs', 'admin_berita', 'user'];

        foreach ($rolesCanApprove as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->assertTrue($user->canApproveContent(), "User with role {$role} should be able to approve content");
        }

        foreach ($rolesCannotApprove as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->assertFalse($user->canApproveContent(), "User with role {$role} should not be able to approve content");
        }
    }

    /** @test */
    public function get_accessible_modules_method_works_correctly()
    {
        $moduleAccess = [
            'super_admin' => ['all'],
            'admin' => ['beranda', 'profil', 'unit_kerja', 'pelayanan', 'dokumen', 'berita', 'galeri', 'kontak', 'statistik', 'wbs', 'portal_opd'],
            'content_manager' => ['beranda', 'berita', 'galeri', 'faq'],
            'service_manager' => ['beranda', 'pelayanan', 'dokumen', 'kontak'],
            'opd_manager' => ['beranda', 'portal_opd', 'unit_kerja'],
            'wbs_manager' => ['beranda', 'wbs', 'statistik'],
            'admin_wbs' => ['beranda', 'wbs'],
            'admin_berita' => ['beranda', 'berita'],
            'admin_portal_opd' => ['beranda', 'portal_opd'],
            'admin_pelayanan' => ['beranda', 'pelayanan'],
            'admin_dokumen' => ['beranda', 'dokumen'],
            'admin_galeri' => ['beranda', 'galeri'],
            'admin_faq' => ['beranda', 'faq'],
            'user' => ['beranda']
        ];

        foreach ($moduleAccess as $role => $expectedModules) {
            $user = User::factory()->create(['role' => $role]);
            $actualModules = $user->getAccessibleModules();
            $this->assertEquals($expectedModules, $actualModules, "User with role {$role} should have access to modules: " . implode(', ', $expectedModules));
        }
    }

    /** @test */
    public function get_roles_static_method_works_correctly()
    {
        $roles = User::getRoles();
        
        $expectedRoles = [
            'user' => 'User',
            'admin_wbs' => 'Admin WBS',
            'admin_berita' => 'Admin Berita',
            'admin_portal_opd' => 'Admin Portal OPD',
            'admin_pelayanan' => 'Admin Pelayanan',
            'admin_dokumen' => 'Admin Dokumen',
            'admin_galeri' => 'Admin Galeri',
            'admin_faq' => 'Admin FAQ',
            'content_manager' => 'Content Manager',
            'service_manager' => 'Service Manager',
            'opd_manager' => 'OPD Manager',
            'wbs_manager' => 'WBS Manager',
            'admin' => 'Admin',
            'super_admin' => 'Super Admin'
        ];

        $this->assertEquals($expectedRoles, $roles);
    }

    /** @test */
    public function get_role_description_method_works_correctly()
    {
        $roleDescriptions = [
            'super_admin' => 'Akses penuh ke semua modul termasuk manajemen user',
            'admin' => 'Akses ke semua modul operasional',
            'content_manager' => 'Mengelola konten: berita, galeri, FAQ',
            'service_manager' => 'Mengelola layanan: pelayanan, dokumen, kontak',
            'opd_manager' => 'Mengelola data OPD dan unit kerja',
            'wbs_manager' => 'Mengelola WBS dan statistik terkait',
            'admin_wbs' => 'Khusus mengelola WBS',
            'admin_berita' => 'Khusus mengelola berita',
            'admin_portal_opd' => 'Khusus mengelola Portal OPD',
            'admin_pelayanan' => 'Khusus mengelola pelayanan',
            'admin_dokumen' => 'Khusus mengelola dokumen',
            'admin_galeri' => 'Khusus mengelola galeri',
            'admin_faq' => 'Khusus mengelola FAQ',
            'user' => 'Akses terbatas, hanya view'
        ];

        foreach ($roleDescriptions as $role => $expectedDescription) {
            $user = User::factory()->create(['role' => $role]);
            $this->assertEquals($expectedDescription, $user->getRoleDescription(), "Role {$role} should have description: {$expectedDescription}");
        }

        // Test unknown role
        $user = User::factory()->create(['role' => 'unknown_role']);
        $this->assertEquals('Role tidak dikenali', $user->getRoleDescription());
    }

    /** @test */
    public function user_has_audit_log_trait()
    {
        $user = User::factory()->create();
        
        // Check if the trait is used
        $traits = class_uses_recursive(User::class);
        $this->assertContains('App\Traits\HasAuditLog', $traits);
    }

    /** @test */
    public function user_has_api_tokens_trait()
    {
        $user = User::factory()->create();
        
        // Check if the trait is used
        $traits = class_uses_recursive(User::class);
        $this->assertContains('Laravel\Sanctum\HasApiTokens', $traits);
    }

    /** @test */
    public function user_fillable_attributes_are_correct()
    {
        $user = new User();
        $expected = ['name', 'email', 'password', 'role'];
        
        $this->assertEquals($expected, $user->getFillable());
    }

    /** @test */
    public function user_hidden_attributes_are_correct()
    {
        $user = new User();
        $expected = ['password', 'remember_token'];
        
        $this->assertEquals($expected, $user->getHidden());
    }

    /** @test */
    public function user_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plain-password'
        ]);

        $this->assertTrue(\Hash::check('plain-password', $user->password));
        $this->assertNotEquals('plain-password', $user->password);
    }

    /** @test */
    public function user_email_verified_at_is_cast_to_datetime()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }
}
