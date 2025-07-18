<?php

namespace Tests\Browser\Public;

use App\Models\Pelayanan;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ServicesTestWithResults extends DuskTestCase
{
    /**
     * Test public services page functionality with comprehensive results validation
     */
    public function testPublicServicesPageWithResults(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services')
                ->waitFor('.services-container', 10)
                ->assertSee('Layanan Inspektorat')
                ->assertSee('Daftar Layanan')
                ->screenshot('services-page-loaded');

            // Test service search functionality
            $browser->type('input[name="search"]', 'audit')
                ->press('Cari')
                ->waitFor('.service-results', 5)
                ->assertSee('Hasil Pencarian')
                ->screenshot('services-search-results');

            // Test service category filter
            $browser->select('select[name="category"]', 'audit')
                ->waitFor('.filtered-services', 5)
                ->assertSee('Layanan Audit')
                ->screenshot('services-category-filter');

            // Test service detail view
            $browser->click('.service-item:first-child .btn-detail')
                ->waitFor('.service-detail-modal', 5)
                ->assertSee('Detail Layanan')
                ->assertSee('Persyaratan')
                ->assertSee('Prosedur')
                ->assertSee('Waktu Pelayanan')
                ->screenshot('service-detail-modal');

            // Test service request form
            $browser->click('.btn-request-service')
                ->waitFor('#serviceRequestForm', 5)
                ->type('input[name="name"]', 'John Doe')
                ->type('input[name="email"]', 'john@example.com')
                ->type('input[name="phone"]', '081234567890')
                ->type('textarea[name="description"]', 'Permohonan layanan audit internal')
                ->attach('input[name="document"]', storage_path('app/test-files/sample.pdf'))
                ->press('Kirim Permohonan')
                ->waitFor('.success-message', 10)
                ->assertSee('Permohonan berhasil dikirim')
                ->screenshot('service-request-success');

            // Test service download functionality
            $browser->click('.btn-download-template')
                ->waitFor('.download-confirmation', 5)
                ->assertSee('Template berhasil diunduh')
                ->screenshot('service-download-template');

            // Test service FAQ section
            $browser->click('.service-faq-toggle')
                ->waitFor('.faq-content', 5)
                ->assertSee('Pertanyaan Umum')
                ->click('.faq-item:first-child')
                ->waitFor('.faq-answer', 3)
                ->assertSee('Jawaban')
                ->screenshot('service-faq-expanded');

            // Test service feedback form
            $browser->scrollTo('.service-feedback')
                ->type('textarea[name="feedback"]', 'Layanan sangat memuaskan')
                ->select('select[name="rating"]', '5')
                ->press('Kirim Feedback')
                ->waitFor('.feedback-success', 5)
                ->assertSee('Feedback berhasil dikirim')
                ->screenshot('service-feedback-success');

            // Test service contact information
            $browser->click('.service-contact-info')
                ->waitFor('.contact-details', 5)
                ->assertSee('Informasi Kontak')
                ->assertSee('Telepon')
                ->assertSee('Email')
                ->assertSee('Alamat')
                ->screenshot('service-contact-info');

            // Test service working hours
            $browser->click('.service-hours')
                ->waitFor('.working-hours', 5)
                ->assertSee('Jam Pelayanan')
                ->assertSee('Senin - Jumat')
                ->assertSee('08:00 - 16:00')
                ->screenshot('service-working-hours');

            // Test service social media integration
            $browser->click('.service-social-media')
                ->waitFor('.social-links', 5)
                ->assertSee('Media Sosial')
                ->assertPresent('.social-facebook')
                ->assertPresent('.social-twitter')
                ->assertPresent('.social-instagram')
                ->screenshot('service-social-media');
        });
    }

    /**
     * Test service analytics and tracking
     */
    public function testServiceAnalyticsWithResults(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services/analytics')
                ->waitFor('.analytics-dashboard', 10)
                ->assertSee('Analitik Layanan')
                ->assertSee('Statistik Penggunaan')
                ->screenshot('service-analytics-dashboard');

            // Test service usage statistics
            $browser->click('.usage-stats-tab')
                ->waitFor('.usage-charts', 5)
                ->assertSee('Grafik Penggunaan')
                ->assertPresent('.chart-container')
                ->screenshot('service-usage-statistics');

            // Test service performance metrics
            $browser->click('.performance-metrics-tab')
                ->waitFor('.performance-data', 5)
                ->assertSee('Metrik Kinerja')
                ->assertSee('Waktu Respons')
                ->assertSee('Tingkat Kepuasan')
                ->screenshot('service-performance-metrics');

            // Test service report generation
            $browser->click('.generate-report-btn')
                ->waitFor('.report-options', 5)
                ->select('select[name="report_type"]', 'monthly')
                ->click('input[name="include_charts"]')
                ->press('Generate Report')
                ->waitFor('.report-generated', 10)
                ->assertSee('Laporan berhasil dibuat')
                ->screenshot('service-report-generated');

            // Test service export functionality
            $browser->click('.export-data-btn')
                ->waitFor('.export-options', 5)
                ->select('select[name="format"]', 'excel')
                ->press('Export Data')
                ->waitFor('.export-success', 10)
                ->assertSee('Data berhasil diekspor')
                ->screenshot('service-data-exported');
        });
    }

    /**
     * Test service mobile responsiveness
     */
    public function testServiceMobileResponsiveness(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->visit('/services')
                ->waitFor('.services-container', 10)
                ->assertSee('Layanan')
                ->screenshot('services-mobile-view');

            // Test mobile navigation
            $browser->click('.mobile-menu-toggle')
                ->waitFor('.mobile-menu', 5)
                ->assertSee('Menu Layanan')
                ->click('.mobile-menu-close')
                ->waitUntilMissing('.mobile-menu', 5)
                ->screenshot('services-mobile-menu');

            // Test mobile search
            $browser->click('.mobile-search-toggle')
                ->waitFor('.mobile-search', 5)
                ->type('input[name="mobile_search"]', 'audit')
                ->press('Cari')
                ->waitFor('.mobile-search-results', 5)
                ->assertSee('Hasil Pencarian')
                ->screenshot('services-mobile-search');

            // Test mobile service cards
            $browser->click('.service-card:first-child')
                ->waitFor('.mobile-service-detail', 5)
                ->assertSee('Detail Layanan')
                ->swipe('.service-detail-content', 'up')
                ->assertSee('Persyaratan')
                ->screenshot('services-mobile-detail');

            // Test mobile service request
            $browser->click('.mobile-request-btn')
                ->waitFor('.mobile-request-form', 5)
                ->type('input[name="name"]', 'Mobile User')
                ->type('input[name="email"]', 'mobile@example.com')
                ->scrollTo('.mobile-submit-btn')
                ->press('Kirim')
                ->waitFor('.mobile-success', 10)
                ->assertSee('Berhasil')
                ->screenshot('services-mobile-request');

            // Reset to desktop view
            $browser->resize(1920, 1080);
        });
    }

    /**
     * Test service accessibility features
     */
    public function testServiceAccessibilityFeatures(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services?accessibility=true')
                ->waitFor('.accessibility-tools', 10)
                ->assertSee('Aksesibilitas')
                ->screenshot('services-accessibility-enabled');

            // Test high contrast mode
            $browser->click('.high-contrast-toggle')
                ->waitFor('.high-contrast-mode', 5)
                ->assertPresent('.high-contrast-mode')
                ->screenshot('services-high-contrast');

            // Test font size adjustment
            $browser->click('.font-size-increase')
                ->waitFor('.large-font', 3)
                ->assertPresent('.large-font')
                ->click('.font-size-decrease')
                ->waitUntilMissing('.large-font', 3)
                ->screenshot('services-font-adjustment');

            // Test keyboard navigation
            $browser->keys('body', ['{tab}', '{tab}', '{enter}'])
                ->waitFor('.keyboard-focus', 5)
                ->assertPresent('.keyboard-focus')
                ->screenshot('services-keyboard-navigation');

            // Test screen reader compatibility
            $browser->click('.screen-reader-toggle')
                ->waitFor('.screen-reader-mode', 5)
                ->assertPresent('.screen-reader-mode')
                ->assertAttribute('.service-item:first-child', 'aria-label', 'Layanan Audit Internal')
                ->screenshot('services-screen-reader');
        });
    }

    /**
     * Test service integration with external systems
     */
    public function testServiceExternalIntegration(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services/integration')
                ->waitFor('.integration-dashboard', 10)
                ->assertSee('Integrasi Sistem')
                ->screenshot('services-integration-dashboard');

            // Test API integration status
            $browser->click('.api-status-tab')
                ->waitFor('.api-status-list', 5)
                ->assertSee('Status API')
                ->assertPresent('.api-status-active')
                ->screenshot('services-api-status');

            // Test webhook configuration
            $browser->click('.webhook-config-tab')
                ->waitFor('.webhook-settings', 5)
                ->assertSee('Konfigurasi Webhook')
                ->type('input[name="webhook_url"]', 'https://example.com/webhook')
                ->press('Test Webhook')
                ->waitFor('.webhook-test-result', 10)
                ->assertSee('Webhook berhasil ditest')
                ->screenshot('services-webhook-config');

            // Test third-party service integration
            $browser->click('.third-party-tab')
                ->waitFor('.third-party-services', 5)
                ->assertSee('Layanan Pihak Ketiga')
                ->click('.connect-service-btn')
                ->waitFor('.service-connection-modal', 5)
                ->assertSee('Koneksi Layanan')
                ->screenshot('services-third-party-integration');

            // Test data synchronization
            $browser->click('.sync-data-btn')
                ->waitFor('.sync-progress', 5)
                ->assertSee('Sinkronisasi Data')
                ->waitFor('.sync-complete', 30)
                ->assertSee('Sinkronisasi selesai')
                ->screenshot('services-data-sync');
        });
    }

    /**
     * Test service notification system
     */
    public function testServiceNotificationSystem(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services/notifications')
                ->waitFor('.notification-center', 10)
                ->assertSee('Pusat Notifikasi')
                ->screenshot('services-notification-center');

            // Test notification preferences
            $browser->click('.notification-preferences')
                ->waitFor('.preference-settings', 5)
                ->assertSee('Pengaturan Notifikasi')
                ->click('input[name="email_notifications"]')
                ->click('input[name="sms_notifications"]')
                ->press('Simpan Pengaturan')
                ->waitFor('.settings-saved', 5)
                ->assertSee('Pengaturan berhasil disimpan')
                ->screenshot('services-notification-preferences');

            // Test notification history
            $browser->click('.notification-history-tab')
                ->waitFor('.notification-list', 5)
                ->assertSee('Riwayat Notifikasi')
                ->click('.notification-item:first-child')
                ->waitFor('.notification-detail', 5)
                ->assertSee('Detail Notifikasi')
                ->screenshot('services-notification-history');

            // Test notification templates
            $browser->click('.notification-templates-tab')
                ->waitFor('.template-list', 5)
                ->assertSee('Template Notifikasi')
                ->click('.create-template-btn')
                ->waitFor('.template-form', 5)
                ->type('input[name="template_name"]', 'New Service Template')
                ->type('textarea[name="template_content"]', 'Layanan baru telah tersedia')
                ->press('Buat Template')
                ->waitFor('.template-created', 10)
                ->assertSee('Template berhasil dibuat')
                ->screenshot('services-notification-templates');

            // Test notification broadcast
            $browser->click('.broadcast-notification-btn')
                ->waitFor('.broadcast-form', 5)
                ->assertSee('Kirim Notifikasi')
                ->select('select[name="notification_type"]', 'service_update')
                ->type('textarea[name="message"]', 'Pembaruan layanan tersedia')
                ->press('Kirim Notifikasi')
                ->waitFor('.broadcast-success', 10)
                ->assertSee('Notifikasi berhasil dikirim')
                ->screenshot('services-notification-broadcast');
        });
    }

    /**
     * Test service backup and recovery
     */
    public function testServiceBackupRecovery(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services/backup')
                ->waitFor('.backup-dashboard', 10)
                ->assertSee('Backup dan Recovery')
                ->screenshot('services-backup-dashboard');

            // Test manual backup
            $browser->click('.manual-backup-btn')
                ->waitFor('.backup-options', 5)
                ->assertSee('Opsi Backup')
                ->click('input[name="include_files"]')
                ->click('input[name="include_database"]')
                ->press('Buat Backup')
                ->waitFor('.backup-progress', 5)
                ->assertSee('Backup sedang berlangsung')
                ->waitFor('.backup-complete', 60)
                ->assertSee('Backup selesai')
                ->screenshot('services-manual-backup');

            // Test backup schedule
            $browser->click('.schedule-backup-tab')
                ->waitFor('.schedule-form', 5)
                ->assertSee('Jadwal Backup')
                ->select('select[name="frequency"]', 'daily')
                ->type('input[name="backup_time"]', '02:00')
                ->press('Atur Jadwal')
                ->waitFor('.schedule-saved', 10)
                ->assertSee('Jadwal berhasil diatur')
                ->screenshot('services-backup-schedule');

            // Test backup history
            $browser->click('.backup-history-tab')
                ->waitFor('.backup-list', 5)
                ->assertSee('Riwayat Backup')
                ->click('.backup-item:first-child .restore-btn')
                ->waitFor('.restore-confirmation', 5)
                ->assertSee('Konfirmasi Restore')
                ->press('Ya, Restore')
                ->waitFor('.restore-progress', 5)
                ->assertSee('Restore sedang berlangsung')
                ->screenshot('services-backup-restore');

            // Test backup verification
            $browser->click('.verify-backup-btn')
                ->waitFor('.verification-result', 30)
                ->assertSee('Verifikasi Backup')
                ->assertSee('Backup valid')
                ->screenshot('services-backup-verification');
        });
    }

    /**
     * Test service monitoring and alerts
     */
    public function testServiceMonitoringAlerts(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services/monitoring')
                ->waitFor('.monitoring-dashboard', 10)
                ->assertSee('Monitoring Layanan')
                ->screenshot('services-monitoring-dashboard');

            // Test service health check
            $browser->click('.health-check-btn')
                ->waitFor('.health-status', 10)
                ->assertSee('Status Kesehatan')
                ->assertPresent('.status-healthy')
                ->screenshot('services-health-check');

            // Test alert configuration
            $browser->click('.alert-config-tab')
                ->waitFor('.alert-settings', 5)
                ->assertSee('Konfigurasi Alert')
                ->type('input[name="cpu_threshold"]', '80')
                ->type('input[name="memory_threshold"]', '85')
                ->type('input[name="disk_threshold"]', '90')
                ->press('Simpan Konfigurasi')
                ->waitFor('.config-saved', 10)
                ->assertSee('Konfigurasi berhasil disimpan')
                ->screenshot('services-alert-config');

            // Test monitoring logs
            $browser->click('.monitoring-logs-tab')
                ->waitFor('.log-viewer', 5)
                ->assertSee('Log Monitoring')
                ->select('select[name="log_level"]', 'error')
                ->press('Filter Log')
                ->waitFor('.filtered-logs', 5)
                ->assertSee('Error Log')
                ->screenshot('services-monitoring-logs');

            // Test performance metrics
            $browser->click('.performance-tab')
                ->waitFor('.performance-charts', 5)
                ->assertSee('Metrik Performa')
                ->assertPresent('.cpu-chart')
                ->assertPresent('.memory-chart')
                ->assertPresent('.disk-chart')
                ->screenshot('services-performance-metrics');

            // Test alert history
            $browser->click('.alert-history-tab')
                ->waitFor('.alert-list', 5)
                ->assertSee('Riwayat Alert')
                ->click('.alert-item:first-child')
                ->waitFor('.alert-detail', 5)
                ->assertSee('Detail Alert')
                ->screenshot('services-alert-history');
        });
    }

    /**
     * Test service security features
     */
    public function testServiceSecurityFeatures(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/services/security')
                ->waitFor('.security-dashboard', 10)
                ->assertSee('Keamanan Layanan')
                ->screenshot('services-security-dashboard');

            // Test security audit
            $browser->click('.security-audit-btn')
                ->waitFor('.audit-progress', 5)
                ->assertSee('Audit Keamanan')
                ->waitFor('.audit-results', 30)
                ->assertSee('Hasil Audit')
                ->screenshot('services-security-audit');

            // Test vulnerability scan
            $browser->click('.vulnerability-scan-tab')
                ->waitFor('.scan-options', 5)
                ->assertSee('Scan Kerentanan')
                ->click('input[name="deep_scan"]')
                ->press('Mulai Scan')
                ->waitFor('.scan-progress', 5)
                ->assertSee('Scan sedang berlangsung')
                ->screenshot('services-vulnerability-scan');

            // Test security policies
            $browser->click('.security-policies-tab')
                ->waitFor('.policy-list', 5)
                ->assertSee('Kebijakan Keamanan')
                ->click('.create-policy-btn')
                ->waitFor('.policy-form', 5)
                ->type('input[name="policy_name"]', 'Service Access Policy')
                ->type('textarea[name="policy_description"]', 'Kebijakan akses layanan')
                ->press('Buat Kebijakan')
                ->waitFor('.policy-created', 10)
                ->assertSee('Kebijakan berhasil dibuat')
                ->screenshot('services-security-policies');

            // Test access control
            $browser->click('.access-control-tab')
                ->waitFor('.access-settings', 5)
                ->assertSee('Kontrol Akses')
                ->click('input[name="require_2fa"]')
                ->click('input[name="ip_whitelist"]')
                ->press('Simpan Pengaturan')
                ->waitFor('.settings-saved', 10)
                ->assertSee('Pengaturan berhasil disimpan')
                ->screenshot('services-access-control');

            // Test security reports
            $browser->click('.security-reports-tab')
                ->waitFor('.report-generator', 5)
                ->assertSee('Laporan Keamanan')
                ->select('select[name="report_type"]', 'comprehensive')
                ->select('select[name="time_period"]', 'last_30_days')
                ->press('Generate Report')
                ->waitFor('.report-generated', 30)
                ->assertSee('Laporan berhasil dibuat')
                ->screenshot('services-security-reports');
        });
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->createTestServices();
    }

    protected function createTestServices(): void
    {
        // Create sample services for testing
        Pelayanan::factory()->create([
            'nama' => 'Audit Internal',
            'deskripsi' => 'Layanan audit internal untuk instansi pemerintah',
            'kategori' => 'audit',
            'status' => 'aktif',
            'persyaratan' => 'Surat permohonan, dokumen pendukung',
            'prosedur' => 'Pengajuan -> Verifikasi -> Pelaksanaan -> Laporan',
            'waktu_pelayanan' => '14 hari kerja',
            'biaya' => 'Gratis',
            'penanggung_jawab' => 'Tim Audit Internal'
        ]);

        Pelayanan::factory()->create([
            'nama' => 'Konsultasi Manajemen',
            'deskripsi' => 'Layanan konsultasi manajemen dan tata kelola',
            'kategori' => 'konsultasi',
            'status' => 'aktif',
            'persyaratan' => 'Surat permohonan, data organisasi',
            'prosedur' => 'Pengajuan -> Analisis -> Konsultasi -> Rekomendasi',
            'waktu_pelayanan' => '7 hari kerja',
            'biaya' => 'Gratis',
            'penanggung_jawab' => 'Tim Konsultan'
        ]);

        Pelayanan::factory()->create([
            'nama' => 'Pengawasan Keuangan',
            'deskripsi' => 'Layanan pengawasan pengelolaan keuangan daerah',
            'kategori' => 'pengawasan',
            'status' => 'aktif',
            'persyaratan' => 'Laporan keuangan, dokumen anggaran',
            'prosedur' => 'Pengajuan -> Review -> Pengawasan -> Laporan',
            'waktu_pelayanan' => '21 hari kerja',
            'biaya' => 'Gratis',
            'penanggung_jawab' => 'Tim Pengawasan'
        ]);
    }
}
