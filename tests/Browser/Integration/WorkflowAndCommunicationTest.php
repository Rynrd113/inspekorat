<?php

namespace Tests\Browser\Integration;

use App\Models\User;
use App\Models\PortalOpd;
use App\Models\Pelayanan;
use App\Models\Wbs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Workflow and Communication Integration Test
 * Tests for workflow processes and communication features in Portal Inspektorat Papua Tengah
 */
class WorkflowAndCommunicationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create test users
        User::create([
            'name' => 'SuperAdmin User',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'superadmin',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Admin WBS',
            'email' => 'admin.wbs@inspektorat.id',
            'password' => bcrypt('adminwbs123'),
            'role' => 'admin_wbs',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Admin Pelayanan',
            'email' => 'admin.pelayanan@inspektorat.id',
            'password' => bcrypt('adminpelayanan123'),
            'role' => 'admin_pelayanan',
            'is_active' => true
        ]);

        // Create test data
        PortalOpd::create([
            'nama' => 'Test OPD',
            'alamat' => 'Jl. Test No. 123',
            'telepon' => '081234567890',
            'email' => 'test@opd.id',
            'website' => 'https://test.opd.id',
            'kepala' => 'Kepala Test',
            'status' => 'active'
        ]);

        Pelayanan::create([
            'nama_layanan' => 'Test Service',
            'deskripsi' => 'Test service description',
            'persyaratan' => 'Test requirements',
            'biaya' => 'Gratis',
            'waktu_proses' => '3 hari kerja',
            'kategori' => 'pelayanan',
            'status' => 'active'
        ]);
    }

    /**
     * Test complete WBS workflow from submission to resolution
     */
    public function testCompleteWbsWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Public user submits WBS report
            $browser->visit('/wbs')
                ->waitFor('form', 10)
                ->type('judul', 'Test WBS Report')
                ->type('isi', 'This is a test WBS report content')
                ->select('kategori', 'pelayanan')
                ->type('nama_pelapor', 'John Doe')
                ->type('email_pelapor', 'john@example.com')
                ->type('telepon_pelapor', '081234567890')
                ->press('Submit')
                ->waitForText('Report submitted successfully', 10)
                ->screenshot('workflow-wbs-submission');

            // Step 2: Admin WBS receives and reviews report
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.wbs@inspektorat.id')
                ->type('password', 'adminwbs123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            $browser->visit('/admin/wbs')
                ->waitFor('.data-table', 10)
                ->assertSee('Test WBS Report')
                ->click('.view-report-btn')
                ->waitFor('.report-detail', 10)
                ->screenshot('workflow-wbs-admin-review');

            // Step 3: Admin updates report status
            $browser->select('status', 'in_progress')
                ->type('admin_notes', 'Report is being investigated')
                ->press('Update Status')
                ->waitForText('Status updated successfully', 10)
                ->screenshot('workflow-wbs-status-update');

            // Step 4: Admin assigns report to investigator
            $browser->select('assigned_to', 'investigator@inspektorat.id')
                ->press('Assign')
                ->waitForText('Report assigned successfully', 10)
                ->screenshot('workflow-wbs-assignment');

            // Step 5: Complete investigation and close report
            $browser->select('status', 'resolved')
                ->type('resolution_notes', 'Investigation completed, issue resolved')
                ->press('Close Report')
                ->waitForText('Report closed successfully', 10)
                ->screenshot('workflow-wbs-resolution');
        });
    }

    /**
     * Test service request workflow
     */
    public function testServiceRequestWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Public user browses services
            $browser->visit('/pelayanan')
                ->waitFor('.service-cards', 10)
                ->assertSee('Test Service')
                ->click('.service-detail-btn')
                ->waitFor('.service-detail', 10)
                ->screenshot('workflow-service-browse');

            // Step 2: User initiates service request
            $browser->press('Request Service')
                ->waitFor('.service-request-form', 10)
                ->type('applicant_name', 'Jane Doe')
                ->type('applicant_email', 'jane@example.com')
                ->type('applicant_phone', '081234567890')
                ->type('request_details', 'I need this service for my business')
                ->press('Submit Request')
                ->waitForText('Service request submitted', 10)
                ->screenshot('workflow-service-request');

            // Step 3: Admin reviews service request
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.pelayanan@inspektorat.id')
                ->type('password', 'adminpelayanan123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            $browser->visit('/admin/pelayanan/requests')
                ->waitFor('.request-table', 10)
                ->assertSee('Jane Doe')
                ->click('.review-request-btn')
                ->waitFor('.request-detail', 10)
                ->screenshot('workflow-service-admin-review');

            // Step 4: Admin approves service request
            $browser->select('status', 'approved')
                ->type('admin_notes', 'Request approved, processing started')
                ->press('Update Status')
                ->waitForText('Status updated successfully', 10)
                ->screenshot('workflow-service-approval');
        });
    }

    /**
     * Test communication between modules
     */
    public function testInterModuleCommunication()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test Portal OPD to Services integration
            $browser->visit('/admin/portal-opd')
                ->waitFor('.data-table', 10)
                ->click('.view-services-btn')
                ->waitFor('.opd-services', 10)
                ->assertSee('Test Service')
                ->screenshot('workflow-opd-services-integration');

            // Test Services to FAQ integration
            $browser->visit('/admin/pelayanan')
                ->waitFor('.data-table', 10)
                ->click('.view-faqs-btn')
                ->waitFor('.related-faqs', 10)
                ->screenshot('workflow-services-faq-integration');

            // Test WBS to User Management integration
            $browser->visit('/admin/wbs')
                ->waitFor('.data-table', 10)
                ->click('.assign-investigator-btn')
                ->waitFor('.user-selection', 10)
                ->assertSee('investigator@inspektorat.id')
                ->screenshot('workflow-wbs-user-integration');
        });
    }

    /**
     * Test notification workflow
     */
    public function testNotificationWorkflow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test new WBS report notification
            $browser->visit('/admin/dashboard')
                ->waitFor('.notifications', 10)
                ->assertSee('New WBS Report')
                ->click('.notification-item')
                ->waitFor('.notification-detail', 10)
                ->screenshot('workflow-notification-wbs');

            // Test service request notification
            $browser->visit('/admin/dashboard')
                ->waitFor('.notifications', 10)
                ->assertSee('New Service Request')
                ->click('.notification-item')
                ->waitFor('.notification-detail', 10)
                ->screenshot('workflow-notification-service');

            // Mark notification as read
            $browser->click('.mark-read-btn')
                ->waitForText('Notification marked as read', 10)
                ->screenshot('workflow-notification-read');
        });
    }

    /**
     * Test approval workflow
     */
    public function testApprovalWorkflow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test content approval workflow
            $browser->visit('/admin/portal-papua-tengah/create')
                ->waitFor('form', 10)
                ->type('judul', 'Test News Article')
                ->type('isi', 'This is a test news article content')
                ->select('kategori', 'news')
                ->select('status', 'draft')
                ->press('Save')
                ->waitForText('Article saved as draft', 10)
                ->screenshot('workflow-content-draft');

            // Submit for approval
            $browser->select('status', 'pending_approval')
                ->press('Submit for Approval')
                ->waitForText('Article submitted for approval', 10)
                ->screenshot('workflow-content-pending');

            // Approve content
            $browser->select('status', 'published')
                ->type('approval_notes', 'Content approved and published')
                ->press('Approve')
                ->waitForText('Article approved and published', 10)
                ->screenshot('workflow-content-approved');
        });
    }

    /**
     * Test multi-step form workflow
     */
    public function testMultiStepFormWorkflow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test multi-step Portal OPD creation
            $browser->visit('/admin/portal-opd/create')
                ->waitFor('.step-1', 10)
                ->type('nama', 'Multi-Step OPD')
                ->type('alamat', 'Jl. Multi Step No. 123')
                ->press('Next')
                ->waitFor('.step-2', 10)
                ->screenshot('workflow-multistep-step2');

            $browser->type('telepon', '081234567890')
                ->type('email', 'multistep@opd.id')
                ->press('Next')
                ->waitFor('.step-3', 10)
                ->screenshot('workflow-multistep-step3');

            $browser->type('website', 'https://multistep.opd.id')
                ->type('kepala', 'Kepala Multi-Step')
                ->press('Save')
                ->waitForText('OPD created successfully', 10)
                ->screenshot('workflow-multistep-complete');
        });
    }

    /**
     * Test data synchronization workflow
     */
    public function testDataSynchronizationWorkflow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test data sync between modules
            $browser->visit('/admin/portal-opd')
                ->waitFor('.data-table', 10)
                ->click('.sync-data-btn')
                ->waitFor('.sync-progress', 10)
                ->waitForText('Data synchronization completed', 30)
                ->screenshot('workflow-data-sync');

            // Verify sync results
            $browser->visit('/admin/pelayanan')
                ->waitFor('.data-table', 10)
                ->assertSee('synchronized data')
                ->screenshot('workflow-sync-verification');
        });
    }

    /**
     * Test escalation workflow
     */
    public function testEscalationWorkflow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.wbs@inspektorat.id')
                ->type('password', 'adminwbs123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test automatic escalation
            $browser->visit('/admin/wbs')
                ->waitFor('.data-table', 10)
                ->click('.escalate-btn')
                ->waitFor('.escalation-form', 10)
                ->select('escalate_to', 'supervisor@inspektorat.id')
                ->type('escalation_reason', 'Requires supervisor attention')
                ->press('Escalate')
                ->waitForText('Case escalated successfully', 10)
                ->screenshot('workflow-escalation');

            // Verify escalation notification
            $browser->visit('/admin/dashboard')
                ->waitFor('.notifications', 10)
                ->assertSee('Case Escalated')
                ->screenshot('workflow-escalation-notification');
        });
    }

    /**
     * Test reporting workflow
     */
    public function testReportingWorkflow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test report generation
            $browser->visit('/admin/reports')
                ->waitFor('.report-options', 10)
                ->select('report_type', 'wbs_monthly')
                ->select('period', 'last_month')
                ->press('Generate Report')
                ->waitFor('.report-preview', 10)
                ->screenshot('workflow-report-generation');

            // Test report export
            $browser->click('.export-pdf-btn')
                ->waitFor('.export-progress', 10)
                ->waitForText('Report exported successfully', 30)
                ->screenshot('workflow-report-export');

            // Test report scheduling
            $browser->visit('/admin/reports/schedule')
                ->waitFor('.schedule-form', 10)
                ->select('report_type', 'wbs_monthly')
                ->select('frequency', 'monthly')
                ->select('recipients', 'supervisor@inspektorat.id')
                ->press('Schedule Report')
                ->waitForText('Report scheduled successfully', 10)
                ->screenshot('workflow-report-schedule');
        });
    }
}