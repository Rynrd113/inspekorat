<?php

namespace Tests\Browser\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Security and Performance Integration Test
 * Tests for security measures and performance optimization in Portal Inspektorat Papua Tengah
 */
class SecurityAndPerformanceTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
        User::create([
            'name' => 'SuperAdmin User',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'superadmin',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test@inspektorat.id',
            'password' => bcrypt('test123'),
            'role' => 'admin',
            'is_active' => true
        ]);
    }

    /**
     * Test SQL injection protection
     */
    public function testSqlInjectionProtection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', "admin@inspektorat.id' OR '1'='1")
                ->type('password', "' OR '1'='1")
                ->press('Login')
                ->waitFor('.error-message', 10)
                ->assertSee('Invalid credentials')
                ->screenshot('security-sql-injection-protection');
        });
    }

    /**
     * Test XSS protection
     */
    public function testXssProtection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test XSS in search forms
            $browser->visit('/admin/portal-opd')
                ->waitFor('input[name="search"]', 10)
                ->type('search', '<script>alert("xss")</script>')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertDontSee('<script>')
                ->screenshot('security-xss-protection');
        });
    }

    /**
     * Test CSRF protection
     */
    public function testCsrfProtection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Verify CSRF token exists in forms
            $browser->visit('/admin/portal-opd/create')
                ->waitFor('form', 10)
                ->assertSourceHas('csrf_token')
                ->screenshot('security-csrf-token-present');
        });
    }

    /**
     * Test session security
     */
    public function testSessionSecurity()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test session timeout
            $browser->visit('/admin/dashboard')
                ->waitFor('.dashboard-content', 10)
                ->screenshot('security-session-valid');

            // Verify secure session cookies
            $cookies = $browser->driver->manage()->getCookies();
            $sessionCookie = collect($cookies)->firstWhere('name', 'laravel_session');
            $this->assertNotNull($sessionCookie);
            $this->assertTrue($sessionCookie['httpOnly'] ?? false);
            $this->assertTrue($sessionCookie['secure'] ?? false);
        });
    }

    /**
     * Test file upload security
     */
    public function testFileUploadSecurity()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test malicious file upload prevention
            $browser->visit('/admin/dokumen/create')
                ->waitFor('input[type="file"]', 10)
                ->attach('file', __DIR__ . '/../../fixtures/malicious.php')
                ->press('Save')
                ->waitFor('.error-message', 10)
                ->assertSee('Invalid file type')
                ->screenshot('security-file-upload-blocked');
        });
    }

    /**
     * Test page load performance
     */
    public function testPageLoadPerformance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->visit('/')
                ->waitFor('.homepage-content', 10);

            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;

            // Page should load within 3 seconds
            $this->assertLessThan(3, $loadTime);
            $browser->screenshot('performance-homepage-load');
        });
    }

    /**
     * Test admin dashboard performance
     */
    public function testAdminDashboardPerformance()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            $startTime = microtime(true);

            $browser->visit('/admin/dashboard')
                ->waitFor('.dashboard-content', 10);

            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;

            // Dashboard should load within 2 seconds
            $this->assertLessThan(2, $loadTime);
            $browser->screenshot('performance-dashboard-load');
        });
    }

    /**
     * Test database query performance
     */
    public function testDatabaseQueryPerformance()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            $startTime = microtime(true);

            // Test data-heavy page
            $browser->visit('/admin/portal-opd')
                ->waitFor('.data-table', 10);

            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;

            // Data table should load within 1.5 seconds
            $this->assertLessThan(1.5, $loadTime);
            $browser->screenshot('performance-data-table-load');
        });
    }

    /**
     * Test form validation security
     */
    public function testFormValidationSecurity()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test server-side validation
            $browser->visit('/admin/portal-opd/create')
                ->waitFor('form', 10)
                ->type('nama', str_repeat('a', 1000)) // Long input
                ->press('Save')
                ->waitFor('.error-message', 10)
                ->assertSee('validation error')
                ->screenshot('security-form-validation');
        });
    }

    /**
     * Test brute force protection
     */
    public function testBruteForceProtection()
    {
        $this->browse(function (Browser $browser) {
            // Attempt multiple failed logins
            for ($i = 0; $i < 5; $i++) {
                $browser->visit('/login')
                    ->waitFor('input[name="email"]', 10)
                    ->type('email', 'admin@inspektorat.id')
                    ->type('password', 'wrongpassword')
                    ->press('Login')
                    ->waitFor('.error-message', 10);
            }

            // Should be rate limited
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin@inspektorat.id')
                ->type('password', 'wrongpassword')
                ->press('Login')
                ->waitFor('.error-message', 10)
                ->assertSee('Too many attempts')
                ->screenshot('security-brute-force-protection');
        });
    }

    /**
     * Test HTTP security headers
     */
    public function testHttpSecurityHeaders()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            
            // Check for security headers
            $response = $browser->driver->getPageSource();
            $headers = $browser->driver->manage()->getCookies();
            
            // Verify security headers are present
            $this->assertNotEmpty($headers);
            $browser->screenshot('security-headers-check');
        });
    }

    /**
     * Test API endpoint security
     */
    public function testApiEndpointSecurity()
    {
        $this->browse(function (Browser $browser) {
            // Test unauthenticated API access
            $browser->visit('/api/admin/users')
                ->waitFor('.error-message', 10)
                ->assertSee('Unauthorized')
                ->screenshot('security-api-unauthorized');

            // Test authenticated API access
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Should have API access when authenticated
            $browser->visit('/api/admin/dashboard-stats')
                ->waitFor('.api-response', 10)
                ->screenshot('security-api-authenticated');
        });
    }

    /**
     * Test password security requirements
     */
    public function testPasswordSecurityRequirements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test weak password rejection
            $browser->visit('/admin/users/create')
                ->waitFor('form', 10)
                ->type('name', 'Test User')
                ->type('email', 'newuser@inspektorat.id')
                ->type('password', '123') // Weak password
                ->type('password_confirmation', '123')
                ->press('Save')
                ->waitFor('.error-message', 10)
                ->assertSee('Password must be at least 8 characters')
                ->screenshot('security-password-requirements');
        });
    }

    /**
     * Test data encryption
     */
    public function testDataEncryption()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test sensitive data is encrypted
            $browser->visit('/admin/wbs')
                ->waitFor('.data-table', 10)
                ->assertDontSee('raw sensitive data')
                ->screenshot('security-data-encryption');
        });
    }

    /**
     * Test comprehensive security scan
     */
    public function testComprehensiveSecurityScan()
    {
        $this->browse(function (Browser $browser) {
            $securityTests = [
                '/' => 'Homepage Security',
                '/login' => 'Login Security',
                '/profil' => 'Profile Security',
                '/pelayanan' => 'Services Security',
                '/wbs' => 'WBS Security'
            ];

            foreach ($securityTests as $route => $testName) {
                $browser->visit($route)
                    ->waitFor('body', 10)
                    ->assertDontSee('error')
                    ->assertDontSee('exception')
                    ->assertDontSee('stack trace')
                    ->screenshot('security-scan-' . str_replace('/', '', $route ?: 'home'));
            }
        });
    }
}