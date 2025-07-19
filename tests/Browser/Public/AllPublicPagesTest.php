<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AllPublicPagesTest extends DuskTestCase
{
    /**
     * Test all public pages accessibility
     */
    public function testAllPublicPagesAccessibility()
    {
        $publicPages = [
            '/' => 'Beranda',
            '/profil' => 'Profil',
            '/pelayanan' => 'Pelayanan',
            '/dokumen' => 'Dokumen',
            '/galeri' => 'Galeri',
            '/berita' => 'Berita',
            '/kontak' => 'Kontak',
            '/wbs' => 'WBS',
            '/faq' => 'FAQ',
            '/portal-opd' => 'Portal OPD'
        ];

        foreach ($publicPages as $url => $expectedContent) {
            $this->browse(function (Browser $browser) use ($url, $expectedContent) {
                $browser->visit($url)
                    ->assertSee($expectedContent)
                    ->screenshot('system_accessibility_' . str_replace('/', '_', $url ?: 'home'));
            });
        }
    }

    /**
     * Test profile page comprehensive
     */
    public function testProfilePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->assertSee('Profil')
                ->assertPresent('.organization-info, .profile-content')
                ->screenshot('profile-page-main')
                ->assertSee('Visi')
                ->assertSee('Misi')
                ->screenshot('profile-mission-items')
                ->assertPresent('footer')
                ->screenshot('profile-contact-info');
        });
    }

    /**
     * Test services page comprehensive
     */
    public function testServicesPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->assertSee('Pelayanan')
                ->assertPresent('.service-list, .pelayanan-list')
                ->screenshot('services-page-main')
                ->resize(375, 667) // Mobile
                ->assertSee('Pelayanan')
                ->screenshot('services-mobile-responsive')
                ->resize(768, 1024) // Tablet
                ->assertSee('Pelayanan')
                ->screenshot('services-tablet-responsive')
                ->resize(1280, 720); // Desktop
        });
    }

    /**
     * Test documents page comprehensive
     */
    public function testDocumentsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->assertSee('Dokumen')
                ->assertPresent('.document-list, .dokumen-list')
                ->screenshot('documents-page-main')
                ->assertPresent('a[href*="download"], .download-link')
                ->screenshot('documents-download-links')
                ->resize(375, 667) // Mobile
                ->assertSee('Dokumen')
                ->screenshot('documents-mobile-responsive')
                ->resize(768, 1024) // Tablet
                ->assertSee('Dokumen')
                ->screenshot('documents-tablet-responsive')
                ->resize(1280, 720); // Desktop
        });
    }

    /**
     * Test gallery page comprehensive
     */
    public function testGalleryPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->assertSee('Galeri')
                ->assertPresent('.gallery-grid, .galeri-grid')
                ->screenshot('gallery-page-main')
                ->assertPresent('.media-item, .gallery-item')
                ->screenshot('gallery-media-items')
                ->resize(375, 667) // Mobile
                ->assertSee('Galeri')
                ->screenshot('gallery-mobile-responsive')
                ->resize(768, 1024) // Tablet
                ->assertSee('Galeri')
                ->screenshot('gallery-tablet-responsive')
                ->resize(1280, 720); // Desktop
        });
    }

    /**
     * Test news page comprehensive
     */
    public function testNewsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->assertSee('Berita')
                ->assertPresent('.news-list, .berita-list')
                ->screenshot('news-list-page-main')
                ->assertPresent('.breadcrumb')
                ->screenshot('news-list-breadcrumb')
                ->resize(375, 667) // Mobile
                ->assertSee('Berita')
                ->screenshot('news-list-mobile-responsive')
                ->resize(768, 1024) // Tablet
                ->assertSee('Berita')
                ->screenshot('news-list-tablet-responsive')
                ->resize(1280, 720); // Desktop
        });
    }

    /**
     * Test contact page comprehensive
     */
    public function testContactPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->assertSee('Kontak')
                ->assertPresent('form')
                ->screenshot('contact-page-form')
                ->assertPresent('.contact-info, .kontak-info')
                ->screenshot('contact-page-info')
                ->assertPresent('.breadcrumb')
                ->screenshot('contact-breadcrumb')
                ->assertSee('Alamat')
                ->screenshot('contact-address-display')
                ->assertSee('Telepon')
                ->assertSee('Email')
                ->screenshot('contact-office-hours')
                ->resize(375, 667) // Mobile
                ->assertSee('Kontak')
                ->screenshot('contact-mobile-responsive')
                ->resize(768, 1024) // Tablet
                ->assertSee('Kontak')
                ->screenshot('contact-tablet-responsive')
                ->resize(1280, 720); // Desktop
        });
    }

    /**
     * Test WBS page comprehensive
     */
    public function testWbsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->assertSee('WBS')
                ->assertPresent('form')
                ->screenshot('wbs-page-form')
                ->assertPresent('input[name="nama"], input[name="email"]')
                ->screenshot('wbs-form-fields');
        });
    }

    /**
     * Test FAQ page comprehensive
     */
    public function testFaqPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->assertSee('FAQ')
                ->assertPresent('.faq-list, .faq-content')
                ->screenshot('faq-page-main')
                ->assertPresent('.faq-item, .question')
                ->screenshot('faq-list-display')
                ->assertPresent('.breadcrumb')
                ->screenshot('faq-breadcrumb')
                ->resize(375, 667) // Mobile
                ->assertSee('FAQ')
                ->screenshot('faq-mobile-responsive')
                ->resize(768, 1024) // Tablet
                ->assertSee('FAQ')
                ->screenshot('faq-tablet-responsive')
                ->resize(1280, 720); // Desktop
        });
    }

    /**
     * Test contact form submission
     */
    public function testContactFormSubmission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->type('nama', 'Test User')
                ->type('email', 'test@example.com')
                ->type('subjek', 'Testing Kontak Form')
                ->type('pesan', 'Ini adalah pesan testing untuk form kontak')
                ->screenshot('contact-form-submission-valid')
                ->press('Kirim')
                ->pause(2000)
                ->screenshot('contact-form-after-submission');
        });
    }

    /**
     * Test WBS form submission
     */
    public function testWbsFormSubmission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->type('nama', 'Pelapor Test')
                ->type('email', 'pelapor@example.com')
                ->type('telepon', '081234567890')
                ->type('judul', 'Laporan Testing WBS')
                ->type('isi', 'Ini adalah laporan testing untuk sistem WBS')
                ->select('kategori', 'pelayanan')
                ->screenshot('wbs-form-submission-valid')
                ->press('Kirim')
                ->pause(2000)
                ->screenshot('wbs-form-after-submission');
        });
    }

    /**
     * Test all pages SEO elements
     */
    public function testAllPagesSEO()
    {
        $publicPages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/berita', '/kontak', '/wbs', '/faq', '/portal-opd'];

        foreach ($publicPages as $url) {
            $this->browse(function (Browser $browser) use ($url) {
                $browser->visit($url)
                    ->assertPresent('title')
                    ->assertPresent('meta[name="description"]')
                    ->screenshot('seo-check-' . str_replace('/', '_', $url ?: 'home'));
            });
        }
    }

    /**
     * Test footer links consistency
     */
    public function testFooterLinksConsistency()
    {
        $publicPages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/berita', '/kontak'];

        foreach ($publicPages as $url) {
            $this->browse(function (Browser $browser) use ($url) {
                $browser->visit($url)
                    ->assertPresent('footer')
                    ->assertSee('Inspektorat')
                    ->screenshot('footer-links-' . str_replace('/', '_', $url ?: 'home'));
            });
        }
    }

    /**
     * Test system-wide contact information consistency
     */
    public function testSystemContactConsistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->screenshot('system-contact-consistency')
                ->visit('/profil')
                ->screenshot('system-profile')
                ->visit('/')
                ->screenshot('system-homepage');
        });
    }
}