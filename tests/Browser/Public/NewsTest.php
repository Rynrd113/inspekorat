<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NewsTest extends DuskTestCase
{
    /**
     * Test news list page loads successfully
     */
    public function test_news_list_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->assertSee('Berita')
                ->assertSee('Inspektorat Papua Tengah')
                ->screenshot('news_list_page_main');
        });
    }

    /**
     * Test news list page displays articles
     */
    public function test_news_list_page_displays_articles()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->assertPresent('.news-item, .berita-item, .article-item, .card')
                ->screenshot('news_list_articles');
        });
    }

    /**
     * Test news list page navigation breadcrumb
     */
    public function test_news_list_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->assertSee('Beranda')
                ->assertSee('Berita')
                ->screenshot('news_list_breadcrumb');
        });
    }

    /**
     * Test news list page search functionality
     */
    public function test_news_list_page_search_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->whenAvailable('input[name="search"]', function ($input) {
                    $input->type('inspektorat')
                        ->press('Enter');
                })
                ->waitFor('body', 3)
                ->screenshot('news_list_search_functionality');
        });
    }

    /**
     * Test news list page category filter
     */
    public function test_news_list_page_category_filter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->whenAvailable('select[name="kategori"]', function ($select) {
                    $select->selectByValue('berita');
                })
                ->waitFor('body', 2)
                ->screenshot('news_list_category_filter');
        });
    }

    /**
     * Test news list page sort functionality
     */
    public function test_news_list_page_sort_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->whenAvailable('select[name="sort"]', function ($select) {
                    $select->selectByValue('terpopuler');
                })
                ->waitFor('body', 2)
                ->screenshot('news_list_sort_functionality');
        });
    }

    /**
     * Test news list page article click
     */
    public function test_news_list_page_article_click()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->whenAvailable('a[href*="berita/"]', function ($link) {
                    $link->click();
                })
                ->waitFor('body', 3)
                ->screenshot('news_list_article_click');
        });
    }

    /**
     * Test news list page pagination
     */
    public function test_news_list_page_pagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->whenAvailable('.pagination', function ($pagination) {
                    $pagination->assertPresent('a, button');
                })
                ->screenshot('news_list_pagination');
        });
    }

    /**
     * Test news list page responsive design on mobile
     */
    public function test_news_list_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/berita')
                ->waitForText('Berita')
                ->assertSee('Berita')
                ->screenshot('news_list_mobile_responsive');
        });
    }

    /**
     * Test news list page responsive design on tablet
     */
    public function test_news_list_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/berita')
                ->waitForText('Berita')
                ->assertSee('Berita')
                ->screenshot('news_list_tablet_responsive');
        });
    }

    /**
     * Test news detail page loads successfully
     */
    public function test_news_detail_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->screenshot('news_detail_page_main');
        });
    }

    /**
     * Test news detail page displays article content
     */
    public function test_news_detail_page_displays_content()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->whenAvailable('.article-content, .berita-content', function ($content) {
                    $content->assertPresent('h1, h2, .title, .judul');
                })
                ->screenshot('news_detail_content');
        });
    }

    /**
     * Test news detail page breadcrumb
     */
    public function test_news_detail_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->assertSee('Beranda')
                ->assertSee('Berita')
                ->screenshot('news_detail_breadcrumb');
        });
    }

    /**
     * Test news detail page related articles
     */
    public function test_news_detail_page_related_articles()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->whenAvailable('.related-articles, .berita-terkait', function ($related) {
                    $related->assertSee('Berita Terkait');
                })
                ->screenshot('news_detail_related_articles');
        });
    }

    /**
     * Test news detail page author information
     */
    public function test_news_detail_page_author_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->whenAvailable('.author, .penulis', function ($author) {
                    $author->assertPresent('span, .author-name');
                })
                ->screenshot('news_detail_author_info');
        });
    }

    /**
     * Test news detail page publication date
     */
    public function test_news_detail_page_publication_date()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->whenAvailable('.publication-date, .tanggal-publikasi, .date', function ($date) {
                    $date->assertSee('2024');
                })
                ->screenshot('news_detail_publication_date');
        });
    }

    /**
     * Test news detail page view count
     */
    public function test_news_detail_page_view_count()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->whenAvailable('.views, .view-count', function ($views) {
                    $views->assertSee('views');
                })
                ->screenshot('news_detail_view_count');
        });
    }

    /**
     * Test news detail page category display
     */
    public function test_news_detail_page_category_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->whenAvailable('.category, .kategori', function ($category) {
                    $category->assertPresent('span, .badge');
                })
                ->screenshot('news_detail_category_display');
        });
    }

    /**
     * Test news detail page social sharing
     */
    public function test_news_detail_page_social_sharing()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->whenAvailable('.social-share, .share-buttons', function ($share) {
                    $share->assertPresent('a, button');
                })
                ->screenshot('news_detail_social_sharing');
        });
    }

    /**
     * Test news detail page back to list
     */
    public function test_news_detail_page_back_to_list()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->click('a[href="/berita"]')
                ->waitForText('Berita')
                ->assertPathIs('/berita')
                ->screenshot('news_detail_back_to_list');
        });
    }

    /**
     * Test news list page empty state
     */
    public function test_news_list_page_empty_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita?search=nonexistentnews')
                ->waitForText('Berita')
                ->whenAvailable('.empty-state, .no-results', function ($empty) {
                    $empty->assertSee('Tidak ada berita');
                })
                ->screenshot('news_list_empty_state');
        });
    }

    /**
     * Test news list page SEO elements
     */
    public function test_news_list_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->assertTitleContains('Berita')
                ->screenshot('news_list_seo_check');
        });
    }

    /**
     * Test news list page performance
     */
    public function test_news_list_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'News list page should load within 5 seconds');
    }

    /**
     * Test news list page back to homepage
     */
    public function test_news_list_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('news_list_back_to_homepage');
        });
    }

    /**
     * Test news list page footer links
     */
    public function test_news_list_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('news_list_footer_links');
        });
    }

    /**
     * Test news list page navigation menu consistency
     */
    public function test_news_list_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->waitForText('Berita')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('Kontak')
                ->assertSee('WBS')
                ->assertSee('Portal OPD')
                ->screenshot('news_list_navigation_consistency');
        });
    }

    /**
     * Test news detail page navigation menu consistency
     */
    public function test_news_detail_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita/1')
                ->waitFor('body', 5)
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('Kontak')
                ->assertSee('WBS')
                ->assertSee('Portal OPD')
                ->screenshot('news_detail_navigation_consistency');
        });
    }

    /**
     * Test news detail page responsive design on mobile
     */
    public function test_news_detail_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/berita/1')
                ->waitFor('body', 5)
                ->screenshot('news_detail_mobile_responsive');
        });
    }

    /**
     * Test news detail page responsive design on tablet
     */
    public function test_news_detail_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/berita/1')
                ->waitFor('body', 5)
                ->screenshot('news_detail_tablet_responsive');
        });
    }
}
