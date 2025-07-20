<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\ContentApproval;
use App\Models\PortalPapuaTengah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ContentApprovalTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;
    protected $contentManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'admin@inspektorat.id'
        ]);
        
        $this->contentManager = User::factory()->create([
            'role' => 'content_manager',
            'email' => 'content@inspektorat.id'
        ]);
        
        $this->createTestContentApprovalData();
    }

    private function createTestContentApprovalData()
    {
        // Create some news articles for approval
        for ($i = 1; $i <= 10; $i++) {
            $berita = PortalPapuaTengah::create([
                'judul' => 'Berita Perlu Approval ' . $i,
                'slug' => 'berita-perlu-approval-' . $i,
                'konten' => 'Konten berita yang memerlukan persetujuan ' . $i,
                'penulis' => $this->contentManager->name,
                'kategori' => 'berita',
                'is_published' => false,
                'created_by' => $this->contentManager->id
            ]);

            // Create approval request
            ContentApproval::create([
                'model_type' => PortalPapuaTengah::class,
                'model_id' => $berita->id,
                'action' => 'publish',
                'status' => ($i <= 3) ? 'pending' : (($i <= 6) ? 'approved' : 'rejected'),
                'submitted_by' => $this->contentManager->id,
                'submitted_at' => now()->subHours(rand(1, 48)),
                'notes' => 'Mohon review dan persetujuan untuk publikasi',
                'approved_by' => ($i > 3) ? $this->admin->id : null,
                'approved_at' => ($i > 3) ? now()->subHours(rand(1, 24)) : null,
                'approval_notes' => ($i > 3) ? 'Reviewed and processed' : null
            ]);
        }
    }

    /**
     * Test Content Approval index page
     */
    public function testContentApprovalIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->assertSee('Persetujuan Konten')
                ->assertSee('Pending')
                ->assertSee('Approved')
                ->assertSee('Rejected')
                ->screenshot('admin_content_approval_index');
        });
    }

    /**
     * Test Content Approval filter by status
     */
    public function testContentApprovalStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->select('status', 'pending')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Berita Perlu Approval 1')
                ->assertSee('pending')
                ->screenshot('admin_content_approval_filter');
        });
    }

    /**
     * Test Content Approval show page
     */
    public function testContentApprovalShowPage()
    {
        $approval = ContentApproval::where('status', 'pending')->first();
        
        $this->browse(function (Browser $browser) use ($approval) {
            $browser->loginAs($this->admin)
                ->visit("/admin/approvals/{$approval->id}")
                ->assertSee('Detail Persetujuan')
                ->assertSee($approval->notes)
                ->assertSee('Setujui')
                ->assertSee('Tolak')
                ->screenshot('admin_content_approval_show');
        });
    }

    /**
     * Test Content Approval approve functionality
     */
    public function testContentApprovalApprove()
    {
        $approval = ContentApproval::where('status', 'pending')->first();
        
        $this->browse(function (Browser $browser) use ($approval) {
            $browser->loginAs($this->admin)
                ->visit("/admin/approvals/{$approval->id}")
                ->type('notes', 'Konten sudah sesuai dan siap dipublikasi')
                ->press('Setujui')
                ->pause(2000)
                ->assertSee('Konten berhasil disetujui')
                ->assertPathIs('/admin/approvals')
                ->screenshot('admin_content_approval_approve');
        });
    }

    /**
     * Test Content Approval reject functionality
     */
    public function testContentApprovalReject()
    {
        $approval = ContentApproval::where('status', 'pending')->skip(1)->first();
        
        $this->browse(function (Browser $browser) use ($approval) {
            $browser->loginAs($this->admin)
                ->visit("/admin/approvals/{$approval->id}")
                ->type('notes', 'Konten perlu diperbaiki sebelum dipublikasi')
                ->press('Tolak')
                ->pause(2000)
                ->assertSee('Konten berhasil ditolak')
                ->assertPathIs('/admin/approvals')
                ->screenshot('admin_content_approval_reject');
        });
    }

    /**
     * Test Content Approval bulk actions
     */
    public function testContentApprovalBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->select('status', 'pending')
                ->press('Filter')
                ->pause(1000)
                ->check('approvals[]')
                ->select('action', 'approve')
                ->type('notes', 'Bulk approval untuk konten yang sudah direview')
                ->press('Terapkan Aksi')
                ->pause(2000)
                ->assertSee('konten berhasil disetujui')
                ->screenshot('admin_content_approval_bulk');
        });
    }

    /**
     * Test Content Approval statistics
     */
    public function testContentApprovalStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals/stats')
                ->assertSee('Statistik Persetujuan')
                ->assertSee('Total Pending')
                ->assertSee('Total Approved')
                ->assertSee('Total Rejected')
                ->screenshot('admin_content_approval_stats');
        });
    }

    /**
     * Test Content Manager submission workflow
     */
    public function testContentManagerSubmissionWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // Content Manager creates and submits for approval
            $browser->loginAs($this->contentManager)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita Submit untuk Approval')
                ->type('slug', 'berita-submit-approval')
                ->type('konten', 'Konten berita yang akan disubmit untuk approval')
                ->select('kategori', 'berita')
                ->type('penulis', $this->contentManager->name)
                ->press('Simpan Draft')
                ->pause(2000)
                ->assertSee('Berita berhasil disimpan')
                ->screenshot('content_manager_submission');
        });
    }

    /**
     * Test Content Approval workflow complete cycle
     */
    public function testContentApprovalWorkflowCycle()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Content Manager creates content
            $browser->loginAs($this->contentManager)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Test Workflow Cycle')
                ->type('slug', 'test-workflow-cycle')
                ->type('konten', 'Konten untuk testing workflow lengkap')
                ->select('kategori', 'berita')
                ->type('penulis', $this->contentManager->name)
                ->press('Submit untuk Approval')
                ->pause(2000)
                ->assertSee('Konten berhasil disubmit untuk approval')
                ->screenshot('workflow_step1_submit');

            // Step 2: Admin reviews and approves
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->assertSee('Test Workflow Cycle')
                ->click('a[href*="approvals"][href$="/1"]') // Assuming first approval
                ->type('notes', 'Konten bagus, siap dipublikasi')
                ->press('Setujui')
                ->pause(2000)
                ->assertSee('Konten berhasil disetujui')
                ->screenshot('workflow_step2_approve');

            // Step 3: Verify content is published
            $browser->visit('/berita')
                ->assertSee('Test Workflow Cycle')
                ->screenshot('workflow_step3_published');
        });
    }

    /**
     * Test Content Approval notifications
     */
    public function testContentApprovalNotifications()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->assertSee('notifications')
                ->screenshot('admin_content_approval_notifications');
        });
    }

    /**
     * Test Content Approval search functionality
     */
    public function testContentApprovalSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/approvals')
                ->type('search', 'Berita Perlu Approval 1')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Berita Perlu Approval 1')
                ->screenshot('admin_content_approval_search');
        });
    }
}
