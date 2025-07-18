<?php

namespace Tests\Browser\Public;

use App\Models\Wbs;
use App\Models\WbsCategory;
use App\Models\WbsComment;
use App\Models\WbsAttachment;
use App\Models\WbsStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * WBS Public Test With Results
 * Test Whistle Blowing System public interface with database result verification
 */
class WbsPublicTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestWbsData();
    }

    private function createTestWbsData()
    {
        // Create test WBS categories
        $korupsi = WbsCategory::create([
            'nama' => 'Korupsi',
            'deskripsi' => 'Laporan terkait dugaan korupsi',
            'kode' => 'KOR',
            'status' => 'active'
        ]);

        $nepotisme = WbsCategory::create([
            'nama' => 'Nepotisme',
            'deskripsi' => 'Laporan terkait dugaan nepotisme',
            'kode' => 'NEP',
            'status' => 'active'
        ]);

        $kolusi = WbsCategory::create([
            'nama' => 'Kolusi',
            'deskripsi' => 'Laporan terkait dugaan kolusi',
            'kode' => 'KOL',
            'status' => 'active'
        ]);

        $penyalahgunaan = WbsCategory::create([
            'nama' => 'Penyalahgunaan Wewenang',
            'deskripsi' => 'Laporan terkait dugaan penyalahgunaan wewenang',
            'kode' => 'PW',
            'status' => 'active'
        ]);

        // Create test WBS statuses
        WbsStatus::create([
            'nama' => 'Diterima',
            'deskripsi' => 'Laporan telah diterima dan akan diproses',
            'kode' => 'DITERIMA',
            'warna' => 'blue'
        ]);

        WbsStatus::create([
            'nama' => 'Dalam Proses',
            'deskripsi' => 'Laporan sedang dalam proses verifikasi',
            'kode' => 'PROSES',
            'warna' => 'yellow'
        ]);

        WbsStatus::create([
            'nama' => 'Selesai',
            'deskripsi' => 'Laporan telah selesai diproses',
            'kode' => 'SELESAI',
            'warna' => 'green'
        ]);

        WbsStatus::create([
            'nama' => 'Ditolak',
            'deskripsi' => 'Laporan ditolak karena tidak memenuhi kriteria',
            'kode' => 'DITOLAK',
            'warna' => 'red'
        ]);

        // Create test WBS reports
        $wbs1 = Wbs::create([
            'nomor_laporan' => 'WBS-2025-001',
            'judul' => 'Dugaan Korupsi Pengadaan Barang',
            'deskripsi' => 'Terdapat dugaan korupsi dalam pengadaan barang senilai 500 juta rupiah di OPD tertentu.',
            'lokasi' => 'Kantor OPD ABC',
            'tanggal_kejadian' => '2024-12-15',
            'kategori_id' => $korupsi->id,
            'status_id' => 1,
            'pelapor_nama' => 'Anonymous',
            'pelapor_email' => 'anonymous@example.com',
            'pelapor_telepon' => '081234567890',
            'prioritas' => 'tinggi',
            'is_anonim' => true,
            'view_count' => 0,
            'created_at' => now()->subDays(1)
        ]);

        $wbs2 = Wbs::create([
            'nomor_laporan' => 'WBS-2025-002',
            'judul' => 'Dugaan Nepotisme dalam Rekrutmen',
            'deskripsi' => 'Terdapat dugaan nepotisme dalam proses rekrutmen pegawai baru di instansi tertentu.',
            'lokasi' => 'Kantor OPD XYZ',
            'tanggal_kejadian' => '2024-12-10',
            'kategori_id' => $nepotisme->id,
            'status_id' => 2,
            'pelapor_nama' => 'John Doe',
            'pelapor_email' => 'john@example.com',
            'pelapor_telepon' => '081234567891',
            'prioritas' => 'sedang',
            'is_anonim' => false,
            'view_count' => 5,
            'created_at' => now()->subDays(2)
        ]);

        $wbs3 = Wbs::create([
            'nomor_laporan' => 'WBS-2025-003',
            'judul' => 'Dugaan Kolusi dalam Tender',
            'deskripsi' => 'Terdapat dugaan kolusi antara pihak penyedia dan panitia tender dalam pengadaan jasa konsultansi.',
            'lokasi' => 'Kantor OPD DEF',
            'tanggal_kejadian' => '2024-12-05',
            'kategori_id' => $kolusi->id,
            'status_id' => 3,
            'pelapor_nama' => 'Jane Smith',
            'pelapor_email' => 'jane@example.com',
            'pelapor_telepon' => '081234567892',
            'prioritas' => 'rendah',
            'is_anonim' => false,
            'view_count' => 10,
            'created_at' => now()->subDays(3)
        ]);

        // Create test WBS comments
        WbsComment::create([
            'wbs_id' => $wbs1->id,
            'nama' => 'Admin WBS',
            'email' => 'admin@inspektorat.go.id',
            'komentar' => 'Laporan telah diterima dan akan segera diproses.',
            'is_admin' => true,
            'status' => 'approved'
        ]);

        WbsComment::create([
            'wbs_id' => $wbs2->id,
            'nama' => 'Verifikator',
            'email' => 'verifikator@inspektorat.go.id',
            'komentar' => 'Laporan sedang dalam tahap verifikasi dokumen.',
            'is_admin' => true,
            'status' => 'approved'
        ]);

        // Create test WBS attachments
        WbsAttachment::create([
            'wbs_id' => $wbs1->id,
            'nama_file' => 'bukti_korupsi.pdf',
            'path_file' => 'attachments/bukti_korupsi.pdf',
            'ukuran_file' => 1024768,
            'tipe_file' => 'pdf',
            'keterangan' => 'Dokumen bukti dugaan korupsi'
        ]);

        WbsAttachment::create([
            'wbs_id' => $wbs2->id,
            'nama_file' => 'screenshot_nepotisme.jpg',
            'path_file' => 'attachments/screenshot_nepotisme.jpg',
            'ukuran_file' => 512384,
            'tipe_file' => 'jpg',
            'keterangan' => 'Screenshot percakapan dugaan nepotisme'
        ]);
    }

    /**
     * Test WBS home page display
     */
    public function testWbsHomePageDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.wbs-hero', 10)
                ->assertSee('Whistle Blowing System')
                ->assertSee('Laporkan Dugaan Pelanggaran')
                ->assertSee('Transparansi dan Akuntabilitas')
                ->assertSee('Laporan Anda Akan Ditangani Secara Profesional')
                ->assertPresent('.wbs-statistics')
                ->assertPresent('.wbs-categories')
                ->screenshot('wbs-home-page-display');

            // Verify statistics display
            $browser->assertSee('Total Laporan: 3')
                ->assertSee('Dalam Proses: 1')
                ->assertSee('Selesai: 1')
                ->assertSee('Tingkat Penyelesaian: 67%');
        });
    }

    /**
     * Test WBS report submission form
     */
    public function testWbsReportSubmissionForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.submit-report-btn', 10)
                ->click('.submit-report-btn')
                ->waitFor('.wbs-form', 10)
                ->assertSee('Formulir Laporan WBS')
                ->assertPresent('input[name="judul"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('select[name="kategori_id"]')
                ->assertPresent('input[name="lokasi"]')
                ->assertPresent('input[name="tanggal_kejadian"]')
                ->screenshot('wbs-report-submission-form');

            // Fill and submit form
            $browser->type('judul', 'Test Laporan WBS')
                ->type('deskripsi', 'Ini adalah laporan test untuk WBS system.')
                ->select('kategori_id', '1')
                ->type('lokasi', 'Kantor Test')
                ->type('tanggal_kejadian', '2025-01-15')
                ->type('pelapor_nama', 'Test User')
                ->type('pelapor_email', 'test@example.com')
                ->type('pelapor_telepon', '081234567890')
                ->select('prioritas', 'sedang')
                ->check('is_anonim')
                ->press('Submit Laporan')
                ->waitForText('Laporan berhasil dikirim', 10)
                ->screenshot('wbs-report-submitted');

            // Verify report was saved
            $this->assertDatabaseHas('wbs', [
                'judul' => 'Test Laporan WBS',
                'deskripsi' => 'Ini adalah laporan test untuk WBS system.',
                'pelapor_nama' => 'Test User',
                'pelapor_email' => 'test@example.com',
                'is_anonim' => true
            ]);
        });
    }

    /**
     * Test WBS report tracking
     */
    public function testWbsReportTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.track-report-btn', 10)
                ->click('.track-report-btn')
                ->waitFor('.tracking-form', 10)
                ->assertSee('Lacak Laporan Anda')
                ->type('nomor_laporan', 'WBS-2025-001')
                ->press('Track Report')
                ->waitFor('.tracking-result', 10)
                ->assertSee('WBS-2025-001')
                ->assertSee('Dugaan Korupsi Pengadaan Barang')
                ->assertSee('Diterima')
                ->assertSee('Laporan telah diterima dan akan diproses')
                ->screenshot('wbs-report-tracking');

            // Test tracking with invalid number
            $browser->type('nomor_laporan', 'WBS-2025-999')
                ->press('Track Report')
                ->waitFor('.tracking-error', 10)
                ->assertSee('Nomor laporan tidak ditemukan')
                ->screenshot('wbs-report-tracking-invalid');
        });
    }

    /**
     * Test WBS categories display
     */
    public function testWbsCategoriesDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.wbs-categories', 10)
                ->assertSee('Kategori Laporan')
                ->assertSee('Korupsi')
                ->assertSee('Nepotisme')
                ->assertSee('Kolusi')
                ->assertSee('Penyalahgunaan Wewenang')
                ->assertPresent('.category-card')
                ->screenshot('wbs-categories-display');

            // Test category selection
            $browser->click('.category-card:first-child')
                ->waitFor('.category-detail', 10)
                ->assertSee('Korupsi')
                ->assertSee('Laporan terkait dugaan korupsi')
                ->screenshot('wbs-category-detail');
        });
    }

    /**
     * Test WBS FAQ section
     */
    public function testWbsFaqSection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.wbs-faq', 10)
                ->assertSee('Frequently Asked Questions')
                ->assertSee('Bagaimana cara melaporkan dugaan pelanggaran?')
                ->assertSee('Apakah identitas pelapor akan dirahasiakan?')
                ->assertSee('Berapa lama proses penanganan laporan?')
                ->assertPresent('.faq-item')
                ->screenshot('wbs-faq-section');

            // Test FAQ expand/collapse
            $browser->click('.faq-item:first-child .faq-question')
                ->waitFor('.faq-answer', 10)
                ->assertSee('Anda dapat melaporkan dugaan pelanggaran')
                ->screenshot('wbs-faq-expanded');
        });
    }

    /**
     * Test WBS guidelines page
     */
    public function testWbsGuidelinesPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.guidelines-link', 10)
                ->click('.guidelines-link')
                ->waitFor('.wbs-guidelines', 10)
                ->assertSee('Panduan Pelaporan WBS')
                ->assertSee('Jenis Pelanggaran yang Dapat Dilaporkan')
                ->assertSee('Prosedur Pelaporan')
                ->assertSee('Jaminan Kerahasiaan')
                ->assertSee('Sanksi bagi Pelapor Palsu')
                ->screenshot('wbs-guidelines-page');
        });
    }

    /**
     * Test WBS anonymous reporting
     */
    public function testWbsAnonymousReporting()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.anonymous-report-btn', 10)
                ->click('.anonymous-report-btn')
                ->waitFor('.anonymous-form', 10)
                ->assertSee('Laporan Anonim')
                ->assertSee('Identitas Anda akan dirahasiakan')
                ->assertChecked('is_anonim')
                ->assertAttributeContains('input[name="pelapor_nama"]', 'placeholder', 'Opsional')
                ->screenshot('wbs-anonymous-reporting');

            // Submit anonymous report
            $browser->type('judul', 'Laporan Anonim Test')
                ->type('deskripsi', 'Ini adalah laporan anonim untuk testing.')
                ->select('kategori_id', '1')
                ->type('lokasi', 'Lokasi Rahasia')
                ->type('tanggal_kejadian', '2025-01-10')
                ->select('prioritas', 'tinggi')
                ->press('Submit Laporan')
                ->waitForText('Laporan anonim berhasil dikirim', 10)
                ->screenshot('wbs-anonymous-report-submitted');

            // Verify anonymous report was saved
            $this->assertDatabaseHas('wbs', [
                'judul' => 'Laporan Anonim Test',
                'is_anonim' => true
            ]);
        });
    }

    /**
     * Test WBS file attachment upload
     */
    public function testWbsFileAttachmentUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.submit-report-btn', 10)
                ->click('.submit-report-btn')
                ->waitFor('.wbs-form', 10)
                ->type('judul', 'Laporan dengan Lampiran')
                ->type('deskripsi', 'Laporan test dengan file lampiran.')
                ->select('kategori_id', '1')
                ->type('lokasi', 'Kantor Test')
                ->type('tanggal_kejadian', '2025-01-15')
                ->type('pelapor_nama', 'Test User')
                ->type('pelapor_email', 'test@example.com')
                ->screenshot('wbs-file-attachment-form');

            // Test file upload
            $browser->attach('lampiran[]', __DIR__ . '/../../fixtures/test_document.pdf')
                ->waitFor('.file-preview', 10)
                ->assertSee('test_document.pdf')
                ->press('Submit Laporan')
                ->waitForText('Laporan berhasil dikirim', 10)
                ->screenshot('wbs-file-attachment-uploaded');

            // Verify attachment was saved
            $this->assertDatabaseHas('wbs_attachments', [
                'nama_file' => 'test_document.pdf'
            ]);
        });
    }

    /**
     * Test WBS report progress updates
     */
    public function testWbsReportProgressUpdates()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.track-report-btn', 10)
                ->click('.track-report-btn')
                ->waitFor('.tracking-form', 10)
                ->type('nomor_laporan', 'WBS-2025-002')
                ->press('Track Report')
                ->waitFor('.progress-timeline', 10)
                ->assertSee('Timeline Progress')
                ->assertSee('Laporan Diterima')
                ->assertSee('Dalam Proses Verifikasi')
                ->assertSee('Sedang Ditindaklanjuti')
                ->screenshot('wbs-report-progress-updates');

            // Test progress notifications
            $browser->click('.subscribe-updates')
                ->waitFor('.subscription-form', 10)
                ->type('email', 'updates@example.com')
                ->press('Subscribe')
                ->waitForText('Berhasil berlangganan update', 10)
                ->screenshot('wbs-progress-subscription');
        });
    }

    /**
     * Test WBS report comments
     */
    public function testWbsReportComments()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.track-report-btn', 10)
                ->click('.track-report-btn')
                ->waitFor('.tracking-form', 10)
                ->type('nomor_laporan', 'WBS-2025-001')
                ->press('Track Report')
                ->waitFor('.report-comments', 10)
                ->assertSee('Komentar Progress')
                ->assertSee('Admin WBS')
                ->assertSee('Laporan telah diterima dan akan segera diproses')
                ->screenshot('wbs-report-comments');

            // Test add comment
            $browser->type('new_comment', 'Terima kasih atas update progressnya.')
                ->press('Add Comment')
                ->waitForText('Komentar berhasil ditambahkan', 10)
                ->screenshot('wbs-comment-added');
        });
    }

    /**
     * Test WBS statistics page
     */
    public function testWbsStatisticsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.statistics-link', 10)
                ->click('.statistics-link')
                ->waitFor('.wbs-statistics-page', 10)
                ->assertSee('Statistik WBS')
                ->assertSee('Laporan per Kategori')
                ->assertSee('Korupsi: 1')
                ->assertSee('Nepotisme: 1')
                ->assertSee('Kolusi: 1')
                ->assertSee('Laporan per Status')
                ->assertSee('Grafik Bulanan')
                ->assertPresent('.statistics-chart')
                ->screenshot('wbs-statistics-page');
        });
    }

    /**
     * Test WBS mobile responsiveness
     */
    public function testWbsMobileResponsiveness()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone size
                ->visit('/wbs')
                ->waitFor('.wbs-hero', 10)
                ->assertSee('Whistle Blowing System')
                ->assertPresent('.mobile-menu')
                ->click('.mobile-menu-toggle')
                ->waitFor('.mobile-nav', 10)
                ->assertSee('Buat Laporan')
                ->assertSee('Lacak Laporan')
                ->assertSee('Panduan')
                ->screenshot('wbs-mobile-responsiveness');

            // Test mobile form
            $browser->click('.mobile-submit-btn')
                ->waitFor('.mobile-form', 10)
                ->assertPresent('.mobile-form-container')
                ->screenshot('wbs-mobile-form');
        });
    }

    /**
     * Test WBS accessibility features
     */
    public function testWbsAccessibilityFeatures()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.wbs-hero', 10)
                ->assertPresent('[aria-label]')
                ->assertPresent('[alt]')
                ->assertPresent('[role]')
                ->assertPresent('.sr-only')
                ->screenshot('wbs-accessibility-features');

            // Test keyboard navigation
            $browser->keys('body', '{tab}')
                ->assertFocused('.submit-report-btn')
                ->keys('body', '{tab}')
                ->assertFocused('.track-report-btn')
                ->screenshot('wbs-keyboard-navigation');
        });
    }

    /**
     * Test WBS security features
     */
    public function testWbsSecurityFeatures()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.submit-report-btn', 10)
                ->click('.submit-report-btn')
                ->waitFor('.wbs-form', 10)
                ->assertPresent('input[name="_token"]')
                ->assertPresent('.captcha-container')
                ->screenshot('wbs-security-features');

            // Test CSRF protection
            $browser->type('judul', 'Test Security')
                ->type('deskripsi', 'Testing security features')
                ->select('kategori_id', '1')
                ->type('lokasi', 'Test Location')
                ->type('tanggal_kejadian', '2025-01-15')
                ->type('pelapor_nama', 'Security Tester')
                ->type('pelapor_email', 'security@example.com')
                ->press('Submit Laporan')
                ->waitForText('Laporan berhasil dikirim', 10)
                ->screenshot('wbs-security-test-submitted');
        });
    }

    /**
     * Test WBS search functionality
     */
    public function testWbsSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitFor('.wbs-search', 10)
                ->type('search', 'korupsi')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Hasil Pencarian')
                ->assertSee('Dugaan Korupsi Pengadaan Barang')
                ->assertSee('1 laporan ditemukan')
                ->screenshot('wbs-search-functionality');

            // Test search with no results
            $browser->type('search', 'nonexistent')
                ->press('Search')
                ->waitFor('.no-search-results', 10)
                ->assertSee('Tidak ada hasil yang ditemukan')
                ->screenshot('wbs-search-no-results');
        });
    }

    /**
     * Test WBS report view count increment
     */
    public function testWbsReportViewCountIncrement()
    {
        $this->browse(function (Browser $browser) {
            $wbsReport = Wbs::first();
            $initialViewCount = $wbsReport->view_count;

            $browser->visit('/wbs')
                ->waitFor('.track-report-btn', 10)
                ->click('.track-report-btn')
                ->waitFor('.tracking-form', 10)
                ->type('nomor_laporan', $wbsReport->nomor_laporan)
                ->press('Track Report')
                ->waitFor('.tracking-result', 10)
                ->assertSee($wbsReport->judul)
                ->screenshot('wbs-report-view-count');

            // Verify view count increased
            $wbsReport->refresh();
            $this->assertEquals($initialViewCount + 1, $wbsReport->view_count);
        });
    }
}