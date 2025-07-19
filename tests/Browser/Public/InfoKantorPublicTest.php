<?php

namespace Tests\Browser\Public;

use App\Models\InfoKantor;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Info Kantor Public Test
 * Test info kantor public interface functionality
 */
class InfoKantorPublicTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestInfoKantorData();
    }

    private function createTestInfoKantorData()
    {
        $categories = ['pengumuman', 'informasi', 'berita', 'kegiatan'];
        
        for ($i = 1; $i <= 12; $i++) {
            InfoKantor::create([
                'judul' => 'Info Kantor Public Test ' . $i,
                'konten' => 'Konten informasi kantor public test ' . $i . '. Informasi penting untuk masyarakat.',
                'kategori' => $categories[($i - 1) % 4],
                'gambar' => 'info-kantor/public-' . $i . '.jpg',
                'status' => true,
                'view_count' => rand(50, 500),
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }

    /**
     * Test info kantor public page loads successfully
     */
    public function testInfoKantorPageLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/info-kantor')
                ->assertSee('Informasi Kantor')
                ->assertSee('Info Kantor Public Test 1')
                ->screenshot('public_info_kantor_loads');
        });
    }

    /**
     * Test info kantor list display
     */
    public function testInfoKantorListDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/info-kantor')
                ->assertSee('Info Kantor Public Test 1')
                ->assertSee('Info Kantor Public Test 10')
                ->assertSee('Selengkapnya')
                ->screenshot('public_info_kantor_list');
        });
    }

    /**
     * Test info kantor category filter
     */
    public function testInfoKantorCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/info-kantor')
                ->select('kategori', 'pengumuman')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('pengumuman')
                ->screenshot('public_info_kantor_category_filter');
        });
    }

    /**
     * Test info kantor search functionality
     */
    public function testInfoKantorSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/info-kantor')
                ->type('search', 'Test 1')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Info Kantor Public Test 1')
                ->assertDontSee('Info Kantor Public Test 2')
                ->screenshot('public_info_kantor_search');
        });
    }

    /**
     * Test info kantor detail view
     */
    public function testInfoKantorDetailView()
    {
        $infoKantor = InfoKantor::first();
        
        $this->browse(function (Browser $browser) use ($infoKantor) {
            $browser->visit("/info-kantor/{$infoKantor->id}")
                ->assertSee($infoKantor->judul)
                ->assertSee($infoKantor->konten)
                ->assertSee('Kembali')
                ->screenshot('public_info_kantor_detail');
        });
    }

    /**
     * Test info kantor pagination
     */
    public function testInfoKantorPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/info-kantor')
                ->assertVisible('.pagination')
                ->screenshot('public_info_kantor_pagination');
        });
    }

    /**
     * Test info kantor responsive design
     */
    public function testInfoKantorResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // Tablet
                ->visit('/info-kantor')
                ->assertSee('Informasi Kantor')
                ->screenshot('public_info_kantor_tablet')
                ->resize(375, 667) // Mobile
                ->visit('/info-kantor')
                ->assertSee('Informasi Kantor')
                ->screenshot('public_info_kantor_mobile');
        });
    }

    /**
     * Test info kantor breadcrumb navigation
     */
    public function testInfoKantorBreadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/info-kantor')
                ->assertSee('Beranda')
                ->assertSee('Informasi Kantor')
                ->screenshot('public_info_kantor_breadcrumb');
        });
    }

    /**
     * Test info kantor view count increment
     */
    public function testInfoKantorViewCountIncrement()
    {
        $infoKantor = InfoKantor::first();
        $initialViewCount = $infoKantor->view_count;
        
        $this->browse(function (Browser $browser) use ($infoKantor, $initialViewCount) {
            $browser->visit("/info-kantor/{$infoKantor->id}")
                ->assertSee($infoKantor->judul)
                ->pause(2000);
                
            // Verify view count increased in database
            $updatedInfoKantor = InfoKantor::find($infoKantor->id);
            $this->assertTrue($updatedInfoKantor->view_count > $initialViewCount);
            
            $browser->screenshot('public_info_kantor_view_count');
        });
    }

    /**
     * Test info kantor social sharing
     */
    public function testInfoKantorSocialSharing()
    {
        $infoKantor = InfoKantor::first();
        
        $this->browse(function (Browser $browser) use ($infoKantor) {
            $browser->visit("/info-kantor/{$infoKantor->id}")
                ->assertSee('Bagikan')
                ->assertPresent('a[href*="facebook.com"]')
                ->assertPresent('a[href*="twitter.com"]')
                ->assertPresent('a[href*="whatsapp.com"]')
                ->screenshot('public_info_kantor_social_sharing');
        });
    }

    /**
     * Test info kantor related items
     */
    public function testInfoKantorRelatedItems()
    {
        $infoKantor = InfoKantor::first();
        
        $this->browse(function (Browser $browser) use ($infoKantor) {
            $browser->visit("/info-kantor/{$infoKantor->id}")
                ->assertSee('Informasi Terkait')
                ->screenshot('public_info_kantor_related');
        });
    }
}
