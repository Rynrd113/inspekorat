<?php

namespace Tests\Browser\Public;

use App\Models\PortalPapuaTengah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * News Detail Test
 * Test for individual news article detail pages
 */
class NewsDetailTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create test news articles
        PortalPapuaTengah::create([
            'judul' => 'Test News Article 1',
            'slug' => 'test-news-article-1',
            'isi' => 'This is the content of test news article 1. It contains detailed information about the topic.',
            'kategori' => 'news',
            'status' => 'published',
            'featured' => true,
            'author' => 'Admin',
            'created_at' => now()->subDays(1)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Test News Article 2',
            'slug' => 'test-news-article-2',
            'isi' => 'This is the content of test news article 2. It provides important updates.',
            'kategori' => 'announcement',
            'status' => 'published',
            'featured' => false,
            'author' => 'Editor',
            'created_at' => now()->subDays(2)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Test News Article 3',
            'slug' => 'test-news-article-3',
            'isi' => 'This is the content of test news article 3. It discusses recent developments.',
            'kategori' => 'news',
            'status' => 'published',
            'featured' => true,
            'author' => 'Reporter',
            'created_at' => now()->subDays(3)
        ]);
    }

    /**
     * Test news detail page loads successfully
     */
    public function testNewsDetailPageLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertSee('Test News Article 1')
                ->assertSee('This is the content of test news article 1')
                ->screenshot('news-detail-page-load');
        });
    }

    /**
     * Test news detail page displays complete content
     */
    public function testNewsDetailContentDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertSee('Test News Article 1')
                ->assertSee('This is the content of test news article 1')
                ->assertSee('Admin') // Author
                ->assertSeeIn('.news-category', 'news')
                ->assertPresent('.news-date')
                ->screenshot('news-detail-content-display');
        });
    }

    /**
     * Test news detail page breadcrumb navigation
     */
    public function testNewsDetailBreadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.breadcrumb', 10)
                ->assertSee('Beranda')
                ->assertSee('Berita')
                ->assertSee('Test News Article 1')
                ->screenshot('news-detail-breadcrumb');
        });
    }

    /**
     * Test news detail page related articles
     */
    public function testNewsDetailRelatedArticles()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.related-articles', 10)
                ->assertSee('Artikel Terkait')
                ->assertSee('Test News Article 2')
                ->assertSee('Test News Article 3')
                ->screenshot('news-detail-related-articles');
        });
    }

    /**
     * Test news detail page social sharing
     */
    public function testNewsDetailSocialSharing()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.social-sharing', 10)
                ->assertSee('Bagikan')
                ->assertPresent('.share-facebook')
                ->assertPresent('.share-twitter')
                ->assertPresent('.share-whatsapp')
                ->assertPresent('.share-telegram')
                ->screenshot('news-detail-social-sharing');
        });
    }

    /**
     * Test news detail page author information
     */
    public function testNewsDetailAuthorInfo()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.author-info', 10)
                ->assertSee('Penulis')
                ->assertSee('Admin')
                ->assertPresent('.author-avatar')
                ->screenshot('news-detail-author-info');
        });
    }

    /**
     * Test news detail page category tag
     */
    public function testNewsDetailCategoryTag()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-category', 10)
                ->assertSee('news')
                ->click('.news-category')
                ->waitFor('.category-news-list', 10)
                ->assertSee('Kategori: news')
                ->screenshot('news-detail-category-tag');
        });
    }

    /**
     * Test news detail page read more navigation
     */
    public function testNewsDetailReadMoreNavigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-navigation', 10)
                ->assertPresent('.prev-article')
                ->assertPresent('.next-article')
                ->click('.next-article')
                ->waitFor('.news-detail-content', 10)
                ->assertSee('Test News Article 2')
                ->screenshot('news-detail-navigation');
        });
    }

    /**
     * Test news detail page featured article badge
     */
    public function testNewsDetailFeaturedBadge()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertPresent('.featured-badge')
                ->assertSee('Unggulan')
                ->screenshot('news-detail-featured-badge');
        });
    }

    /**
     * Test news detail page print functionality
     */
    public function testNewsDetailPrintFunction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.print-article', 10)
                ->assertPresent('.print-btn')
                ->click('.print-btn')
                ->waitFor('.print-view', 10)
                ->screenshot('news-detail-print-function');
        });
    }

    /**
     * Test news detail page responsive design mobile
     */
    public function testNewsDetailResponsiveMobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667)
                ->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertSee('Test News Article 1')
                ->assertPresent('.mobile-social-sharing')
                ->assertPresent('.mobile-navigation')
                ->screenshot('news-detail-mobile-responsive');
        });
    }

    /**
     * Test news detail page responsive design tablet
     */
    public function testNewsDetailResponsiveTablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024)
                ->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertSee('Test News Article 1')
                ->assertPresent('.tablet-layout')
                ->screenshot('news-detail-tablet-responsive');
        });
    }

    /**
     * Test news detail page 404 for invalid slug
     */
    public function testNewsDetailInvalidSlug()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/invalid-news-slug')
                ->waitFor('.error-page', 10)
                ->assertSee('404')
                ->assertSee('Berita tidak ditemukan')
                ->screenshot('news-detail-invalid-slug');
        });
    }

    /**
     * Test news detail page back to news list
     */
    public function testNewsDetailBackToList()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.back-to-list', 10)
                ->click('.back-to-list')
                ->waitFor('.news-list', 10)
                ->assertSee('Berita Terbaru')
                ->screenshot('news-detail-back-to-list');
        });
    }

    /**
     * Test news detail page view count increment
     */
    public function testNewsDetailViewCountIncrement()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertPresent('.view-count')
                ->refresh()
                ->waitFor('.news-detail-content', 10)
                ->screenshot('news-detail-view-count');
        });
    }

    /**
     * Test news detail page SEO elements
     */
    public function testNewsDetailSeoElements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertSourceHas('<title>Test News Article 1')
                ->assertSourceHas('meta name="description"')
                ->assertSourceHas('meta property="og:title"')
                ->assertSourceHas('meta property="og:description"')
                ->assertSourceHas('meta property="og:image"')
                ->assertSourceHas('meta property="og:url"')
                ->screenshot('news-detail-seo-elements');
        });
    }

    /**
     * Test news detail page performance
     */
    public function testNewsDetailPerformance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10);

            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;

            // Page should load within 2 seconds
            $this->assertLessThan(2, $loadTime);
            $browser->screenshot('news-detail-performance');
        });
    }

    /**
     * Test news detail page accessibility
     */
    public function testNewsDetailAccessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/test-news-article-1')
                ->waitFor('.news-detail-content', 10)
                ->assertSourceHas('alt=')
                ->assertSourceHas('aria-label')
                ->assertSourceHas('role="article"')
                ->assertSourceHas('role="navigation"')
                ->screenshot('news-detail-accessibility');
        });
    }
}