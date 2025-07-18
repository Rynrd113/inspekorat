<?php

namespace Tests\Browser\Integration;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Comprehensive End-to-End Test
 * Complete system workflow testing for Portal Inspektorat Papua Tengah
 */
class ComprehensiveEndToEndTest extends DuskTestCase
{
    /**
     * Test complete citizen journey from public access to service completion
     */
    public function testCompleteCitizenJourney()
    {
        $this->browse(function (Browser $browser) {
            // 1. Citizen discovers service through homepage
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->click('[href="/pelayanan"]')
                ->waitForText('Layanan Kami', 10)
                ->screenshot('e2e-citizen-discover-service');

            // 2. Citizen browses available services
            $browser->assertSee('Surat Keterangan Sehat')
                ->click('[data-service="Surat Keterangan Sehat"]')
                ->waitForText('Detail Layanan', 10)
                ->assertSee('Persyaratan')
                ->assertSee('Biaya')
                ->assertSee('Waktu Proses')
                ->screenshot('e2e-citizen-view-service-detail');

            // 3. Citizen downloads required forms
            $browser->click('[href="/dokumen"]')
                ->waitForText('Dokumen', 10)
                ->type('search', 'formulir surat keterangan sehat')
                ->press('Cari')
                ->waitForText('Formulir Surat Keterangan Sehat', 10)
                ->click('.download-btn')
                ->screenshot('e2e-citizen-download-form');

            // 4. Citizen checks FAQ for additional information
            $browser->visit('/faq')
                ->waitForText('FAQ', 10)
                ->type('search', 'surat keterangan sehat')
                ->press('Cari')
                ->waitForText('Bagaimana cara mendapatkan surat keterangan sehat?', 10)
                ->click('[data-faq="Bagaimana cara mendapatkan surat keterangan sehat?"]')
                ->waitForText('Silakan kunjungi Dinas Kesehatan', 10)
                ->screenshot('e2e-citizen-check-faq');

            // 5. Citizen contacts for additional questions
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami', 10)
                ->type('nama', 'Jane Doe')
                ->type('email', 'jane.doe@example.com')
                ->type('subjek', 'Pertanyaan tentang Surat Keterangan Sehat')
                ->type('pesan', 'Apakah bisa mengurus surat keterangan sehat untuk keluarga?')
                ->press('Kirim')
                ->waitForText('Pesan berhasil dikirim', 10)
                ->screenshot('e2e-citizen-contact-inquiry');

            // 6. Citizen finds OPD location
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->type('search', 'dinas kesehatan')
                ->press('Cari')
                ->waitForText('Dinas Kesehatan Papua Tengah', 10)
                ->click('[data-opd="Dinas Kesehatan Papua Tengah"]')
                ->waitForText('Jl. Kesehatan No. 789', 10)
                ->screenshot('e2e-citizen-find-opd-location');
        });
    }

    /**
     * Test complete admin workflow from login to service management
     */
    public function testCompleteAdminWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. Admin login
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('e2e-admin-login');

            // 2. Admin reviews dashboard and analytics
            $browser->assertSee('Total OPD')
                ->assertSee('Total Pelayanan')
                ->assertSee('Total Dokumen')
                ->assertSee('Total Berita')
                ->click('.view-analytics')
                ->waitForText('Statistik Detail', 10)
                ->screenshot('e2e-admin-view-analytics');

            // 3. Admin manages OPD data
            $browser->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->click('.btn-create')
                ->waitFor('input[name="nama"]', 10)
                ->type('nama', 'Badan Perencanaan Pembangunan Daerah')
                ->type('alamat', 'Jl. Perencanaan No. 456')
                ->type('telepon', '0984-88888')
                ->type('email', 'bappeda@papuatengah.id')
                ->type('website', 'https://bappeda.papuatengah.id')
                ->type('kepala', 'Drs. Ahmad Wijaya')
                ->press('Simpan')
                ->waitForText('Badan Perencanaan Pembangunan Daerah', 10)
                ->screenshot('e2e-admin-create-opd');

