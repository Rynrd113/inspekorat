<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create super admin user
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create test user data
        $this->createTestUserData();
    }

    private function createTestUserData()
    {
        $roles = ['admin', 'admin_profil', 'admin_pelayanan', 'admin_dokumen', 'admin_galeri', 'admin_faq', 'admin_berita', 'admin_wbs', 'admin_opd', 'user'];
        
        for ($i = 1; $i <= 15; $i++) {
            User::create([
                'name' => 'User Test ' . $i,
                'email' => 'user' . $i . '@inspektorat.id',
                'password' => bcrypt('password123'),
                'role' => $roles[($i - 1) % 10],
                'is_active' => $i % 5 !== 0, // Every 5th user is inactive
                'phone' => '0901234567' . $i,
                'avatar' => 'avatars/user-' . $i . '.jpg',
                'department' => 'Department ' . ceil($i / 3),
                'position' => 'Position ' . $i,
                'last_login' => now()->subDays(rand(1, 30)),
                'created_by' => $this->superAdmin->id,
                'updated_by' => $this->superAdmin->id,
            ]);
        }
    }

    /**
     * Test User index page
     */
    public function testUserIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->assertSee('Daftar User')
                ->assertSee('Tambah User')
                ->assertSee('User Test 1')
                ->assertSee('User Test 2')
                ->assertSee('User Test 3')
                ->assertSee('Role')
                ->assertSee('Status')
                ->assertSee('Last Login');
        });
    }

    /**
     * Test User pagination
     */
    public function testUserPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->assertSee('Daftar User')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('User Test 11')
                ->assertSee('User Test 12');
        });
    }

    /**
     * Test User search functionality
     */
    public function testUserSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->type('search', 'User Test 5')
                ->press('Cari User')
                ->pause(1000)
                ->assertSee('User Test 5')
                ->assertDontSee('User Test 1')
                ->assertDontSee('User Test 2');
        });
    }

    /**
     * Test User create page
     */
    public function testUserCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->click('a[href="' . route('admin.users.create') . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/users/create')
                ->assertSee('Tambah User')
                ->assertPresent('input[name="name"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('input[name="password_confirmation"]')
                ->assertPresent('select[name="role"]')
                ->assertPresent('input[name="phone"]')
                ->assertPresent('input[name="department"]')
                ->assertPresent('input[name="position"]')
                ->assertPresent('input[name="avatar"]')
                ->assertPresent('input[name="is_active"]');
        });
    }

    /**
     * Test User store functionality
     */
    public function testUserStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/create')
                ->type('name', 'John Doe')
                ->type('email', 'john.doe@inspektorat.id')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->select('role', 'admin')
                ->type('phone', '09012345678')
                ->type('department', 'Audit Internal')
                ->type('position', 'Auditor')
                ->attach('avatar', __DIR__ . '/../../fixtures/user-avatar.jpg')
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/users')
                ->assertSee('berhasil')
                ->assertSee('John Doe')
                ->assertSee('john.doe@inspektorat.id');
        });
    }

    /**
     * Test User store validation
     */
    public function testUserStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('required');
        });
    }

    /**
     * Test User email uniqueness validation
     */
    public function testUserEmailUniquenessValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/create')
                ->type('name', 'Test User')
                ->type('email', 'user1@inspektorat.id') // Already exists
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->select('role', 'admin')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The email has already been taken');
        });
    }

    /**
     * Test User show page
     */
    public function testUserShowPage()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->click('a[href="/admin/users/' . $user->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/users/' . $user->id)
                ->assertSee($user->name)
                ->assertSee($user->email)
                ->assertSee($user->role)
                ->assertSee($user->phone)
                ->assertSee($user->department)
                ->assertSee($user->position)
                ->assertSee('User Profile')
                ->assertSee('Activity Log');
        });
    }

    /**
     * Test User edit page
     */
    public function testUserEditPage()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->click('a[href="/admin/users/' . $user->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/users/' . $user->id . '/edit')
                ->assertSee('Edit User')
                ->assertInputValue('name', $user->name)
                ->assertInputValue('email', $user->email)
                ->assertInputValue('phone', $user->phone)
                ->assertInputValue('department', $user->department)
                ->assertInputValue('position', $user->position)
                ->assertSelected('role', $user->role);
        });
    }

    /**
     * Test User update functionality
     */
    public function testUserUpdate()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $user->id . '/edit')
                ->clear('name')
                ->type('name', 'Updated User Name')
                ->clear('phone')
                ->type('phone', '09087654321')
                ->clear('department')
                ->type('department', 'Updated Department')
                ->clear('position')
                ->type('position', 'Updated Position')
                ->select('role', 'admin')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/users')
                ->assertSee('berhasil')
                ->assertSee('Updated User Name')
                ->assertSee('Updated Department');
        });
    }

    /**
     * Test User delete functionality
     */
    public function testUserDelete()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        $userName = $user->name;
        
        $this->browse(function (Browser $browser) use ($user, $userName) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus user ini?\')) { document.getElementById(\'delete-form-' . $user->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/users')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($userName);
        });
    }

    /**
     * Test User role filter
     */
    public function testUserRoleFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->select('role', 'admin')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('admin')
                ->select('role', 'user')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('user');
        });
    }

    /**
     * Test User status filter
     */
    public function testUserStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->select('status', 'active')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Active')
                ->select('status', 'inactive')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Inactive');
        });
    }

    /**
     * Test User status toggle
     */
    public function testUserStatusToggle()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->click('input[name="is_active"][data-id="' . $user->id . '"]')
                ->pause(1000)
                ->assertSee('Status berhasil diubah');
        });
    }

    /**
     * Test User password reset
     */
    public function testUserPasswordReset()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $user->id . '/edit')
                ->type('password', 'newpassword123')
                ->type('password_confirmation', 'newpassword123')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Password berhasil diubah')
                ->assertSee('Data berhasil diperbarui');
        });
    }

    /**
     * Test User bulk actions
     */
    public function testUserBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->check('select-all')
                ->select('bulk-action', 'activate')
                ->press('Apply')
                ->pause(1000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test User responsive design
     */
    public function testUserResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->assertSee('Users')
                ->assertSee('Tambah User')
                ->resize(768, 1024) // iPad size
                ->assertSee('Users')
                ->assertSee('Tambah User')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test User avatar upload
     */
    public function testUserAvatarUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/create')
                ->type('name', 'User with Avatar')
                ->type('email', 'avatar@inspektorat.id')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->select('role', 'admin')
                ->attach('avatar', __DIR__ . '/../../fixtures/user-avatar.jpg')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Avatar uploaded successfully')
                ->assertSee('Data berhasil disimpan');
        });
    }

    /**
     * Test User profile update
     */
    public function testUserProfileUpdate()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $user->id . '/edit')
                ->clear('name')
                ->type('name', 'Updated Profile Name')
                ->clear('phone')
                ->type('phone', '09087654321')
                ->type('bio', 'Updated bio information')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Profile berhasil diperbarui')
                ->assertSee('Updated Profile Name');
        });
    }

    /**
     * Test User role change restrictions
     */
    public function testUserRoleChangeRestrictions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $this->superAdmin->id . '/edit')
                ->assertSee('Cannot change role of super admin')
                ->assertPresent('select[name="role"][disabled]');
        });
    }

    /**
     * Test User activity log
     */
    public function testUserActivityLog()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $user->id)
                ->click('a[href="#activity-log"]')
                ->pause(1000)
                ->assertSee('Activity Log')
                ->assertSee('Login')
                ->assertSee('Profile Updated');
        });
    }

    /**
     * Test User statistics
     */
    public function testUserStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->assertSee('Total Users')
                ->assertSee('Active Users')
                ->assertSee('Inactive Users')
                ->assertSee('Users by Role')
                ->assertSee('Recent Logins');
        });
    }

    /**
     * Test User export functionality
     */
    public function testUserExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->click('a[href="/admin/users/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test User import functionality
     */
    public function testUserImport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->clickLink('Import')
                ->pause(1000)
                ->attach('file', __DIR__ . '/../../fixtures/users-import.xlsx')
                ->press('Import')
                ->pause(2000)
                ->assertSee('Import berhasil');
        });
    }

    /**
     * Test User advanced search
     */
    public function testUserAdvancedSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->clickLink('Advanced Search')
                ->pause(1000)
                ->type('name', 'Test')
                ->type('email', '@inspektorat.id')
                ->select('role', 'admin')
                ->select('status', 'active')
                ->type('department', 'Department')
                ->type('position', 'Position')
                ->press('Search')
                ->pause(1000)
                ->assertSee('User Test');
        });
    }

    /**
     * Test User cannot delete superadmin
     */
    public function testUserCannotDeleteSuperadmin()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users')
                ->assertDontSee('button[onclick*="delete-form-' . $this->superAdmin->id . '"]')
                ->assertSee('Cannot delete super admin');
        });
    }

    /**
     * Test User login history
     */
    public function testUserLoginHistory()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $user->id)
                ->click('a[href="#login-history"]')
                ->pause(1000)
                ->assertSee('Login History')
                ->assertSee('IP Address')
                ->assertSee('User Agent')
                ->assertSee('Login Time');
        });
    }

    /**
     * Test User permissions management
     */
    public function testUserPermissionsManagement()
    {
        $user = User::where('email', '!=', 'superadmin@inspektorat.id')->first();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/users/' . $user->id . '/edit')
                ->click('a[href="#permissions"]')
                ->pause(1000)
                ->assertSee('Permissions')
                ->assertSee('Module Access')
                ->check('permission[can_view_users]')
                ->check('permission[can_edit_users]')
                ->press('Update Permissions')
                ->pause(1000)
                ->assertSee('Permissions updated successfully');
        });
    }
}
