<?php

namespace Tests\Browser\Public;

use App\Models\PortalOpd;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Portal OPD Public Test With Results
 * Test Portal OPD public interface with database result verification
 */
class PortalOpdPublicTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestPortalOpdData();
    }

    private function createTestPortalOpdData()
    {
        // Create test Portal OPD data
        $inspektorat = PortalOpd::create([
            'nama_opd' => 'Inspektorat Papua Tengah',
            'singkatan' => 'INSPEKTORAT',
            'alamat' => 'Jl. Soekarno Hatta No. 123, Nabire',
            'telepon' => '0984-421234',
            'email' => 'inspektorat@paputeng.go.id',
            'website' => 'https://inspektorat.paputeng.go.id',
            'kepala_opd' => 'Dr. John Doe, M.Si',
            'nip_kepala' => '196501011990031001',
            'deskripsi' => 'Inspektorat Papua Tengah adalah lembaga pengawasan internal pemerintah yang bertugas melakukan pengawasan, pembinaan, dan evaluasi terhadap penyelenggaraan pemerintahan.',
            'visi' => 'Menjadi inspektorat yang profesional, independen, dan terpercaya dalam mendukung terwujudnya pemerintahan yang bersih dan akuntabel.',
            'misi' => ['Melakukan pengawasan yang efektif', 'Memberikan pembinaan yang berkelanjutan', 'Meningkatkan kualitas pelayanan publik'],
            'logo' => 'logo_inspektorat.png',
            'banner' => 'banner_inspektorat.jpg',
            'status' => true
        ]);

        $disdik = PortalOpd::create([
            'nama_opd' => 'Dinas Pendidikan Papua Tengah',
            'singkatan' => 'DISDIK',
            'alamat' => 'Jl. Pendidikan No. 456, Nabire',
            'telepon' => '0984-421567',
            'email' => 'disdik@paputeng.go.id',
            'website' => 'https://disdik.paputeng.go.id',
            'kepala_opd' => 'Dra. Jane Smith, M.Pd',
            'nip_kepala' => '196502021991032002',
            'deskripsi' => 'Dinas Pendidikan Papua Tengah bertanggung jawab atas penyelenggaraan pendidikan di wilayah Papua Tengah.',
            'visi' => 'Terwujudnya pendidikan yang berkualitas, merata, dan berkarakter.',
            'misi' => ['Meningkatkan kualitas pendidikan', 'Memperluas akses pendidikan', 'Mengembangkan karakter siswa'],
            'logo' => 'logo_disdik.png',
            'banner' => 'banner_disdik.jpg',
            'status' => true
        ]);

        $dinkes = PortalOpd::create([
            'nama_opd' => 'Dinas Kesehatan Papua Tengah',
            'singkatan' => 'DINKES',
            'alamat' => 'Jl. Kesehatan No. 789, Nabire',
            'telepon' => '0984-421890',
            'email' => 'dinkes@paputeng.go.id',
            'website' => 'https://dinkes.paputeng.go.id',
            'kepala_opd' => 'dr. Bob Johnson, Sp.A',
            'nip_kepala' => '196503031992033003',
            'deskripsi' => 'Dinas Kesehatan Papua Tengah bertugas menyelenggarakan urusan pemerintahan di bidang kesehatan.',
            'visi' => 'Mewujudkan masyarakat Papua Tengah yang sehat, mandiri, dan berkeadilan.',
            'misi' => ['Meningkatkan derajat kesehatan masyarakat', 'Memberikan pelayanan kesehatan yang berkualitas'],
            'logo' => 'logo_dinkes.png',
            'banner' => 'banner_dinkes.jpg',
            'status' => true
        ]);
    }

    /**
     * Test Portal OPD list display
     */
    public function testPortalOpdListDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->assertSee('Portal OPD Papua Tengah')
                ->assertSee('Inspektorat Papua Tengah')
                ->assertSee('Dinas Pendidikan Papua Tengah')
                ->assertSee('Dinas Kesehatan Papua Tengah')
                ->assertPresent('.opd-card')
                ->screenshot('portal-opd-list-display');

            // Verify OPD count
            $browser->assertSee('3 OPD terdaftar');
        });
    }

    /**
     * Test Portal OPD detail view with view count increment
     */
    public function testPortalOpdDetailViewWithViewCount()
    {
        $this->browse(function (Browser $browser) {
            $opdData = PortalOpd::first();
            $initialViewCount = $opdData->view_count;

            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-detail', 10)
                ->assertSee($opdData->nama_opd)
                ->assertSee($opdData->deskripsi)
                ->assertSee($opdData->visi)
                ->assertSee($opdData->misi)
                ->assertSee($opdData->alamat)
                ->assertSee($opdData->telepon)
                ->assertSee($opdData->email)
                ->screenshot('portal-opd-detail-view');

            // Verify view count increased
            $opdData->refresh();
            $this->assertEquals($initialViewCount + 1, $opdData->view_count);
        });
    }

    /**
     * Test Portal OPD search functionality
     */
    public function testPortalOpdSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.search-form', 10)
                ->type('search', 'Inspektorat')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Inspektorat Papua Tengah')
                ->assertDontSee('Dinas Pendidikan Papua Tengah')
                ->assertDontSee('Dinas Kesehatan Papua Tengah')
                ->screenshot('portal-opd-search-functionality');

            // Verify search results count
            $browser->assertSee('1 OPD ditemukan');
        });
    }

    /**
     * Test Portal OPD basic contact information display
     */
    public function testPortalOpdContactInformation()
    {
        $this->browse(function (Browser $browser) {
            $opdData = PortalOpd::first();
            
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-detail', 10)
                ->assertSee($opdData->alamat)
                ->assertSee($opdData->telepon)
                ->assertSee($opdData->email)
                ->screenshot('portal-opd-contact-information');
        });
    }

    /**
     * Test Portal OPD pagination functionality
     */
    public function testPortalOpdPaginationFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->assertSee('Portal OPD Papua Tengah')
                ->assertPresent('.opd-card')
                ->screenshot('portal-opd-pagination-functionality');

            // Check if pagination controls exist when there are more OPDs
            if ($browser->element('.pagination')) {
                $browser->assertPresent('.pagination .page-link');
            }
        });
    }

    /**
     * Test Portal OPD contact display
     */
    public function testPortalOpdContactDisplay()
    {
        $this->browse(function (Browser $browser) {
            $opdData = PortalOpd::first();
            
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-detail', 10)
                ->assertSee($opdData->alamat)
                ->assertSee($opdData->telepon)
                ->assertSee($opdData->email)
                ->screenshot('portal-opd-contact-display');
        });
    }    /**
     * Test Portal OPD filter functionality
     */
    public function testPortalOpdFilterFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.filter-form', 10)
                ->select('kategori', 'inspektorat')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Inspektorat Papua Tengah')
                ->assertDontSee('Dinas Pendidikan Papua Tengah')
                ->screenshot('portal-opd-filter-functionality');

            // Test clear filter
            $browser->click('.clear-filter')
                ->waitFor('.opd-list', 10)
                ->assertSee('Inspektorat Papua Tengah')
                ->assertSee('Dinas Pendidikan Papua Tengah')
                ->assertSee('Dinas Kesehatan Papua Tengah')
                ->screenshot('portal-opd-filter-cleared');
        });
    }

    /**
     * Test Portal OPD statistics display
     */
    public function testPortalOpdStatisticsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-statistics', 10)
                ->assertSee('Statistik Portal OPD')
                ->assertSee('Total OPD: 3')
                ->screenshot('portal-opd-statistics-display');
        });
    }

    /**
     * Test Portal OPD responsive design
     */
    public function testPortalOpdResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            // Test desktop view
            $browser->resize(1200, 800)
                ->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->assertPresent('.opd-card')
                ->screenshot('portal-opd-desktop-view');

            // Test tablet view
            $browser->resize(768, 1024)
                ->refresh()
                ->waitFor('.opd-list', 10)
                ->assertPresent('.opd-card')
                ->screenshot('portal-opd-tablet-view');

            // Test mobile view
            $browser->resize(375, 667)
                ->refresh()
                ->waitFor('.opd-list', 10)
                ->assertPresent('.opd-card')
                ->screenshot('portal-opd-mobile-view');
        });
    }

    /**
     * Test Portal OPD basic functionality
     */
    public function testPortalOpdBasicFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $opdData = PortalOpd::first();
            
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-detail', 10)
                ->assertSee($opdData->nama_opd)
                ->assertSee($opdData->deskripsi)
                ->screenshot('portal-opd-basic-functionality');
        });
    }

    /**
     * Test Portal OPD view count tracking
     */
    public function testPortalOpdViewCountTracking()
    {
        $this->browse(function (Browser $browser) {
            $opdData = PortalOpd::first();
            $initialViewCount = $opdData->view_count;

            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->click('.opd-card:first-child a')
                ->waitFor('.opd-detail', 10);

            // Verify view count increased
            $opdData->refresh();
            $this->assertEquals($initialViewCount + 1, $opdData->view_count);
        });
    }

    /**
     * Test Portal OPD data integrity
     */
    public function testPortalOpdDataIntegrity()
    {
        $this->browse(function (Browser $browser) {
            $opdCount = PortalOpd::count();
            
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->assertSee("$opdCount OPD")
                ->screenshot('portal-opd-data-integrity');
        });
    }

    /**
     * Test Portal OPD pagination
     */
    public function testPortalOpdPagination()
    {
        // Create additional OPD data for pagination testing
        for ($i = 4; $i <= 15; $i++) {
            PortalOpd::create([
                'nama_opd' => "OPD Test $i",
                'singkatan' => "OPD$i",
                'alamat' => "Alamat Test $i",
                'telepon' => "0984-42100$i",
                'email' => "opd$i@paputeng.go.id",
                'website' => "https://opd$i.paputeng.go.id",
                'deskripsi' => "Deskripsi OPD Test $i",
                'visi' => "Visi OPD Test $i",
                'misi' => "Misi OPD Test $i",
                'status' => 'active',
                'view_count' => 0
            ]);
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->waitFor('.opd-list', 10)
                ->assertSee('OPD Test 4')
                ->assertSee('OPD Test 10')
                ->screenshot('portal-opd-pagination-page-1');

            // Test pagination
            $browser->click('.pagination .next')
                ->waitFor('.opd-list', 10)
                ->assertSee('OPD Test 11')
                ->assertSee('OPD Test 15')
                ->screenshot('portal-opd-pagination-page-2');

            // Verify pagination info
            $browser->assertSee('Halaman 2 dari 2');
        });
    }
}