            // 4. Admin creates related services
            $browser->visit('/admin/pelayanan')
                ->waitForText('Manajemen Pelayanan', 10)
                ->click('.btn-create')
                ->waitFor('input[name="nama_layanan"]', 10)
                ->type('nama_layanan', 'Konsultasi Perencanaan Pembangunan')
                ->type('deskripsi', 'Layanan konsultasi untuk perencanaan pembangunan daerah')
                ->type('persyaratan', 'Proposal proyek, KTP, surat pengantar')
                ->type('biaya', '100000')
                ->type('waktu_proses', '7 hari kerja')
                ->select('opd_id', 'Badan Perencanaan Pembangunan Daerah')
                ->press('Simpan')
                ->waitForText('Konsultasi Perencanaan Pembangunan', 10)
                ->screenshot('e2e-admin-create-service');

            // 5. Admin uploads supporting documents
            $browser->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Panduan Konsultasi Perencanaan Pembangunan')
                ->type('deskripsi', 'Panduan lengkap untuk konsultasi perencanaan pembangunan')
                ->select('kategori', 'Panduan')
                ->select('pelayanan_id', 'Konsultasi Perencanaan Pembangunan')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-document.pdf')
                ->press('Simpan')
                ->waitForText('Panduan Konsultasi Perencanaan Pembangunan', 10)
                ->screenshot('e2e-admin-upload-document');

            // 6. Admin creates FAQ entries
            $browser->visit('/admin/faq')
                ->waitForText('Manajemen FAQ', 10)
                ->click('.btn-create')
                ->waitFor('input[name="pertanyaan"]', 10)
                ->type('pertanyaan', 'Siapa yang bisa mengajukan konsultasi perencanaan pembangunan?')
                ->type('jawaban', 'Semua pihak yang akan melakukan pembangunan di wilayah Papua Tengah')
                ->select('kategori', 'Perencanaan')
                ->select('pelayanan_id', 'Konsultasi Perencanaan Pembangunan')
                ->type('urutan', '1')
                ->press('Simpan')
                ->waitForText('Siapa yang bisa mengajukan konsultasi perencanaan pembangunan?', 10)
                ->screenshot('e2e-admin-create-faq');

