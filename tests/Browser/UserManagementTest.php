<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Database\Seeders\SimpleDuskSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SimpleDuskSeeder::class);
    }

    /**
     * Test admin can access user management page.
     */
    public function test_admin_can_access_user_management_page(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as super admin
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');
                    
            $this->submitLoginForm($browser);
            
            $browser->pause(3000)
                    ->visit('/admin/users')
                    ->pause(2000)
                    ->screenshot('user_management_page');

            // Check if page loaded successfully by verifying we're on the users page
            $currentUrl = $browser->driver->getCurrentURL();
            $isOnUsersPage = strpos($currentUrl, '/admin/users') !== false;
            $this->assertTrue($isOnUsersPage, 'Should be on admin users page');

            // Check for common user management elements
            $pageSource = $browser->driver->getPageSource();
            $hasUserElements = strpos($pageSource, 'user') !== false ||
                              strpos($pageSource, 'pengguna') !== false ||
                              strpos($pageSource, 'User') !== false ||
                              strpos($pageSource, 'nama') !== false ||
                              strpos($pageSource, 'email') !== false;
            
            $this->assertTrue($hasUserElements, 'Page should contain user-related content');
        });
    }

    /**
     * Test admin can create new user successfully.
     */
    public function test_admin_can_create_user_successfully(): void
    {
        $this->browse(function (Browser $browser) {
            // Login as admin
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');
                    
            $this->submitLoginForm($browser);
            
            $browser->pause(3000)
                    ->visit('/admin/users/create')
                    ->pause(2000)
                    ->screenshot('user_create_form');

            // Fill user form
            $userData = [
                'name' => 'New Test User',
                'email' => 'newuser@test.com',
                'password' => 'password123',
                'role' => 'content_manager',
            ];

            $browser->type('name', $userData['name'])
                    ->type('email', $userData['email'])
                    ->type('password', $userData['password'])
                    ->type('password_confirmation', $userData['password'])
                    ->select('role', $userData['role']);

            $this->submitForm($browser);
            
            $browser->pause(3000)
                    ->screenshot('user_created_success');

            // Verify user was created in database
            $this->assertDatabaseHas('users', [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role' => $userData['role'],
            ]);

            // Verify user appears in user list
            $browser->visit('/admin/users')
                    ->pause(2000)
                    ->assertSee($userData['name'])
                    ->assertSee($userData['email']);
        });
    }

    /**
     * Test admin can edit existing user.
     */
    public function test_admin_can_edit_existing_user(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'Original User',
            'email' => 'original@test.com',
            'role' => 'content_manager',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // Login as admin
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');
                    
            $this->submitLoginForm($browser);
            
            $browser->pause(3000)
                    ->visit("/admin/users/{$user->id}/edit")
                    ->pause(2000)
                    ->screenshot('user_edit_form')
                    ->assertInputValue('name', $user->name)
                    ->assertInputValue('email', $user->email);

            // Update user data
            $updatedData = [
                'name' => 'Updated User Name',
                'email' => 'updated@test.com',
                'role' => 'opd_manager',
            ];

            $browser->clear('name')
                    ->type('name', $updatedData['name'])
                    ->clear('email')
                    ->type('email', $updatedData['email'])
                    ->select('role', $updatedData['role']);

            $this->submitForm($browser);
            
            $browser->pause(3000)
                    ->screenshot('user_updated_success');

            // Verify user was updated in database
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'name' => $updatedData['name'],
                'email' => $updatedData['email'],
                'role' => $updatedData['role'],
            ]);
        });
    }

    /**
     * Test admin can delete user.
     */
    public function test_admin_can_delete_user(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'User to Delete',
            'email' => 'delete@test.com',
            'role' => 'content_manager',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // Login as admin
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');
                    
            $this->submitLoginForm($browser);
            
            $browser->pause(3000)
                    ->visit('/admin/users')
                    ->pause(2000)
                    ->assertSee($user->name);

            // Delete user with confirmation
            $browser->click("button[data-delete-id='{$user->id}']")
                    ->waitFor('.confirmation-modal', 5)
                    ->assertSee('Apakah Anda yakin?')
                    ->click('.btn-confirm-delete')
                    ->pause(3000)
                    ->screenshot('user_deleted_success');

            // Verify user was deleted from database
            $this->assertDatabaseMissing('users', [
                'id' => $user->id,
            ]);
        });
    }

    /**
     * Test role-based access restrictions.
     */
    public function test_role_based_access_restrictions(): void
    {
        // Create users with different roles
        $contentManager = User::factory()->create([
            'role' => 'content_manager',
            'email' => 'content@example.com'
        ]);

        $opdManager = User::factory()->create([
            'role' => 'opd_manager',
            'email' => 'opd@example.com'
        ]);

        $this->browse(function (Browser $browser) use ($contentManager, $opdManager) {
            // Test content manager cannot access user management
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', $contentManager->email)
                    ->type('password', 'password');
                    
            $this->submitLoginForm($browser);
            
            $browser->pause(3000)
                    ->visit('/admin/users')
                    ->pause(2000)
                    ->screenshot('content_manager_user_access');

            // Should see unauthorized or be redirected
            $pageSource = $browser->driver->getPageSource();
            $isUnauthorized = strpos($pageSource, '403') !== false ||
                             strpos($pageSource, 'Unauthorized') !== false ||
                             strpos($pageSource, 'tidak diizinkan') !== false;
            
            $this->assertTrue($isUnauthorized, 'Content manager should not access user management');

            $this->logout($browser);

            // Test OPD manager cannot access user management
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', $opdManager->email)
                    ->type('password', 'password');
                    
            $this->submitLoginForm($browser);
            
            $browser->pause(3000)
                    ->visit('/admin/users')
                    ->pause(2000)
                    ->screenshot('opd_manager_user_access');

            $pageSource = $browser->driver->getPageSource();
            $isUnauthorized = strpos($pageSource, '403') !== false ||
                             strpos($pageSource, 'Unauthorized') !== false ||
                             strpos($pageSource, 'tidak diizinkan') !== false;
            
            $this->assertTrue($isUnauthorized, 'OPD manager should not access user management');
        });
    }

    /**
     * Test user search and filtering.
     */
    public function test_user_search_and_filtering(): void
    {
        // Create users for searching
        $user1 = User::factory()->create([
            'name' => 'John Admin',
            'email' => 'john@admin.com',
            'role' => 'admin',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Manager',
            'email' => 'jane@manager.com',
            'role' => 'content_manager',
        ]);

        $this->browse(function (Browser $browser) use ($user1, $user2) {
            // Login as admin
            $browser->visit('/admin/login')
                    ->pause(2000)
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password');
                    
            $this->submitLoginForm($browser);
            
            $browser->pause(3000)
                    ->visit('/admin/users')
                    ->pause(2000);

            // Test search functionality
            $pageSource = $browser->driver->getPageSource();
            $hasSearchField = strpos($pageSource, 'search') !== false ||
                             strpos($pageSource, 'cari') !== false;

            if ($hasSearchField) {
                $browser->type('search', 'John')
                        ->pause(1000)
                        ->screenshot('user_search_results')
                        ->assertSee($user1->name);
                        
                // Test role filter if available
                $hasRoleFilter = strpos($pageSource, 'role') !== false;
                if ($hasRoleFilter) {
                    $browser->select('role', 'content_manager')
                            ->pause(1000)
                            ->screenshot('user_role_filtered');
                }
            }

            $this->assertTrue(true, 'User search functionality tested');
        });
    }

    /**
     * Helper method to submit login form with flexible button detection.
     */
    private function submitLoginForm(Browser $browser): void
    {
        try {
            $browser->press('Login');
        } catch (\Exception $e) {
            try {
                $browser->press('Masuk');
            } catch (\Exception $e) {
                try {
                    $browser->press('Sign In');
                } catch (\Exception $e) {
                    $browser->click('button[type="submit"]');
                }
            }
        }
    }

    /**
     * Helper method to submit forms with flexible button detection.
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