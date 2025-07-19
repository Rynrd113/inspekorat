<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WbsPublicTest extends DuskTestCase
{
    /**
     * Test WBS page loads successfully
     */
    public function test_wbs_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertSee('Whistleblowing System')
                ->assertSee('WBS')
                ->screenshot('wbs_page_main');
        });
    }

    /**
     * Test WBS page displays form
     */
    public function test_wbs_page_displays_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertPresent('form')
                ->assertPresent('input[name="subjek"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('input[name="nama_pelapor"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="is_anonymous"]')
                ->assertPresent('button[type="submit"]')
                ->screenshot('wbs_page_form');
        });
    }

    /**
     * Test WBS page form submission with valid data (non-anonymous)
     */
    public function test_wbs_page_form_submission_valid_non_anonymous()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->type('subjek', 'Test Laporan WBS')
                ->type('deskripsi', 'Ini adalah laporan test untuk sistem WBS.')
                ->type('nama_pelapor', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('no_telepon', '081234567890')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil')
                ->assertSee('berhasil')
                ->screenshot('wbs_form_submission_valid_non_anonymous');
        });
    }

    /**
     * Test WBS page form submission with valid data (anonymous)
     */
    public function test_wbs_page_form_submission_valid_anonymous()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->type('subjek', 'Test Laporan WBS Anonim')
                ->type('deskripsi', 'Ini adalah laporan test anonim untuk sistem WBS.')
                ->check('is_anonymous')
                ->press('Kirim Laporan WBS')
                ->waitForText('berhasil')
                ->assertSee('berhasil')
                ->screenshot('wbs_form_submission_valid_anonymous');
        });
    }

    /**
     * Test WBS page form validation - empty fields
     */
    public function test_wbs_page_form_validation_empty_fields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->press('Kirim Laporan WBS')
                ->waitFor('.text-red-600', 5)
                ->screenshot('wbs_form_validation_empty');
        });
    }

    /**
     * Test WBS page form validation - invalid email
     */
    public function test_wbs_page_form_validation_invalid_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->type('subjek', 'Test Laporan')
                ->type('deskripsi', 'Test laporan')
                ->type('nama_pelapor', 'John Doe')
                ->type('email', 'invalid-email')
                ->press('Kirim Laporan WBS')
                ->waitFor('.text-red-600', 5)
                ->screenshot('wbs_form_validation_invalid_email');
        });
    }

    /**
     * Test WBS page anonymous checkbox functionality
     */
    public function test_wbs_page_anonymous_checkbox()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertPresent('input[name="nama_pelapor"]')
                ->assertPresent('input[name="email"]')
                ->check('is_anonymous')
                ->waitFor('body', 1)
                ->screenshot('wbs_anonymous_checkbox');
        });
    }

    /**
     * Test WBS page file upload functionality
     */
    public function test_wbs_page_file_upload()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->whenAvailable('input[name="attachments[]"]', function ($fileInput) {
                    $fileInput->assertPresent('input[type="file"]');
                })
                ->screenshot('wbs_file_upload');
        });
    }

    /**
     * Test WBS page optional fields
     */
    public function test_wbs_page_category_selection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertPresent('input[name="tanggal_kejadian"]')
                ->assertPresent('input[name="lokasi_kejadian"]')
                ->assertPresent('textarea[name="pihak_terlibat"]')
                ->assertPresent('textarea[name="kronologi"]')
                ->screenshot('wbs_optional_fields');
        });
    }

    /**
     * Test WBS page navigation breadcrumb
     */
    public function test_wbs_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertSee('Beranda')
                ->assertSee('WBS')
                ->screenshot('wbs_breadcrumb');
        });
    }

    /**
     * Test WBS page responsive design on mobile
     */
    public function test_wbs_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertSee('WBS')
                ->assertPresent('form')
                ->screenshot('wbs_mobile_responsive');
        });
    }

    /**
     * Test WBS page responsive design on tablet
     */
    public function test_wbs_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertSee('WBS')
                ->assertPresent('form')
                ->screenshot('wbs_tablet_responsive');
        });
    }

    /**
     * Test WBS page information display
     */
    public function test_wbs_page_information_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertSee('Whistleblowing System')
                ->assertSee('laporan')
                ->assertSee('pelanggaran')
                ->screenshot('wbs_information_display');
        });
    }

    /**
     * Test WBS page privacy notice
     */
    public function test_wbs_page_privacy_notice()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->whenAvailable('.privacy-notice, .disclaimer', function ($notice) {
                    $notice->assertSee('privasi');
                })
                ->screenshot('wbs_privacy_notice');
        });
    }

    /**
     * Test WBS page form reset functionality
     */
    public function test_wbs_page_form_reset()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->type('subjek', 'Test Laporan')
                ->type('deskripsi', 'Test isi laporan')
                ->type('nama_pelapor', 'John Doe')
                ->whenAvailable('button[type="reset"]', function ($reset) {
                    $reset->click();
                })
                ->assertInputValue('subjek', '')
                ->assertInputValue('deskripsi', '')
                ->assertInputValue('nama_pelapor', '')
                ->screenshot('wbs_form_reset');
        });
    }

    /**
     * Test WBS page SEO elements
     */
    public function test_wbs_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertTitleContains('WBS')
                ->screenshot('wbs_seo_check');
        });
    }

    /**
     * Test WBS page performance
     */
    public function test_wbs_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'WBS page should load within 5 seconds');
    }

    /**
     * Test WBS page back to homepage
     */
    public function test_wbs_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('wbs_back_to_homepage');
        });
    }

    /**
     * Test WBS page footer links
     */
    public function test_wbs_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('wbs_footer_links');
        });
    }

    /**
     * Test WBS page navigation menu consistency
     */
    public function test_wbs_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('Kontak')
                ->assertSee('Portal OPD')
                ->screenshot('wbs_navigation_consistency');
        });
    }

    /**
     * Test WBS page guidelines display
     */
    public function test_wbs_page_guidelines_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->whenAvailable('.guidelines, .panduan', function ($guide) {
                    $guide->assertSee('panduan');
                })
                ->screenshot('wbs_guidelines_display');
        });
    }

    /**
     * Test WBS page form character limits
     */
    public function test_wbs_page_form_character_limits()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->type('subjek', str_repeat('a', 300)) // Test title length
                ->type('deskripsi', str_repeat('b', 3000)) // Test content length
                ->press('Kirim Laporan WBS')
                ->waitFor('body', 3)
                ->screenshot('wbs_form_character_limits');
        });
    }

    /**
     * Test WBS page contact information display
     */
    public function test_wbs_page_contact_info_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->whenAvailable('.contact-info, .kontak-info', function ($contact) {
                    $contact->assertSee('kontak');
                })
                ->screenshot('wbs_contact_info_display');
        });
    }

    /**
     * Test WBS page emergency contact display
     */
    public function test_wbs_page_emergency_contact()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('Whistleblowing System')
                ->whenAvailable('.emergency-contact, .kontak-darurat', function ($emergency) {
                    $emergency->assertSee('darurat');
                })
                ->screenshot('wbs_emergency_contact');
        });
    }
}
