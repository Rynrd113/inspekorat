<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\Wbs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WbsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test public can access WBS submission form.
     */
    public function test_public_can_access_wbs_submission_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->assertSee('Whistleblowing System')
                    ->assertSee('Laporkan Dugaan Penyimpangan')
                    ->assertVisible('.wbs-form')
                    ->assertVisible('input[name="judul_laporan"]')
                    ->assertVisible('textarea[name="isi_laporan"]')
                    ->assertVisible('input[name="lampiran"]');
        });
    }

    /**
     * Test anonymous WBS report submission.
     */
    public function test_anonymous_wbs_report_submission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->type('judul_laporan', 'Laporan Dugaan Korupsi Test')
                    ->type('isi_laporan', 'Deskripsi lengkap tentang dugaan penyimpangan yang terjadi.')
                    ->type('lokasi_kejadian', 'Kantor Inspektorat Papua Tengah')
                    ->type('waktu_kejadian', '2024-01-15')
                    ->check('is_anonymous'); // Submit as anonymous

            // Upload evidence file
            $this->uploadFile($browser, 'lampiran[]', 'evidence.pdf', 'fake-pdf-content');

            $browser->press('Kirim Laporan')
                    ->waitForText('Laporan berhasil dikirim', 10)
                    ->assertSee('Laporan berhasil dikirim')
                    ->assertSee('Nomor Tiket');

            // Verify report is stored in database
            $this->assertDatabaseHas('wbs', [
                'judul_laporan' => 'Laporan Dugaan Korupsi Test',
                'isi_laporan' => 'Deskripsi lengkap tentang dugaan penyimpangan yang terjadi.',
                'is_anonymous' => true,
                'status' => 'pending',
            ]);

            // Check tracking number is displayed
            $ticketNumber = $browser->text('.ticket-number');
            $this->assertNotEmpty($ticketNumber);
        });
    }

    /**
     * Test named WBS report submission.
     */
    public function test_named_wbs_report_submission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->type('judul_laporan', 'Laporan dengan Identitas')
                    ->type('isi_laporan', 'Laporan dengan identitas pelapor.')
                    ->type('lokasi_kejadian', 'Dinas XYZ')
                    ->type('waktu_kejadian', '2024-01-10')
                    ->uncheck('is_anonymous')
                    ->waitFor('.reporter-info')
                    ->type('nama_pelapor', 'John Doe')
                    ->type('email_pelapor', 'john@example.com')
                    ->type('telepon_pelapor', '08123456789')
                    ->press('Kirim Laporan')
                    ->waitForText('Laporan berhasil dikirim', 10);

            // Verify named report is stored
            $this->assertDatabaseHas('wbs', [
                'judul_laporan' => 'Laporan dengan Identitas',
                'is_anonymous' => false,
                'nama_pelapor' => 'John Doe',
                'email_pelapor' => 'john@example.com',
                'telepon_pelapor' => '08123456789',
            ]);
        });
    }

    /**
     * Test WBS form validation.
     */
    public function test_wbs_form_validation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->press('Kirim Laporan') // Submit without required fields
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The judul laporan field is required')
                    ->assertSee('The isi laporan field is required')
                    ->assertSee('The lokasi kejadian field is required');
        });
    }

    /**
     * Test named report validation requires reporter info.
     */
    public function test_named_report_validation_requires_reporter_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->type('judul_laporan', 'Test Report')
                    ->type('isi_laporan', 'Test content')
                    ->type('lokasi_kejadian', 'Test location')
                    ->uncheck('is_anonymous')
                    ->waitFor('.reporter-info')
                    ->press('Kirim Laporan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The nama pelapor field is required')
                    ->assertSee('The email pelapor field is required');
        });
    }

    /**
     * Test file upload validation.
     */
    public function test_file_upload_validation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->type('judul_laporan', 'Test dengan File Besar')
                    ->type('isi_laporan', 'Test content')
                    ->type('lokasi_kejadian', 'Test location');

            // Try to upload oversized file (simulate)
            $this->uploadFile($browser, 'lampiran[]', 'large-file.pdf', str_repeat('x', 10 * 1024 * 1024)); // 10MB

            $browser->press('Kirim Laporan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The lampiran field must not be greater than');
        });
    }

    /**
     * Test multiple file uploads.
     */
    public function test_multiple_file_uploads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->type('judul_laporan', 'Laporan Multiple Files')
                    ->type('isi_laporan', 'Test dengan multiple files')
                    ->type('lokasi_kejadian', 'Test location');

            // Upload multiple files
            $this->uploadFile($browser, 'lampiran[]', 'evidence1.pdf', 'file1-content');
            $browser->pause(500);
            $this->uploadFile($browser, 'lampiran[]', 'evidence2.jpg', 'file2-content');

            $browser->press('Kirim Laporan')
                    ->waitForText('Laporan berhasil dikirim', 10);

            // Verify both files are stored
            $wbs = Wbs::where('judul_laporan', 'Laporan Multiple Files')->first();
            $this->assertNotNull($wbs);
            $this->assertCount(2, $wbs->lampiran);
        });
    }

    /**
     * Test admin can view WBS reports.
     */
    public function test_admin_can_view_wbs_reports()
    {
        $admin = $this->createWbsManager();
        $wbs = Wbs::factory()->create([
            'judul_laporan' => 'Laporan Admin View Test',
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $wbs) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/wbs')
                    ->assertSee('Manajemen WBS')
                    ->assertSee($wbs->judul_laporan)
                    ->assertSee('Pending')
                    ->assertVisible('.wbs-table');
        });
    }

    /**
     * Test admin can update WBS status.
     */
    public function test_admin_can_update_wbs_status()
    {
        $admin = $this->createWbsManager();
        $wbs = Wbs::factory()->create([
            'judul_laporan' => 'Laporan Status Update',
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $wbs) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/wbs')
                    ->click("a[href*='/admin/wbs/{$wbs->id}']")
                    ->waitForLocation("/admin/wbs/{$wbs->id}")
                    ->assertSee($wbs->judul_laporan)
                    ->select('status', 'proses')
                    ->type('tanggapan_admin', 'Laporan sedang dalam proses investigasi.')
                    ->press('Update Status')
                    ->waitForText('Status berhasil diupdate', 10);

            // Verify database is updated
            $this->assertDatabaseHas('wbs', [
                'id' => $wbs->id,
                'status' => 'proses',
                'tanggapan_admin' => 'Laporan sedang dalam proses investigasi.',
            ]);
        });
    }

    /**
     * Test admin can mark WBS as completed.
     */
    public function test_admin_can_mark_wbs_as_completed()
    {
        $admin = $this->createWbsManager();
        $wbs = Wbs::factory()->create([
            'status' => 'proses',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $wbs) {
            $this->loginAs($admin, $browser);
            
            $browser->visit("/admin/wbs/{$wbs->id}")
                    ->select('status', 'selesai')
                    ->type('tanggapan_admin', 'Laporan telah diselesaikan. Terima kasih atas partisipasinya.')
                    ->press('Update Status')
                    ->waitForText('Status berhasil diupdate', 10);

            // Verify status is updated
            $this->assertDatabaseHas('wbs', [
                'id' => $wbs->id,
                'status' => 'selesai',
            ]);
        });
    }

    /**
     * Test WBS tracking functionality for public.
     */
    public function test_wbs_tracking_functionality_for_public()
    {
        $wbs = Wbs::factory()->create([
            'judul_laporan' => 'Laporan Tracking Test',
            'nomor_tiket' => 'WBS-2024-001',
            'status' => 'proses',
            'tanggapan_admin' => 'Laporan sedang diproses.',
        ]);

        $this->browse(function (Browser $browser) use ($wbs) {
            $browser->visit('/wbs/tracking')
                    ->assertSee('Lacak Laporan WBS')
                    ->type('nomor_tiket', $wbs->nomor_tiket)
                    ->press('Cari')
                    ->waitForText($wbs->judul_laporan, 10)
                    ->assertSee($wbs->judul_laporan)
                    ->assertSee('Proses')
                    ->assertSee($wbs->tanggapan_admin);
        });
    }

    /**
     * Test invalid ticket tracking.
     */
    public function test_invalid_ticket_tracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs/tracking')
                    ->type('nomor_tiket', 'INVALID-TICKET')
                    ->press('Cari')
                    ->waitForText('Nomor tiket tidak ditemukan', 10)
                    ->assertSee('Nomor tiket tidak ditemukan');
        });
    }

    /**
     * Test WBS admin dashboard statistics.
     */
    public function test_wbs_admin_dashboard_statistics()
    {
        $admin = $this->createWbsManager();
        
        // Create reports with different statuses
        Wbs::factory()->count(3)->create(['status' => 'pending']);
        Wbs::factory()->count(2)->create(['status' => 'proses']);
        Wbs::factory()->count(1)->create(['status' => 'selesai']);

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/dashboard')
                    ->assertSee('WBS Statistics')
                    ->assertSee('Pending: 3')
                    ->assertSee('Proses: 2')
                    ->assertSee('Selesai: 1')
                    ->assertVisible('.wbs-chart');
        });
    }

    /**
     * Test WBS filtering and search in admin.
     */
    public function test_wbs_filtering_and_search_in_admin()
    {
        $admin = $this->createWbsManager();
        
        $pendingWbs = Wbs::factory()->create([
            'judul_laporan' => 'Laporan Pending Search',
            'status' => 'pending',
        ]);
        
        $prosesWbs = Wbs::factory()->create([
            'judul_laporan' => 'Laporan Proses Search',
            'status' => 'proses',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $pendingWbs, $prosesWbs) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/wbs');

            // Test search functionality
            $browser->type('search', 'Search')
                    ->press('Cari')
                    ->waitFor('.search-results')
                    ->assertSee($pendingWbs->judul_laporan)
                    ->assertSee($prosesWbs->judul_laporan);

            // Test status filtering
            $browser->select('status_filter', 'pending')
                    ->press('Filter')
                    ->waitFor('.filtered-results')
                    ->assertSee($pendingWbs->judul_laporan)
                    ->assertDontSee($prosesWbs->judul_laporan);
        });
    }

    /**
     * Test WBS report confidentiality.
     */
    public function test_wbs_report_confidentiality()
    {
        $wbsManager = $this->createWbsManager();
        $contentManager = $this->createContentManager();
        
        $wbs = Wbs::factory()->create([
            'judul_laporan' => 'Confidential Report',
            'is_anonymous' => true,
        ]);

        $this->browse(function (Browser $browser) use ($wbsManager, $contentManager, $wbs) {
            // WBS manager should access WBS
            $this->loginAs($wbsManager, $browser);
            
            $browser->visit('/admin/wbs')
                    ->assertSee($wbs->judul_laporan);
                    
            $this->logout($browser);

            // Content manager should NOT access WBS
            $this->loginAs($contentManager, $browser);
            
            $browser->visit('/admin/wbs')
                    ->assertSee('403')
                    ->orAssertSee('Unauthorized');
        });
    }

    /**
     * Test WBS email notifications (if implemented).
     */
    public function test_wbs_email_notifications()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->type('judul_laporan', 'Notification Test')
                    ->type('isi_laporan', 'Test content')
                    ->type('lokasi_kejadian', 'Test location')
                    ->uncheck('is_anonymous')
                    ->waitFor('.reporter-info')
                    ->type('nama_pelapor', 'John Doe')
                    ->type('email_pelapor', 'john@example.com')
                    ->press('Kirim Laporan')
                    ->waitForText('Laporan berhasil dikirim', 10);

            // Check if notification email option is available
            if ($this->elementExists($browser, '.email-notification-info')) {
                $browser->assertSee('Email konfirmasi telah dikirim');
            }
        });
    }
}