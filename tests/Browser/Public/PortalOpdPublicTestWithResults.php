<?php

namespace Tests\Browser\Public;

use App\Models\PortalOpd;
use App\Models\PortalOpdDocument;
use App\Models\PortalOpdService;
use App\Models\PortalOpdContact;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Portal OPD Public Test With Results
 * Test Portal OPD public interface with database result verification
 */
class PortalOpdPublicTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestPortalOpdData();
    }

    private function createTestPortalOpdData()
    {
        // Create test Portal OPD data
        $inspektorat = PortalOpd::create([
            'nama_opd' => 'Inspektorat Papua Tengah',
            'singkatan' => 'INSPEKTORAT',
            'alamat' => 'Jl. Soekarno Hatta No. 123, Nabire',
            'telepon' => '0984-421234',
            'email' => 'inspektorat@paputeng.go.id',
            'website' => 'https://inspektorat.paputeng.go.id',
            'deskripsi' => 'Inspektorat Papua Tengah adalah lembaga pengawasan internal pemerintah yang bertugas melakukan pengawasan, pembinaan, dan evaluasi terhadap penyelenggaraan pemerintahan.',
            'visi' => 'Menjadi inspektorat yang profesional, independen, dan terpercaya dalam mendukung terwujudnya pemerintahan yang bersih dan akuntabel.',
            'misi' => 'Melakukan pengawasan yang efektif, Memberikan pembinaan yang berkelanjutan, Meningkatkan kualitas pelayanan publik',
            'struktur_organisasi' => 'struktur_inspektorat.jpg',
            'logo' => 'logo_inspektorat.png',
            'status' => 'active',
            'view_count' => 0,
            'created_at' => now()
        ]);

        $disdik = PortalOpd::create([
            'nama_opd' => 'Dinas Pendidikan Papua Tengah',
            'singkatan' => 'DISDIK',
            'alamat' => 'Jl. Pendidikan No. 456, Nabire',
            'telepon' => '0984-421567',
            'email' => 'disdik@paputeng.go.id',
            'website' => 'https://disdik.paputeng.go.id',
            'deskripsi' => 'Dinas Pendidikan Papua Tengah bertanggung jawab atas penyelenggaraan pendidikan di wilayah Papua Tengah.',
            'visi' => 'Terwujudnya pendidikan yang berkualitas, merata, dan berkarakter.',
            'misi' => 'Meningkatkan kualitas pendidikan, Memperluas akses pendidikan, Mengembangkan karakter siswa',
            'struktur_organisasi' => 'struktur_disdik.jpg',
            'logo' => 'logo_disdik.png',
            'status' => 'active',
            'view_count' => 5,
            'created_at' => now()
        ]);

        $dinkes = PortalOpd::create([
            'nama_opd' => 'Dinas Kesehatan Papua Tengah',
            'singkatan' => 'DINKES',
            'alamat' => 'Jl. Kesehatan No. 789, Nabire',
            'telepon' => '0984-421890',
            'email' => 'dinkes@paputeng.go.id',
            'website' => 'https://dinkes.paputeng.go.id',
            'deskripsi' => 'Dinas Kesehatan Papua Tengah bertugas menyelenggarakan urusan pemerintahan di bidang kesehatan.',
            'visi' => 'Mewujudkan masyarakat Papua Tengah yang sehat, mandiri, dan berkeadilan.',
            'misi' => 'Meningkatkan derajat kesehatan masyarakat, Memberikan pelayanan kesehatan yang berkualitas',
            'struktur_organisasi' => 'struktur_dinkes.jpg',
            'logo' => 'logo_dinkes.png',
            'status' => 'active',
            'view_count' => 10,
            'created_at' => now()
        ]);

        // Create test services
        PortalOpdService::create([
            'opd_id' => $inspektorat->id,
            'nama_layanan' => 'Layanan Pengaduan Masyarakat',
            'deskripsi' => 'Layanan penerimaan dan penanganan pengaduan masyarakat terkait dugaan penyimpangan dalam penyelenggaraan pemerintahan.',
            'persyaratan' => 'KTP, Surat pengaduan, Dokumen pendukung',
            'waktu_pelayanan' => '14 hari kerja',
            'biaya' => 'Gratis',
            'url_form' => 'https://inspektorat.paputeng.go.id/pengaduan',
            'status' => 'active',
            'view_count' => 0
        ]);

        PortalOpdService::create([
            'opd_id' => $disdik->id,
            'nama_layanan' => 'Legalisasi Ijazah',
            'deskripsi' => 'Layanan legalisasi ijazah untuk keperluan administratif.',
            'persyaratan' => 'Ijazah asli, Fotocopy ijazah, KTP',
            'waktu_pelayanan' => '3 hari kerja',
            'biaya' => 'Rp 10.000',
            'url_form' => 'https://disdik.paputeng.go.id/legalisasi',
            'status' => 'active',
            'view_count' => 5
        ]);

        PortalOpdService::create([
            'opd_id' => $dinkes->id,
            'nama_layanan' => 'Surat Keterangan Sehat',
            'deskripsi' => 'Layanan penerbitan surat keterangan sehat untuk berbagai keperluan.',
            'persyaratan' => 'KTP, Foto 3x4, Hasil pemeriksaan kesehatan',
            'waktu_pelayanan' => '1 hari kerja',
            'biaya' => 'Rp 25.000',
            'url_form' => 'https://dinkes.paputeng.go.id/surat-sehat',
            'status' => 'active',
            'view_count' => 8
        ]);

        // Create test documents
        PortalOpdDocument::create([
            'opd_id' => $inspektorat->id,
            'judul' => 'Laporan Hasil Pengawasan 2024',
            'deskripsi' => 'Laporan hasil pengawasan Inspektorat Papua Tengah tahun 2024.',
            'kategori' => 'laporan',
            'file_path' => 'documents/laporan_pengawasan_2024.pdf',
            'file_size' => 2048576,
            'download_count' => 0,
            'status' => 'published'
        ]);

        PortalOpdDocument::create([
            'opd_id' => $disdik->id,
            'judul' => 'Pedoman Penerimaan Siswa Baru 2025',
            'deskripsi' => 'Pedoman teknis penerimaan siswa baru tahun ajaran 2025/2026.',
            'kategori' => 'pedoman',
            'file_path' => 'documents/pedoman_psb_2025.pdf',
            'file_size' => 1024768,
            'download_count' => 15,
            'status' => 'published'
        ]);

        PortalOpdDocument::create([
            'opd_id' => $dinkes->id,
            'judul' => 'Standar Operasional Prosedur Puskesmas',
            'deskripsi' => 'SOP standar pelayanan kesehatan di puskesmas seluruh Papua Tengah.',
            'kategori' => 'sop',
            'file_path' => 'documents/sop_puskesmas.pdf',
            'file_size' => 3072896,
            'download_count' => 25,
            'status' => 'published'
        ]);

        // Create test contacts
        PortalOpdContact::create([
            'opd_id' => $inspektorat->id,
            'nama' => 'Drs. Ahmad Sulaiman, M.Si',
            'jabatan' => 'Inspektur Papua Tengah',
            'telepon' => '0984-421234',
            'email' => 'inspektur@paputeng.go.id',
            'whatsapp' => '081234567890',
            'status' => 'active'
        ]);

        PortalOpdContact::create([
            'opd_id' => $disdik->id,
            'nama' => 'Dr. Siti Nurhaliza, M.Pd',
            'jabatan' => 'Kepala Dinas Pendidikan',
            'telepon' => '0984-421567',
            'email' => 'kadisdik@paputeng.go.id',
            'whatsapp' => '081234567891',
            'status' => 'active'
        ]);

        PortalOpdContact::create([
            'opd_id' => $dinkes->id,
            'nama' => 'dr. Budi Santoso, M.Kes',
            'jabatan' => 'Kepala Dinas Kesehatan',
            'telepon' => '0984-421890',
            'email' => 'kadinkes@paputeng.go.id',
            'whatsapp' => '081234567892',
            'status' => 'active'
        ]);
    }

    /**
     * Test Portal OPD list display
     */
    public function testPortalOpdListDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->assertSee('Portal OPD Papua Tengah')
                ->assertSee('Inspektorat Papua Tengah')
                ->assertSee('Dinas Pendidikan Papua Tengah')
                ->assertSee('Dinas Kesehatan Papua Tengah')
                ->assertPresent('.opd-card')
                ->screenshot('portal-opd-list-display');

            // Verify OPD count
            $browser->assertSee('3 OPD terdaftar');
        });
    }

    /**
     * Test Portal OPD detail view with view count increment
     */
    public function testPortalOpdDetailViewWithViewCount()
    {
        $this->browse(function (Browser $browser) {
            $opdData = PortalOpd::first();
            $initialViewCount = $opdData->view_count;

            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-detail', 10)
                ->assertSee($opdData->nama_opd)
                ->assertSee($opdData->deskripsi)
                ->assertSee($opdData->visi)
                ->assertSee($opdData->misi)
                ->assertSee($opdData->alamat)
                ->assertSee($opdData->telepon)
                ->assertSee($opdData->email)
                ->screenshot('portal-opd-detail-view');

            // Verify view count increased
            $opdData->refresh();
            $this->assertEquals($initialViewCount + 1, $opdData->view_count);
        });
    }

    /**
     * Test Portal OPD search functionality
     */
    public function testPortalOpdSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.search-form', 10)
                ->type('search', 'Inspektorat')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Inspektorat Papua Tengah')
                ->assertDontSee('Dinas Pendidikan Papua Tengah')
                ->assertDontSee('Dinas Kesehatan Papua Tengah')
                ->screenshot('portal-opd-search-functionality');

            // Verify search results count
            $browser->assertSee('1 OPD ditemukan');
        });
    }

    /**
     * Test Portal OPD services display
     */
    public function testPortalOpdServicesDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-services', 10)
                ->assertSee('Layanan OPD')
                ->assertSee('Layanan Pengaduan Masyarakat')
                ->assertSee('14 hari kerja')
                ->assertSee('Gratis')
                ->assertPresent('.service-item')
                ->screenshot('portal-opd-services-display');

            // Test service detail view
            $browser->click('.service-item:first-child')
                ->waitFor('.service-detail', 10)
                ->assertSee('Layanan Pengaduan Masyarakat')
                ->assertSee('KTP, Surat pengaduan, Dokumen pendukung')
                ->screenshot('portal-opd-service-detail');
        });
    }

    /**
     * Test Portal OPD documents display
     */
    public function testPortalOpdDocumentsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-documents', 10)
                ->assertSee('Dokumen OPD')
                ->assertSee('Laporan Hasil Pengawasan 2024')
                ->assertSee('laporan')
                ->assertSee('2.0 MB')
                ->assertPresent('.document-item')
                ->screenshot('portal-opd-documents-display');

            // Test document download
            $browser->click('.download-btn')
                ->waitFor('.download-modal', 10)
                ->assertSee('Download Dokumen')
                ->assertSee('Laporan Hasil Pengawasan 2024')
                ->screenshot('portal-opd-document-download');
        });
    }

    /**
     * Test Portal OPD contact display
     */
    public function testPortalOpdContactDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-contact', 10)
                ->assertSee('Kontak OPD')
                ->assertSee('Drs. Ahmad Sulaiman, M.Si')
                ->assertSee('Inspektur Papua Tengah')
                ->assertSee('0984-421234')
                ->assertSee('inspektur@paputeng.go.id')
                ->assertPresent('.contact-item')
                ->screenshot('portal-opd-contact-display');

            // Test WhatsApp contact
            $browser->click('.whatsapp-btn')
                ->waitFor('.whatsapp-modal', 10)
                ->assertSee('Hubungi via WhatsApp')
                ->screenshot('portal-opd-whatsapp-contact');
        });
    }

    /**
     * Test Portal OPD filter functionality
     */
    public function testPortalOpdFilterFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.filter-form', 10)
                ->select('kategori', 'inspektorat')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Inspektorat Papua Tengah')
                ->assertDontSee('Dinas Pendidikan Papua Tengah')
                ->screenshot('portal-opd-filter-functionality');

            // Test clear filter
            $browser->click('.clear-filter')
                ->waitFor('.opd-list', 10)
                ->assertSee('Inspektorat Papua Tengah')
                ->assertSee('Dinas Pendidikan Papua Tengah')
                ->assertSee('Dinas Kesehatan Papua Tengah')
                ->screenshot('portal-opd-filter-cleared');
        });
    }

    /**
     * Test Portal OPD statistics display
     */
    public function testPortalOpdStatisticsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-statistics', 10)
                ->assertSee('Statistik Portal OPD')
                ->assertSee('Total OPD: 3')
                ->assertSee('Total Layanan: 3')
                ->assertSee('Total Dokumen: 3')
                ->assertSee('Total Kontak: 3')
                ->screenshot('portal-opd-statistics-display');
        });
    }

    /**
     * Test Portal OPD service form access
     */
    public function testPortalOpdServiceFormAccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-services', 10)
                ->click('.service-item:first-child')
                ->waitFor('.service-detail', 10)
                ->click('.access-form-btn')
                ->waitFor('.service-form', 10)
                ->assertSee('Formulir Layanan Pengaduan Masyarakat')
                ->assertPresent('input[name="nama"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('textarea[name="pengaduan"]')
                ->screenshot('portal-opd-service-form-access');

            // Test form submission
            $browser->type('nama', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('pengaduan', 'This is a test complaint.')
                ->press('Submit')
                ->waitForText('Pengaduan berhasil dikirim', 10)
                ->screenshot('portal-opd-service-form-submitted');
        });
    }

    /**
     * Test Portal OPD document search
     */
    public function testPortalOpdDocumentSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-documents', 10)
                ->type('document_search', 'Laporan')
                ->press('Search Documents')
                ->waitFor('.document-search-results', 10)
                ->assertSee('Laporan Hasil Pengawasan 2024')
                ->screenshot('portal-opd-document-search');

            // Test document search with no results
            $browser->type('document_search', 'NonExistentDocument')
                ->press('Search Documents')
                ->waitFor('.no-document-results', 10)
                ->assertSee('Tidak ada dokumen yang ditemukan')
                ->screenshot('portal-opd-document-search-no-results');
        });
    }

    /**
     * Test Portal OPD social media links
     */
    public function testPortalOpdSocialMediaLinks()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-social-media', 10)
                ->assertSee('Media Sosial')
                ->assertPresent('.facebook-link')
                ->assertPresent('.twitter-link')
                ->assertPresent('.instagram-link')
                ->assertPresent('.youtube-link')
                ->screenshot('portal-opd-social-media-links');
        });
    }

    /**
     * Test Portal OPD organization chart
     */
    public function testPortalOpdOrganizationChart()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.org-chart', 10)
                ->assertSee('Struktur Organisasi')
                ->assertPresent('.org-chart-image')
                ->click('.view-full-chart')
                ->waitFor('.org-chart-modal', 10)
                ->assertSee('Struktur Organisasi - Inspektorat Papua Tengah')
                ->screenshot('portal-opd-organization-chart');
        });
    }

    /**
     * Test Portal OPD performance indicators
     */
    public function testPortalOpdPerformanceIndicators()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.performance-indicators', 10)
                ->assertSee('Indikator Kinerja')
                ->assertSee('Jumlah Pengawasan: 25')
                ->assertSee('Tingkat Kepuasan: 95%')
                ->assertSee('Waktu Respons: 2 hari')
                ->screenshot('portal-opd-performance-indicators');
        });
    }

    /**
     * Test Portal OPD news and updates
     */
    public function testPortalOpdNewsAndUpdates()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-news', 10)
                ->assertSee('Berita dan Update')
                ->assertSee('Inspektorat Papua Tengah Luncurkan Program Baru')
                ->assertSee('Workshop Pengawasan Internal')
                ->assertPresent('.news-item')
                ->screenshot('portal-opd-news-and-updates');

            // Test news detail
            $browser->click('.news-item:first-child')
                ->waitFor('.news-detail', 10)
                ->assertSee('Inspektorat Papua Tengah Luncurkan Program Baru')
                ->screenshot('portal-opd-news-detail');
        });
    }

    /**
     * Test Portal OPD feedback form
     */
    public function testPortalOpdFeedbackForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.feedback-form', 10)
                ->assertSee('Berikan Feedback')
                ->type('feedback_name', 'Jane Doe')
                ->type('feedback_email', 'jane@example.com')
                ->select('feedback_rating', '5')
                ->type('feedback_message', 'Excellent service and information provided.')
                ->press('Submit Feedback')
                ->waitForText('Feedback berhasil dikirim', 10)
                ->screenshot('portal-opd-feedback-form');

            // Verify feedback was saved
            $this->assertDatabaseHas('opd_feedback', [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'rating' => '5',
                'message' => 'Excellent service and information provided.'
            ]);
        });
    }

    /**
     * Test Portal OPD pagination
     */
    public function testPortalOpdPagination()
    {
        // Create additional OPD data for pagination testing
        for ($i = 4; $i <= 15; $i++) {
            PortalOpd::create([
                'nama_opd' => "OPD Test $i",
                'singkatan' => "OPD$i",
                'alamat' => "Alamat Test $i",
                'telepon' => "0984-42100$i",
                'email' => "opd$i@paputeng.go.id",
                'website' => "https://opd$i.paputeng.go.id",
                'deskripsi' => "Deskripsi OPD Test $i",
                'visi' => "Visi OPD Test $i",
                'misi' => "Misi OPD Test $i",
                'status' => 'active',
                'view_count' => 0
            ]);
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->assertSee('OPD Test 4')
                ->assertSee('OPD Test 10')
                ->screenshot('portal-opd-pagination-page-1');

            // Test pagination
            $browser->click('.pagination .next')
                ->waitFor('.opd-list', 10)
                ->assertSee('OPD Test 11')
                ->assertSee('OPD Test 15')
                ->screenshot('portal-opd-pagination-page-2');

            // Verify pagination info
            $browser->assertSee('Halaman 2 dari 2');
        });
    }
}