            // 7. Admin publishes news about new service
            $browser->visit('/admin/portal-papua-tengah')
                ->waitForText('Manajemen Berita', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Layanan Konsultasi Perencanaan Pembangunan Telah Tersedia')
                ->type('isi', 'Bappeda Papua Tengah telah membuka layanan konsultasi perencanaan pembangunan untuk mendukung pembangunan daerah.')
                ->select('kategori', 'Layanan')
                ->select('pelayanan_id', 'Konsultasi Perencanaan Pembangunan')
                ->attach('gambar', __DIR__ . '/../../Fixtures/sample-image.jpg')
                ->press('Simpan')
                ->waitForText('Layanan Konsultasi Perencanaan Pembangunan Telah Tersedia', 10)
                ->screenshot('e2e-admin-publish-news');

            // 8. Admin verifies public view
            $browser->visit('/berita')
                ->waitForText('Layanan Konsultasi Perencanaan Pembangunan Telah Tersedia', 10)
                ->click('[data-berita="Layanan Konsultasi Perencanaan Pembangunan Telah Tersedia"]')
                ->waitForText('Bappeda Papua Tengah', 10)
                ->screenshot('e2e-admin-verify-public-news');

            // 9. Admin checks contact messages
            $browser->visit('/admin/kontak')
                ->waitForText('Pesan Kontak', 10)
                ->assertSee('Jane Doe')
                ->assertSee('Pertanyaan tentang Surat Keterangan Sehat')
                ->click('[data-message="Jane Doe"]')
                ->waitForText('Detail Pesan', 10)
                ->type('balasan', 'Terima kasih atas pertanyaan Anda. Surat keterangan sehat bisa diurus untuk keluarga dengan membawa KTP dan KK.')
                ->press('Kirim Balasan')
                ->waitForText('Balasan berhasil dikirim', 10)
                ->screenshot('e2e-admin-reply-contact');

            // 10. Admin manages user accounts
            $browser->visit('/admin/users')
                ->waitForText('Manajemen User', 10)
                ->click('.btn-create')
                ->waitFor('input[name="name"]', 10)
                ->type('name', 'Admin Bappeda')
                ->type('email', 'admin.bappeda@papuatengah.id')
                ->type('password', 'adminbappeda123')
                ->type('password_confirmation', 'adminbappeda123')
                ->select('role', 'Admin Pelayanan')
                ->press('Simpan')
                ->waitForText('Admin Bappeda', 10)
                ->screenshot('e2e-admin-create-user');
        });
    }

    /**
     * Test complete WBS reporting workflow
     */
    public function testCompleteWbsWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. Anonymous user accesses WBS
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System', 10)
                ->assertSee('Laporkan Pelanggaran')
                ->screenshot('e2e-wbs-anonymous-access');

            // 2. Anonymous user submits report
            $browser->type('judul', 'Dugaan Korupsi Proyek Jalan')
                ->type('isi', 'Terdapat dugaan korupsi pada proyek pembangunan jalan di Kecamatan X dengan nilai kerugian sekitar 2 miliar rupiah.')
                ->select('kategori', 'Korupsi')
                ->check('anonim')
                ->press('Kirim Laporan')
                ->waitForText('Laporan berhasil dikirim', 10)
                ->assertSee('Nomor Laporan')
                ->screenshot('e2e-wbs-report-submitted');

            // 3. WBS admin receives and processes report
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.wbs@inspektorat.id')
                ->type('password', 'adminwbs123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/wbs')
                ->waitForText('Manajemen WBS', 10)
                ->assertSee('Dugaan Korupsi Proyek Jalan')
                ->click('[data-report="Dugaan Korupsi Proyek Jalan"]')
                ->waitForText('Detail Laporan', 10)
                ->screenshot('e2e-wbs-admin-view-report');

            // 4. WBS admin updates report status
            $browser->select('status', 'Sedang Diproses')
                ->type('catatan', 'Laporan sedang dalam tahap verifikasi dan investigasi awal')
                ->press('Update Status')
                ->waitForText('Status berhasil diupdate', 10)
                ->screenshot('e2e-wbs-status-updated');

            // 5. SuperAdmin reviews WBS reports
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10)
                ->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/wbs')
                ->waitForText('Manajemen WBS', 10)
                ->assertSee('Dugaan Korupsi Proyek Jalan')
                ->assertSee('Sedang Diproses')
                ->screenshot('e2e-wbs-superadmin-review');

            // 6. SuperAdmin assigns investigation team
            $browser->click('[data-report="Dugaan Korupsi Proyek Jalan"]')
                ->waitForText('Detail Laporan', 10)
                ->select('investigator', 'Tim Investigasi A')
                ->type('catatan', 'Laporan telah ditugaskan ke Tim Investigasi A untuk penanganan lebih lanjut')
                ->select('status', 'Dalam Investigasi')
                ->press('Update Status')
                ->waitForText('Status berhasil diupdate', 10)
                ->screenshot('e2e-wbs-investigation-assigned');

            // 7. Generate WBS report
            $browser->visit('/admin/wbs/reports')
                ->waitForText('Laporan WBS', 10)
                ->select('periode', 'Bulan Ini')
                ->select('kategori', 'Korupsi')
                ->press('Generate Laporan')
                ->waitForText('Laporan berhasil dibuat', 10)
                ->assertSee('Total Laporan: 1')
                ->assertSee('Dalam Investigasi: 1')
                ->screenshot('e2e-wbs-report-generated');
        });
    }

    /**
     * Test complete content management workflow
     */
    public function testCompleteContentManagementWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. Content admin login
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.berita@inspektorat.id')
                ->type('password', 'adminberita123')
                ->press('Login')
                ->waitForText('Dashboard', 10);

            // 2. Create gallery content
            $browser->visit('/admin/galeri')
                ->waitForText('Manajemen Galeri', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Rapat Koordinasi Inspektorat 2024')
                ->type('deskripsi', 'Dokumentasi rapat koordinasi tahunan inspektorat')
                ->select('kategori', 'Kegiatan')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-image.jpg')
                ->press('Simpan')
                ->waitForText('Rapat Koordinasi Inspektorat 2024', 10)
                ->screenshot('e2e-content-gallery-created');

            // 3. Create news with gallery reference
            $browser->visit('/admin/portal-papua-tengah')
                ->waitForText('Manajemen Berita', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Inspektorat Papua Tengah Gelar Rapat Koordinasi Tahunan')
                ->type('isi', 'Inspektorat Papua Tengah telah menggelar rapat koordinasi tahunan untuk membahas program kerja tahun 2024.')
                ->select('kategori', 'Kegiatan')
                ->select('galeri_id', 'Rapat Koordinasi Inspektorat 2024')
                ->attach('gambar', __DIR__ . '/../../Fixtures/sample-image.jpg')
                ->press('Simpan')
                ->waitForText('Inspektorat Papua Tengah Gelar Rapat Koordinasi Tahunan', 10)
                ->screenshot('e2e-content-news-created');

            // 4. Update organization profile
            $browser->visit('/admin/profil')
                ->waitForText('Profil Organisasi', 10)
                ->type('sejarah', 'Inspektorat Papua Tengah didirikan pada tahun 2022 sebagai lembaga pengawas internal pemerintah daerah.')
                ->type('struktur_organisasi', 'Inspektorat dipimpin oleh Inspektur dengan dibantu oleh Sekretaris dan Inspektur Pembantu.')
                ->press('Simpan')
                ->waitForText('Profil berhasil diperbarui', 10)
                ->screenshot('e2e-content-profile-updated');

            // 5. Verify public content display
            $browser->visit('/profil')
                ->waitForText('Profil Organisasi', 10)
                ->assertSee('Inspektorat Papua Tengah didirikan pada tahun 2022')
                ->visit('/berita')
                ->waitForText('Inspektorat Papua Tengah Gelar Rapat Koordinasi Tahunan', 10)
                ->click('[data-berita="Inspektorat Papua Tengah Gelar Rapat Koordinasi Tahunan"]')
                ->waitForText('Galeri Terkait', 10)
                ->visit('/galeri')
                ->waitForText('Rapat Koordinasi Inspektorat 2024', 10)
                ->screenshot('e2e-content-public-display');

            // 6. Content scheduling and publishing
            $browser->visit('/admin/portal-papua-tengah')
                ->waitForText('Manajemen Berita', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Pengumuman Libur Nasional')
                ->type('isi', 'Dalam rangka memperingati hari nasional, kantor inspektorat akan libur.')
                ->select('kategori', 'Pengumuman')
                ->select('status', 'Draft')
                ->input('tanggal_publish', '2024-08-17')
                ->press('Simpan')
                ->waitForText('Pengumuman Libur Nasional', 10)
                ->screenshot('e2e-content-scheduled');

            // 7. Content analytics and performance
            $browser->visit('/admin/analytics')
                ->waitForText('Analitik Konten', 10)
                ->assertSee('Berita Terpopuler')
                ->assertSee('Galeri Terpopuler')
                ->assertSee('Dokumen Terunduh')
                ->screenshot('e2e-content-analytics');
        });
    }

    /**
     * Test complete system backup and maintenance workflow
     */
    public function testCompleteSystemMaintenanceWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. SuperAdmin access system maintenance
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/system')
                ->waitForText('Sistem Maintenance', 10)
                ->screenshot('e2e-system-maintenance-access');

            // 2. System backup
            $browser->click('.backup-btn')
                ->waitForText('Backup Sistem', 10)
                ->check('database')
                ->check('files')
                ->check('configs')
                ->press('Mulai Backup')
                ->waitForText('Backup berhasil', 30)
                ->screenshot('e2e-system-backup-completed');

            // 3. System health check
            $browser->visit('/admin/system/health')
                ->waitForText('System Health Check', 10)
                ->press('Run Health Check')
                ->waitForText('Health Check Completed', 20)
                ->assertSee('Database: OK')
                ->assertSee('Storage: OK')
                ->assertSee('Cache: OK')
                ->screenshot('e2e-system-health-check');

            // 4. System logs review
            $browser->visit('/admin/system/logs')
                ->waitForText('System Logs', 10)
                ->select('log_type', 'Error')
                ->select('date_range', 'Last 7 Days')
                ->press('Filter')
                ->waitForText('Log Entries', 10)
                ->screenshot('e2e-system-logs-review');

            // 5. System configuration update
            $browser->visit('/admin/system/config')
                ->waitForText('System Configuration', 10)
                ->type('site_name', 'Portal Inspektorat Papua Tengah')
                ->type('admin_email', 'admin@inspektorat.papuatengah.id')
                ->select('maintenance_mode', 'Disabled')
                ->press('Simpan Konfigurasi')
                ->waitForText('Konfigurasi berhasil disimpan', 10)
                ->screenshot('e2e-system-config-updated');

            // 6. Database optimization
            $browser->visit('/admin/system/database')
                ->waitForText('Database Management', 10)
                ->press('Optimize Database')
                ->waitForText('Database optimization completed', 20)
                ->press('Clean Cache')
                ->waitForText('Cache cleared successfully', 10)
                ->screenshot('e2e-system-database-optimized');

            // 7. System performance monitoring
            $browser->visit('/admin/system/performance')
                ->waitForText('Performance Monitoring', 10)
                ->assertSee('Server Load')
                ->assertSee('Memory Usage')
                ->assertSee('Database Performance')
                ->assertSee('Response Time')
                ->screenshot('e2e-system-performance-monitoring');
        });
    }

    /**
     * Test complete user onboarding workflow
     */
    public function testCompleteUserOnboardingWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. SuperAdmin creates new user
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/users')
                ->waitForText('Manajemen User', 10)
                ->click('.btn-create')
                ->waitFor('input[name="name"]', 10)
                ->type('name', 'Admin Baru')
                ->type('email', 'admin.baru@inspektorat.id')
                ->type('password', 'adminbaru123')
                ->type('password_confirmation', 'adminbaru123')
                ->select('role', 'Admin Dokumen')
                ->press('Simpan')
                ->waitForText('Admin Baru', 10)
                ->screenshot('e2e-user-created');

            // 2. New user first login
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10)
                ->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.baru@inspektorat.id')
                ->type('password', 'adminbaru123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->screenshot('e2e-user-first-login');

            // 3. User profile completion
            $browser->visit('/profile')
                ->waitForText('Profile', 10)
                ->type('phone', '081234567890')
                ->type('address', 'Jl. Contoh No. 123, Nabire')
                ->attach('avatar', __DIR__ . '/../../Fixtures/sample-avatar.jpg')
                ->press('Update Profile')
                ->waitForText('Profile updated successfully', 10)
                ->screenshot('e2e-user-profile-completed');

            // 4. User accesses permitted modules
            $browser->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->screenshot('e2e-user-access-permitted');

            // 5. User attempts unauthorized access
            $browser->visit('/admin/users')
                ->waitForText('Unauthorized', 10)
                ->screenshot('e2e-user-unauthorized-access');

            // 6. User performs first document upload
            $browser->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'Dokumen Pertama Saya')
                ->type('deskripsi', 'Ini adalah dokumen pertama yang saya upload')
                ->select('kategori', 'Panduan')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-document.pdf')
                ->press('Simpan')
                ->waitForText('Dokumen Pertama Saya', 10)
                ->screenshot('e2e-user-first-document');

            // 7. User receives notification
            $browser->visit('/admin/dashboard')
                ->waitFor('.notification-icon', 10)
                ->click('.notification-icon')
                ->waitFor('.notification-list', 10)
                ->assertSee('Selamat datang')
                ->screenshot('e2e-user-welcome-notification');

            // 8. User training completion
            $browser->visit('/admin/training')
                ->waitForText('Training Materials', 10)
                ->click('.start-training')
                ->waitFor('.training-content', 10)
                ->click('.complete-training')
                ->waitForText('Training completed', 10)
                ->screenshot('e2e-user-training-completed');
        });
    }

    /**
     * Test complete system integration with external services
     */
    public function testCompleteExternalIntegrationWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. Test email service integration
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami', 10)
                ->type('nama', 'External Integration Test')
                ->type('email', 'test@external.com')
                ->type('subjek', 'Test Email Integration')
                ->type('pesan', 'This is a test of email integration functionality')
                ->press('Kirim')
                ->waitForText('Pesan berhasil dikirim', 10)
                ->screenshot('e2e-external-email-sent');

            // 2. Test file storage integration
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'admin.dokumen@inspektorat.id')
                ->type('password', 'admindokumen123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->click('.btn-create')
                ->waitFor('input[name="judul"]', 10)
                ->type('judul', 'External Storage Test')
                ->type('deskripsi', 'Test document for external storage integration')
                ->select('kategori', 'Test')
                ->attach('file', __DIR__ . '/../../Fixtures/sample-document.pdf')
                ->press('Simpan')
                ->waitForText('External Storage Test', 10)
                ->screenshot('e2e-external-storage-upload');

            // 3. Test API integration
            $browser->visit('/api/public/services')
                ->waitFor('body', 10)
                ->screenshot('e2e-external-api-access');

            // 4. Test social media integration
            $browser->visit('/berita')
                ->waitForText('Berita', 10)
                ->click('.share-facebook')
                ->waitFor('.social-share-popup', 10)
                ->screenshot('e2e-external-social-share');

            // 5. Test analytics integration
            $browser->visit('/admin/analytics')
                ->waitForText('Analytics', 10)
                ->assertSee('Page Views')
                ->assertSee('User Sessions')
                ->assertSee('Popular Content')
                ->screenshot('e2e-external-analytics');
        });
    }

    /**
     * Test complete disaster recovery workflow
     */
    public function testCompleteDisasterRecoveryWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. Normal system operation
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('e2e-disaster-normal-operation');

            // 2. System backup before disaster
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/system/backup')
                ->waitForText('System Backup', 10)
                ->press('Create Backup')
                ->waitForText('Backup created successfully', 30)
                ->screenshot('e2e-disaster-backup-created');

            // 3. Simulate system recovery
            $browser->visit('/admin/system/recovery')
                ->waitForText('System Recovery', 10)
                ->select('backup_file', 'Latest Backup')
                ->press('Start Recovery')
                ->waitForText('Recovery completed successfully', 60)
                ->screenshot('e2e-disaster-recovery-completed');

            // 4. Verify system functionality after recovery
            $browser->visit('/admin/dashboard')
                ->waitForText('Dashboard', 10)
                ->assertSee('System Status: Normal')
                ->visit('/admin/portal-opd')
                ->waitForText('Portal OPD', 10)
                ->assertSee('Badan Perencanaan Pembangunan Daerah')
                ->screenshot('e2e-disaster-recovery-verified');

            // 5. Test all critical functions
            $browser->visit('/admin/pelayanan')
                ->waitForText('Manajemen Pelayanan', 10)
                ->visit('/admin/dokumen')
                ->waitForText('Manajemen Dokumen', 10)
                ->visit('/admin/wbs')
                ->waitForText('Manajemen WBS', 10)
                ->screenshot('e2e-disaster-all-functions-working');
        });
    }

    /**
     * Test complete performance optimization workflow
     */
    public function testCompletePerformanceOptimizationWorkflow()
    {
        $this->browse(function (Browser $browser) {
            // 1. Performance baseline measurement
            $startTime = microtime(true);
            
            $browser->visit('/')
                ->waitFor('.homepage-content', 10);
            
            $homeLoadTime = microtime(true) - $startTime;
            
            $browser->screenshot('e2e-performance-baseline');

            // 2. Cache optimization
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/system/cache')
                ->waitForText('Cache Management', 10)
                ->press('Clear All Cache')
                ->waitForText('Cache cleared successfully', 10)
                ->press('Optimize Cache')
                ->waitForText('Cache optimized successfully', 10)
                ->screenshot('e2e-performance-cache-optimized');

            // 3. Database optimization
            $browser->visit('/admin/system/database')
                ->waitForText('Database Management', 10)
                ->press('Optimize Tables')
                ->waitForText('Tables optimized successfully', 30)
                ->press('Update Statistics')
                ->waitForText('Statistics updated successfully', 10)
                ->screenshot('e2e-performance-database-optimized');

            // 4. Performance after optimization
            $browser->visit('/logout')
                ->waitFor('.homepage-content', 10);
            
            $startTime = microtime(true);
            $browser->visit('/')
                ->waitFor('.homepage-content', 10);
            $optimizedLoadTime = microtime(true) - $startTime;
            
            $browser->screenshot('e2e-performance-optimized');

            // 5. Load testing simulation
            for ($i = 0; $i < 10; $i++) {
                $browser->visit('/berita')
                    ->waitFor('body', 10)
                    ->visit('/pelayanan')
                    ->waitFor('body', 10)
                    ->visit('/dokumen')
                    ->waitFor('body', 10);
            }
            
            $browser->screenshot('e2e-performance-load-test');

            // 6. Performance monitoring
            $browser->visit('/login')
                ->waitFor('input[name="email"]', 10)
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->visit('/admin/system/performance')
                ->waitForText('Performance Monitoring', 10)
                ->assertSee('Average Response Time')
                ->assertSee('Memory Usage')
                ->assertSee('Database Queries')
                ->screenshot('e2e-performance-monitoring');

            // Assert performance improvement
            $this->assertLessThan($homeLoadTime, $optimizedLoadTime, 'Performance optimization should improve load times');
        });
    }
}
