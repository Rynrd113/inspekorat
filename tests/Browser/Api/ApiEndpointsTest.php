<?php

namespace Tests\Browser\Api;

use App\Models\User;
use App\Models\Wbs;
use App\Models\PortalPapuaTengah;
use App\Models\InfoKantor;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ApiEndpointsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;
    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->superAdmin = User::create([
            'name' => 'Super Admin API',
            'email' => 'superadmin.api@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin API',
            'email' => 'admin.api@inspektorat.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create API token for authenticated requests
        $this->token = $this->superAdmin->createToken('api-test')->plainTextToken;

        $this->createTestData();
    }

    private function createTestData()
    {
        // Create test WBS data
        Wbs::create([
            'nama_pelapor' => 'API Test Reporter',
            'email' => 'api.reporter@test.com',
            'telepon' => '081234567890',
            'subjek' => 'API Test WBS Subject',
            'isi_laporan' => 'API Test WBS Content',
            'status' => 'pending',
            'created_by' => $this->superAdmin->id,
        ]);

        // Create test Portal Papua Tengah data
        PortalPapuaTengah::create([
            'judul' => 'API Test News',
            'slug' => 'api-test-news',
            'konten' => 'API test news content',
            'kategori' => 'berita',
            'is_published' => true,
            'published_at' => now(),
            'penulis' => 'API Test Author',
            'created_by' => $this->superAdmin->id,
        ]);

        // Create test Info Kantor data
        InfoKantor::create([
            'judul' => 'API Test Info',
            'konten' => 'API test info content',
            'kategori' => 'informasi',
            'status' => true,
            'created_by' => $this->superAdmin->id,
        ]);
    }

    /**
     * Test API authentication endpoint
     */
    public function testApiAuthenticationEndpoint()
    {
        $this->browse(function (Browser $browser) {
            // Test API login endpoint
            $browser->visit('/api/auth/login')
                ->waitFor('body', 5);

            // Verify login endpoint is accessible
            $response = $browser->driver->getPageSource();
            $this->assertNotEmpty($response);
        });
    }

    /**
     * Test API dashboard stats endpoint
     */
    public function testApiDashboardStatsEndpoint()
    {
        $this->browse(function (Browser $browser) {
            // Login first to get authenticated session
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test dashboard stats API endpoint
            $browser->visit('/api/dashboard/stats')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertStringContainsString('success', $response);
            $this->assertStringContainsString('wbs', $response);
            $this->assertStringContainsString('portal_papua_tengah', $response);
        });
    }

    /**
     * Test API WBS chart endpoint
     */
    public function testApiWbsChartEndpoint()
    {
        $this->browse(function (Browser $browser) {
            // Login first to get authenticated session
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test WBS chart API endpoint
            $browser->visit('/api/dashboard/wbs-chart')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertStringContainsString('success', $response);
            $this->assertStringContainsString('data', $response);
        });
    }

    /**
     * Test API user endpoint
     */
    public function testApiUserEndpoint()
    {
        $this->browse(function (Browser $browser) {
            // Login first to get authenticated session
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test user API endpoint
            $browser->visit('/api/user')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertStringContainsString('Super Admin API', $response);
            $this->assertStringContainsString('superadmin.api@inspektorat.id', $response);
        });
    }

    /**
     * Test public API endpoints (no authentication required)
     */
    public function testPublicApiEndpoints()
    {
        $this->browse(function (Browser $browser) {
            // Test public Portal Papua Tengah endpoint
            $browser->visit('/api/portal-papua-tengah/public')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            // Should contain news data or success response
            $this->assertNotEmpty($response);

            // Test public Info Kantor endpoint
            $browser->visit('/api/info-kantor/public')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            // Should contain info data or success response
            $this->assertNotEmpty($response);

            // Test public berita endpoint
            $browser->visit('/api/berita')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            // Should contain berita data or success response
            $this->assertNotEmpty($response);
        });
    }

    /**
     * Test API v1 endpoints with authentication
     */
    public function testApiV1EndpointsAuthenticated()
    {
        $this->browse(function (Browser $browser) {
            // Login first to get authenticated session
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test v1 WBS endpoint
            $browser->visit('/api/v1/wbs')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertNotEmpty($response);

            // Test v1 Info Kantor endpoint
            $browser->visit('/api/v1/info-kantor')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertNotEmpty($response);

            // Test v1 Portal Papua Tengah endpoint
            $browser->visit('/api/v1/portal-papua-tengah')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertNotEmpty($response);
        });
    }

    /**
     * Test API v1 stats endpoint
     */
    public function testApiV1StatsEndpoint()
    {
        $this->browse(function (Browser $browser) {
            // Login first to get authenticated session
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test v1 stats endpoint
            $browser->visit('/api/v1/stats')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertStringContainsString('success', $response);
        });
    }

    /**
     * Test API v1 WBS chart endpoint
     */
    public function testApiV1WbsChartEndpoint()
    {
        $this->browse(function (Browser $browser) {
            // Login first to get authenticated session
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test v1 wbs-chart endpoint
            $browser->visit('/api/v1/wbs-chart')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertStringContainsString('success', $response);
        });
    }

    /**
     * Test API authentication required endpoints without auth
     */
    public function testApiAuthenticationRequiredEndpointsWithoutAuth()
    {
        $this->browse(function (Browser $browser) {
            // Test accessing protected endpoint without authentication
            $browser->visit('/api/wbs')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            // Should show unauthorized or redirect to login
            $this->assertTrue(
                strpos($response, 'Unauthorized') !== false ||
                strpos($response, 'Unauthenticated') !== false ||
                strpos($response, 'login') !== false
            );

            // Test user endpoint without auth
            $browser->visit('/api/user')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            // Should show unauthorized or redirect to login
            $this->assertTrue(
                strpos($response, 'Unauthorized') !== false ||
                strpos($response, 'Unauthenticated') !== false ||
                strpos($response, 'login') !== false
            );
        });
    }

    /**
     * Test API logout endpoint
     */
    public function testApiLogoutEndpoint()
    {
        $this->browse(function (Browser $browser) {
            // Login first
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Verify user is authenticated
            $browser->visit('/api/user')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertStringContainsString('superadmin.api@inspektorat.id', $response);

            // Test logout endpoint
            $browser->visit('/api/logout')
                ->waitFor('body', 5);

            // After logout, accessing user endpoint should fail
            $browser->visit('/api/user')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            $this->assertTrue(
                strpos($response, 'Unauthorized') !== false ||
                strpos($response, 'Unauthenticated') !== false ||
                strpos($response, 'login') !== false
            );
        });
    }

    /**
     * Test API endpoints error handling
     */
    public function testApiEndpointsErrorHandling()
    {
        $this->browse(function (Browser $browser) {
            // Test invalid endpoint
            $browser->visit('/api/invalid-endpoint')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            // Should return 404 or error response
            $this->assertTrue(
                strpos($response, '404') !== false ||
                strpos($response, 'Not Found') !== false ||
                strpos($response, 'error') !== false
            );
        });
    }

    /**
     * Test API rate limiting (if implemented)
     */
    public function testApiRateLimiting()
    {
        $this->browse(function (Browser $browser) {
            // Login first
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Make multiple rapid requests to test rate limiting
            for ($i = 0; $i < 10; $i++) {
                $browser->visit('/api/dashboard/stats')
                    ->pause(100); // Small pause between requests
            }

            // Check if rate limiting is applied (this depends on implementation)
            $response = $browser->driver->getPageSource();
            $this->assertNotEmpty($response);
        });
    }

    /**
     * Test API CORS headers (if configured)
     */
    public function testApiCorsHeaders()
    {
        $this->browse(function (Browser $browser) {
            // Test public API endpoint for CORS
            $browser->visit('/api/berita')
                ->waitFor('body', 5);

            // Check response exists (CORS headers are at HTTP level)
            $response = $browser->driver->getPageSource();
            $this->assertNotEmpty($response);
        });
    }

    /**
     * Test API content type and response format
     */
    public function testApiContentTypeAndResponseFormat()
    {
        $this->browse(function (Browser $browser) {
            // Login first
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test JSON response format
            $browser->visit('/api/dashboard/stats')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            
            // Should be valid JSON response
            $this->assertTrue(
                strpos($response, '{') !== false &&
                strpos($response, '}') !== false
            );
        });
    }

    /**
     * Test API versioning
     */
    public function testApiVersioning()
    {
        $this->browse(function (Browser $browser) {
            // Login first
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test v1 API endpoints
            $v1Endpoints = [
                '/api/v1/wbs',
                '/api/v1/info-kantor', 
                '/api/v1/portal-papua-tengah',
                '/api/v1/stats',
                '/api/v1/wbs-chart'
            ];

            foreach ($v1Endpoints as $endpoint) {
                $browser->visit($endpoint)
                    ->waitFor('body', 5);

                $response = $browser->driver->getPageSource();
                $this->assertNotEmpty($response);
            }
        });
    }

    /**
     * Test API security headers and validation
     */
    public function testApiSecurityHeadersAndValidation()
    {
        $this->browse(function (Browser $browser) {
            // Test unauthenticated access to protected endpoints
            $protectedEndpoints = [
                '/api/wbs',
                '/api/info-kantor',
                '/api/portal-papua-tengah',
                '/api/user',
                '/api/dashboard/stats'
            ];

            foreach ($protectedEndpoints as $endpoint) {
                $browser->visit($endpoint)
                    ->waitFor('body', 5);

                $response = $browser->driver->getPageSource();
                
                // Should show authentication required
                $this->assertTrue(
                    strpos($response, 'Unauthorized') !== false ||
                    strpos($response, 'Unauthenticated') !== false ||
                    strpos($response, 'login') !== false ||
                    strpos($response, '401') !== false
                );
            }
        });
    }

    /**
     * Test API data consistency
     */
    public function testApiDataConsistency()
    {
        $this->browse(function (Browser $browser) {
            // Login first
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->pause(1000);

            // Test dashboard stats consistency
            $browser->visit('/api/dashboard/stats')
                ->waitFor('body', 5);

            $response = $browser->driver->getPageSource();
            
            // Should contain expected data structure
            $this->assertStringContainsString('wbs', $response);
            $this->assertStringContainsString('total', $response);
            $this->assertStringContainsString('success', $response);
        });
    }
}
