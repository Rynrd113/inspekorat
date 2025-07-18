<?php

namespace Tests\Browser\Public;

use App\Models\PortalPapuaTengah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * News Test With Results
 * Test news functionality with database result verification
 */
class NewsTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestNewsArticles();
    }

    private function createTestNewsArticles()
    {
        // Create test news articles
        PortalPapuaTengah::create([
            'judul' => 'Inspektorat Papua Tengah Luncurkan Program Pengawasan Terintegrasi',
            'slug' => 'inspektorat-papua-tengah-luncurkan-program-pengawasan-terintegrasi',
            'isi' => 'Inspektorat Papua Tengah resmi meluncurkan program pengawasan terintegrasi untuk meningkatkan kualitas pengawasan internal di seluruh OPD. Program ini bertujuan untuk menciptakan sistem pengawasan yang lebih efektif dan efisien.',
            'kategori' => 'news',
            'status' => 'published',
            'featured' => true,
            'author' => 'Admin Berita',
            'view_count' => 0,
            'created_at' => now()->subDays(1)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Sosialisasi Whistle Blowing System untuk Pegawai OPD',
            'slug' => 'sosialisasi-whistle-blowing-system-untuk-pegawai-opd',
            'isi' => 'Inspektorat Papua Tengah mengadakan sosialisasi Whistle Blowing System (WBS) untuk seluruh pegawai OPD. Kegiatan ini bertujuan untuk meningkatkan kesadaran dan partisipasi pegawai dalam pelaporan pelanggaran.',
            'kategori' => 'announcement',
            'status' => 'published',
            'featured' => false,
            'author' => 'Tim Sosialisasi',
            'view_count' => 5,
            'created_at' => now()->subDays(2)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Rapat Koordinasi Bulanan dengan Seluruh OPD',
            'slug' => 'rapat-koordinasi-bulanan-dengan-seluruh-opd',
            'isi' => 'Inspektorat Papua Tengah mengadakan rapat koordinasi bulanan dengan seluruh OPD untuk membahas progress pengawasan dan evaluasi kinerja. Rapat ini dihadiri oleh perwakilan dari 25 OPD di Papua Tengah.',
            'kategori' => 'news',
            'status' => 'published',
            'featured' => true,
            'author' => 'Sekretaris',
            'view_count' => 10,
            'created_at' => now()->subDays(3)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Workshop Peningkatan Kapasitas Auditor Internal',
            'slug' => 'workshop-peningkatan-kapasitas-auditor-internal',
            'isi' => 'Inspektorat Papua Tengah menyelenggarakan workshop peningkatan kapasitas auditor internal selama 3 hari. Workshop ini diikuti oleh 50 auditor dari berbagai OPD dengan materi terkini tentang teknik audit modern.',
            'kategori' => 'event',
            'status' => 'published',
            'featured' => false,
            'author' => 'Koordinator Pelatihan',
            'view_count' => 15,
            'created_at' => now()->subDays(4)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Peluncuran Portal Transparansi Keuangan Daerah',
            'slug' => 'peluncuran-portal-transparansi-keuangan-daerah',
            'isi' => 'Pemerintah Papua Tengah melalui Inspektorat meluncurkan portal transparansi keuangan daerah untuk meningkatkan akuntabilitas pengelolaan keuangan. Portal ini dapat diakses secara online oleh seluruh masyarakat.',
            'kategori' => 'announcement',
            'status' => 'published',
            'featured' => true,
            'author' => 'Tim IT',
            'view_count' => 20,
            'created_at' => now()->subDays(5)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Kunjungan Kerja Inspektorat ke Kabupaten Mimika',
            'slug' => 'kunjungan-kerja-inspektorat-ke-kabupaten-mimika',
            'isi' => 'Tim Inspektorat Papua Tengah melakukan kunjungan kerja ke Kabupaten Mimika untuk melakukan supervisi dan pembinaan terhadap implementasi sistem pengawasan internal di tingkat kabupaten.',
            'kategori' => 'news',
            'status' => 'published',
            'featured' => false,
            'author' => 'Tim Supervisi',
            'view_count' => 12,
            'created_at' => now()->subDays(6)
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Pengumuman Hasil Audit Kinerja OPD Tahun 2024',
            'slug' => 'pengumuman-hasil-audit-kinerja-opd-tahun-2024',
            'isi' => 'Inspektorat Papua Tengah mengumumkan hasil audit kinerja OPD tahun 2024. Hasil audit menunjukkan peningkatan kinerja yang signifikan pada sebagian besar OPD dengan beberapa rekomendasi perbaikan.',
            'kategori' => 'announcement',
            'status' => 'published',
            'featured' => true,
            'author' => 'Tim Audit',
            'view_count' => 25,
            'created_at' => now()->subDays(7)
        ]);
    }

    /**
     * Test news view count increment
     */
    public function testNewsViewCountIncrement()
    {
        $this->browse(function (Browser $browser) {
            $newsArticle = PortalPapuaTengah::first();
            $initialViewCount = $newsArticle->view_count;

            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->click('.news-item:first-child a')
                ->waitFor('.news-detail', 10)
                ->assertSee($newsArticle->judul)
                ->screenshot('news-view-count-increment');

            // Verify view count increased
            $newsArticle->refresh();
            $this->assertEquals($initialViewCount + 1, $newsArticle->view_count);
        });
    }

    /**
     * Test news search functionality with results
     */
    public function testNewsSearchWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.search-form', 10)
                ->type('search', 'Inspektorat')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Inspektorat Papua Tengah Luncurkan Program Pengawasan Terintegrasi')
                ->assertSee('Kunjungan Kerja Inspektorat ke Kabupaten Mimika')
                ->assertSee('Pengumuman Hasil Audit Kinerja OPD Tahun 2024')
                ->screenshot('news-search-results');

            // Verify search results count
            $browser->assertSee('7 berita ditemukan');
        });
    }

    /**
     * Test news category filter with results
     */
    public function testNewsCategoryFilterWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.category-filter', 10)
                ->select('kategori', 'announcement')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Sosialisasi Whistle Blowing System untuk Pegawai OPD')
                ->assertSee('Peluncuran Portal Transparansi Keuangan Daerah')
                ->assertSee('Pengumuman Hasil Audit Kinerja OPD Tahun 2024')
                ->assertDontSee('Rapat Koordinasi Bulanan dengan Seluruh OPD')
                ->screenshot('news-category-filter');

            // Verify category filter results count
            $browser->assertSee('3 berita kategori announcement');
        });
    }

    /**
     * Test news featured articles display
     */
    public function testNewsFeaturedArticlesDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.featured-news', 10)
                ->assertSee('Berita Utama')
                ->assertSee('Inspektorat Papua Tengah Luncurkan Program Pengawasan Terintegrasi')
                ->assertSee('Rapat Koordinasi Bulanan dengan Seluruh OPD')
                ->assertSee('Peluncuran Portal Transparansi Keuangan Daerah')
                ->assertSee('Pengumuman Hasil Audit Kinerja OPD Tahun 2024')
                ->screenshot('news-featured-articles');

            // Verify featured articles are marked
            $browser->assertPresent('.featured-badge');
        });
    }

    /**
     * Test news popular articles display
     */
    public function testNewsPopularArticlesDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.popular-news', 10)
                ->assertSee('Berita Populer')
                ->assertSee('Pengumuman Hasil Audit Kinerja OPD Tahun 2024') // Highest view count
                ->assertSee('Peluncuran Portal Transparansi Keuangan Daerah')
                ->assertSee('Workshop Peningkatan Kapasitas Auditor Internal')
                ->screenshot('news-popular-articles');

            // Verify popular articles are ordered by view count
            $browser->assertSeeInOrder([
                'Pengumuman Hasil Audit Kinerja OPD Tahun 2024',
                'Peluncuran Portal Transparansi Keuangan Daerah',
                'Workshop Peningkatan Kapasitas Auditor Internal'
            ]);
        });
    }

    /**
     * Test news pagination with results
     */
    public function testNewsPaginationWithResults()
    {
        // Create additional news articles for pagination testing
        for ($i = 8; $i <= 20; $i++) {
            PortalPapuaTengah::create([
                'judul' => "Test News Article $i",
                'slug' => "test-news-article-$i",
                'isi' => "Content for test news article $i",
                'kategori' => 'news',
                'status' => 'published',
                'featured' => false,
                'author' => 'Test Author',
                'view_count' => 0
            ]);
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->assertSee('Test News Article 8')
                ->assertSee('Test News Article 12')
                ->screenshot('news-pagination-page-1');

            // Test pagination
            $browser->click('.pagination .next')
                ->waitFor('.news-list', 10)
                ->assertSee('Test News Article 13')
                ->assertSee('Test News Article 20')
                ->screenshot('news-pagination-page-2');

            // Verify pagination info
            $browser->assertSee('Halaman 2 dari 2');
        });
    }

    /**
     * Test news sorting with results
     */
    public function testNewsSortingWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.sort-options', 10)
                ->select('sort', 'newest')
                ->press('Sort')
                ->waitFor('.sorted-results', 10)
                ->screenshot('news-sort-newest');

            // Verify newest article appears first
            $browser->assertSeeIn('.news-item:first-child', 'Inspektorat Papua Tengah Luncurkan Program Pengawasan Terintegrasi');

            // Test sort by most viewed
            $browser->select('sort', 'most_viewed')
                ->press('Sort')
                ->waitFor('.sorted-results', 10)
                ->screenshot('news-sort-most-viewed');

            // Verify most viewed article appears first
            $browser->assertSeeIn('.news-item:first-child', 'Pengumuman Hasil Audit Kinerja OPD Tahun 2024');
        });
    }

    /**
     * Test news sharing functionality
     */
    public function testNewsSharingFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->click('.news-item:first-child a')
                ->waitFor('.news-detail', 10)
                ->click('.share-btn')
                ->waitFor('.share-options', 10)
                ->assertSee('Bagikan Berita')
                ->assertPresent('.share-facebook')
                ->assertPresent('.share-twitter')
                ->assertPresent('.share-whatsapp')
                ->assertPresent('.share-telegram')
                ->screenshot('news-sharing-functionality');

            // Test copy link functionality
            $browser->click('.copy-link-btn')
                ->waitForText('Link copied to clipboard', 10)
                ->screenshot('news-copy-link');
        });
    }

    /**
     * Test news related articles display
     */
    public function testNewsRelatedArticlesDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->click('.news-item:first-child a')
                ->waitFor('.related-articles', 10)
                ->assertSee('Berita Terkait')
                ->assertSee('Rapat Koordinasi Bulanan dengan Seluruh OPD')
                ->assertSee('Kunjungan Kerja Inspektorat ke Kabupaten Mimika')
                ->screenshot('news-related-articles');

            // Test related article click
            $browser->click('.related-article:first-child')
                ->waitFor('.news-detail', 10)
                ->screenshot('news-related-article-click');
        });
    }

    /**
     * Test news author tracking
     */
    public function testNewsAuthorTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->click('.news-item:first-child a')
                ->waitFor('.news-detail', 10)
                ->assertSee('Oleh: Admin Berita')
                ->assertPresent('.author-info')
                ->screenshot('news-author-tracking');

            // Test author articles
            $browser->click('.author-articles-btn')
                ->waitFor('.author-articles', 10)
                ->assertSee('Berita oleh Admin Berita')
                ->screenshot('news-author-articles');
        });
    }

    /**
     * Test news statistics tracking
     */
    public function testNewsStatisticsTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-statistics', 10)
                ->assertSee('Total Berita: 7')
                ->assertSee('Total Dibaca: 87')
                ->assertSee('Berita Hari Ini: 1')
                ->assertSee('Berita Minggu Ini: 7')
                ->screenshot('news-statistics-tracking');

            // Test statistics after interaction
            $browser->click('.news-item:first-child a')
                ->waitFor('.news-detail', 10)
                ->visit('/berita')
                ->waitFor('.news-statistics', 10)
                ->assertSee('Total Dibaca: 88')
                ->screenshot('news-statistics-after-interaction');
        });
    }

    /**
     * Test news comment functionality
     */
    public function testNewsCommentFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->click('.news-item:first-child a')
                ->waitFor('.news-detail', 10)
                ->scrollTo('.comment-section')
                ->waitFor('.comment-form', 10)
                ->type('comment_name', 'John Doe')
                ->type('comment_email', 'john@example.com')
                ->type('comment_content', 'This is a test comment on the news article.')
                ->press('Submit Comment')
                ->waitForText('Comment submitted successfully', 10)
                ->screenshot('news-comment-functionality');

            // Verify comment was saved
            $this->assertDatabaseHas('news_comments', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'content' => 'This is a test comment on the news article.'
            ]);
        });
    }

    /**
     * Test news bookmark functionality
     */
    public function testNewsBookmarkFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $newsArticle = PortalPapuaTengah::first();

            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->click('.news-item:first-child a')
                ->waitFor('.news-detail', 10)
                ->click('.bookmark-btn')
                ->waitForText('Article bookmarked', 10)
                ->screenshot('news-bookmark-functionality');

            // Verify bookmark was saved
            $this->assertDatabaseHas('news_bookmarks', [
                'news_id' => $newsArticle->id,
                'ip_address' => '127.0.0.1'
            ]);
        });
    }

    /**
     * Test news search with no results
     */
    public function testNewsSearchWithNoResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.search-form', 10)
                ->type('search', 'NonExistentNewsArticle')
                ->press('Search')
                ->waitFor('.no-results', 10)
                ->assertSee('Tidak ada berita yang ditemukan')
                ->assertSee('Coba kata kunci yang berbeda')
                ->screenshot('news-search-no-results');
        });
    }

    /**
     * Test news archive functionality
     */
    public function testNewsArchiveFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-archive', 10)
                ->assertSee('Arsip Berita')
                ->assertSee('Januari 2025')
                ->click('.archive-month')
                ->waitFor('.archive-results', 10)
                ->assertSee('Berita Januari 2025')
                ->screenshot('news-archive-functionality');

            // Test year archive
            $browser->click('.archive-year-2025')
                ->waitFor('.archive-results', 10)
                ->assertSee('Berita Tahun 2025')
                ->screenshot('news-archive-year');
        });
    }

    /**
     * Test news RSS feed functionality
     */
    public function testNewsRssFeedFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.rss-feed', 10)
                ->assertSee('RSS Feed')
                ->click('.rss-feed-btn')
                ->waitFor('.rss-modal', 10)
                ->assertSee('Subscribe to RSS Feed')
                ->assertPresent('.rss-url')
                ->screenshot('news-rss-feed-functionality');
        });
    }

    /**
     * Test news reading time calculation
     */
    public function testNewsReadingTimeCalculation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitFor('.news-list', 10)
                ->click('.news-item:first-child a')
                ->waitFor('.news-detail', 10)
                ->assertSee('Estimasi waktu baca:')
                ->assertSee('menit')
                ->assertPresent('.reading-time')
                ->screenshot('news-reading-time-calculation');
        });
    }
}