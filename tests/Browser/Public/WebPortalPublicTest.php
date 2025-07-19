<?php

namespace Tests\Browser\Public;

use App\Models\WebPortal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Web Portal Public Test
 * Test web portal public interface functionality
 */
class WebPortalPublicTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestWebPortalData();
    }

    private function createTestWebPortalData()
    {
        $categories = ['portal', 'website', 'aplikasi', 'sistem'];
        
        for ($i = 1; $i <= 12; $i++) {
            WebPortal::create([
                'nama_portal' => 'Web Portal Public Test ' . $i,
                'deskripsi' => 'Deskripsi web portal public test ' . $i . '. Portal web untuk layanan masyarakat.',
                'url' => 'https://portal' . $i . '.inspektorat.paputengah.go.id',
                'kategori' => $categories[($i - 1) % 4],
                'icon' => 'fas fa-globe',
                'status' => 'active',
                'urutan' => $i,
                'view_count' => rand(100, 1000),
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }

    /**
     * Test web portal public page loads successfully
     */
    public function testWebPortalPageLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->assertSee('Portal Web')
                ->assertSee('Web Portal Public Test 1')
                ->screenshot('public_web_portal_loads');
        });
    }

    /**
     * Test web portal grid display
     */
    public function testWebPortalGridDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->assertSee('Web Portal Public Test 1')
                ->assertSee('Web Portal Public Test 10')
                ->assertPresent('.portal-grid')
                ->screenshot('public_web_portal_grid');
        });
    }

    /**
     * Test web portal category filter
     */
    public function testWebPortalCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->select('kategori', 'portal')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('portal')
                ->screenshot('public_web_portal_category_filter');
        });
    }

    /**
     * Test web portal search functionality
     */
    public function testWebPortalSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->type('search', 'Test 1')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Web Portal Public Test 1')
                ->assertDontSee('Web Portal Public Test 2')
                ->screenshot('public_web_portal_search');
        });
    }

    /**
     * Test web portal link functionality
     */
    public function testWebPortalLinkFunctionality()
    {
        $webPortal = WebPortal::first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->visit('/web-portal')
                ->assertPresent("a[href='{$webPortal->url}']")
                ->assertPresent("a[target='_blank']")
                ->screenshot('public_web_portal_links');
        });
    }

    /**
     * Test web portal view count increment
     */
    public function testWebPortalViewCountIncrement()
    {
        $webPortal = WebPortal::first();
        $initialViewCount = $webPortal->view_count;
        
        $this->browse(function (Browser $browser) use ($webPortal, $initialViewCount) {
            $browser->visit('/web-portal')
                ->click("a[data-portal-id='{$webPortal->id}']")
                ->pause(2000);
                
            // Verify view count increased in database
            $updatedWebPortal = WebPortal::find($webPortal->id);
            $this->assertTrue($updatedWebPortal->view_count > $initialViewCount);
            
            $browser->screenshot('public_web_portal_view_count');
        });
    }

    /**
     * Test web portal responsive design
     */
    public function testWebPortalResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // Tablet
                ->visit('/web-portal')
                ->assertSee('Portal Web')
                ->screenshot('public_web_portal_tablet')
                ->resize(375, 667) // Mobile
                ->visit('/web-portal')
                ->assertSee('Portal Web')
                ->screenshot('public_web_portal_mobile');
        });
    }

    /**
     * Test web portal breadcrumb navigation
     */
    public function testWebPortalBreadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->assertSee('Beranda')
                ->assertSee('Portal Web')
                ->screenshot('public_web_portal_breadcrumb');
        });
    }

    /**
     * Test web portal icon display
     */
    public function testWebPortalIconDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->assertPresent('i.fas.fa-globe')
                ->screenshot('public_web_portal_icons');
        });
    }

    /**
     * Test web portal sorting functionality
     */
    public function testWebPortalSorting()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->select('sort', 'nama_asc')
                ->press('Urutkan')
                ->pause(1000)
                ->screenshot('public_web_portal_sorting');
        });
    }

    /**
     * Test web portal statistics display
     */
    public function testWebPortalStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->assertSee('Total Portal')
                ->assertSee('Portal Aktif')
                ->screenshot('public_web_portal_statistics');
        });
    }

    /**
     * Test web portal popular section
     */
    public function testWebPortalPopularSection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->assertSee('Portal Populer')
                ->screenshot('public_web_portal_popular');
        });
    }

    /**
     * Test web portal empty state
     */
    public function testWebPortalEmptyState()
    {
        // Remove all portals temporarily
        WebPortal::query()->delete();
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/web-portal')
                ->assertSee('Belum ada portal web')
                ->screenshot('public_web_portal_empty_state');
        });
    }
}
