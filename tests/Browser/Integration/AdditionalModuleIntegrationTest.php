<?php

namespace Tests\Browser\Integration;

use App\Models\User;
use App\Models\InfoKantor;
use App\Models\WebPortal;
use App\Models\ContentApproval;
use App\Models\SystemConfiguration;
use App\Models\AuditLog;
use App\Models\Pengaduan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Additional Module Integration Test
 * Tests integration between InfoKantor, WebPortal, ContentApproval, SystemConfiguration, and AuditLog modules
 */
class AdditionalModuleIntegrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;
    protected $admin;
    protected $contentManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'superadmin@inspektorat.id'
        ]);
        
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@inspektorat.id'
        ]);
        
        $this->contentManager = User::factory()->create([
            'role' => 'content_manager',
            'email' => 'content@inspektorat.id'
        ]);
        
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create system configurations
        SystemConfiguration::create([
            'key' => 'app_name',
            'value' => 'Portal Inspektorat Integration Test',
            'type' => 'text',
            'group' => 'general',
            'description' => 'Application name for integration test',
            'is_public' => true
        ]);

        // Create info kantor data
        InfoKantor::create([
            'judul' => 'Info Integration Test',
            'konten' => 'Content for integration testing',
            'kategori' => 'pengumuman',
            'status' => true,
            'created_by' => $this->admin->id
        ]);

        // Create web portal data
        WebPortal::create([
            'nama_portal' => 'Integration Test Portal',
            'deskripsi' => 'Portal for integration testing',
            'url' => 'https://test.inspektorat.id',
            'kategori' => 'portal',
            'status' => 'active',
            'created_by' => $this->admin->id
        ]);
    }

    /**
     * Test complete content approval workflow integration
     */
    public function testContentApprovalWorkflowIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Content Manager creates Info Kantor content
            $browser->loginAs($this->contentManager)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Content for Approval Workflow')
                ->type('konten', 'This content needs approval before publishing')
                ->select('kategori', 'pengumuman')
                ->press('Submit untuk Approval')
                ->pause(2000)
                ->assertSee('Konten berhasil disubmit untuk approval')
                ->screenshot('content_approval_workflow_step1');

            // Step 2: Admin reviews in approval system
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->assertSee('Content for Approval Workflow')
                ->click('a[href*="approvals"]')
                ->type('notes', 'Content reviewed and approved for publication')
                ->press('Setujui')
                ->pause(2000)
                ->assertSee('Konten berhasil disetujui')
                ->screenshot('content_approval_workflow_step2');

            // Step 3: Verify audit log was created
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('approved')
                ->assertSee('ContentApproval')
                ->screenshot('content_approval_workflow_step3');

            // Step 4: Verify content is now public
            $browser->visit('/info-kantor')
                ->assertSee('Content for Approval Workflow')
                ->screenshot('content_approval_workflow_step4');
        });
    }

    /**
     * Test system configuration impact on public interface
     */
    public function testSystemConfigurationPublicIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Super Admin updates app name
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->click("button[data-key='app_name']")
                ->whenAvailable('.modal', function ($modal) {
                    $modal->clear('value')
                        ->type('value', 'Updated Portal Name')
                        ->press('Update');
                })
                ->pause(2000)
                ->assertSee('Konfigurasi berhasil diperbarui')
                ->screenshot('system_config_integration_step1');

            // Step 2: Verify change appears on public site
            $browser->visit('/')
                ->assertSee('Updated Portal Name')
                ->screenshot('system_config_integration_step2');

            // Step 3: Verify audit log of configuration change
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('SystemConfiguration')
                ->assertSee('updated')
                ->screenshot('system_config_integration_step3');
        });
    }

    /**
     * Test cross-module data flow and dependencies
     */
    public function testCrossModuleDataFlow()
    {
        $this->browse(function (Browser $browser) {
            // Create InfoKantor content
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Cross Module Test Content')
                ->type('konten', 'Content for testing cross-module functionality')
                ->select('kategori', 'informasi')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Info Kantor berhasil ditambahkan')
                ->screenshot('cross_module_step1');

            // Verify audit log creation
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('InfoKantor')
                ->assertSee('created')
                ->screenshot('cross_module_step2');

            // View on public interface
            $browser->visit('/info-kantor')
                ->assertSee('Cross Module Test Content')
                ->click('a[href*="info-kantor"]')
                ->assertSee('Content for testing cross-module functionality')
                ->screenshot('cross_module_step3');

            // Verify view count tracking
            $infoKantor = InfoKantor::where('judul', 'Cross Module Test Content')->first();
            $this->assertTrue($infoKantor->view_count > 0);
        });
    }

    /**
     * Test user role permissions across modules
     */
    public function testUserRolePermissionsIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test Content Manager access
            $browser->loginAs($this->contentManager)
                ->visit('/admin/info-kantor')
                ->assertSee('Info Kantor') // Should have access
                ->visit('/admin/configurations')
                ->assertSee('403') // Should not have access
                ->screenshot('role_permissions_content_manager');

            // Test Admin access
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->assertSee('Info Kantor') // Should have access
                ->visit('/admin/audit-logs')
                ->assertSee('403') // Should not have access
                ->screenshot('role_permissions_admin');

            // Test Super Admin access
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->assertSee('Konfigurasi Sistem') // Should have access
                ->visit('/admin/audit-logs')
                ->assertSee('Log Audit') // Should have access
                ->screenshot('role_permissions_super_admin');
        });
    }

    /**
     * Test module performance under load
     */
    public function testModulePerformanceIntegration()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            // Load multiple modules in sequence
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->pause(500)
                ->visit('/admin/web-portal')
                ->pause(500)
                ->visit('/admin/pengaduan')
                ->pause(500)
                ->visit('/admin/approvals')
                ->pause(500);
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Assert reasonable load time (less than 10 seconds)
            $this->assertLessThan(10, $loadTime);
            
            $browser->screenshot('module_performance_integration');
        });
    }

    /**
     * Test search functionality across modules
     */
    public function testSearchIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Search in InfoKantor
            $browser->visit('/info-kantor')
                ->type('search', 'Integration')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Info Integration Test')
                ->screenshot('search_integration_info_kantor');

            // Search in WebPortal
            $browser->visit('/web-portal')
                ->type('search', 'Integration')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Integration Test Portal')
                ->screenshot('search_integration_web_portal');
        });
    }

    /**
     * Test notification system integration
     */
    public function testNotificationSystemIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Create content that requires approval
            $browser->loginAs($this->contentManager)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Content for Notification Test')
                ->type('konten', 'This content will trigger notifications')
                ->select('kategori', 'pengumuman')
                ->press('Submit untuk Approval')
                ->pause(2000)
                ->screenshot('notification_integration_step1');

            // Check admin receives notification
            $browser->loginAs($this->admin)
                ->visit('/admin/dashboard')
                ->assertSee('approval') // Notification indicator
                ->visit('/admin/approvals')
                ->assertSee('Content for Notification Test')
                ->screenshot('notification_integration_step2');
        });
    }

    /**
     * Test backup and recovery workflow
     */
    public function testBackupRecoveryIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Create test data
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Data for Backup Test')
                ->type('konten', 'This data will be used for backup testing')
                ->select('kategori', 'informasi')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->screenshot('backup_integration_step1');

            // Export system configuration (simulating backup)
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->press('Export Konfigurasi')
                ->pause(2000)
                ->screenshot('backup_integration_step2');

            // Verify audit log of backup action
            $browser->visit('/admin/audit-logs')
                ->assertSee('export')
                ->screenshot('backup_integration_step3');
        });
    }

    /**
     * Test complete admin workflow integration
     */
    public function testCompleteAdminWorkflowIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: System configuration
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->assertSee('Konfigurasi Sistem')
                ->screenshot('complete_workflow_step1');

            // Step 2: Content creation
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Complete Workflow Test')
                ->type('konten', 'Testing complete admin workflow')
                ->select('kategori', 'pengumuman')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->screenshot('complete_workflow_step2');

            // Step 3: Web portal management
            $browser->visit('/admin/web-portal/create')
                ->type('nama_portal', 'Workflow Test Portal')
                ->type('deskripsi', 'Portal for workflow testing')
                ->type('url', 'https://workflow.test.id')
                ->select('kategori', 'portal')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->screenshot('complete_workflow_step3');

            // Step 4: Verify audit logs
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('InfoKantor')
                ->assertSee('WebPortal')
                ->assertSee('created')
                ->screenshot('complete_workflow_step4');

            // Step 5: Verify public access
            $browser->visit('/info-kantor')
                ->assertSee('Complete Workflow Test')
                ->visit('/web-portal')
                ->assertSee('Workflow Test Portal')
                ->screenshot('complete_workflow_step5');
        });
    }
}
