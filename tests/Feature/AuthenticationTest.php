<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create test users for all roles
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
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
            User::factory()->create([
                'name' => "Test {$role}",
                'email' => "test_{$role}@example.com",
                'password' => Hash::make('password'),
                'role' => $role
            ]);
        }
    }

    /** @test */
    public function admin_login_page_loads()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::where('email', 'test_admin@example.com')->first();

        $response = $this->post('/admin/login', [
            'email' => 'test_admin@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_credentials()
    {
        $response = $this->post('/admin/login', [
            'email' => 'test_admin@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::where('email', 'test_admin@example.com')->first();

        $response = $this->actingAs($user)->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    /** @test */
    public function all_roles_can_login_and_access_dashboard()
    {
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
            'admin_faq'
        ];

        foreach ($roles as $role) {
            $user = User::where('email', "test_{$role}@example.com")->first();

            // Login
            $response = $this->post('/admin/login', [
                'email' => "test_{$role}@example.com",
                'password' => 'password'
            ]);

            $response->assertRedirect('/admin/dashboard');
            $this->assertAuthenticatedAs($user);

            // Access dashboard
            $response = $this->actingAs($user)->get('/admin/dashboard');
            $response->assertStatus(200);

            // Logout
            $this->post('/admin/logout');
            $this->assertGuest();
        }
    }

    /** @test */
    public function guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function api_authentication_works()
    {
        $user = User::where('email', 'test_admin@example.com')->first();

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test_admin@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user',
            'token'
        ]);
    }

    /** @test */
    public function api_authentication_fails_with_wrong_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test_admin@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(401);
    }
}
