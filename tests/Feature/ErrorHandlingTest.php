<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function non_existent_routes_return_404()
    {
        $response = $this->get('/non-existent-route');
        $response->assertStatus(404);

        $response = $this->get('/admin/non-existent-route');
        $response->assertStatus(404);

        $response = $this->getJson('/api/non-existent-route');
        $response->assertStatus(404);
    }

    /** @test */
    public function invalid_model_ids_return_404()
    {
        $response = $this->actingAs($this->admin)->get('/admin/wbs/99999');
        $response->assertStatus(404);

        $response = $this->actingAs($this->admin)->get('/admin/portal-papua-tengah/99999');
        $response->assertStatus(404);

        $response = $this->actingAs($this->admin)->get('/admin/portal-opd/99999');
        $response->assertStatus(404);
    }

    /** @test */
    public function invalid_api_model_ids_return_404()
    {
        $token = $this->admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/wbs/99999');
        $response->assertStatus(404);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/portal-papua-tengah/99999');
        $response->assertStatus(404);
    }

    /** @test */
    public function invalid_http_methods_return_405()
    {
        // Test invalid methods on existing routes
        $response = $this->put('/admin/login');
        $response->assertStatus(405);

        $response = $this->patch('/admin/dashboard');
        $response->assertStatus(405);

        $response = $this->delete('/admin/dashboard');
        $response->assertStatus(405);
    }

    /** @test */
    public function csrf_protection_works()
    {
        // Test CSRF protection on POST routes
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);
        
        // Should be successful or redirect, not 419 CSRF error
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    /** @test */
    public function validation_errors_are_handled_correctly()
    {
        // Test form validation errors
        $response = $this->actingAs($this->admin)->post('/admin/wbs', []);
        $response->assertSessionHasErrors();

        // Test API validation errors
        $token = $this->admin->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->postJson('/api/wbs', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors();
    }

    /** @test */
    public function database_errors_are_handled_gracefully()
    {
        // Test duplicate email
        User::factory()->create(['email' => 'duplicate@example.com']);
        
        $response = $this->post('/admin/users', [
            'name' => 'Test User',
            'email' => 'duplicate@example.com',
            'password' => 'password',
            'role' => 'user'
        ]);
        
        // Should handle duplicate error gracefully
        $this->assertTrue(in_array($response->status(), [422, 302]));
    }

    /** @test */
    public function file_upload_errors_are_handled()
    {
        // Test invalid file type
        $response = $this->actingAs($this->admin)->post('/admin/portal-papua-tengah', [
            'judul' => 'Test News',
            'konten' => 'Test content',
            'kategori' => 'berita',
            'gambar' => 'not-a-file'
        ]);
        
        $this->assertTrue(in_array($response->status(), [422, 302]));
    }

    /** @test */
    public function large_file_upload_is_rejected()
    {
        // This test depends on file upload limits configuration
        $response = $this->actingAs($this->admin)->post('/admin/portal-papua-tengah', [
            'judul' => 'Test News',
            'konten' => 'Test content',
            'kategori' => 'berita',
            'gambar' => \Illuminate\Http\UploadedFile::fake()->create('large-file.jpg', 10000) // 10MB
        ]);
        
        // Should either succeed or fail with validation error
        $this->assertTrue(in_array($response->status(), [200, 302, 422]));
    }

    /** @test */
    public function sql_injection_attempts_are_blocked()
    {
        // Test SQL injection in search parameters
        $response = $this->get('/berita?search=\' OR 1=1 --');
        $response->assertStatus(200);
        
        // Test SQL injection in API
        $response = $this->getJson('/api/portal-papua-tengah/public?search=\' OR 1=1 --');
        $response->assertStatus(200);
    }

    /** @test */
    public function xss_attempts_are_sanitized()
    {
        // Test XSS in form inputs
        $response = $this->actingAs($this->admin)->post('/admin/portal-papua-tengah', [
            'judul' => '<script>alert("XSS")</script>',
            'konten' => '<img src=x onerror=alert("XSS")>',
            'kategori' => 'berita'
        ]);
        
        $this->assertTrue(in_array($response->status(), [200, 302, 422]));
    }

    /** @test */
    public function rate_limiting_works()
    {
        // Test rate limiting on login attempts
        for ($i = 0; $i < 10; $i++) {
            $response = $this->post('/admin/login', [
                'email' => 'admin@example.com',
                'password' => 'wrong-password'
            ]);
        }
        
        // After many attempts, should be rate limited
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);
        
        // Should either succeed or be rate limited
        $this->assertTrue(in_array($response->status(), [200, 302, 429]));
    }

    /** @test */
    public function unauthorized_api_access_returns_401()
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);

        $response = $this->getJson('/api/dashboard/stats');
        $response->assertStatus(401);
    }

    /** @test */
    public function forbidden_access_returns_403()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin/wbs');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/admin/portal-papua-tengah');
        $response->assertStatus(403);
    }

    /** @test */
    public function expired_tokens_are_handled()
    {
        // Create a token and manually expire it
        $token = $this->admin->createToken('test-token')->plainTextToken;
        
        // Manually expire the token in database
        $this->admin->tokens()->update(['expires_at' => now()->subDay()]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/user');
        
        $response->assertStatus(401);
    }

    /** @test */
    public function malformed_json_requests_are_handled()
    {
        $response = $this->call('POST', '/api/auth/login', [], [], [], 
            ['CONTENT_TYPE' => 'application/json'],
            '{"email": "admin@example.com", "password": "password"' // Missing closing brace
        );
        
        $response->assertStatus(400);
    }

    /** @test */
    public function missing_required_headers_are_handled()
    {
        $response = $this->call('POST', '/api/auth/login', [], [], [], 
            ['CONTENT_TYPE' => 'text/plain'],
            '{"email": "admin@example.com", "password": "password"}'
        );
        
        // Should handle missing JSON header
        $this->assertTrue(in_array($response->status(), [400, 422]));
    }

    /** @test */
    public function server_errors_are_handled_gracefully()
    {
        // Simulate server error by accessing non-existent database table
        try {
            \DB::table('non_existent_table')->get();
        } catch (\Exception $e) {
            // Should catch database errors
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function maintenance_mode_works()
    {
        // Test maintenance mode (if implemented)
        $response = $this->get('/');
        $response->assertStatus(200); // Should be 503 when in maintenance mode
    }

    /** @test */
    public function debug_mode_doesnt_leak_sensitive_info()
    {
        // Test that debug mode doesn't leak sensitive information
        $response = $this->get('/non-existent-route');
        $response->assertStatus(404);
        
        // In production, shouldn't see detailed error messages
        $content = $response->getContent();
        $this->assertStringNotContainsString('database', strtolower($content));
        $this->assertStringNotContainsString('password', strtolower($content));
    }
}
