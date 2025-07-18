<?php

namespace Tests\Browser\Public;

use App\Models\PortalOpd;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Faq;
use App\Models\PortalPapuaTengah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * End-to-End Integration Test for Public Interface
 * Complete user journey testing for Portal Inspektorat Papua Tengah
 */
class EndToEndIntegrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create test Portal OPD
        PortalOpd::create([
            'nama' => 'Test OPD',
            'alamat' => 'Jl. Test No. 123',
            'telepon' => '081234567890',
            'email' => 'test@opd.id',
            'website' => 'https://test.opd.id',
            'kepala' => 'Kepala Test',
            'status' => 'active'
        ]);

        // Create test services
        Pelayanan::create([
            'nama_layanan' => 'Test Service',
            'deskripsi' => 'Test service description',
            'persyaratan' => 'Test requirements',
            'biaya' => 'Gratis',
            'waktu_proses' => '3 hari kerja',
            'kategori' => 'pelayanan',
            'status' => 'active'
        ]);

        // Create test documents
        Dokumen::create([
            'judul' => 'Test Document',
            'deskripsi' => 'Test document description',
            'kategori' => 'peraturan',
            'file_path' => 'test-document.pdf',
            'status' => 'active'
        ]);

        // Create test gallery items
        Galeri::create([
            'judul' => 'Test Gallery Item',
            'deskripsi' => 'Test gallery description',
            'kategori' => 'kegiatan',
            'file_path' => 'test-image.jpg',
            'tipe' => 'image',
            'status' => 'active'
        ]);

        // Create test FAQs
        Faq::create([
            'pertanyaan' => 'Test FAQ Question',
            'jawaban' => 'Test FAQ Answer',
            'kategori' => 'pelayanan',
            'urutan' => 1,
            'status' => 'active'
        ]);

        // Create test news
        PortalPapuaTengah::create([
            'judul' => 'Test News Article',
            'slug' => 'test-news-article',
            'isi' => 'Test news article content',
            'kategori' => 'news',
            'status' => 'published'
        ]);
    }

    /**
     * Test complete citizen journey from homepage to service completion
     */
    public function testCompleteCitizenJourney()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Visit homepage
            $browser->visit('/')
                ->waitFor('.homepage-content', 10)
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('e2e-homepage-visit');

            // Step 2: Browse organization profile
            $browser->click('a[href="/profil"]')
                ->waitFor('.profile-content', 10)
                ->assertSee('Profil Organisasi')
                ->screenshot('e2e-profile-browse');

            // Step 3: Explore available services
            $browser->click('a[href="/pelayanan"]')
                ->waitFor('.service-cards', 10)
                ->assertSee('Test Service')
                ->screenshot('e2e-services-browse');

            // Step 4: Access service details
            $browser->click('.service-detail-btn')
                ->waitFor('.service-detail', 10)
                ->assertSee('Test service description')
                ->screenshot('e2e-service-detail');

            // Step 5: Check required documents
            $browser->click('a[href="/dokumen"]')
                ->waitFor('.document-list', 10)
                ->assertSee('Test Document')
                ->screenshot('e2e-documents-browse');

            // Step 6: Browse FAQ for help
            $browser->click('a[href="/faq"]')
                ->waitFor('.faq-list', 10)
                ->assertSee('Test FAQ Question')
                ->screenshot('e2e-faq-browse');

            // Step 7: Submit WBS report
            $browser->click('a[href="/wbs"]')
                ->waitFor('.wbs-form', 10)
                ->type('judul', 'Test WBS Report')
                ->type('isi', 'This is a test WBS report')
                ->type('nama_pelapor', 'John Doe')
                ->type('email_pelapor', 'john@example.com')
                ->press('Submit')
                ->waitForText('Report submitted successfully', 10)
                ->screenshot('e2e-wbs-submit');

            // Step 8: Contact organization
            $browser->click('a[href="/kontak"]')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'Jane Doe')
                ->type('email', 'jane@example.com')
                ->type('subjek', 'Test Contact')
                ->type('pesan', 'This is a test contact message')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('e2e-contact-submit');

            // Step 9: Browse news and updates
            $browser->click('a[href="/berita"]')
                ->waitFor('.news-list', 10)
                ->assertSee('Test News Article')
                ->click('.news-item')
                ->waitFor('.news-detail', 10)
                ->screenshot('e2e-news-browse');

            // Step 10: Check gallery
            $browser->click('a[href="/galeri"]')
                ->waitFor('.gallery-grid', 10)
                ->assertSee('Test Gallery Item')
                ->screenshot('e2e-gallery-browse');

            // Step 11: Browse Portal OPD
            $browser->click('a[href="/portal-opd"]')
                ->waitFor('.opd-cards', 10)
                ->assertSee('Test OPD')
                ->screenshot('e2e-opd-browse');
        });
    }

    /**
     * Test responsive design across all public pages
     */
    public function testResponsiveDesignIntegration()
    {
        $this->browse(function (Browser $browser) {
            $pages = [
                '/' => 'Homepage',
                '/profil' => 'Profile',
                '/pelayanan' => 'Services',
                '/dokumen' => 'Documents',
                '/galeri' => 'Gallery',
                '/faq' => 'FAQ',
                '/berita' => 'News',
                '/kontak' => 'Contact',
                '/wbs' => 'WBS',
                '/portal-opd' => 'Portal OPD'
            ];

            foreach ($pages as $route => $pageName) {
                // Test mobile view
                $browser->resize(375, 667)
                    ->visit($route)
                    ->waitFor('body', 10)
                    ->screenshot('e2e-responsive-mobile-' . strtolower(str_replace(' ', '-', $pageName)));

                // Test tablet view
                $browser->resize(768, 1024)
                    ->visit($route)
                    ->waitFor('body', 10)
                    ->screenshot('e2e-responsive-tablet-' . strtolower(str_replace(' ', '-', $pageName)));

                // Test desktop view
                $browser->resize(1920, 1080)
                    ->visit($route)
                    ->waitFor('body', 10)
                    ->screenshot('e2e-responsive-desktop-' . strtolower(str_replace(' ', '-', $pageName)));
            }
        });
    }

    /**
     * Test search functionality across all modules
     */
    public function testSearchIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test service search
            $browser->visit('/pelayanan')
                ->waitFor('.search-form', 10)
                ->type('search', 'Test Service')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Test Service')
                ->screenshot('e2e-search-services');

            // Test document search
            $browser->visit('/dokumen')
                ->waitFor('.search-form', 10)
                ->type('search', 'Test Document')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Test Document')
                ->screenshot('e2e-search-documents');

            // Test FAQ search
            $browser->visit('/faq')
                ->waitFor('.search-form', 10)
                ->type('search', 'Test FAQ')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Test FAQ Question')
                ->screenshot('e2e-search-faq');

            // Test news search
            $browser->visit('/berita')
                ->waitFor('.search-form', 10)
                ->type('search', 'Test News')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Test News Article')
                ->screenshot('e2e-search-news');

            // Test gallery search
            $browser->visit('/galeri')
                ->waitFor('.search-form', 10)
                ->type('search', 'Test Gallery')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Test Gallery Item')
                ->screenshot('e2e-search-gallery');

            // Test Portal OPD search
            $browser->visit('/portal-opd')
                ->waitFor('.search-form', 10)
                ->type('search', 'Test OPD')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Test OPD')
                ->screenshot('e2e-search-opd');
        });
    }

    /**
     * Test navigation consistency across all pages
     */
    public function testNavigationConsistency()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('.navbar', 10)
                    ->assertSee('Portal Inspektorat Papua Tengah')
                    ->assertSee('Beranda')
                    ->assertSee('Profil')
                    ->assertSee('Pelayanan')
                    ->assertSee('Dokumen')
                    ->assertSee('Galeri')
                    ->assertSee('FAQ')
                    ->assertSee('Berita')
                    ->assertSee('Kontak')
                    ->assertSee('WBS')
                    ->assertSee('Portal OPD')
                    ->screenshot('e2e-navigation-' . str_replace('/', 'home', $page));
            }
        });
    }

    /**
     * Test form validation across all forms
     */
    public function testFormValidationIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test WBS form validation
            $browser->visit('/wbs')
                ->waitFor('.wbs-form', 10)
                ->press('Submit')
                ->waitFor('.error-message', 10)
                ->assertSee('validation error')
                ->screenshot('e2e-validation-wbs');

            // Test contact form validation
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->press('Send')
                ->waitFor('.error-message', 10)
                ->assertSee('validation error')
                ->screenshot('e2e-validation-contact');

            // Test search form validation
            $browser->visit('/pelayanan')
                ->waitFor('.search-form', 10)
                ->press('Search')
                ->waitFor('.error-message', 10)
                ->assertSee('Please enter search term')
                ->screenshot('e2e-validation-search');
        });
    }

    /**
     * Test file download functionality
     */
    public function testFileDownloadIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test document download
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->click('.download-btn')
                ->waitFor('.download-progress', 10)
                ->screenshot('e2e-download-document');

            // Test gallery media download
            $browser->visit('/galeri')
                ->waitFor('.gallery-grid', 10)
                ->click('.media-item')
                ->waitFor('.media-detail', 10)
                ->click('.download-btn')
                ->waitFor('.download-progress', 10)
                ->screenshot('e2e-download-media');
        });
    }

    /**
     * Test accessibility features
     */
    public function testAccessibilityIntegration()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->assertSourceHas('alt=')
                    ->assertSourceHas('aria-label')
                    ->assertSourceHas('role=')
                    ->screenshot('e2e-accessibility-' . str_replace('/', 'home', $page));
            }
        });
    }

    /**
     * Test performance across all pages
     */
    public function testPerformanceIntegration()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $startTime = microtime(true);

                $browser->visit($page)
                    ->waitFor('body', 10);

                $endTime = microtime(true);
                $loadTime = $endTime - $startTime;

                // Page should load within 3 seconds
                $this->assertLessThan(3, $loadTime, "Page {$page} took too long to load: {$loadTime}s");
                $browser->screenshot('e2e-performance-' . str_replace('/', 'home', $page));
            }
        });
    }

    /**
     * Test SEO elements across all pages
     */
    public function testSeoIntegration()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/profil', '/pelayanan', '/dokumen', '/galeri', '/faq', '/berita', '/kontak', '/wbs', '/portal-opd'];

            foreach ($pages as $page) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->assertSourceHas('<title>')
                    ->assertSourceHas('meta name="description"')
                    ->assertSourceHas('meta name="keywords"')
                    ->assertSourceHas('meta property="og:title"')
                    ->assertSourceHas('meta property="og:description"')
                    ->screenshot('e2e-seo-' . str_replace('/', 'home', $page));
            }
        });
    }

    /**
     * Test error handling across all pages
     */
    public function testErrorHandlingIntegration()
    {
        $this->browse(function (Browser $browser) {
            // Test 404 error handling
            $browser->visit('/nonexistent-page')
                ->waitFor('.error-page', 10)
                ->assertSee('404')
                ->assertSee('Page Not Found')
                ->screenshot('e2e-error-404');

            // Test invalid parameters
            $browser->visit('/berita/invalid-slug')
                ->waitFor('.error-page', 10)
                ->assertSee('404')
                ->screenshot('e2e-error-invalid-slug');

            // Test invalid search
            $browser->visit('/pelayanan?search=<script>')
                ->waitFor('.search-results', 10)
                ->assertDontSee('<script>')
                ->screenshot('e2e-error-invalid-search');
        });
    }
}