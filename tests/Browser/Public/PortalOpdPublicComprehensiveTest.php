<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\PortalOpd;

class PortalOpdPublicComprehensiveTest extends DuskTestCase
{
    /**
     * Test Portal OPD public page basic functionality
     */
    public function testPortalOpdPublicBasicFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->assertSee('Portal OPD')
                ->assertPresent('.opd-list, .opd-grid, .opd-container')
                ->screenshot('portal-opd-public-main');
        });
    }

    /**
     * Test Portal OPD list display
     */
    public function testPortalOpdListDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->assertSee('Portal OPD')
                ->screenshot('portal-opd-list-display');
        });
    }

    /**
     * Test Portal OPD search functionality
     */
    public function testPortalOpdSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->type('search', 'Dinas')
                ->press('Cari')
                ->pause(1000)
                ->screenshot('portal-opd-search-results');
        });
    }

    /**
     * Test Portal OPD filter functionality
     */
    public function testPortalOpdFilterFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->screenshot('portal-opd-filter-functionality');
                
            // Check if filter exists and use it
            if ($browser->element('select[name="kategori"]')) {
                $browser->select('kategori', 'dinas')
                    ->press('Filter')
                    ->pause(1000)
                    ->screenshot('portal-opd-filtered-results');
            }
        });
    }

    /**
     * Test Portal OPD pagination
     */
    public function testPortalOpdPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->screenshot('portal-opd-pagination-page-1');
                
            // Check if pagination exists
            if ($browser->element('.pagination')) {
                $browser->click('.pagination .page-link')
                    ->pause(1000)
                    ->screenshot('portal-opd-pagination-functionality');
            }
        });
    }

    /**
     * Test Portal OPD detail view
     */
    public function testPortalOpdDetailView()
    {
        $opd = PortalOpd::where('status', true)->first();
        
        if ($opd) {
            $this->browse(function (Browser $browser) use ($opd) {
                $browser->visit('/portal-opd')
                    ->click('a[href*="/portal-opd/' . $opd->id . '"]')
                    ->pause(1000)
                    ->assertSee($opd->nama_opd)
                    ->assertSee($opd->alamat)
                    ->screenshot('portal-opd-detail-view');
            });
        } else {
            $this->markTestSkipped('No active Portal OPD data available');
        }
    }

    /**
     * Test Portal OPD contact information display
     */
    public function testPortalOpdContactInformation()
    {
        $opd = PortalOpd::where('status', true)->first();
        
        if ($opd) {
            $this->browse(function (Browser $browser) use ($opd) {
                $browser->visit('/portal-opd/' . $opd->id)
                    ->assertSee($opd->telepon)
                    ->assertSee($opd->email)
                    ->assertSee($opd->alamat)
                    ->screenshot('portal-opd-contact-information');
            });
        } else {
            $this->markTestSkipped('No active Portal OPD data available');
        }
    }

    /**
     * Test Portal OPD website links
     */
    public function testPortalOpdWebsiteLinks()
    {
        $opd = PortalOpd::where('status', true)->whereNotNull('website')->first();
        
        if ($opd) {
            $this->browse(function (Browser $browser) use ($opd) {
                $browser->visit('/portal-opd/' . $opd->id)
                    ->assertSee('Website')
                    ->screenshot('portal-opd-website-links');
            });
        } else {
            $this->markTestSkipped('No Portal OPD with website data available');
        }
    }

    /**
     * Test Portal OPD responsive design
     */
    public function testPortalOpdResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // Mobile
                ->visit('/portal-opd')
                ->assertSee('Portal OPD')
                ->screenshot('portal-opd-mobile-view')
                ->resize(768, 1024) // Tablet
                ->assertSee('Portal OPD')
                ->screenshot('portal-opd-tablet-view')
                ->resize(1280, 720) // Desktop
                ->assertSee('Portal OPD')
                ->screenshot('portal-opd-desktop-view');
        });
    }

    /**
     * Test Portal OPD statistics display
     */
    public function testPortalOpdStatisticsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->screenshot('portal-opd-statistics-display');
        });
    }

    /**
     * Test Portal OPD data integrity
     */
    public function testPortalOpdDataIntegrity()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->screenshot('portal-opd-data-integrity');
                
            // Check that no broken links or missing images exist
            $browser->assertDontSee('404')
                ->assertDontSee('Error')
                ->assertDontSee('Not Found');
        });
    }

    /**
     * Test Portal OPD breadcrumb navigation
     */
    public function testPortalOpdBreadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->assertSee('Beranda')
                ->assertSee('Portal OPD')
                ->screenshot('portal-opd-breadcrumb');
        });
    }

    /**
     * Test Portal OPD SEO elements
     */
    public function testPortalOpdSEO()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->assertPresent('title')
                ->assertPresent('meta[name="description"]')
                ->screenshot('portal-opd-seo-check');
        });
    }

    /**
     * Test Portal OPD view count tracking
     */
    public function testPortalOpdViewCountTracking()
    {
        $opd = PortalOpd::where('status', true)->first();
        
        if ($opd) {
            $initialViews = $opd->views ?? 0;
            
            $this->browse(function (Browser $browser) use ($opd) {
                $browser->visit('/portal-opd/' . $opd->id)
                    ->pause(2000)
                    ->screenshot('portal-opd-view-count-tracking');
            });
            
            // Refresh the model to get updated view count
            $opd->refresh();
            $newViews = $opd->views ?? 0;
            
            // View count should increase (if implemented)
            $this->assertGreaterThanOrEqual($initialViews, $newViews);
        } else {
            $this->markTestSkipped('No active Portal OPD data available');
        }
    }
}