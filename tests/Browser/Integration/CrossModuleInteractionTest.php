<?php

namespace Tests\Browser\Integration;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Cross Module Interaction Test
 * Tests interactions between different modules in Portal Inspektorat Papua Tengah
 */
class CrossModuleInteractionTest extends DuskTestCase
{
    /**
     * Test interaction between Portal OPD and Pelayanan modules
     */
    public function testPortalOpdPelayananInteraction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Create OPD data
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->click('.btn-create')
                ->waitFor('input[name="nama"]', 10)
                ->type('nama', 'Dinas Pendidikan Papua Tengah')
                ->type('alamat', 'Jl. Pendidikan No. 123')
                ->type('telepon', '081234567890')
                ->type('email', 'pendidikan@papuatengah.id')
                ->type('website', 'https://pendidikan.papuatengah.id')
                ->type('kepala', 'Dr. John Doe')
                ->press('Simpan')
                ->waitForText('Dinas Pendidikan Papua Tengah', 10)
                ->screenshot('cross-module-opd-created');

            // Create related Pelayanan
            $browser->visit('/admin/pelayanan')
                ->waitForText('Manajemen Pelayanan', 10)
                ->click('.btn-create')
                ->waitFor('input[name="nama_layanan"]', 10)
                ->type('nama_layanan', 'Legalisasi Ijazah')
                ->type('deskripsi', 'Layanan legalisasi ijazah untuk keperluan administrasi')
                ->type('persyaratan', 'Fotokopi ijazah, KTP, dan pas foto')
                ->type('biaya', '50000')
                ->type('waktu_proses', '3 hari kerja')
                ->select('opd_id', 'Dinas Pendidikan Papua Tengah')
                ->press('Simpan')
                ->waitForText('Legalisasi Ijazah', 10)
                ->screenshot('cross-module-pelayanan-created');

