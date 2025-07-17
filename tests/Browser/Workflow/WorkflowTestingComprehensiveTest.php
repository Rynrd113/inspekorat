<?php

namespace Tests\Browser\Workflow;

use App\Models\User;
use App\Models\Berita;
use App\Models\Wbs;
use App\Models\Dokumen;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\InteractsWithAuthentication;

class WorkflowTestingComprehensiveTest extends DuskTestCase
{
    use DatabaseMigrations, InteractsWithAuthentication;

    /**
     * Test Complete User Registration to Dashboard Workflow
     */
    public function test_complete_user_registration_to_dashboard_workflow()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        $this->browse(function (Browser $browser) use ($superAdmin) {
            // Super admin creates new user
            $browser->loginAs($superAdmin)
                ->visit('/admin/users/create')
                ->type('name', 'New User Workflow')
                ->type('email', 'workflow@example.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->select('role', 'content_manager')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->logout();
            
            // New user logs in and accesses dashboard
            $browser->visit('/admin/login')
                ->type('email', 'workflow@example.com')
                ->type('password', 'password123')
                ->press('Masuk')
                ->waitForText('Dashboard')
                ->assertSee('Dashboard')
                ->assertSee('Content Manager');
        });
    }

    /**
     * Test Complete Berita Creation to Publication Workflow
     */
    public function test_complete_berita_creation_to_publication_workflow()
    {
        $contentManager = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($contentManager) {
            $browser->loginAs($contentManager)
                ->visit('/admin/berita/create')
                ->type('judul', 'Workflow Test Article')
                ->type('konten', 'This is a test article for workflow testing')
                ->select('status', 'draft')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Edit to published
                ->visit('/admin/berita')
                ->click('a[href*="edit"]')
                ->waitForText('Edit')
                ->select('status', 'published')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Verify on public page
                ->visit('/berita')
                ->waitForText('Workflow Test Article')
                ->assertSee('Workflow Test Article');
        });
    }

    /**
     * Test Complete WBS Report Submission to Resolution Workflow
     */
    public function test_complete_wbs_report_submission_to_resolution_workflow()
    {
        $wbsManager = User::factory()->create(['role' => 'wbs_manager']);
        
        $this->browse(function (Browser $browser) use ($wbsManager) {
            // Submit WBS report
            $browser->visit('/wbs')
                ->type('nama', 'Anonymous Reporter')
                ->type('email', 'reporter@example.com')
                ->type('judul', 'Test WBS Report')
                ->type('keterangan', 'This is a test WBS report')
                ->press('Kirim Laporan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            // WBS Manager processes report
            $browser->loginAs($wbsManager)
                ->visit('/admin/wbs')
                ->waitForText('Test WBS Report')
                ->click('a[href*="show"]')
                ->waitForText('Detail')
                ->select('status', 'diproses')
                ->press('Update Status')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Complete resolution
                ->select('status', 'selesai')
                ->type('tindak_lanjut', 'Report has been processed and resolved')
                ->press('Update Status')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
    }

    /**
     * Test Complete Document Upload to Public Access Workflow
     */
    public function test_complete_document_upload_to_public_access_workflow()
    {
        $serviceManager = User::factory()->create(['role' => 'service_manager']);
        
        // Create test file
        $testFile = tempnam(sys_get_temp_dir(), 'test_doc') . '.pdf';
        file_put_contents($testFile, 'Test document content');
        
        $this->browse(function (Browser $browser) use ($serviceManager, $testFile) {
            $browser->loginAs($serviceManager)
                ->visit('/admin/dokumen/create')
                ->type('judul', 'Test Document Workflow')
                ->type('deskripsi', 'This is a test document for workflow testing')
                ->select('kategori', 'peraturan')
                ->attach('file', $testFile)
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Verify document is accessible publicly
                ->visit('/dokumen')
                ->waitForText('Test Document Workflow')
                ->assertSee('Test Document Workflow')
                ->click('a[href*="download"]')
                ->waitForReload();
        });
        
        unlink($testFile);
    }

    /**
     * Test Complete User Role Change Workflow
     */
    public function test_complete_user_role_change_workflow()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $user = User::factory()->create(['role' => 'user']);
        
        $this->browse(function (Browser $browser) use ($superAdmin, $user) {
            // Super admin changes user role
            $browser->loginAs($superAdmin)
                ->visit('/admin/users')
                ->click('a[href*="' . $user->id . '/edit"]')
                ->waitForText('Edit User')
                ->select('role', 'content_manager')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->logout();
            
            // User logs in with new permissions
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->waitForText('Dashboard')
                ->assertSee('Dashboard')
                ->visit('/admin/berita')
                ->waitForText('Berita')
                ->assertSee('Tambah Berita'); // Should now have access
        });
    }

    /**
     * Test Complete Content Approval Workflow
     */
    public function test_complete_content_approval_workflow()
    {
        $contentManager = User::factory()->create(['role' => 'content_manager']);
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($contentManager, $admin) {
            // Content manager creates draft
            $browser->loginAs($contentManager)
                ->visit('/admin/berita/create')
                ->type('judul', 'Draft Article for Approval')
                ->type('konten', 'This article needs approval')
                ->select('status', 'draft')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->logout();
            
            // Admin approves and publishes
            $browser->loginAs($admin)
                ->visit('/admin/berita')
                ->waitForText('Draft Article for Approval')
                ->click('a[href*="edit"]')
                ->waitForText('Edit')
                ->select('status', 'published')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Verify public visibility
                ->visit('/berita')
                ->waitForText('Draft Article for Approval')
                ->assertSee('Draft Article for Approval');
        });
    }

    /**
     * Test Complete File Upload and Replacement Workflow
     */
    public function test_complete_file_upload_and_replacement_workflow()
    {
        $contentManager = User::factory()->create(['role' => 'content_manager']);
        
        // Create test files
        $originalFile = tempnam(sys_get_temp_dir(), 'original') . '.jpg';
        $replacementFile = tempnam(sys_get_temp_dir(), 'replacement') . '.jpg';
        file_put_contents($originalFile, 'Original image content');
        file_put_contents($replacementFile, 'Replacement image content');
        
        $this->browse(function (Browser $browser) use ($contentManager, $originalFile, $replacementFile) {
            $browser->loginAs($contentManager)
                ->visit('/admin/berita/create')
                ->type('judul', 'Article with Image')
                ->type('konten', 'This article has an image')
                ->attach('gambar', $originalFile)
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Edit and replace image
                ->visit('/admin/berita')
                ->click('a[href*="edit"]')
                ->waitForText('Edit')
                ->attach('gambar', $replacementFile)
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
        
        unlink($originalFile);
        unlink($replacementFile);
    }

    /**
     * Test Complete Multi-User Collaboration Workflow
     */
    public function test_complete_multi_user_collaboration_workflow()
    {
        $contentManager = User::factory()->create(['role' => 'content_manager']);
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        $this->browse(function (Browser $browser) use ($contentManager, $admin, $superAdmin) {
            // Content manager creates draft
            $browser->loginAs($contentManager)
                ->visit('/admin/berita/create')
                ->type('judul', 'Collaboration Article')
                ->type('konten', 'This is a collaboration test')
                ->select('status', 'draft')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->logout();
            
            // Admin reviews and edits
            $browser->loginAs($admin)
                ->visit('/admin/berita')
                ->click('a[href*="edit"]')
                ->waitForText('Edit')
                ->clear('konten')
                ->type('konten', 'Edited by admin for collaboration test')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->logout();
            
            // Super admin publishes
            $browser->loginAs($superAdmin)
                ->visit('/admin/berita')
                ->click('a[href*="edit"]')
                ->waitForText('Edit')
                ->select('status', 'published')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
    }

    /**
     * Test Complete Search and Filter Workflow
     */
    public function test_complete_search_and_filter_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Create test data
        Berita::factory()->create(['judul' => 'Published Article 1', 'status' => 'published']);
        Berita::factory()->create(['judul' => 'Draft Article 2', 'status' => 'draft']);
        Berita::factory()->create(['judul' => 'Published Article 3', 'status' => 'published']);
        
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/berita')
                ->waitForText('Published Article 1')
                
                // Test search
                ->type('search', 'Published')
                ->press('Cari')
                ->waitForText('Published Article 1')
                ->assertSee('Published Article 1')
                ->assertSee('Published Article 3')
                ->assertDontSee('Draft Article 2')
                
                // Test filter
                ->select('status', 'draft')
                ->press('Filter')
                ->waitForText('Draft Article 2')
                ->assertSee('Draft Article 2')
                ->assertDontSee('Published Article 1')
                
                // Reset filters
                ->select('status', '')
                ->clear('search')
                ->press('Filter')
                ->waitForText('Published Article 1')
                ->assertSee('Published Article 1')
                ->assertSee('Draft Article 2')
                ->assertSee('Published Article 3');
        });
    }

    /**
     * Test Complete Bulk Operations Workflow
     */
    public function test_complete_bulk_operations_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Create test data
        $berita1 = Berita::factory()->create(['judul' => 'Bulk Test 1', 'status' => 'draft']);
        $berita2 = Berita::factory()->create(['judul' => 'Bulk Test 2', 'status' => 'draft']);
        $berita3 = Berita::factory()->create(['judul' => 'Bulk Test 3', 'status' => 'draft']);
        
        $this->browse(function (Browser $browser) use ($admin, $berita1, $berita2, $berita3) {
            $browser->loginAs($admin)
                ->visit('/admin/berita')
                ->waitForText('Bulk Test 1')
                
                // Select multiple items
                ->check('selected[]', $berita1->id)
                ->check('selected[]', $berita2->id)
                ->select('bulk_action', 'publish')
                ->press('Terapkan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Verify changes
                ->visit('/berita')
                ->waitForText('Bulk Test 1')
                ->assertSee('Bulk Test 1')
                ->assertSee('Bulk Test 2')
                ->assertDontSee('Bulk Test 3');
        });
    }

    /**
     * Test Complete Error Recovery Workflow
     */
    public function test_complete_error_recovery_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/berita/create')
                
                // Submit form with errors
                ->type('judul', '') // Empty required field
                ->press('Simpan')
                ->waitForText('Judul wajib diisi')
                ->assertSee('Judul wajib diisi')
                
                // Correct errors and resubmit
                ->type('judul', 'Corrected Article')
                ->type('konten', 'This article was corrected after error')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Verify success
                ->visit('/admin/berita')
                ->waitForText('Corrected Article')
                ->assertSee('Corrected Article');
        });
    }

    /**
     * Test Complete Session Management Workflow
     */
    public function test_complete_session_management_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->waitForText('Dashboard')
                ->assertSee('Dashboard')
                
                // Simulate session activity
                ->visit('/admin/berita')
                ->waitForText('Berita')
                ->visit('/admin/wbs')
                ->waitForText('WBS')
                
                // Test logout
                ->click('a[href*="logout"]')
                ->waitForText('Login')
                ->assertSee('Login')
                
                // Test access after logout
                ->visit('/admin/dashboard')
                ->waitForText('Login')
                ->assertSee('Login');
        });
    }

    /**
     * Test Complete Data Export Workflow
     */
    public function test_complete_data_export_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Create test data
        Berita::factory()->count(5)->create(['status' => 'published']);
        
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/berita')
                ->waitForText('Export')
                ->click('a[href*="export"]')
                ->waitForReload()
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test Complete Notification Workflow
     */
    public function test_complete_notification_workflow()
    {
        $contentManager = User::factory()->create(['role' => 'content_manager']);
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($contentManager, $admin) {
            // Content manager creates content
            $browser->loginAs($contentManager)
                ->visit('/admin/berita/create')
                ->type('judul', 'Notification Test Article')
                ->type('konten', 'This should trigger notification')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->logout();
            
            // Admin checks notifications
            $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->waitForText('Dashboard')
                ->click('.notification-icon')
                ->waitForText('Notification Test Article')
                ->assertSee('Notification Test Article');
        });
    }

    /**
     * Test Complete Backup and Recovery Workflow
     */
    public function test_complete_backup_and_recovery_workflow()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $testData = Berita::factory()->create(['judul' => 'Backup Test Article']);
        
        $this->browse(function (Browser $browser) use ($superAdmin, $testData) {
            $browser->loginAs($superAdmin)
                ->visit('/admin/configurations')
                ->waitForText('Backup')
                ->click('a[href*="backup"]')
                ->waitForText('Backup berhasil')
                ->assertSee('Backup berhasil')
                
                // Simulate data loss
                ->visit('/admin/berita')
                ->click('a[href*="delete"]')
                ->waitForText('Konfirmasi')
                ->press('Ya, Hapus')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Restore backup
                ->visit('/admin/configurations')
                ->click('a[href*="restore"]')
                ->waitForText('Restore berhasil')
                ->assertSee('Restore berhasil')
                
                // Verify restoration
                ->visit('/admin/berita')
                ->waitForText('Backup Test Article')
                ->assertSee('Backup Test Article');
        });
    }

    /**
     * Test Complete Multi-Language Content Workflow
     */
    public function test_complete_multi_language_content_workflow()
    {
        $contentManager = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($contentManager) {
            $browser->loginAs($contentManager)
                ->visit('/admin/berita/create')
                ->type('judul', 'Indonesian Article')
                ->type('judul_en', 'English Article')
                ->type('konten', 'Artikel dalam bahasa Indonesia')
                ->type('konten_en', 'Article in English language')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                
                // Test language switching
                ->visit('/berita')
                ->waitForText('Indonesian Article')
                ->assertSee('Indonesian Article')
                ->click('a[href*="lang=en"]')
                ->waitForText('English Article')
                ->assertSee('English Article');
        });
    }
}