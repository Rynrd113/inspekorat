<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Wbs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WbsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin WBS',
            'email' => 'admin.wbs@inspektorat.id',
            'password' => bcrypt('adminwbs123'),
            'role' => 'admin_wbs',
            'is_active' => true,
        ]);

        // Create test WBS data
        $this->createTestWbsData();
    }

    private function createTestWbsData()
    {
        for ($i = 1; $i <= 15; $i++) {
            Wbs::create([
                'nama_pelapor' => $i % 3 === 0 ? null : 'Pelapor Test ' . $i,
                'email' => $i % 3 === 0 ? null : 'pelapor' . $i . '@email.com',
                'no_telepon' => $i % 3 === 0 ? null : '0901234567' . $i,
                'subjek' => 'Laporan WBS Test ' . $i,
                'deskripsi' => 'Deskripsi lengkap laporan WBS test ' . $i . '. Ini adalah laporan mengenai dugaan penyimpangan yang terjadi di lingkungan pemerintahan.',
                'tanggal_kejadian' => now()->subDays(rand(1, 30)),
                'lokasi_kejadian' => 'Lokasi Kejadian Test ' . $i . ', Papua Tengah',
                'pihak_terlibat' => 'Pihak terlibat untuk laporan ' . $i,
                'kronologi' => 'Kronologi lengkap kejadian untuk laporan ' . $i . '. Kejadian dimulai pada...',
                'bukti_files' => $i % 2 === 0 ? ['evidence/evidence-' . $i . '.pdf', 'evidence/evidence-' . $i . '.jpg'] : null,
                'status' => ['pending', 'proses', 'selesai'][($i - 1) % 3],
                'is_anonymous' => $i % 3 === 0,
                'response' => $i > 10 ? 'Response untuk laporan ' . $i : null,
                'responded_at' => $i > 10 ? now()->subDays(rand(1, 5)) : null,
                'admin_note' => 'Catatan admin untuk laporan ' . $i,
                'created_by' => $this->admin->id,
                'updated_by' => $this->admin->id,
            ]);
        }
    }

    /**
     * Test WBS index page
     */
    public function testWbsIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->assertSee('WBS Reports')
                ->assertSee('Laporan WBS Test 1')
                ->assertSee('Laporan WBS Test 2')
                ->assertSee('Laporan WBS Test 3')
                ->assertSee('Status')
                ->assertSee('pending')
                ->assertSee('proses')
                ->assertSee('selesai');
        });
    }

    /**
     * Test WBS pagination
     */
    public function testWbsPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->assertSee('WBS Reports')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Laporan WBS Test 11')
                ->assertSee('Laporan WBS Test 12');
        });
    }

    /**
     * Test WBS search functionality
     */
    public function testWbsSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->type('search', 'Laporan WBS Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Laporan WBS Test 5')
                ->assertDontSee('Laporan WBS Test 1')
                ->assertDontSee('Laporan WBS Test 2');
        });
    }

    /**
     * Test WBS show page
     */
    public function testWbsShowPage()
    {
        $wbs = Wbs::first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->click('a[href="/admin/wbs/' . $wbs->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/wbs/' . $wbs->id)
                ->assertSee($wbs->subjek)
                ->assertSee($wbs->deskripsi)
                ->assertSee($wbs->lokasi_kejadian)
                ->assertSee($wbs->kategori)
                ->assertSee($wbs->status)
                ->assertSee($wbs->priority);
        });
    }

    /**
     * Test WBS show anonymous report
     */
    public function testWbsShowAnonymousReport()
    {
        $wbs = Wbs::where('is_anonymous', true)->first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id)
                ->assertSee($wbs->subjek)
                ->assertSee('Anonymous Report')
                ->assertSee('Nama: [Hidden]')
                ->assertSee('Email: [Hidden]')
                ->assertSee('Telepon: [Hidden]');
        });
    }

    /**
     * Test WBS edit page
     */
    public function testWbsEditPage()
    {
        $wbs = Wbs::first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->click('a[href="/admin/wbs/' . $wbs->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/wbs/' . $wbs->id . '/edit')
                ->assertSee('Edit WBS Report')
                ->assertInputValue('subjek', $wbs->subjek)
                ->assertSee($wbs->deskripsi)
                ->assertInputValue('lokasi_kejadian', $wbs->lokasi_kejadian)
                ->assertPresent('select[name="status"]')
                ->assertPresent('select[name="priority"]')
                ->assertPresent('select[name="assigned_to"]')
                ->assertPresent('textarea[name="follow_up_notes"]');
        });
    }

    /**
     * Test WBS update functionality
     */
    public function testWbsUpdate()
    {
        $wbs = Wbs::first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id . '/edit')
                ->select('status', 'investigating')
                ->select('priority', 'high')
                ->clear('follow_up_notes')
                ->type('follow_up_notes', 'Laporan sedang dalam tahap investigasi lanjutan')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/wbs')
                ->assertSee('Data berhasil diperbarui')
                ->assertSee('investigating')
                ->assertSee('high');
        });
    }

    /**
     * Test WBS status filter
     */
    public function testWbsStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->select('status', 'pending')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Laporan WBS Test 1')
                ->assertSee('pending')
                ->select('status', 'investigating')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Laporan WBS Test 2')
                ->assertSee('investigating');
        });
    }

    /**
     * Test WBS priority filter
     */
    public function testWbsPriorityFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->select('priority', 'high')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('high')
                ->select('priority', 'urgent')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('urgent');
        });
    }

    /**
     * Test WBS category filter
     */
    public function testWbsCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->select('kategori', 'Korupsi')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Korupsi')
                ->select('kategori', 'Suap')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Suap');
        });
    }

    /**
     * Test WBS anonymous filter
     */
    public function testWbsAnonymousFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->select('is_anonymous', '1')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Anonymous')
                ->select('is_anonymous', '0')
                ->press('Filter')
                ->pause(1000)
                ->assertDontSee('Anonymous');
        });
    }

    /**
     * Test WBS evidence file download
     */
    public function testWbsEvidenceFileDownload()
    {
        $wbs = Wbs::whereNotNull('evidence_files')->first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id)
                ->assertSee('Evidence Files')
                ->click('a[href="/admin/wbs/' . $wbs->id . '/evidence/0"]')
                ->pause(1000);
        });
    }

    /**
     * Test WBS assign to user
     */
    public function testWbsAssignToUser()
    {
        $wbs = Wbs::first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id . '/edit')
                ->select('assigned_to', $this->admin->id)
                ->press('Update')
                ->pause(2000)
                ->assertSee('Data berhasil diperbarui');
        });
    }

    /**
     * Test WBS bulk status update
     */
    public function testWbsBulkStatusUpdate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->check('select-all')
                ->select('bulk-action', 'mark-investigating')
                ->press('Apply')
                ->pause(1000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test WBS delete functionality
     */
    public function testWbsDelete()
    {
        $wbs = Wbs::first();
        $wbsSubject = $wbs->subjek;
        
        $this->browse(function (Browser $browser) use ($wbs, $wbsSubject) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus laporan ini?\')) { document.getElementById(\'delete-form-' . $wbs->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/wbs')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($wbsSubject);
        });
    }

    /**
     * Test WBS responsive design
     */
    public function testWbsResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->assertSee('WBS Reports')
                ->resize(768, 1024) // iPad size
                ->assertSee('WBS Reports')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test WBS statistics dashboard
     */
    public function testWbsStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->assertSee('Total Reports')
                ->assertSee('Pending')
                ->assertSee('Investigating')
                ->assertSee('Completed')
                ->assertSee('Anonymous Reports')
                ->assertSee('High Priority')
                ->assertSee('Reports by Category');
        });
    }

    /**
     * Test WBS export functionality
     */
    public function testWbsExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->click('a[href="/admin/wbs/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test WBS advanced search
     */
    public function testWbsAdvancedSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->clickLink('Advanced Search')
                ->pause(1000)
                ->type('subjek', 'Test')
                ->type('deskripsi', 'laporan')
                ->select('kategori', 'Korupsi')
                ->select('status', 'pending')
                ->select('priority', 'high')
                ->check('is_anonymous')
                ->type('tanggal_dari', '2024-01-01')
                ->type('tanggal_sampai', '2024-12-31')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Laporan WBS Test');
        });
    }

    /**
     * Test WBS timeline view
     */
    public function testWbsTimelineView()
    {
        $wbs = Wbs::first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id)
                ->click('a[href="#timeline"]')
                ->pause(1000)
                ->assertSee('Timeline')
                ->assertSee('Report Created')
                ->assertSee('Status Updated');
        });
    }

    /**
     * Test WBS comment system
     */
    public function testWbsCommentSystem()
    {
        $wbs = Wbs::first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id)
                ->type('comment', 'Laporan ini memerlukan investigasi lebih lanjut')
                ->press('Add Comment')
                ->pause(1000)
                ->assertSee('Comment berhasil ditambahkan')
                ->assertSee('Laporan ini memerlukan investigasi lebih lanjut');
        });
    }

    /**
     * Test WBS notification system
     */
    public function testWbsNotificationSystem()
    {
        $wbs = Wbs::first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id . '/edit')
                ->select('status', 'completed')
                ->check('send_notification')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Notification sent to reporter')
                ->assertSee('Data berhasil diperbarui');
        });
    }

    /**
     * Test WBS report generation
     */
    public function testWbsReportGeneration()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->clickLink('Generate Report')
                ->pause(1000)
                ->select('report_type', 'monthly')
                ->select('report_format', 'pdf')
                ->type('report_month', '2024-01')
                ->press('Generate')
                ->pause(2000)
                ->assertSee('Report generated successfully');
        });
    }

    /**
     * Test WBS archive functionality
     */
    public function testWbsArchive()
    {
        $wbs = Wbs::where('status', 'completed')->first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs')
                ->click('button[onclick="archiveReport(' . $wbs->id . ')"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertSee('Report archived successfully');
        });
    }

    /**
     * Test WBS follow-up reminder
     */
    public function testWbsFollowUpReminder()
    {
        $wbs = Wbs::where('status', 'investigating')->first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id)
                ->click('button[onclick="setReminder(' . $wbs->id . ')"]')
                ->pause(1000)
                ->type('reminder_date', now()->addDays(7)->format('Y-m-d'))
                ->type('reminder_note', 'Follow up on investigation progress')
                ->press('Set Reminder')
                ->pause(1000)
                ->assertSee('Reminder set successfully');
        });
    }

    /**
     * Test WBS data anonymization
     */
    public function testWbsDataAnonymization()
    {
        $wbs = Wbs::where('is_anonymous', false)->first();
        
        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->loginAs($this->admin)
                ->visit('/admin/wbs/' . $wbs->id . '/edit')
                ->check('anonymize_data')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Data anonymized successfully')
                ->assertSee('[Hidden]');
        });
    }
}
