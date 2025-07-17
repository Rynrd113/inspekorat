<?php

namespace Tests\Browser\Integration;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithForms;
use Tests\Browser\Traits\InteractsWithFiles;
use Tests\DuskTestCase;

class IntegrationTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithForms, InteractsWithFiles;

    /**
     * Test user creation and role assignment integration
     */
    public function test_user_creation_and_role_assignment_integration()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create new user
            $userData = [
                'name' => 'Integration Test User',
                'email' => 'integration@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'content_manager',
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
            
            // Logout and login as new user
            $browser->logout()
                    ->visit('/login')
                    ->type('email', $userData['email'])
                    ->type('password', 'password123')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard', 30)
                    ->assertSee('Dashboard');
            
            // Test role-based access
            $browser->visit('/admin/berita')
                    ->assertSee('Berita')
                    ->assertVisible('a[href*="create"]');
            
            // Should not have access to user management
            $browser->visit('/admin/users')
                    ->assertSee('403');
        });
    }

    /**
     * Test berita creation with document attachment integration
     */
    public function test_berita_creation_with_document_attachment()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            // Create document first
            $documentFile = $this->createTestFile('attachment.pdf', 'document-content', 'application/pdf');
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm([
                        'title' => 'Integration Test Document',
                        'description' => 'Document for integration testing',
                        'category' => 'peraturan',
                        'tags' => 'integration, test',
                        'is_public' => 1,
                    ])
                    ->attach('input[name="file"]', $documentFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee('Integration Test Document');
            
            // Create berita with document attachment
            $imageFile = $this->createTestFile('berita.jpg', 'image-content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Integration Test Berita',
                        'konten' => 'Berita dengan lampiran dokumen',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $imageFile)
                    ->select('select[name="attached_documents[]"]', 'Integration Test Document')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Integration Test Berita');
            
            // Verify document attachment
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/berita/*/edit', 30)
                    ->assertSelected('select[name="attached_documents[]"]', 'Integration Test Document');
        });
    }

    /**
     * Test WBS submission with file upload and notification integration
     */
    public function test_wbs_submission_with_file_upload_and_notification()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $evidenceFile = $this->createTestFile('evidence.pdf', 'evidence-content', 'application/pdf');
            
            // Submit WBS report
            $wbsData = [
                'nama_pelapor' => 'Integration Test Reporter',
                'email_pelapor' => 'reporter@test.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'Integration Test WBS Report',
                'deskripsi' => 'WBS report for integration testing',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->attach('input[name="lampiran"]', $evidenceFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee('Integration Test WBS Report');
            
            // Check notification was sent
            $browser->visit('/admin/notifications')
                    ->assertSee('New WBS Report Submitted')
                    ->assertSee('Integration Test WBS Report');
            
            // Update WBS status
            $browser->visit('/admin/wbs')
                    ->click('a[href*="edit"]')
                    ->waitForLocation('/admin/wbs/*/edit', 30)
                    ->select('select[name="status"]', 'in_progress')
                    ->type('textarea[name="catatan"]', 'Sedang dalam proses investigasi')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee('In Progress');
            
            // Verify status change notification
            $browser->visit('/admin/notifications')
                    ->assertSee('WBS Report Status Updated')
                    ->assertSee('in_progress');
        });
    }

    /**
     * Test user activity logging across modules
     */
    public function test_user_activity_logging_across_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Perform various activities
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Activity Log Test',
                        'konten' => 'Test content',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            $browser->visit('/admin/wbs')
                    ->type('input[name="search"]', 'test search')
                    ->press('button[type="submit"]')
                    ->waitForReload();
            
            $browser->visit('/admin/documents')
                    ->select('select[name="category"]', 'peraturan')
                    ->press('Filter')
                    ->waitForReload();
            
            // Check activity log
            $browser->visit('/admin/activity-log')
                    ->assertSee('Created berita: Activity Log Test')
                    ->assertSee('Searched WBS: test search')
                    ->assertSee('Filtered documents: peraturan');
        });
    }

    /**
     * Test search functionality across multiple modules
     */
    public function test_search_functionality_across_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $searchTerm = 'integration search test';
            
            // Create test data in different modules
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => "Berita $searchTerm",
                        'konten' => 'Test content',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            $documentFile = $this->createTestFile('search.pdf', 'content', 'application/pdf');
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm([
                        'title' => "Document $searchTerm",
                        'description' => 'Test description',
                        'category' => 'peraturan',
                        'tags' => 'integration, test',
                        'is_public' => 1,
                    ])
                    ->attach('input[name="file"]', $documentFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30);
            
            // Test global search
            $browser->visit('/admin/search')
                    ->type('input[name="global_search"]', $searchTerm)
                    ->press('Search')
                    ->waitForReload()
                    ->assertSee("Berita $searchTerm")
                    ->assertSee("Document $searchTerm");
            
            // Test module-specific search
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', $searchTerm)
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee("Berita $searchTerm");
            
            $browser->visit('/admin/documents')
                    ->type('input[name="search"]', $searchTerm)
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee("Document $searchTerm");
        });
    }

    /**
     * Test file sharing between modules
     */
    public function test_file_sharing_between_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $sharedFile = $this->createTestFile('shared.jpg', 'shared-content', 'image/jpeg');
            
            // Upload to media library
            $browser->visit('/admin/media')
                    ->click('a[href*="upload"]')
                    ->waitFor('.upload-modal', 10)
                    ->attach('input[name="file"]', $sharedFile)
                    ->press('Upload')
                    ->waitForReload()
                    ->assertSee('shared.jpg');
            
            // Use in berita
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Shared File Test',
                        'konten' => 'Test with shared file',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->click('button[data-action="select-from-media"]')
                    ->waitFor('.media-modal', 10)
                    ->click('img[alt="shared.jpg"]')
                    ->press('Select')
                    ->waitUntilMissing('.media-modal', 10)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Shared File Test');
            
            // Use in documents
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm([
                        'title' => 'Shared Document Test',
                        'description' => 'Test description',
                        'category' => 'peraturan',
                        'tags' => 'shared, test',
                        'is_public' => 1,
                    ])
                    ->click('button[data-action="select-from-media"]')
                    ->waitFor('.media-modal', 10)
                    ->click('img[alt="shared.jpg"]')
                    ->press('Select')
                    ->waitUntilMissing('.media-modal', 10)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee('Shared Document Test');
        });
    }

    /**
     * Test workflow integration between modules
     */
    public function test_workflow_integration_between_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            // Create draft berita
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Workflow Test Berita',
                        'konten' => 'Test workflow content',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Workflow Test Berita');
            
            // Submit for review
            $browser->click('button[data-action="submit-for-review"]')
                    ->waitFor('.modal', 10)
                    ->type('textarea[name="review_note"]', 'Please review this berita')
                    ->press('Submit')
                    ->waitForReload()
                    ->assertSee('Pending Review');
            
            // Login as admin for approval
            $browser->logout();
            $this->loginAsAdmin($browser);
            
            // Review and approve
            $browser->visit('/admin/berita')
                    ->click('a[href*="review"]')
                    ->waitForLocation('/admin/berita/*/review', 30)
                    ->assertSee('Workflow Test Berita')
                    ->assertSee('Please review this berita')
                    ->select('select[name="review_status"]', 'approved')
                    ->type('textarea[name="review_comment"]', 'Approved for publication')
                    ->press('Submit Review')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Approved');
            
            // Publish
            $browser->click('button[data-action="publish"]')
                    ->waitFor('.modal', 10)
                    ->press('Publish')
                    ->waitForReload()
                    ->assertSee('Published');
            
            // Verify notification sent to original author
            $browser->visit('/admin/notifications')
                    ->assertSee('Your berita has been approved')
                    ->assertSee('Workflow Test Berita');
        });
    }

    /**
     * Test data export/import integration
     */
    public function test_data_export_import_integration()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Create test data
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Export Test Berita',
                        'konten' => 'Test export content',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            // Export data
            $browser->visit('/admin/berita')
                    ->click('a[href*="export"]')
                    ->waitFor('.export-modal', 10)
                    ->select('select[name="export_format"]', 'csv')
                    ->check('input[name="include_content"]')
                    ->press('Export')
                    ->pause(3000); // Wait for export
            
            // Import data to another module (if applicable)
            $browser->visit('/admin/import')
                    ->select('select[name="import_type"]', 'berita')
                    ->attach('input[name="import_file"]', '/path/to/exported/file.csv')
                    ->press('Import')
                    ->waitForReload()
                    ->assertSee('Import completed successfully');
            
            // Verify imported data
            $browser->visit('/admin/berita')
                    ->assertSee('Export Test Berita');
        });
    }

    /**
     * Test notification system integration
     */
    public function test_notification_system_integration()
    {
        $this->browse(function (Browser $browser1, Browser $browser2) {
            $this->loginAsAdmin($browser1);
            $this->loginAsContentManager($browser2);
            
            // Admin creates announcement
            $browser1->visit('/admin/announcements')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/announcements/create', 30)
                    ->fillForm([
                        'title' => 'System Maintenance',
                        'content' => 'System will be down for maintenance',
                        'target_roles' => 'all',
                        'priority' => 'high',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/announcements', 30);
            
            // Content manager should receive notification
            $browser2->visit('/admin/dashboard')
                    ->waitFor('.notification-badge', 10)
                    ->assertVisible('.notification-badge')
                    ->click('.notification-dropdown')
                    ->waitFor('.notification-list', 5)
                    ->assertSee('System Maintenance')
                    ->assertSee('System will be down for maintenance');
            
            // Mark as read
            $browser2->click('.notification-item:first-child .mark-as-read')
                    ->waitForReload()
                    ->assertMissing('.notification-badge');
            
            // Create WBS report (should notify WBS manager)
            $browser2->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm([
                        'nama_pelapor' => 'Test Reporter',
                        'email_pelapor' => 'reporter@test.com',
                        'telepon_pelapor' => '081234567890',
                        'kategori' => 'korupsi',
                        'judul_laporan' => 'Notification Test WBS',
                        'deskripsi' => 'WBS for notification testing',
                        'lokasi_kejadian' => 'Jakarta',
                        'tanggal_kejadian' => now()->format('Y-m-d'),
                        'status' => 'pending',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30);
            
            // Login as WBS manager to check notification
            $browser2->logout();
            $this->loginAsWbsManager($browser2);
            
            $browser2->visit('/admin/dashboard')
                    ->waitFor('.notification-badge', 10)
                    ->assertVisible('.notification-badge')
                    ->click('.notification-dropdown')
                    ->waitFor('.notification-list', 5)
                    ->assertSee('New WBS Report')
                    ->assertSee('Notification Test WBS');
        });
    }

    /**
     * Test API integration between modules
     */
    public function test_api_integration_between_modules()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Create berita via API
            $browser->visit('/admin/berita')
                    ->script('
                        fetch("/admin/api/berita", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                            },
                            body: JSON.stringify({
                                judul: "API Integration Test",
                                konten: "Test API content",
                                status: "published",
                                kategori: "umum"
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            window.apiResponse = data;
                        });
                    ')
                    ->pause(2000);
            
            $apiResponse = $browser->script('return window.apiResponse')[0];
            $this->assertArrayHasKey('id', $apiResponse);
            $this->assertEquals('API Integration Test', $apiResponse['judul']);
            
            // Fetch berita via API
            $browser->script('
                fetch("/admin/api/berita/" + window.apiResponse.id)
                    .then(response => response.json())
                    .then(data => {
                        window.fetchedBerita = data;
                    });
            ')
            ->pause(2000);
            
            $fetchedBerita = $browser->script('return window.fetchedBerita')[0];
            $this->assertEquals('API Integration Test', $fetchedBerita['judul']);
            
            // Update berita via API
            $browser->script('
                fetch("/admin/api/berita/" + window.apiResponse.id, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                    },
                    body: JSON.stringify({
                        judul: "Updated API Integration Test",
                        konten: "Updated API content",
                        status: "published",
                        kategori: "umum"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    window.updatedBerita = data;
                });
            ')
            ->pause(2000);
            
            $updatedBerita = $browser->script('return window.updatedBerita')[0];
            $this->assertEquals('Updated API Integration Test', $updatedBerita['judul']);
            
            // Verify update in UI
            $browser->visit('/admin/berita')
                    ->assertSee('Updated API Integration Test');
        });
    }

    /**
     * Test backup and restore integration
     */
    public function test_backup_and_restore_integration()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Create test data
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Backup Test Berita',
                        'konten' => 'Test backup content',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            // Create backup
            $browser->visit('/admin/backup')
                    ->click('button[data-action="create-backup"]')
                    ->waitFor('.modal', 10)
                    ->check('input[name="include_berita"]')
                    ->check('input[name="include_documents"]')
                    ->check('input[name="include_wbs"]')
                    ->type('input[name="backup_name"]', 'Integration Test Backup')
                    ->press('Create Backup')
                    ->waitForReload()
                    ->assertSee('Backup created successfully')
                    ->assertSee('Integration Test Backup');
            
            // Simulate data loss
            $browser->visit('/admin/berita')
                    ->click('button[data-action="delete"]')
                    ->waitFor('.modal', 10)
                    ->press('Ya')
                    ->waitForReload()
                    ->assertDontSee('Backup Test Berita');
            
            // Restore from backup
            $browser->visit('/admin/backup')
                    ->click('button[data-action="restore"]')
                    ->waitFor('.modal', 10)
                    ->select('select[name="restore_modules[]"]', 'berita')
                    ->press('Restore')
                    ->waitForReload()
                    ->assertSee('Restore completed successfully');
            
            // Verify restored data
            $browser->visit('/admin/berita')
                    ->assertSee('Backup Test Berita');
        });
    }

    /**
     * Test real-time updates integration
     */
    public function test_real_time_updates_integration()
    {
        $this->browse(function (Browser $browser1, Browser $browser2) {
            $this->loginAsAdmin($browser1);
            $this->loginAsContentManager($browser2);
            
            // Both users visit same page
            $browser1->visit('/admin/berita');
            $browser2->visit('/admin/berita');
            
            // User 1 creates new berita
            $browser1->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Real-time Test Berita',
                        'konten' => 'Test real-time content',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            // User 2 should see the new berita automatically
            $browser2->waitFor('.real-time-notification', 10)
                    ->assertSee('New berita added')
                    ->assertSee('Real-time Test Berita');
            
            // User 1 updates berita
            $browser1->click('a[href*="edit"]')
                    ->waitForLocation('/admin/berita/*/edit', 30)
                    ->type('input[name="judul"]', 'Updated Real-time Test Berita')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            // User 2 should see the update
            $browser2->waitFor('.real-time-notification', 10)
                    ->assertSee('Berita updated')
                    ->assertSee('Updated Real-time Test Berita');
        });
    }

    /**
     * Test multi-tenant data isolation
     */
    public function test_multi_tenant_data_isolation()
    {
        $this->browse(function (Browser $browser) {
            // Create data for tenant 1
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Tenant 1 Berita',
                        'konten' => 'Content for tenant 1',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Tenant 1 Berita');
            
            // Switch to tenant 2 (simulate tenant switching)
            $browser->visit('/admin/switch-tenant')
                    ->select('select[name="tenant"]', 'tenant2')
                    ->press('Switch')
                    ->waitForReload();
            
            // Verify tenant 2 cannot see tenant 1 data
            $browser->visit('/admin/berita')
                    ->assertDontSee('Tenant 1 Berita');
            
            // Create data for tenant 2
            $browser->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Tenant 2 Berita',
                        'konten' => 'Content for tenant 2',
                        'status' => 'published',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Tenant 2 Berita')
                    ->assertDontSee('Tenant 1 Berita');
        });
    }

    /**
     * Test complete workflow end-to-end
     */
    public function test_complete_workflow_end_to_end()
    {
        $this->browse(function (Browser $browser) {
            // 1. Super admin creates user
            $this->loginAsSuperAdmin($browser);
            
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm([
                        'name' => 'End-to-End Test User',
                        'email' => 'e2e@test.com',
                        'password' => 'password123',
                        'password_confirmation' => 'password123',
                        'role' => 'content_manager',
                        'is_active' => 1,
                        'nip' => '198001011234567890',
                        'jabatan' => 'Staff',
                        'unit_kerja' => 'Inspektorat',
                        'telepon' => '081234567890',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30);
            
            // 2. New user logs in
            $browser->logout();
            $browser->visit('/login')
                    ->type('email', 'e2e@test.com')
                    ->type('password', 'password123')
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard', 30);
            
            // 3. User creates berita with document
            $imageFile = $this->createTestFile('e2e.jpg', 'content', 'image/jpeg');
            $documentFile = $this->createTestFile('e2e.pdf', 'content', 'application/pdf');
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm([
                        'title' => 'E2E Test Document',
                        'description' => 'Document for end-to-end testing',
                        'category' => 'peraturan',
                        'tags' => 'e2e, test',
                        'is_public' => 1,
                    ])
                    ->attach('input[name="file"]', $documentFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30);
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'End-to-End Test Berita',
                        'konten' => 'Complete workflow test',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $imageFile)
                    ->select('select[name="attached_documents[]"]', 'E2E Test Document')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30);
            
            // 4. Submit for review
            $browser->click('button[data-action="submit-for-review"]')
                    ->waitFor('.modal', 10)
                    ->press('Submit')
                    ->waitForReload();
            
            // 5. Admin reviews and approves
            $browser->logout();
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="review"]')
                    ->waitForLocation('/admin/berita/*/review', 30)
                    ->select('select[name="review_status"]', 'approved')
                    ->press('Submit Review')
                    ->waitForLocation('/admin/berita', 30);
            
            // 6. Publish berita
            $browser->click('button[data-action="publish"]')
                    ->waitFor('.modal', 10)
                    ->press('Publish')
                    ->waitForReload()
                    ->assertSee('Published');
            
            // 7. Verify public access
            $browser->logout()
                    ->visit('/berita')
                    ->assertSee('End-to-End Test Berita');
            
            // 8. Search functionality
            $browser->type('input[name="search"]', 'End-to-End')
                    ->press('Search')
                    ->waitForReload()
                    ->assertSee('End-to-End Test Berita');
            
            // 9. View berita detail
            $browser->click('a[href*="berita/end-to-end-test-berita"]')
                    ->waitForLocation('/berita/*', 30)
                    ->assertSee('End-to-End Test Berita')
                    ->assertSee('Complete workflow test')
                    ->assertSee('E2E Test Document');
        });
    }
}