            // Verify interaction in public view
            $browser->visit('/portal-opd')
                ->waitForText('Dinas Pendidikan Papua Tengah', 10)
                ->click('[data-opd="Dinas Pendidikan Papua Tengah"]')
                ->waitForText('Layanan Tersedia', 10)
                ->assertSee('Legalisasi Ijazah')
                ->screenshot('cross-module-opd-pelayanan-public');
        });
    }

    /**
     * Test interaction between Berita and Galeri modules
     */
    public function testBeritaGaleriInteraction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.berita@inspektorat.id')
                ->type('password', 'adminberita123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Create Galeri content first
            $browser->visit('/admin/galeri')
                ->waitForText('Manajemen Galeri', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Inspeksi Infrastruktur 2024')
                ->type('deskripsi', 'Dokumentasi inspeksi infrastruktur Papua Tengah')
                ->select('kategori', 'Kegiatan')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-image.jpg')
                ->press('Simpan')
                ->waitForText('Inspeksi Infrastruktur 2024', 10)
                ->screenshot('cross-module-galeri-created');

            // Create Berita with gallery reference
            $browser->visit('/admin/portal-papua-tengah')
                ->waitForText('Manajemen Berita', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Inspektorat Papua Tengah Lakukan Inspeksi Infrastruktur')
                ->type('isi', 'Inspektorat Papua Tengah telah melakukan inspeksi infrastruktur untuk memastikan kualitas pembangunan.')
                ->select('kategori', 'Kegiatan')
                ->select('galeri_id', 'Inspeksi Infrastruktur 2024')
                ->attach('gambar', __DIR__ . '/../../Fixtures/sample-image.jpg')
                ->press('Simpan')
                ->waitForText('Inspektorat Papua Tengah Lakukan Inspeksi Infrastruktur', 10)
                ->screenshot('cross-module-berita-created');

            // Verify interaction in public view
            $browser->visit('/berita')
                ->waitForText('Inspektorat Papua Tengah Lakukan Inspeksi Infrastruktur', 10)
                ->click('[data-berita="Inspektorat Papua Tengah Lakukan Inspeksi Infrastruktur"]')
                ->waitForText('Galeri Terkait', 10)
                ->assertSee('Inspeksi Infrastruktur 2024')
                ->screenshot('cross-module-berita-galeri-public');
        });
    }

    /**
     * Test interaction between Dokumen and FAQ modules
     */
    public function testDokumenFaqInteraction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.dokumen@inspektorat.id')
                ->type('password', 'admindokumen123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Create Document
            $browser->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Panduan Penggunaan Sistem WBS')
                ->type('deskripsi', 'Panduan lengkap penggunaan sistem WBS untuk pelaporan')
                ->select('kategori', 'Panduan')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-document.pdf')
                ->press('Simpan')
                ->waitForText('Panduan Penggunaan Sistem WBS', 10)
                ->screenshot('cross-module-dokumen-created');

            // Login as FAQ admin
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10)
                ->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.faq@inspektorat.id')
                ->type('password', 'adminfaq123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Create FAQ with document reference
            $browser->visit('/admin/faq')
                ->waitForText('Manajemen FAQ', 10)
                ->click('.btn-create')
                ->waitFor('input[name="pertanyaan"]', 10)
                ->type('pertanyaan', 'Bagaimana cara menggunakan sistem WBS?')
                ->type('jawaban', 'Silakan lihat panduan lengkap di dokumen terkait.')
                ->select('kategori', 'WBS')
                ->select('dokumen_id', 'Panduan Penggunaan Sistem WBS')
                ->type('urutan', '1')
                ->press('Simpan')
                ->waitForText('Bagaimana cara menggunakan sistem WBS?', 10)
                ->screenshot('cross-module-faq-created');

            // Verify interaction in public view
            $browser->visit('/faq')
                ->waitForText('Bagaimana cara menggunakan sistem WBS?', 10)
                ->click('[data-faq="Bagaimana cara menggunakan sistem WBS?"]')
                ->waitForText('Dokumen Terkait', 10)
                ->assertSee('Panduan Penggunaan Sistem WBS')
                ->click('[data-document="Panduan Penggunaan Sistem WBS"]')
                ->waitForText('Download', 10)
                ->screenshot('cross-module-faq-dokumen-public');
        });
    }

    /**
     * Test interaction between WBS and User Management modules
     */
    public function testWbsUserManagementInteraction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Create a new WBS admin user
            $browser->visit('/admin/users')
                ->waitForText('Manajemen User', 10)
                ->click('.btn-create')
                ->waitFor('input[name="name"]', 10)
                ->type('name', 'Admin WBS Baru')
                ->type('email', 'admin.wbs.baru@inspektorat.id')
                ->type('password', 'adminwbsbaru123')
                ->type('password_confirmation', 'adminwbsbaru123')
                ->select('role', 'Admin WBS')
                ->press('Simpan')
                ->waitForText('Admin WBS Baru', 10)
                ->screenshot('cross-module-user-created');

            // Logout and login as new WBS admin
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10)
                ->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.wbs.baru@inspektorat.id')
                ->type('password', 'adminwbsbaru123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test WBS access
            $browser->visit('/admin/wbs')
                ->waitForText('Manajemen WBS', 10)
                ->screenshot('cross-module-wbs-access');

            // Create WBS report
            $browser->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Laporan Penyalahgunaan Anggaran')
                ->type('isi', 'Terdapat indikasi penyalahgunaan anggaran di unit kerja X')
                ->select('kategori', 'Penyalahgunaan Anggaran')
                ->press('Simpan')
                ->waitForText('Laporan Penyalahgunaan Anggaran', 10)
                ->screenshot('cross-module-wbs-report-created');

            // Verify report tracking
            $browser->visit('/admin/wbs')
                ->waitForText('Laporan Penyalahgunaan Anggaran', 10)
                ->click('[data-report="Laporan Penyalahgunaan Anggaran"]')
                ->waitForText('Detail Laporan', 10)
                ->assertSee('Admin WBS Baru')
                ->screenshot('cross-module-wbs-user-tracking');
        });
    }

    /**
     * Test interaction between Profil and Kontak modules
     */
    public function testProfilKontakInteraction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.profil@inspektorat.id')
                ->type('password', 'adminprofil123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Update organization profile
            $browser->visit('/admin/profil')
                ->waitForText('Profil Organisasi', 10)
                ->type('nama_organisasi', 'Inspektorat Papua Tengah')
                ->type('alamat', 'Jl. Kemerdekaan No. 45, Nabire')
                ->type('telepon', '0984-21234')
                ->type('email', 'inspektorat@papuatengah.id')
                ->type('website', 'https://inspektorat.papuatengah.id')
                ->type('visi', 'Menjadi inspektorat yang profesional dan berintegritas')
                ->type('misi', 'Melaksanakan pengawasan yang efektif dan efisien')
                ->press('Simpan')
                ->waitForText('Profil berhasil diperbarui', 10)
                ->screenshot('cross-module-profil-updated');

            // Verify contact information integration
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami', 10)
                ->assertSee('Inspektorat Papua Tengah')
                ->assertSee('Jl. Kemerdekaan No. 45, Nabire')
                ->assertSee('0984-21234')
                ->assertSee('inspektorat@papuatengah.id')
                ->screenshot('cross-module-kontak-integration');

            // Test contact form submission
            $browser->type('nama', 'John Doe')
                ->type('email', 'john.doe@example.com')
                ->type('subjek', 'Pertanyaan tentang layanan')
                ->type('pesan', 'Saya ingin bertanya tentang layanan inspektorat')
                ->press('Kirim')
                ->waitForText('Pesan berhasil dikirim', 10)
                ->screenshot('cross-module-kontak-form-sent');

            // Check received message in admin
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/kontak')
                ->waitForText('Pesan Kontak', 10)
                ->assertSee('John Doe')
                ->assertSee('Pertanyaan tentang layanan')
                ->screenshot('cross-module-kontak-admin-view');
        });
    }

    /**
     * Test interaction between multiple modules in data flow
     */
    public function testMultiModuleDataFlow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Create OPD
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->click('.btn-create')
                ->waitFor('input[name="nama"]', 10)
                ->type('nama', 'Dinas Kesehatan Papua Tengah')
                ->type('alamat', 'Jl. Kesehatan No. 789')
                ->type('telepon', '0984-55555')
                ->type('email', 'kesehatan@papuatengah.id')
                ->press('Simpan')
                ->waitForText('Dinas Kesehatan Papua Tengah', 10);

            // Create Service related to OPD
            $browser->visit('/admin/pelayanan')
                ->waitForText('Manajemen Pelayanan', 10)
                ->click('.btn-create')
                ->waitFor('input[name="nama_layanan"]', 10)
                ->type('nama_layanan', 'Surat Keterangan Sehat')
                ->type('deskripsi', 'Penerbitan surat keterangan sehat')
                ->type('persyaratan', 'KTP, pas foto, biaya administrasi')
                ->type('biaya', '25000')
                ->type('waktu_proses', '1 hari kerja')
                ->select('opd_id', 'Dinas Kesehatan Papua Tengah')
                ->press('Simpan')
                ->waitForText('Surat Keterangan Sehat', 10);

            // Create Document related to service
            $browser->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Formulir Surat Keterangan Sehat')
                ->type('deskripsi', 'Formulir yang harus diisi untuk surat keterangan sehat')
                ->select('kategori', 'Formulir')
                ->select('pelayanan_id', 'Surat Keterangan Sehat')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-document.pdf')
                ->press('Simpan')
                ->waitForText('Formulir Surat Keterangan Sehat', 10);

            // Create FAQ related to service
            $browser->visit('/admin/faq')
                ->waitForText('Manajemen FAQ', 10)
                ->click('.btn-create')
                ->waitFor('input[name="pertanyaan"]', 10)
                ->type('pertanyaan', 'Bagaimana cara mendapatkan surat keterangan sehat?')
                ->type('jawaban', 'Silakan kunjungi Dinas Kesehatan dengan membawa persyaratan yang diperlukan.')
                ->select('kategori', 'Pelayanan')
                ->select('pelayanan_id', 'Surat Keterangan Sehat')
                ->type('urutan', '1')
                ->press('Simpan')
                ->waitForText('Bagaimana cara mendapatkan surat keterangan sehat?', 10);

            // Create news about the service
            $browser->visit('/admin/portal-papua-tengah')
                ->waitForText('Manajemen Berita', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Layanan Surat Keterangan Sehat Kini Lebih Mudah')
                ->type('isi', 'Dinas Kesehatan Papua Tengah telah mempercepat proses penerbitan surat keterangan sehat.')
                ->select('kategori', 'Layanan')
                ->select('pelayanan_id', 'Surat Keterangan Sehat')
                ->press('Simpan')
                ->waitForText('Layanan Surat Keterangan Sehat Kini Lebih Mudah', 10);

            // Verify complete data flow in public view
            $browser->visit('/portal-opd')
                ->waitForText('Dinas Kesehatan Papua Tengah', 10)
                ->click('[data-opd="Dinas Kesehatan Papua Tengah"]')
                ->waitForText('Layanan Tersedia', 10)
                ->assertSee('Surat Keterangan Sehat')
                ->click('[data-service="Surat Keterangan Sehat"]')
                ->waitForText('Detail Layanan', 10)
                ->assertSee('Formulir Surat Keterangan Sehat')
                ->assertSee('FAQ Terkait')
                ->assertSee('Berita Terkait')
                ->screenshot('cross-module-complete-data-flow');
        });
    }

    /**
     * Test search functionality across modules
     */
    public function testCrossModuleSearch()
    {
        $this->browse(function (Browser $browser) {
            // Test public search
            $browser->visit('/')
                ->waitFor('.search-form', 10)
                ->type('search', 'kesehatan')
                ->press('Cari')
                ->waitForText('Hasil Pencarian', 10)
                ->assertSee('Dinas Kesehatan Papua Tengah')
                ->assertSee('Surat Keterangan Sehat')
                ->assertSee('Formulir Surat Keterangan Sehat')
                ->screenshot('cross-module-search-results');

            // Test category filtering
            $browser->select('kategori', 'Pelayanan')
                ->press('Filter')
                ->waitForText('Surat Keterangan Sehat', 10)
                ->screenshot('cross-module-search-filtered');

            // Test search by location
            $browser->type('search', 'Nabire')
                ->press('Cari')
                ->waitForText('Inspektorat Papua Tengah', 10)
                ->screenshot('cross-module-search-location');
        });
    }

    /**
     * Test notification system across modules
     */
    public function testCrossModuleNotifications()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Create WBS report (triggers notification)
            $browser->visit('/wbs')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Laporan Dugaan Korupsi')
                ->type('isi', 'Terdapat dugaan korupsi di proyek infrastruktur')
                ->select('kategori', 'Korupsi')
                ->press('Kirim Laporan')
                ->waitForText('Laporan berhasil dikirim', 10)
                ->screenshot('cross-module-wbs-report-sent');

            // Check notification in admin
            $browser->visit('/admin/dashboard')
                ->waitFor('.notification-icon', 10)
                ->click('.notification-icon')
                ->waitFor('.notification-list', 10)
                ->assertSee('Laporan WBS baru')
                ->screenshot('cross-module-notification-received');

            // Check WBS admin notification
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10)
                ->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.wbs@inspektorat.id')
                ->type('password', 'adminwbs123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/wbs')
                ->waitFor('.notification-badge', 10)
                ->assertSee('1')
                ->screenshot('cross-module-wbs-admin-notification');
        });
    }

    /**
     * Test reporting and analytics across modules
     */
    public function testCrossModuleReporting()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test dashboard analytics
            $browser->visit('/admin/dashboard')
                ->waitForText('Dashboard', 10)
                ->assertSee('Total OPD')
                ->assertSee('Total Pelayanan')
                ->assertSee('Total Dokumen')
                ->assertSee('Total Berita')
                ->assertSee('Total FAQ')
                ->assertSee('Total Laporan WBS')
                ->screenshot('cross-module-dashboard-analytics');

            // Test reports page
            $browser->visit('/admin/reports')
                ->waitForText('Laporan', 10)
                ->select('module', 'Semua Module')
                ->select('period', 'Bulan Ini')
                ->press('Generate Laporan')
                ->waitForText('Laporan berhasil dibuat', 10)
                ->screenshot('cross-module-comprehensive-report');
        });
    }

    /**
     * Test data export/import across modules
     */
    public function testCrossModuleDataExportImport()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // Test data export
            $browser->visit('/admin/export')
                ->waitForText('Export Data', 10)
                ->check('opd')
                ->check('pelayanan')
                ->check('dokumen')
                ->select('format', 'Excel')
                ->press('Export')
                ->waitForText('Export berhasil', 10)
                ->screenshot('cross-module-data-export');

            // Test data import
            $browser->visit('/admin/import')
                ->waitForText('Import Data', 10)
                ->select('module', 'Portal OPD')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-import.xlsx')
                ->press('Import')
                ->waitForText('Import berhasil', 10)
                ->screenshot('cross-module-data-import');
        });
    }
}
