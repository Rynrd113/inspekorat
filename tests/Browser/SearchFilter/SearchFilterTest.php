<?php

namespace Tests\Browser\SearchFilter;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithForms;
use Tests\DuskTestCase;

class SearchFilterTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithForms;

    /**
     * Test basic search functionality
     */
    public function test_basic_search_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test search pada module berita
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Test Search')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee('Test Search');
            
            // Test search pada module WBS
            $browser->visit('/admin/wbs')
                    ->type('input[name="search"]', 'Test WBS')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee('Test WBS');
            
            // Test search pada module documents
            $browser->visit('/admin/documents')
                    ->type('input[name="search"]', 'Test Document')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee('Test Document');
        });
    }

    /**
     * Test advanced search with multiple criteria
     */
    public function test_advanced_search_multiple_criteria()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="advanced-search"]')
                    ->waitFor('.advanced-search-form', 10)
                    ->fillForm([
                        'judul' => 'Advanced Search',
                        'kategori' => 'umum',
                        'status' => 'published',
                        'tanggal_mulai' => '2024-01-01',
                        'tanggal_akhir' => '2024-12-31',
                        'penulis' => 'Admin',
                    ])
                    ->press('Search')
                    ->waitForReload()
                    ->assertSee('Advanced Search');
        });
    }

    /**
     * Test search with wildcard
     */
    public function test_search_with_wildcard()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $searchTerms = [
                'test*',
                '*search',
                '*middle*',
                'test?',
                'te%st',
            ];
            
            foreach ($searchTerms as $term) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $term)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search with boolean operators
     */
    public function test_search_with_boolean_operators()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $booleanSearches = [
                'test AND search',
                'test OR search',
                'test NOT search',
                '"exact phrase"',
                '(test OR search) AND berita',
            ];
            
            foreach ($booleanSearches as $search) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $search)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search with special characters
     */
    public function test_search_with_special_characters()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $specialCharacters = [
                'test@example.com',
                'test#hashtag',
                'test$dollar',
                'test%percent',
                'test&ampersand',
                'test+plus',
                'test-minus',
                'test_underscore',
                'test.dot',
                'test/slash',
                'test\\backslash',
                'test(parentheses)',
                'test[brackets]',
                'test{braces}',
                'test<>angles',
                'test"quotes"',
                "test'apostrophe",
            ];
            
            foreach ($specialCharacters as $char) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $char)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search with empty query
     */
    public function test_search_with_empty_query()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', '')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test search with very long query
     */
    public function test_search_with_long_query()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $longQuery = str_repeat('very long search query ', 50);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', $longQuery)
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test filter by single category
     */
    public function test_filter_by_single_category()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $categories = ['umum', 'pengumuman', 'berita_daerah', 'opini'];
            
            foreach ($categories as $category) {
                $browser->visit('/admin/berita')
                        ->select('select[name="kategori"]', $category)
                        ->press('Filter')
                        ->waitForReload()
                        ->assertSee(ucfirst($category));
            }
        });
    }

    /**
     * Test filter by multiple categories
     */
    public function test_filter_by_multiple_categories()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->script('
                        const select = document.querySelector("select[name=\"kategori[]\"]");
                        if (select) {
                            select.multiple = true;
                            const options = select.querySelectorAll("option");
                            options[1].selected = true;
                            options[2].selected = true;
                        }
                    ')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test filter by date range
     */
    public function test_filter_by_date_range()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="tanggal_mulai"]', '2024-01-01')
                    ->type('input[name="tanggal_akhir"]', '2024-12-31')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test filter by author
     */
    public function test_filter_by_author()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->select('select[name="penulis"]', 'admin')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertSee('Admin');
        });
    }

    /**
     * Test filter by status
     */
    public function test_filter_by_status()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $statuses = ['draft', 'published', 'archived'];
            
            foreach ($statuses as $status) {
                $browser->visit('/admin/berita')
                        ->select('select[name="status"]', $status)
                        ->press('Filter')
                        ->waitForReload()
                        ->assertSee(ucfirst($status));
            }
        });
    }

    /**
     * Test combined search and filter
     */
    public function test_combined_search_and_filter()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Test')
                    ->select('select[name="kategori"]', 'umum')
                    ->select('select[name="status"]', 'published')
                    ->type('input[name="tanggal_mulai"]', '2024-01-01')
                    ->type('input[name="tanggal_akhir"]', '2024-12-31')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test sort functionality
     */
    public function test_sort_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $sortOptions = [
                'title_asc' => 'Judul A-Z',
                'title_desc' => 'Judul Z-A',
                'date_asc' => 'Tanggal Lama',
                'date_desc' => 'Tanggal Baru',
                'views_asc' => 'Views Sedikit',
                'views_desc' => 'Views Banyak',
            ];
            
            foreach ($sortOptions as $value => $label) {
                $browser->visit('/admin/berita')
                        ->select('select[name="sort"]', $value)
                        ->press('Sort')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test pagination with search
     */
    public function test_pagination_with_search()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Test')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertVisible('.pagination');
            
            if ($browser->element('.pagination .page-link[rel="next"]')) {
                $browser->click('.pagination .page-link[rel="next"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search suggestions/autocomplete
     */
    public function test_search_suggestions()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Te')
                    ->pause(1000) // Wait for suggestions
                    ->waitFor('.search-suggestions', 5)
                    ->assertVisible('.search-suggestions')
                    ->click('.search-suggestions li:first-child')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test saved searches
     */
    public function test_saved_searches()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Important Search')
                    ->select('select[name="kategori"]', 'umum')
                    ->click('button[data-action="save-search"]')
                    ->waitFor('.modal', 10)
                    ->type('input[name="search_name"]', 'My Important Search')
                    ->press('Save')
                    ->waitForReload()
                    ->assertSee('Search saved successfully');
            
            // Use saved search
            $browser->click('button[data-action="load-search"]')
                    ->waitFor('.dropdown-menu', 5)
                    ->click('a[data-search="My Important Search"]')
                    ->waitForReload()
                    ->assertInputValue('input[name="search"]', 'Important Search');
        });
    }

    /**
     * Test search history
     */
    public function test_search_history()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $searches = ['First Search', 'Second Search', 'Third Search'];
            
            foreach ($searches as $search) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $search)
                        ->press('button[type="submit"]')
                        ->waitForReload();
            }
            
            $browser->click('button[data-action="search-history"]')
                    ->waitFor('.search-history', 5)
                    ->assertSee('First Search')
                    ->assertSee('Second Search')
                    ->assertSee('Third Search');
        });
    }

    /**
     * Test search with tags
     */
    public function test_search_with_tags()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="tags"]', 'tag1,tag2,tag3')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test search performance
     */
    public function test_search_performance()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Performance Test')
                    ->press('button[type="submit"]')
                    ->waitForReload();
            
            $endTime = microtime(true);
            $searchTime = $endTime - $startTime;
            
            // Search should complete within 2 seconds
            $this->assertLessThan(2, $searchTime, 'Search took too long: ' . $searchTime . ' seconds');
        });
    }

    /**
     * Test search with special queries
     */
    public function test_search_with_special_queries()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $specialQueries = [
                'site:example.com',
                'filetype:pdf',
                'inurl:admin',
                'intitle:test',
                'cache:example.com',
                'related:example.com',
            ];
            
            foreach ($specialQueries as $query) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $query)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search export functionality
     */
    public function test_search_export()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Export Test')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->click('button[data-action="export-results"]')
                    ->waitFor('.modal', 10)
                    ->select('select[name="export_format"]', 'csv')
                    ->press('Export')
                    ->pause(2000); // Wait for export
        });
    }

    /**
     * Test search with fuzzy matching
     */
    public function test_search_with_fuzzy_matching()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $typos = [
                'tets', // test
                'searcj', // search
                'dokumnet', // document
                'berita', // berita (correct)
            ];
            
            foreach ($typos as $typo) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $typo)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search with different languages
     */
    public function test_search_with_different_languages()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $multilangQueries = [
                'berita',
                'news',
                'informasi',
                'information',
                'pengumuman',
                'announcement',
            ];
            
            foreach ($multilangQueries as $query) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $query)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search with numeric values
     */
    public function test_search_with_numeric_values()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $numericQueries = [
                '2024',
                '2023-2024',
                '> 1000',
                '< 500',
                '100-200',
                '1,000',
                '1.5',
                '50%',
            ];
            
            foreach ($numericQueries as $query) {
                $browser->visit('/admin/berita')
                        ->type('input[name="search"]', $query)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test search with location-based queries
     */
    public function test_search_with_location_queries()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $locationQueries = [
                'Jakarta',
                'Papua Tengah',
                'Kota Nabire',
                'near:Jakarta',
                'within:10km',
                'radius:Jakarta,50km',
            ];
            
            foreach ($locationQueries as $query) {
                $browser->visit('/admin/wbs')
                        ->type('input[name="search"]', $query)
                        ->press('button[type="submit"]')
                        ->waitForReload()
                        ->assertVisible('.data-table');
            }
        });
    }

    /**
     * Test clear all filters
     */
    public function test_clear_all_filters()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', 'Test')
                    ->select('select[name="kategori"]', 'umum')
                    ->select('select[name="status"]', 'published')
                    ->type('input[name="tanggal_mulai"]', '2024-01-01')
                    ->press('Filter')
                    ->waitForReload()
                    ->click('button[data-action="clear-filters"]')
                    ->waitForReload()
                    ->assertInputValue('input[name="search"]', '')
                    ->assertSelected('select[name="kategori"]', '')
                    ->assertSelected('select[name="status"]', '')
                    ->assertInputValue('input[name="tanggal_mulai"]', '');
        });
    }
}
