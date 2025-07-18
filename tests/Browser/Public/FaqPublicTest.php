<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FaqPublicTest extends DuskTestCase
{
    /**
     * Test FAQ page loads successfully
     */
    public function test_faq_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->assertSee('FAQ')
                ->assertSee('Frequently Asked Questions')
                ->screenshot('faq_page_main');
        });
    }

    /**
     * Test FAQ page displays FAQ list
     */
    public function test_faq_page_displays_faq_list()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->assertPresent('.faq-item, .accordion-item, .card')
                ->screenshot('faq_list_display');
        });
    }

    /**
     * Test FAQ page navigation breadcrumb
     */
    public function test_faq_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->assertSee('Beranda')
                ->assertSee('FAQ')
                ->screenshot('faq_breadcrumb');
        });
    }

    /**
     * Test FAQ page search functionality
     */
    public function test_faq_page_search_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('input[name="search"]', function ($input) {
                    $input->type('inspektorat')
                        ->press('Enter');
                })
                ->waitFor('body', 3)
                ->screenshot('faq_search_functionality');
        });
    }

    /**
     * Test FAQ page category filter
     */
    public function test_faq_page_category_filter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('select[name="kategori"]', function ($select) {
                    $select->selectByValue('layanan');
                })
                ->waitFor('body', 2)
                ->screenshot('faq_category_filter');
        });
    }

    /**
     * Test FAQ page accordion functionality
     */
    public function test_faq_page_accordion_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.faq-item:first-child .question, .accordion-button:first-child', function ($question) {
                    $question->click();
                })
                ->waitFor('.answer, .accordion-collapse', 2)
                ->screenshot('faq_accordion_functionality');
        });
    }

    /**
     * Test FAQ page popular FAQs section
     */
    public function test_faq_page_popular_faqs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.popular-faqs, .faq-populer', function ($popular) {
                    $popular->assertSee('Populer');
                })
                ->screenshot('faq_popular_faqs');
        });
    }

    /**
     * Test FAQ page categories sidebar
     */
    public function test_faq_page_categories_sidebar()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.categories, .kategori-sidebar', function ($categories) {
                    $categories->assertSee('Kategori');
                })
                ->screenshot('faq_categories_sidebar');
        });
    }

    /**
     * Test FAQ page responsive design on mobile
     */
    public function test_faq_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/faq')
                ->waitForText('FAQ')
                ->assertSee('FAQ')
                ->screenshot('faq_mobile_responsive');
        });
    }

    /**
     * Test FAQ page responsive design on tablet
     */
    public function test_faq_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/faq')
                ->waitForText('FAQ')
                ->assertSee('FAQ')
                ->screenshot('faq_tablet_responsive');
        });
    }

    /**
     * Test FAQ page question and answer display
     */
    public function test_faq_page_question_answer_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.faq-item', function ($item) {
                    $item->assertPresent('.question, .pertanyaan')
                        ->assertPresent('.answer, .jawaban');
                })
                ->screenshot('faq_question_answer_display');
        });
    }

    /**
     * Test FAQ page view count increment
     */
    public function test_faq_page_view_count_increment()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.faq-item:first-child .question', function ($question) {
                    $question->click();
                })
                ->waitFor('.answer', 2)
                ->screenshot('faq_view_count_increment');
        });
    }

    /**
     * Test FAQ page empty state
     */
    public function test_faq_page_empty_state()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq?search=nonexistentfaq')
                ->waitForText('FAQ')
                ->whenAvailable('.empty-state, .no-results', function ($empty) {
                    $empty->assertSee('Tidak ada FAQ');
                })
                ->screenshot('faq_empty_state');
        });
    }

    /**
     * Test FAQ page pagination
     */
    public function test_faq_page_pagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.pagination', function ($pagination) {
                    $pagination->assertPresent('a, button');
                })
                ->screenshot('faq_pagination');
        });
    }

    /**
     * Test FAQ page SEO elements
     */
    public function test_faq_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->assertTitleContains('FAQ')
                ->screenshot('faq_seo_check');
        });
    }

    /**
     * Test FAQ page performance
     */
    public function test_faq_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 5 seconds
        $this->assertLessThan(5, $loadTime, 'FAQ page should load within 5 seconds');
    }

    /**
     * Test FAQ page back to homepage
     */
    public function test_faq_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->assertPathIs('/')
                ->screenshot('faq_back_to_homepage');
        });
    }

    /**
     * Test FAQ page footer links
     */
    public function test_faq_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('faq_footer_links');
        });
    }

    /**
     * Test FAQ page navigation menu consistency
     */
    public function test_faq_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Berita')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('Kontak')
                ->assertSee('WBS')
                ->assertSee('Portal OPD')
                ->screenshot('faq_navigation_consistency');
        });
    }

    /**
     * Test FAQ page search clear functionality
     */
    public function test_faq_page_search_clear()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq?search=test')
                ->waitForText('FAQ')
                ->whenAvailable('a[href="/faq"]:not([href*="search"])', function ($link) {
                    $link->click();
                })
                ->waitFor('body', 2)
                ->screenshot('faq_search_clear');
        });
    }

    /**
     * Test FAQ page category link functionality
     */
    public function test_faq_page_category_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.categories a:first-child', function ($link) {
                    $link->click();
                })
                ->waitFor('body', 2)
                ->screenshot('faq_category_links');
        });
    }

    /**
     * Test FAQ page keyboard navigation
     */
    public function test_faq_page_keyboard_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.faq-item:first-child .question', function ($question) {
                    $question->keys(' '); // Space key to toggle accordion
                })
                ->waitFor('.answer', 2)
                ->screenshot('faq_keyboard_navigation');
        });
    }

    /**
     * Test FAQ page multiple accordion items
     */
    public function test_faq_page_multiple_accordion_items()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.faq-item:first-child .question', function ($question) {
                    $question->click();
                })
                ->waitFor('.answer', 2)
                ->whenAvailable('.faq-item:nth-child(2) .question', function ($question) {
                    $question->click();
                })
                ->waitFor('body', 2)
                ->screenshot('faq_multiple_accordion_items');
        });
    }

    /**
     * Test FAQ page helpful voting (if available)
     */
    public function test_faq_page_helpful_voting()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.helpful-vote, .vote-buttons', function ($vote) {
                    $vote->assertPresent('button');
                })
                ->screenshot('faq_helpful_voting');
        });
    }

    /**
     * Test FAQ page contact support link
     */
    public function test_faq_page_contact_support_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.contact-support, .hubungi-kami', function ($support) {
                    $support->assertSee('Hubungi');
                })
                ->screenshot('faq_contact_support_link');
        });
    }
}
