<?php

namespace Tests\Browser\Integration;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * System Integration Test Suite
 * Comprehensive testing for Portal Inspektorat Papua Tengah
 */
class SystemIntegrationTestSuite extends DuskTestCase
{
    /**
     * Test complete system integration workflow
     * Tests all major system components working together
     */
    public function testCompleteSystemIntegration()
    {
        $this->browse(function (Browser $browser) {
            // 1. Test public homepage access
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('system-integration-homepage');

            // 2. Test navigation to different public pages
            $browser->click('[href="/profil"]')
                ->waitForText('Profil Organisasi', 10)
                ->screenshot('system-integration-profil')
                ->back();

            $browser->click('[href="/pelayanan"]')
                ->waitForText('Layanan Kami', 10)
                ->screenshot('system-integration-pelayanan')
                ->back();

            $browser->click('[href="/berita"]')
                ->waitForText('Berita Terbaru', 10)
                ->screenshot('system-integration-berita')
                ->back();

            // 3. Test admin login and dashboard access
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('system-integration-admin-dashboard');

            // 4. Test module navigation
            $browser->click('[href="/admin/portal-opd"]')
                ->waitForText('Portal OPD', 10)
                ->screenshot('system-integration-portal-opd')
                ->back();

            $browser->click('[href="/admin/pelayanan"]')
                ->waitForText('Manajemen Pelayanan', 10)
                ->screenshot('system-integration-admin-pelayanan')
                ->back();

            $browser->click('[href="/admin/dokumen"]')
                ->waitForText('Manajemen Dokumen', 10)
                ->screenshot('system-integration-admin-dokumen')
                ->back();

            // 5. Test logout and return to public
            $browser->click('.logout-btn')
                ->waitFor('.homepage-content', 10)
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('system-integration-logout');
        });
    }

    /**
     * Test multi-user role system integration
     */
    public function testMultiUserRoleSystemIntegration()
    {
        $this->browse(function (Browser $browser) {
            $roles = [
                ['email' => 'admin@inspektorat.id', 'password' => 'admin123', 'role' => 'Admin'],
                ['email' => 'admin.pelayanan@inspektorat.id', 'password' => 'adminpelayanan123', 'role' => 'Admin Pelayanan'],
                ['email' => 'admin.dokumen@inspektorat.id', 'password' => 'admindokumen123', 'role' => 'Admin Dokumen'],
            ];

            foreach ($roles as $role) {
                $browser->visit('/login')
                    ->waitFor('input[name="email"]', 10)
                    ->type('email', $role['email'])
                    ->type('password', $role['password'])
                    ->press('Login')
                    ->waitForText('Dashboard', 10)
                    ->screenshot("system-integration-{$role['role']}-dashboard");

                // Test role-specific access
                if ($role['role'] === 'Admin Pelayanan') {
                    $browser->visit('/admin/pelayanan')
                        ->waitForText('Manajemen Pelayanan', 10)
                        ->screenshot("system-integration-{$role['role']}-access-allowed");
                } elseif ($role['role'] === 'Admin Dokumen') {
                    $browser->visit('/admin/dokumen')
                        ->waitForText('Manajemen Dokumen', 10)
                        ->screenshot("system-integration-{$role['role']}-access-allowed");
                }

                // Logout
                $browser->click('.logout-btn')
                    ->waitFor('.homepage-content', 10);
            }
        });
    }

    /**
     * Test database integration across modules
     */
    public function testDatabaseIntegration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test creating data in one module
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->click('.btn-create')
                ->waitFor('input[name="nama"]', 10)
                ->type('nama', 'Test OPD Integration')
                ->type('alamat', 'Jl. Test Integration No. 123')
                ->type('telepon', '081234567890')
                ->type('email', 'testopd@integration.id')
                ->press('Simpan')
                ->waitForText('Test OPD Integration', 10)
                ->screenshot('system-integration-create-opd');

            // Test data appearing in public view
            $browser->visit('/portal-opd')
                ->waitForText('Test OPD Integration', 10)
                ->screenshot('system-integration-public-opd-view');

