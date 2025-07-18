<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\Galeri;
use App\Models\Dokumen;
use App\Models\Pelayanan;
use App\Models\Faq;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PublicPortalTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test homepage accessibility and content.
     */
    public function test_homepage_accessibility_and_content()
    {
        // Create sample data
        $berita = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Terbaru Inspektorat',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $this->browse(function (Browser $browser) use ($berita) {
            $browser->visit('/')
                    ->assertSee('Portal Inspektorat Papua Tengah')
                    ->assertSee('Berita Terbaru')
                    ->assertSee($berita->judul)
                    ->assertVisible('.hero-section')
                    ->assertVisible('.news-section')
                    ->assertVisible('.services-section')
                    ->scrollTo('.footer')
                    ->assertSee('Kontak')
                    ->assertSee('Alamat');
        });
    }

    /**
     * Test news listing page functionality.
     */
    public function test_news_listing_page_functionality()
    {
        // Create multiple news articles
        $publishedNews = PortalPapuaTengah::factory()->count(5)->create([
            'status' => 'published',
        ]);

        $draftNews = PortalPapuaTengah::factory()->create([
            'status' => 'draft',
        ]);

        $this->browse(function (Browser $browser) use ($publishedNews, $draftNews) {
            $browser->visit('/berita')
                    ->assertSee('Berita Terbaru')
                    ->waitFor('.news-item', 10);

            // Check published news are visible
            foreach ($publishedNews as $news) {
                $browser->assertSee($news->judul);
            }

            // Check draft news is not visible
            $browser->assertDontSee($draftNews->judul);

            // Test pagination if more than 10 articles
            if ($publishedNews->count() > 10) {
                $browser->assertVisible('.pagination');
            }
        });
    }

    /**
     * Test news detail page functionality.
     */
    public function test_news_detail_page_functionality()
    {
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Detail Test',
            'isi' => 'Konten berita lengkap untuk testing detail page.',
            'status' => 'published',
        ]);

        $this->browse(function (Browser $browser) use ($news) {
            $browser->visit("/berita/{$news->id}")
                    ->assertSee($news->judul)
                    ->assertSee($news->isi)
                    ->assertSee($news->created_at->format('d M Y'))
                    ->assertVisible('.news-content')
                    ->assertVisible('.back-button')
                    ->click('.back-button')
                    ->waitForLocation('/berita')
                    ->assertPathIs('/berita');
        });
    }

    /**
     * Test organization profile page.
     */
    public function test_organization_profile_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                    ->assertSee('Profil Inspektorat')
                    ->assertSee('Visi')
                    ->assertSee('Misi')
                    ->assertSee('Tugas')
                    ->assertSee('Fungsi')
                    ->assertVisible('.profile-content')
                    ->scrollTo('.organization-chart')
                    ->assertVisible('.organization-chart');
        });
    }

    /**
     * Test public services page functionality.
     */
    public function test_public_services_page_functionality()
    {
        $services = Pelayanan::factory()->count(3)->create([
            'status' => 'active',
        ]);

        $this->browse(function (Browser $browser) use ($services) {
            $browser->visit('/pelayanan')
                    ->assertSee('Layanan Publik')
                    ->waitFor('.service-item', 10);

            foreach ($services as $service) {
                $browser->assertSee($service->nama_layanan);
            }

            // Test service detail
            $firstService = $services->first();
            $browser->click("a[href*='/pelayanan/{$firstService->id}']")
                    ->waitForLocation("/pelayanan/{$firstService->id}")
                    ->assertSee($firstService->nama_layanan)
                    ->assertSee($firstService->deskripsi);
        });
    }

    /**
     * Test documents page functionality.
     */
    public function test_documents_page_functionality()
    {
        $documents = Dokumen::factory()->count(3)->create([
            'status' => 'published',
        ]);

        $this->browse(function (Browser $browser) use ($documents) {
            $browser->visit('/dokumen')
                    ->assertSee('Dokumen Publik')
                    ->waitFor('.document-item', 10);

            foreach ($documents as $document) {
                $browser->assertSee($document->judul);
            }

            // Test document download
            $firstDocument = $documents->first();
            $browser->click("a[href*='/dokumen/{$firstDocument->id}/download']");
            // Note: Actual download testing might need additional setup
        });
    }

    /**
     * Test gallery page functionality.
     */
    public function test_gallery_page_functionality()
    {
        $galleries = Galeri::factory()->count(6)->create([
            'status' => 'published',
        ]);

        $this->browse(function (Browser $browser) use ($galleries) {
            $browser->visit('/galeri')
                    ->assertSee('Galeri')
                    ->waitFor('.gallery-item', 10);

            foreach ($galleries->take(3) as $gallery) {
                $browser->assertSee($gallery->judul);
            }

            // Test gallery detail
            $firstGallery = $galleries->first();
            $browser->click("a[href*='/galeri/{$firstGallery->id}']")
                    ->waitForLocation("/galeri/{$firstGallery->id}")
                    ->assertSee($firstGallery->judul)
                    ->assertSee($firstGallery->deskripsi);
        });
    }

    /**
     * Test FAQ page functionality.
     */
    public function test_faq_page_functionality()
    {
        $faqs = Faq::factory()->count(5)->create([
            'status' => 'published',
        ]);

        $this->browse(function (Browser $browser) use ($faqs) {
            $browser->visit('/faq')
                    ->assertSee('Frequently Asked Questions')
                    ->waitFor('.faq-item', 10);

            foreach ($faqs as $faq) {
                $browser->assertSee($faq->pertanyaan);
                
                // Test accordion functionality
                $browser->click("[data-faq-id='{$faq->id}']")
                        ->waitFor(".faq-answer-{$faq->id}")
                        ->assertSee($faq->jawaban);
            }
        });
    }

    /**
     * Test contact page functionality.
     */
    public function test_contact_page_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                    ->assertSee('Kontak Kami')
                    ->assertSee('Alamat')
                    ->assertSee('Telepon')
                    ->assertSee('Email')
                    ->assertVisible('.contact-info')
                    ->assertVisible('.contact-map');

            // Test contact form
            $browser->scrollTo('.contact-form')
                    ->type('name', 'Test User')
                    ->type('email', 'test@example.com')
                    ->type('subject', 'Test Subject')
                    ->type('message', 'Test message content')
                    ->press('Kirim Pesan')
                    ->waitForText('Pesan berhasil dikirim', 10)
                    ->assertSee('Pesan berhasil dikirim');

            // Verify message is stored in database
            $this->assertDatabaseHas('contact_messages', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'subject' => 'Test Subject',
            ]);
        });
    }

    /**
     * Test Portal OPD page functionality.
     */
    public function test_portal_opd_page_functionality()
    {
        $portalOpds = PortalOpd::factory()->count(4)->create([
            'status' => 'active',
        ]);

        $this->browse(function (Browser $browser) use ($portalOpds) {
            $browser->visit('/portal-opd')
                    ->assertSee('Portal OPD')
                    ->waitFor('.opd-item', 10);

            foreach ($portalOpds as $opd) {
                $browser->assertSee($opd->nama_opd);
            }

            // Test OPD detail
            $firstOpd = $portalOpds->first();
            $browser->click("a[href*='/portal-opd/{$firstOpd->id}']")
                    ->waitForLocation("/portal-opd/{$firstOpd->id}")
                    ->assertSee($firstOpd->nama_opd)
                    ->assertSee($firstOpd->deskripsi);
        });
    }

    /**
     * Test search functionality across public pages.
     */
    public function test_search_functionality()
    {
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Pencarian Test',
            'status' => 'published',
        ]);

        $this->browse(function (Browser $browser) use ($news) {
            $browser->visit('/')
                    ->type('search', 'Pencarian')
                    ->press('Cari')
                    ->waitForLocation('/search')
                    ->assertSee('Hasil Pencarian')
                    ->assertSee($news->judul);
        });
    }

    /**
     * Test responsive design on mobile viewport.
     */
    public function test_responsive_design_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE viewport
                    ->visit('/')
                    ->assertVisible('.mobile-menu-toggle')
                    ->click('.mobile-menu-toggle')
                    ->waitFor('.mobile-menu')
                    ->assertVisible('.mobile-menu')
                    ->assertSee('Beranda')
                    ->assertSee('Berita')
                    ->assertSee('Profil');
        });
    }

    /**
     * Test page loading performance.
     */
    public function test_page_loading_performance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            $browser->visit('/');
            $this->waitForPageLoad($browser);
            
            $loadTime = microtime(true) - $startTime;
            
            // Assert page loads within reasonable time (3 seconds)
            $this->assertLessThan(3, $loadTime, 'Homepage loading time exceeds 3 seconds');
            
            $browser->assertVisible('.hero-section');
        });
    }

    /**
     * Test SEO meta tags presence.
     */
    public function test_seo_meta_tags_presence()
    {
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'SEO Test Berita',
            'meta_description' => 'Meta description untuk testing SEO',
            'status' => 'published',
        ]);

        $this->browse(function (Browser $browser) use ($news) {
            $browser->visit("/berita/{$news->id}");
            
            // Check for essential SEO meta tags
            $titleTag = $browser->element('title')->getText();
            $this->assertStringContainsString($news->judul, $titleTag);
            
            $metaDescription = $browser->attribute('meta[name="description"]', 'content');
            $this->assertEquals($news->meta_description, $metaDescription);
        });
    }

    /**
     * Test accessibility features.
     */
    public function test_accessibility_features()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPresent('header[role="banner"]')
                    ->assertPresent('main[role="main"]')
                    ->assertPresent('footer[role="contentinfo"]')
                    ->assertPresent('nav[role="navigation"]');
                    
            // Test keyboard navigation
            $browser->keys('body', ['{tab}', '{tab}', '{enter}']);
            
            // Check for alt attributes on images
            $images = $browser->elements('img');
            foreach ($images as $image) {
                $alt = $image->getAttribute('alt');
                $this->assertNotEmpty($alt, 'Image missing alt attribute');
            }
        });
    }
}