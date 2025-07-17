<?php

namespace Tests\Browser\Modules\User;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithForms;
use Tests\DuskTestCase;

class UserManagementTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithForms;

    /**
     * Test super admin dapat melihat daftar user
     */
    public function test_super_admin_dapat_melihat_daftar_user()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->assertSee('Users')
                    ->assertVisible('.data-table')
                    ->assertVisible('a[href*="create"]');
        });
    }

    /**
     * Test super admin dapat membuat user baru
     */
    public function test_super_admin_dapat_membuat_user_baru()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $userData = [
                'name' => 'Test User ' . time(),
                'email' => 'testuser' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($userData['name']);
        });
    }

    /**
     * Test validasi form create user
     */
    public function test_validasi_form_create_user()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->press('button[type="submit"]') // Submit empty form
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('name wajib diisi')
                    ->assertSee('email wajib diisi')
                    ->assertSee('password wajib diisi')
                    ->assertSee('role wajib dipilih');
        });
    }

    /**
     * Test validasi email unique
     */
    public function test_validasi_email_unique()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $existingEmail = 'existing@example.com';
            
            // Create first user
            $userData1 = [
                'name' => 'First User',
                'email' => $existingEmail,
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData1)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($userData1['name']);
            
            // Try to create second user with same email
            $userData2 = [
                'name' => 'Second User',
                'email' => $existingEmail,
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567891',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567891',
            ];
            
            $browser->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData2)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Email sudah digunakan');
        });
    }

    /**
     * Test validasi password confirmation
     */
    public function test_validasi_password_confirmation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm([
                        'name' => 'Test User',
                        'email' => 'test@example.com',
                        'password' => 'password123',
                        'password_confirmation' => 'differentpassword',
                        'role' => 'admin',
                        'is_active' => 1,
                        'nip' => '198001011234567890',
                        'jabatan' => 'Staff',
                        'unit_kerja' => 'Inspektorat',
                        'telepon' => '081234567890',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Password confirmation tidak cocok');
        });
    }

    /**
     * Test super admin dapat mengedit user
     */
    public function test_super_admin_dapat_mengedit_user()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create user first
            $originalData = [
                'name' => 'Original User ' . time(),
                'email' => 'original' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($originalData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($originalData['name']);
            
            // Edit user
            $updatedData = [
                'name' => 'Updated User ' . time(),
                'jabatan' => 'Supervisor',
                'unit_kerja' => 'Inspektorat Papua Tengah',
                'telepon' => '081234567891',
            ];
            
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/users/*/edit', 30)
                    ->fillForm($updatedData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($updatedData['name']);
        });
    }

    /**
     * Test super admin dapat mengubah password user
     */
    public function test_super_admin_dapat_mengubah_password_user()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create user first
            $userData = [
                'name' => 'Password Test User ' . time(),
                'email' => 'passwordtest' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($userData['name']);
            
            // Change password
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/users/*/edit', 30)
                    ->fillForm([
                        'password' => 'newpassword123',
                        'password_confirmation' => 'newpassword123',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee('Password berhasil diubah');
        });
    }

    /**
     * Test super admin dapat mengubah role user
     */
    public function test_super_admin_dapat_mengubah_role_user()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create user first
            $userData = [
                'name' => 'Role Test User ' . time(),
                'email' => 'roletest' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($userData['name']);
            
            // Change role
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/users/*/edit', 30)
                    ->select('select[name="role"]', 'content_manager')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee('Content Manager');
        });
    }

    /**
     * Test super admin dapat menonaktifkan user
     */
    public function test_super_admin_dapat_menonaktifkan_user()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create user first
            $userData = [
                'name' => 'Deactivate Test User ' . time(),
                'email' => 'deactivate' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($userData['name']);
            
            // Deactivate user
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/users/*/edit', 30)
                    ->uncheck('input[name="is_active"]')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee('Inactive');
        });
    }

    /**
     * Test super admin dapat menghapus user
     */
    public function test_super_admin_dapat_menghapus_user()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create user first
            $userData = [
                'name' => 'Delete Test User ' . time(),
                'email' => 'delete' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->assertSee($userData['name']);
            
            // Delete user
            $browser->click('button[data-action="delete"]')
                    ->waitFor('.modal', 10)
                    ->press('Ya')
                    ->waitForReload()
                    ->assertDontSee($userData['name']);
        });
    }

    /**
     * Test search user functionality
     */
    public function test_search_user_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create test user
            $userData = [
                'name' => 'Search Test User Unique ' . time(),
                'email' => 'searchunique' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->type('input[name="search"]', 'Unique')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee($userData['name']);
        });
    }

    /**
     * Test filter user by role
     */
    public function test_filter_user_by_role()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->select('select[name="role"]', 'admin')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertSee('Admin');
        });
    }

    /**
     * Test filter user by status
     */
    public function test_filter_user_by_status()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->select('select[name="status"]', 'active')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertSee('Active');
        });
    }

    /**
     * Test pagination users
     */
    public function test_pagination_users()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->assertVisible('.pagination');
        });
    }

    /**
     * Test role admin tidak dapat akses user management
     */
    public function test_admin_tidak_dapat_akses_user_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden');
        });
    }

    /**
     * Test role content manager tidak dapat akses user management
     */
    public function test_content_manager_tidak_dapat_akses_user_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit('/admin/users')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden');
        });
    }

    /**
     * Test user profile page
     */
    public function test_user_profile_page()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/profile')
                    ->assertSee('Profile')
                    ->assertVisible('form');
        });
    }

    /**
     * Test user dapat mengubah profile sendiri
     */
    public function test_user_dapat_mengubah_profile_sendiri()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/profile')
                    ->fillForm([
                        'name' => 'Updated Name ' . time(),
                        'jabatan' => 'Senior Staff',
                        'telepon' => '081234567899',
                    ])
                    ->press('Update Profile')
                    ->waitForReload()
                    ->assertSee('Profile berhasil diperbarui');
        });
    }

    /**
     * Test user dapat mengubah password sendiri
     */
    public function test_user_dapat_mengubah_password_sendiri()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/profile')
                    ->fillForm([
                        'current_password' => 'password',
                        'password' => 'newpassword123',
                        'password_confirmation' => 'newpassword123',
                    ])
                    ->press('Update Password')
                    ->waitForReload()
                    ->assertSee('Password berhasil diubah');
        });
    }

    /**
     * Test validasi current password
     */
    public function test_validasi_current_password()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/profile')
                    ->fillForm([
                        'current_password' => 'wrongpassword',
                        'password' => 'newpassword123',
                        'password_confirmation' => 'newpassword123',
                    ])
                    ->press('Update Password')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Current password salah');
        });
    }

    /**
     * Test bulk actions users
     */
    public function test_bulk_actions_users()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->check('input[name="select_all"]')
                    ->select('select[name="bulk_action"]', 'deactivate')
                    ->press('Apply')
                    ->waitForReload();
        });
    }

    /**
     * Test export users data
     */
    public function test_export_users_data()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->click('a[href*="export"]')
                    ->pause(2000); // Wait for export
        });
    }

    /**
     * Test responsive design pada user management
     */
    public function test_responsive_design_user_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users');
            
            $this->testResponsiveDesign($browser, function ($browser, $device) {
                $browser->assertVisible('.data-table')
                        ->assertVisible('a[href*="create"]');
            });
        });
    }

    /**
     * Test performance pada user management
     */
    public function test_performance_user_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/users')
                    ->waitForLoadingToFinish($browser);
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Page should load within 3 seconds
            $this->assertLessThan(3, $loadTime, 'Users index page took too long to load: ' . $loadTime . ' seconds');
        });
    }

    /**
     * Test security protection user management
     */
    public function test_security_protection_user_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users/create')
                    ->assertPresent('input[name="_token"]'); // CSRF protection
        });
    }

    /**
     * Test audit log untuk user management
     */
    public function test_audit_log_user_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $userData = [
                'name' => 'Audit Test User ' . time(),
                'email' => 'audit' . time() . '@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'is_active' => 1,
                'nip' => '198001011234567890',
                'jabatan' => 'Staff',
                'unit_kerja' => 'Inspektorat',
                'telepon' => '081234567890',
            ];
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm($userData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30)
                    ->visit('/admin/audit-logs')
                    ->assertSee('User created');
        });
    }
}