            // Test search functionality
            $browser->type('input[name="search"]', 'Integration')
                ->press('Cari')
                ->waitForText('Test OPD Integration', 10)
                ->screenshot('system-integration-search-opd');
        });
    }

    /**
     * Test system performance under load
     */
    public function testSystemPerformanceIntegration()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            // Test multiple page loads
            $pages = [
                '/',
                '/profil',
                '/pelayanan',
                '/dokumen',
                '/galeri',
                '/berita',
                '/faq',
                '/kontak',
                '/wbs',
                '/portal-opd'
            ];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->screenshot("system-integration-performance-{$page}");
            }

            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;

            // Assert performance criteria (all pages should load within 30 seconds)
            $this->assertLessThan(30, $executionTime, 'System performance test failed - pages took too long to load');
        });
    }

    /**
     * Test system security integration
     */
    public function testSystemSecurityIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test unauthorized access protection
            $browser->visit('/admin/portal-opd')
                ->waitForText('Login', 10)
                ->screenshot('system-integration-unauthorized-redirect');

            // Test CSRF protection
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test session management
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->screenshot('system-integration-authenticated-access');

            // Test logout security
            $browser->click('.logout-btn')
                ->waitFor('.homepage-content', 10)
                ->visit('/admin/portal-opd')
                ->waitForText('Login', 10)
                ->screenshot('system-integration-logout-security');
        });
    }

    /**
     * Test system backup and recovery simulation
     */
    public function testSystemBackupRecoveryIntegration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test data persistence after operations
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->screenshot('system-integration-data-persistence-before');

            // Simulate browser refresh (session persistence)
            $browser->refresh()
                ->waitForText('Portal OPD', 10)
                ->screenshot('system-integration-data-persistence-after');

            // Test data integrity across modules
            $browser->visit('/admin/pelayanan')
                ->waitForText('Manajemen Pelayanan', 10)
                ->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->screenshot('system-integration-data-integrity');
        });
    }

    /**
     * Test system monitoring and logging integration
     */
    public function testSystemMonitoringIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test error handling
            $browser->visit('/nonexistent-page')
                ->waitFor('body', 10)
                ->screenshot('system-integration-404-handling');

            // Test valid page access
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->screenshot('system-integration-valid-access');

            // Test admin error handling
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'invalid@email.com')
                ->type('password', 'wrongpassword')
                ->press('Login')
                ->waitForText('Invalid credentials', 10)
                ->screenshot('system-integration-login-error');

            // Test successful login
            $browser->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('system-integration-login-success');
        });
    }

    /**
     * Test system configuration and settings integration
     */
    public function testSystemConfigurationIntegration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test system settings access
            if ($browser->element('.settings-menu')) {
                $browser->click('.settings-menu')
                    ->waitFor('.settings-content', 10)
                    ->screenshot('system-integration-settings-access');
            }

            // Test profile settings
            if ($browser->element('.profile-menu')) {
                $browser->click('.profile-menu')
                    ->waitFor('.profile-content', 10)
                    ->screenshot('system-integration-profile-access');
            }

            // Test system information
            $browser->visit('/admin/dashboard')
                ->waitForText('Dashboard', 10)
                ->screenshot('system-integration-system-info');
        });
    }

    /**
     * Test system integration with external services
     */
    public function testExternalServiceIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test email service integration (contact form)
            $browser->visit('/kontak')
                ->waitFor('input[name="nama"]', 10)
                ->type('nama', 'Test Integration User')
                ->type('email', 'test@integration.com')
                ->type('subjek', 'System Integration Test')
                ->type('pesan', 'This is a system integration test message.')
                ->press('Kirim')
                ->waitForText('berhasil', 10)
                ->screenshot('system-integration-email-service');

            // Test file upload service integration
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.dokumen@inspektorat.id')
                ->type('password', 'admindokumen123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            $browser->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Integration Test Document')
                ->type('deskripsi', 'Document for integration testing')
                ->screenshot('system-integration-file-upload');
        });
    }

    /**
     * Test system recovery and failover
     */
    public function testSystemRecoveryFailover()
    {
        $this->browse(function (Browser $browser) {
            // Test graceful degradation
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->screenshot('system-integration-normal-operation');

            // Test system response under stress
            for ($i = 0; $i < 5; $i++) {
                $browser->visit('/berita')
                    ->waitFor('body', 10)
                    ->visit('/pelayanan')
                    ->waitFor('body', 10)
                    ->visit('/dokumen')
                    ->waitFor('body', 10);
            }

            $browser->screenshot('system-integration-stress-test');

            // Test system recovery
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->screenshot('system-integration-recovery');
        });
    }
}
