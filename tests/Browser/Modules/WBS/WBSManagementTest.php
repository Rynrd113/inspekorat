<?php

namespace Tests\Browser\Modules\WBS;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithForms;
use Tests\Browser\Traits\InteractsWithFiles;
use Tests\DuskTestCase;

class WBSManagementTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithForms, InteractsWithFiles;

    /**
     * Test WBS manager dapat melihat daftar WBS
     */
    public function test_wbs_manager_dapat_melihat_daftar_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->assertSee('WBS')
                    ->assertVisible('.data-table')
                    ->assertVisible('a[href*="create"]');
        });
    }

    /**
     * Test WBS manager dapat membuat WBS baru
     */
    public function test_wbs_manager_dapat_membuat_wbs_baru()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $wbsData = [
                'nama_pelapor' => 'John Doe',
                'email_pelapor' => 'john@example.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'Laporan Test WBS ' . time(),
                'deskripsi' => 'Deskripsi lengkap laporan WBS untuk testing.',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee($wbsData['judul_laporan']);
        });
    }

    /**
     * Test WBS manager dapat membuat WBS dengan file lampiran
     */
    public function test_wbs_manager_dapat_membuat_wbs_dengan_file_lampiran()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $filePath = $this->createTestFile('evidence.pdf', 'fake-pdf-content', 'application/pdf');
            
            $wbsData = [
                'nama_pelapor' => 'Jane Doe',
                'email_pelapor' => 'jane@example.com',
                'telepon_pelapor' => '081234567891',
                'kategori' => 'nepotisme',
                'judul_laporan' => 'WBS dengan Lampiran ' . time(),
                'deskripsi' => 'WBS dengan file lampiran evidence.',
                'lokasi_kejadian' => 'Surabaya',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->attach('input[name="lampiran"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee($wbsData['judul_laporan']);
        });
    }

    /**
     * Test validasi form create WBS
     */
    public function test_validasi_form_create_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->press('button[type="submit"]') // Submit empty form
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('nama_pelapor wajib diisi')
                    ->assertSee('email_pelapor wajib diisi')
                    ->assertSee('judul_laporan wajib diisi')
                    ->assertSee('deskripsi wajib diisi');
        });
    }

    /**
     * Test validasi file lampiran WBS
     */
    public function test_validasi_file_lampiran_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $invalidFile = $this->createTestFile('invalid.exe', 'invalid-content', 'application/octet-stream');
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm([
                        'nama_pelapor' => 'Test User',
                        'email_pelapor' => 'test@example.com',
                        'telepon_pelapor' => '081234567890',
                        'kategori' => 'korupsi',
                        'judul_laporan' => 'Test WBS',
                        'deskripsi' => 'Test deskripsi WBS.',
                        'lokasi_kejadian' => 'Jakarta',
                        'tanggal_kejadian' => now()->format('Y-m-d'),
                    ])
                    ->attach('input[name="lampiran"]', $invalidFile)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format file tidak valid');
        });
    }

    /**
     * Test WBS manager dapat mengedit WBS
     */
    public function test_wbs_manager_dapat_mengedit_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            // Create WBS first
            $originalData = [
                'nama_pelapor' => 'Original Name',
                'email_pelapor' => 'original@example.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'Original WBS ' . time(),
                'deskripsi' => 'Original deskripsi WBS.',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($originalData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee($originalData['judul_laporan']);
            
            // Edit WBS
            $updatedData = [
                'judul_laporan' => 'Updated WBS ' . time(),
                'deskripsi' => 'Updated deskripsi WBS.',
                'status' => 'in_progress',
            ];
            
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/wbs/*/edit', 30)
                    ->fillForm($updatedData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee($updatedData['judul_laporan']);
        });
    }

    /**
     * Test WBS manager dapat mengubah status WBS
     */
    public function test_wbs_manager_dapat_mengubah_status_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            // Create WBS first
            $wbsData = [
                'nama_pelapor' => 'Status Test',
                'email_pelapor' => 'status@example.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'Status Test WBS ' . time(),
                'deskripsi' => 'WBS untuk testing perubahan status.',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee($wbsData['judul_laporan']);
            
            // Change status
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/wbs/*/edit', 30)
                    ->select('select[name="status"]', 'in_progress')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee('In Progress');
        });
    }

    /**
     * Test WBS manager dapat menghapus WBS
     */
    public function test_wbs_manager_dapat_menghapus_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            // Create WBS first
            $wbsData = [
                'nama_pelapor' => 'Delete Test',
                'email_pelapor' => 'delete@example.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'Delete Test WBS ' . time(),
                'deskripsi' => 'WBS untuk testing penghapusan.',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee($wbsData['judul_laporan']);
            
            // Delete WBS
            $browser->click('button[data-action="delete"]')
                    ->waitFor('.modal', 10)
                    ->press('Ya')
                    ->waitForReload()
                    ->assertDontSee($wbsData['judul_laporan']);
        });
    }

    /**
     * Test search functionality WBS
     */
    public function test_search_wbs_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            // Create test WBS
            $wbsData = [
                'nama_pelapor' => 'Search Test',
                'email_pelapor' => 'search@example.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'WBS Pencarian Unik ' . time(),
                'deskripsi' => 'WBS untuk testing pencarian.',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->type('input[name="search"]', 'Pencarian Unik')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee($wbsData['judul_laporan']);
        });
    }

    /**
     * Test filter WBS by status
     */
    public function test_filter_wbs_by_status()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->select('select[name="status"]', 'pending')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertSee('Pending');
        });
    }

    /**
     * Test filter WBS by kategori
     */
    public function test_filter_wbs_by_kategori()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->select('select[name="kategori"]', 'korupsi')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertSee('Korupsi');
        });
    }

    /**
     * Test pagination WBS
     */
    public function test_pagination_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->assertVisible('.pagination');
        });
    }

    /**
     * Test download file lampiran WBS
     */
    public function test_download_file_lampiran_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $filePath = $this->createTestFile('evidence.pdf', 'fake-pdf-content', 'application/pdf');
            
            $wbsData = [
                'nama_pelapor' => 'Download Test',
                'email_pelapor' => 'download@example.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'Download Test WBS ' . time(),
                'deskripsi' => 'WBS untuk testing download.',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->attach('input[name="lampiran"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->click('a[href*="download"]')
                    ->pause(2000); // Wait for download
        });
    }

    /**
     * Test role-based access untuk WBS
     */
    public function test_admin_wbs_dapat_akses_semua_fungsi()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminWbs($browser);
            
            $browser->visit('/admin/wbs')
                    ->assertVisible('a[href*="create"]')
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test role lain tidak dapat akses WBS
     */
    public function test_admin_berita_tidak_dapat_akses_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminBerita($browser);
            
            $browser->visit('/admin/wbs')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden');
        });
    }

    /**
     * Test validasi email format WBS
     */
    public function test_validasi_email_format_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm([
                        'nama_pelapor' => 'Test User',
                        'email_pelapor' => 'invalid-email',
                        'telepon_pelapor' => '081234567890',
                        'kategori' => 'korupsi',
                        'judul_laporan' => 'Test WBS',
                        'deskripsi' => 'Test deskripsi WBS.',
                        'lokasi_kejadian' => 'Jakarta',
                        'tanggal_kejadian' => now()->format('Y-m-d'),
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format email tidak valid');
        });
    }

    /**
     * Test validasi nomor telepon WBS
     */
    public function test_validasi_nomor_telepon_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm([
                        'nama_pelapor' => 'Test User',
                        'email_pelapor' => 'test@example.com',
                        'telepon_pelapor' => '123', // Invalid phone number
                        'kategori' => 'korupsi',
                        'judul_laporan' => 'Test WBS',
                        'deskripsi' => 'Test deskripsi WBS.',
                        'lokasi_kejadian' => 'Jakarta',
                        'tanggal_kejadian' => now()->format('Y-m-d'),
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format nomor telepon tidak valid');
        });
    }

    /**
     * Test responsive design pada WBS module
     */
    public function test_responsive_design_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs');
            
            $this->testResponsiveDesign($browser, function ($browser, $device) {
                $browser->assertVisible('.data-table')
                        ->assertVisible('a[href*="create"]');
            });
        });
    }

    /**
     * Test bulk actions WBS
     */
    public function test_bulk_actions_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->check('input[name="select_all"]')
                    ->select('select[name="bulk_action"]', 'change_status')
                    ->press('Apply')
                    ->waitForReload();
        });
    }

    /**
     * Test export WBS data
     */
    public function test_export_wbs_data()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="export"]')
                    ->pause(2000); // Wait for export
        });
    }

    /**
     * Test performance pada WBS module
     */
    public function test_performance_wbs_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/wbs')
                    ->waitForLoadingToFinish($browser);
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Page should load within 3 seconds
            $this->assertLessThan(3, $loadTime, 'WBS index page took too long to load: ' . $loadTime . ' seconds');
        });
    }

    /**
     * Test anonymity protection WBS
     */
    public function test_anonymity_protection_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $wbsData = [
                'nama_pelapor' => 'Anonim',
                'email_pelapor' => 'anonymous@example.com',
                'telepon_pelapor' => '081234567890',
                'kategori' => 'korupsi',
                'judul_laporan' => 'Anonymous WBS ' . time(),
                'deskripsi' => 'WBS dengan identitas anonim.',
                'lokasi_kejadian' => 'Jakarta',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'status' => 'pending',
                'is_anonymous' => true,
            ];
            
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm($wbsData)
                    ->check('input[name="is_anonymous"]')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/wbs', 30)
                    ->assertSee('Anonymous');
        });
    }

    /**
     * Test security protection WBS
     */
    public function test_security_protection_wbs()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsWbsManager($browser);
            
            $browser->visit('/admin/wbs/create')
                    ->assertPresent('input[name="_token"]'); // CSRF protection
        });
    }
}
