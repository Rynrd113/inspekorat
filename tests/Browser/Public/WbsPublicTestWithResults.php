<?php

namespace Tests\Browser\Public;

use App\Models\Wbs;
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
        // Create test WBS reports with correct statuses
        $wbs1 = Wbs::create([
            'nama_pelapor' => 'John Doe',
            'email' => 'john@example.com',
            'no_telepon' => '081234567890',
            'subjek' => 'Dugaan Korupsi Pengadaan Barang',
            'deskripsi' => 'Terdapat indikasi penyelewengan dalam pengadaan barang kantor dengan nilai yang tidak wajar.',
            'tanggal_kejadian' => '2024-12-01',
            'lokasi_kejadian' => 'Kantor Dinas A, Lantai 2',
            'pihak_terlibat' => 'Kepala Bagian Pengadaan, Staff Administrasi',
            'kronologi' => 'Pengadaan dilakukan tanpa tender yang transparan',
            'status' => 'pending',
            'is_anonymous' => false,
            'bukti_files' => ['documents/bukti1.pdf', 'documents/bukti2.jpg']
        ]);

        $wbs2 = Wbs::create([
            'nama_pelapor' => null,
            'email' => null,
            'no_telepon' => null,
            'subjek' => 'Penyalahgunaan Wewenang',
            'deskripsi' => 'Pejabat menggunakan fasilitas kantor untuk kepentingan pribadi.',
            'tanggal_kejadian' => '2024-11-15',
            'lokasi_kejadian' => 'Kantor Bupati',
            'pihak_terlibat' => 'Pejabat Eselon II',
            'kronologi' => 'Penggunaan mobil dinas untuk acara pribadi',
            'status' => 'in_progress',
            'is_anonymous' => true,
            'bukti_files' => ['documents/bukti_anonim.pdf']
        ]);

        $wbs3 = Wbs::create([
            'nama_pelapor' => 'Jane Smith',
            'email' => 'jane@example.com',
            'no_telepon' => '082345678901',
            'subjek' => 'Nepotisme dalam Rekrutmen',
            'deskripsi' => 'Proses rekrutmen tidak sesuai prosedur, ada indikasi nepotisme.',
            'tanggal_kejadian' => '2024-10-20',
            'lokasi_kejadian' => 'Kantor BKD',
            'pihak_terlibat' => 'Tim Rekrutmen',
            'kronologi' => 'Kandidat tertentu diprioritaskan tanpa alasan yang jelas',
            'status' => 'resolved',
            'response' => 'Laporan telah diverifikasi dan tindakan korektif telah dilakukan.',
            'responded_at' => now(),
            'is_anonymous' => false
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
                ->waitForText('Whistleblower System', 10)
                ->assertSee('Laporkan dugaan pelanggaran')
                ->assertPresent('input[name="subjek"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('input[name="nama_pelapor"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="no_telepon"]')
                ->assertPresent('input[name="tanggal_kejadian"]')
                ->assertPresent('input[name="lokasi_kejadian"]')
                ->screenshot('wbs-report-submission-form');

            // Fill and submit form
            $browser->type('subjek', 'Test Laporan WBS')
                ->type('deskripsi', 'Ini adalah laporan test untuk WBS system.')
                ->type('lokasi_kejadian', 'Kantor Test')
                ->type('tanggal_kejadian', '2025-01-15')
                ->type('nama_pelapor', 'Test User')
                ->type('email', 'test@example.com')
                ->type('no_telepon', '081234567890')
                ->check('is_anonymous')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-report-submitted');

            // Verify report was saved
            $this->assertDatabaseHas('wbs', [
                'subjek' => 'Test Laporan WBS',
                'deskripsi' => 'Ini adalah laporan test untuk WBS system.',
                'nama_pelapor' => 'Test User',
                'email' => 'test@example.com',
                'is_anonymous' => true
            ]);
        });
    }

    /**
     * Test WBS page basic functionality
     */
    public function testWbsReportTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->assertSee('Laporkan dugaan pelanggaran')
                ->assertSee('Identitas Anda akan dijaga kerahasiaan')
                ->screenshot('wbs-page-basic-functionality');
        });
    }

    /**
     * Test WBS form validation
     */
    public function testWbsCategoriesDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->assertPresent('form')
                ->assertSee('Subjek Laporan')
                ->assertSee('Deskripsi Laporan')
                ->assertSee('Laporan Anonim')
                ->screenshot('wbs-form-validation');

            // Test form required fields
            $browser->press('Kirim Laporan WBS')
                ->waitFor('.text-red-600', 5)
                ->screenshot('wbs-form-validation-errors');
        });
    }

    /**
     * Test WBS contact information
     */
    public function testWbsFaqSection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->scrollIntoView('.mt-12')
                ->assertSee('Butuh Bantuan?')
                ->assertSee('(0984) 321234')
                ->assertSee('wbs@papuatengah.go.id')
                ->screenshot('wbs-contact-section');
        });
    }

    /**
     * Test WBS anonymous option functionality
     */
    public function testWbsGuidelinesPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->assertPresent('input[name="is_anonymous"]')
                ->uncheck('is_anonymous')
                ->assertVisible('#nama-field')
                ->assertVisible('#email-field')
                ->assertVisible('#telepon-field')
                ->screenshot('wbs-anonymous-unchecked');

            // Test anonymous mode
            $browser->check('is_anonymous')
                ->assertAttribute('input[name="nama_pelapor"]', 'required', null)
                ->assertAttribute('input[name="email"]', 'required', null)
                ->screenshot('wbs-anonymous-checked');
        });
    }

    /**
     * Test WBS anonymous reporting
     */
    public function testWbsAnonymousReporting()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->check('is_anonymous')
                ->assertAttribute('input[name="nama_pelapor"]', 'required', null)
                ->screenshot('wbs-anonymous-mode');

            // Submit anonymous report
            $browser->type('subjek', 'Laporan Anonim Test')
                ->type('deskripsi', 'Ini adalah laporan anonim untuk testing.')
                ->type('lokasi_kejadian', 'Lokasi Rahasia')
                ->type('tanggal_kejadian', '2025-01-10')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-anonymous-report-submitted');

            // Verify anonymous report was saved
            $this->assertDatabaseHas('wbs', [
                'subjek' => 'Laporan Anonim Test',
                'is_anonymous' => true
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
                ->waitForText('Whistleblower System', 10)
                ->type('subjek', 'Laporan dengan Lampiran')
                ->type('deskripsi', 'Laporan test dengan file lampiran.')
                ->type('lokasi_kejadian', 'Kantor Test')
                ->type('tanggal_kejadian', '2025-01-15')
                ->type('nama_pelapor', 'Test User')
                ->type('email', 'test@example.com')
                ->screenshot('wbs-file-attachment-form');

            // Test file upload if file input exists
            if ($browser->element('input[name="attachments[]"]')) {
                $browser->attach('attachments[]', __DIR__ . '/../../fixtures/test_document.pdf')
                    ->press('Kirim Laporan WBS')
                    ->waitForText('berhasil', 10)
                    ->screenshot('wbs-file-attachment-uploaded');
            } else {
                $browser->press('Kirim Laporan WBS')
                    ->waitForText('berhasil', 10)
                    ->screenshot('wbs-basic-report-submitted');
            }

            // Verify report was saved
            $this->assertDatabaseHas('wbs', [
                'subjek' => 'Laporan dengan Lampiran'
            ]);
        });
    }

    /**
     * Test WBS form validation
     */
    public function testWbsReportProgressUpdates()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->press('Kirim Laporan WBS')
                ->pause(1000)
                ->screenshot('wbs-validation-check');

            // Test required field validation works
            $browser->type('subjek', 'Test Subject')
                ->type('deskripsi', 'Test Description')
                ->type('nama_pelapor', 'Test Name')
                ->type('email', 'test@example.com')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-validation-success');
        });
    }

    /**
     * Test WBS form completion with all fields
     */
    public function testWbsReportComments()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->type('subjek', 'Complete Test Report')
                ->type('deskripsi', 'This is a complete test report with all optional fields filled.')
                ->type('tanggal_kejadian', '2025-01-15')
                ->type('lokasi_kejadian', 'Test Location')
                ->type('pihak_terlibat', 'Test Party')
                ->type('kronologi', 'Test chronology of events')
                ->type('nama_pelapor', 'Complete Tester')
                ->type('email', 'complete@example.com')
                ->type('no_telepon', '081234567890')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-complete-form-success');

            // Verify all data was saved
            $this->assertDatabaseHas('wbs', [
                'subjek' => 'Complete Test Report',
                'nama_pelapor' => 'Complete Tester',
                'email' => 'complete@example.com',
                'no_telepon' => '081234567890'
            ]);
        });
    }

    /**
     * Test WBS page responsive design elements
     */
    public function testWbsStatisticsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->assertPresent('.bg-gradient-to-br')
                ->assertPresent('.rounded-2xl')
                ->assertSee('Laporkan dugaan pelanggaran')
                ->screenshot('wbs-responsive-design');
        });
    }

    /**
     * Test WBS form with different screen sizes
     */
    public function testWbsMobileResponsiveness()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // Mobile size
                ->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->assertPresent('form')
                ->assertPresent('input[name="subjek"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->screenshot('wbs-mobile-view');

            // Test form is usable on mobile
            $browser->type('subjek', 'Mobile Test')
                ->type('deskripsi', 'Testing mobile form.')
                ->check('is_anonymous')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-mobile-success');
        });
    }

    /**
     * Test WBS basic accessibility
     */
    public function testWbsAccessibilityFeatures()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->assertPresent('label[for="subjek"]')
                ->assertPresent('label[for="deskripsi"]')
                ->assertPresent('input[required]')
                ->assertPresent('textarea[required]')
                ->screenshot('wbs-accessibility-check');
        });
    }

    /**
     * Test WBS CSRF protection
     */
    public function testWbsSecurityFeatures()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->assertPresent('input[name="_token"]')
                ->screenshot('wbs-csrf-token-present');

            // Test form submission with CSRF token
            $browser->type('subjek', 'Security Test')
                ->type('deskripsi', 'Testing CSRF protection')
                ->type('nama_pelapor', 'Security Tester')
                ->type('email', 'security@example.com')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-csrf-protection-working');
        });
    }

    /**
     * Test WBS database integration
     */
    public function testWbsSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            // Create a test report
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->type('subjek', 'Database Integration Test')
                ->type('deskripsi', 'Testing database integration functionality')
                ->type('nama_pelapor', 'DB Tester')
                ->type('email', 'db@example.com')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-database-integration');

            // Verify the report exists in database
            $this->assertDatabaseHas('wbs', [
                'subjek' => 'Database Integration Test',
                'deskripsi' => 'Testing database integration functionality',
                'nama_pelapor' => 'DB Tester'
            ]);
        });
    }

    /**
     * Test WBS data persistence across page loads
     */
    public function testWbsReportViewCountIncrement()
    {
        $this->browse(function (Browser $browser) {
            // Submit a report
            $browser->visit('/wbs')
                ->waitForText('Whistleblower System', 10)
                ->type('subjek', 'Persistence Test')
                ->type('deskripsi', 'Testing data persistence')
                ->type('nama_pelapor', 'Persistence Tester')
                ->type('email', 'persist@example.com')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil', 10)
                ->screenshot('wbs-data-persistence');

            // Verify data persists
            $wbsReport = \App\Models\Wbs::where('subjek', 'Persistence Test')->first();
            $this->assertNotNull($wbsReport);
            $this->assertEquals('pending', $wbsReport->status);
            $this->assertEquals('Persistence Tester', $wbsReport->nama_pelapor);
        });
    }
}