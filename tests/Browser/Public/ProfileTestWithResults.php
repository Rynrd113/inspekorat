<?php

namespace Tests\Browser\Public;

use App\Models\PortalPapuaTengah;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Profile Test With Results
 * Test profile page functionality with database result verification
 */
class ProfileTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestProfileData();
    }

    private function createTestProfileData()
    {
        // Create test admin user
        $admin = User::create([
            'name' => 'Admin Profil',
            'email' => 'admin.profil@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin_profil'
        ]);

        // Create test profile content
        PortalPapuaTengah::create([
            'judul' => 'Sejarah Inspektorat Papua Tengah',
            'slug' => 'sejarah-inspektorat-papua-tengah',
            'isi' => 'Inspektorat Papua Tengah didirikan pada tahun 2002 bersamaan dengan pembentukan Provinsi Papua Tengah. Inspektorat bertugas melakukan pengawasan internal terhadap penyelenggaraan pemerintahan di lingkungan Pemerintah Provinsi Papua Tengah.',
            'kategori' => 'sejarah',
            'status' => 'published',
            'featured_image' => 'sejarah-inspektorat.jpg',
            'view_count' => 0,
            'meta_description' => 'Sejarah pembentukan dan perkembangan Inspektorat Papua Tengah',
            'meta_keywords' => 'sejarah, inspektorat, papua tengah, pengawasan',
            'created_by' => $admin->id
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Visi dan Misi Inspektorat',
            'slug' => 'visi-misi-inspektorat',
            'isi' => 'VISI: Terwujudnya pengawasan internal yang profesional, objektif, dan independen untuk mendukung tata kelola pemerintahan yang baik di Papua Tengah. MISI: 1. Melakukan pengawasan internal yang berkualitas, 2. Memberikan assurance dan consulting yang bernilai tambah, 3. Meningkatkan kapasitas pengawasan internal.',
            'kategori' => 'visi_misi',
            'status' => 'published',
            'featured_image' => 'visi-misi.jpg',
            'view_count' => 0,
            'meta_description' => 'Visi dan misi Inspektorat Papua Tengah',
            'meta_keywords' => 'visi, misi, inspektorat, papua tengah',
            'created_by' => $admin->id
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Struktur Organisasi',
            'slug' => 'struktur-organisasi',
            'isi' => 'Inspektorat Papua Tengah dipimpin oleh Inspektur yang membawahi 4 bidang: Bidang Pengawasan Pemerintahan, Bidang Pengawasan Pembangunan, Bidang Pengawasan Kemasyarakatan, dan Bidang Investigasi. Masing-masing bidang memiliki sub bidang yang menangani area pengawasan spesifik.',
            'kategori' => 'struktur_organisasi',
            'status' => 'published',
            'featured_image' => 'struktur-organisasi.jpg',
            'view_count' => 0,
            'meta_description' => 'Struktur organisasi Inspektorat Papua Tengah',
            'meta_keywords' => 'struktur, organisasi, inspektorat, papua tengah',
            'created_by' => $admin->id
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Tugas dan Fungsi',
            'slug' => 'tugas-fungsi',
            'isi' => 'Inspektorat memiliki tugas melaksanakan pengawasan internal terhadap penyelenggaraan pemerintahan. Fungsi utama meliputi: perencanaan program pengawasan, pelaksanaan pengawasan, pelaporan hasil pengawasan, dan tindak lanjut hasil pengawasan.',
            'kategori' => 'tugas_fungsi',
            'status' => 'published',
            'featured_image' => 'tugas-fungsi.jpg',
            'view_count' => 0,
            'meta_description' => 'Tugas dan fungsi Inspektorat Papua Tengah',
            'meta_keywords' => 'tugas, fungsi, inspektorat, pengawasan, papua tengah',
            'created_by' => $admin->id
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Program Kerja 2024',
            'slug' => 'program-kerja-2024',
            'isi' => 'Program kerja Inspektorat Papua Tengah tahun 2024 mencakup: audit reguler, audit investigasi, reviu laporan keuangan, evaluasi sistem pengendalian intern, dan pembinaan pengawasan. Target audit tahun 2024 adalah 50 kegiatan audit dengan fokus pada OPD prioritas.',
            'kategori' => 'program_kerja',
            'status' => 'published',
            'featured_image' => 'program-kerja-2024.jpg',
            'view_count' => 0,
            'meta_description' => 'Program kerja Inspektorat Papua Tengah tahun 2024',
            'meta_keywords' => 'program kerja, 2024, inspektorat, audit, pengawasan',
            'created_by' => $admin->id
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Prestasi dan Penghargaan',
            'slug' => 'prestasi-penghargaan',
            'isi' => 'Inspektorat Papua Tengah telah meraih berbagai prestasi: Juara 2 Lomba Inovasi Pengawasan Tingkat Nasional 2023, Predikat WBK (Wilayah Bebas Korupsi) 2022, dan Penghargaan Sistem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP) dengan predikat BB tahun 2023.',
            'kategori' => 'prestasi',
            'status' => 'published',
            'featured_image' => 'prestasi-penghargaan.jpg',
            'view_count' => 0,
            'meta_description' => 'Prestasi dan penghargaan Inspektorat Papua Tengah',
            'meta_keywords' => 'prestasi, penghargaan, inspektorat, papua tengah, wbk',
            'created_by' => $admin->id
        ]);
    }

    /**
     * Test profile page navigation and content display
     */
    public function testProfilePageNavigationAndContent()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitFor('.profile-container', 10)
                ->assertSee('Profil Inspektorat')
                ->assertSee('Sejarah')
                ->assertSee('Visi dan Misi')
                ->assertSee('Struktur Organisasi')
                ->assertSee('Tugas dan Fungsi')
                ->screenshot('profile-page-navigation');

            // Verify profile content count
            $profileCount = PortalPapuaTengah::whereIn('kategori', [
                'sejarah', 'visi_misi', 'struktur_organisasi', 'tugas_fungsi', 'program_kerja', 'prestasi'
            ])->where('status', 'published')->count();
            $this->assertEquals(6, $profileCount);
        });
    }

    /**
     * Test profile section view count increment
     */
    public function testProfileSectionViewCountIncrement()
    {
        $this->browse(function (Browser $browser) {
            $sejarah = PortalPapuaTengah::where('kategori', 'sejarah')->first();
            $initialViewCount = $sejarah->view_count;

            $browser->visit('/profil/sejarah')
                ->waitFor('.profile-section', 10)
                ->assertSee('Sejarah Inspektorat Papua Tengah')
                ->assertSee('Inspektorat Papua Tengah didirikan pada tahun 2002')
                ->screenshot('profile-sejarah-view');

            // Verify view count increased
            $sejarah->refresh();
            $this->assertEquals($initialViewCount + 1, $sejarah->view_count);
        });
    }

    /**
     * Test visi misi section display with results
     */
    public function testVisiMisiSectionDisplayWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/visi-misi')
                ->waitFor('.visi-misi-section', 10)
                ->assertSee('Visi dan Misi Inspektorat')
                ->assertSee('VISI:')
                ->assertSee('Terwujudnya pengawasan internal yang profesional')
                ->assertSee('MISI:')
                ->assertSee('Melakukan pengawasan internal yang berkualitas')
                ->screenshot('profile-visi-misi-display');

            // Verify visi misi content exists in database
            $visiMisi = PortalPapuaTengah::where('kategori', 'visi_misi')->first();
            $this->assertNotNull($visiMisi);
            $this->assertStringContainsString('VISI:', $visiMisi->isi);
            $this->assertStringContainsString('MISI:', $visiMisi->isi);
        });
    }

    /**
     * Test struktur organisasi section with results
     */
    public function testStrukturOrganisasiSectionWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/struktur-organisasi')
                ->waitFor('.struktur-organisasi-section', 10)
                ->assertSee('Struktur Organisasi')
                ->assertSee('Inspektur')
                ->assertSee('Bidang Pengawasan Pemerintahan')
                ->assertSee('Bidang Pengawasan Pembangunan')
                ->assertSee('Bidang Pengawasan Kemasyarakatan')
                ->assertSee('Bidang Investigasi')
                ->screenshot('profile-struktur-organisasi');

            // Verify struktur organisasi content
            $strukturOrganisasi = PortalPapuaTengah::where('kategori', 'struktur_organisasi')->first();
            $this->assertNotNull($strukturOrganisasi);
            $this->assertStringContainsString('Inspektur', $strukturOrganisasi->isi);
            $this->assertStringContainsString('4 bidang', $strukturOrganisasi->isi);
        });
    }

    /**
     * Test tugas dan fungsi section with results
     */
    public function testTugasFungsiSectionWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/tugas-fungsi')
                ->waitFor('.tugas-fungsi-section', 10)
                ->assertSee('Tugas dan Fungsi')
                ->assertSee('pengawasan internal')
                ->assertSee('perencanaan program pengawasan')
                ->assertSee('pelaksanaan pengawasan')
                ->assertSee('pelaporan hasil pengawasan')
                ->assertSee('tindak lanjut hasil pengawasan')
                ->screenshot('profile-tugas-fungsi');

            // Verify tugas fungsi content
            $tugasFungsi = PortalPapuaTengah::where('kategori', 'tugas_fungsi')->first();
            $this->assertNotNull($tugasFungsi);
            $this->assertStringContainsString('pengawasan internal', $tugasFungsi->isi);
            $this->assertStringContainsString('perencanaan program', $tugasFungsi->isi);
        });
    }

    /**
     * Test program kerja section with results
     */
    public function testProgramKerjaSectionWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/program-kerja')
                ->waitFor('.program-kerja-section', 10)
                ->assertSee('Program Kerja 2024')
                ->assertSee('audit reguler')
                ->assertSee('audit investigasi')
                ->assertSee('reviu laporan keuangan')
                ->assertSee('evaluasi sistem pengendalian intern')
                ->assertSee('50 kegiatan audit')
                ->screenshot('profile-program-kerja');

            // Verify program kerja content
            $programKerja = PortalPapuaTengah::where('kategori', 'program_kerja')->first();
            $this->assertNotNull($programKerja);
            $this->assertStringContainsString('2024', $programKerja->isi);
            $this->assertStringContainsString('50 kegiatan audit', $programKerja->isi);
        });
    }

    /**
     * Test prestasi dan penghargaan section with results
     */
    public function testPrestasiPenghargaanSectionWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/prestasi-penghargaan')
                ->waitFor('.prestasi-section', 10)
                ->assertSee('Prestasi dan Penghargaan')
                ->assertSee('Juara 2 Lomba Inovasi Pengawasan')
                ->assertSee('Predikat WBK')
                ->assertSee('Penghargaan Sistem Akuntabilitas Kinerja')
                ->assertSee('predikat BB tahun 2023')
                ->screenshot('profile-prestasi-penghargaan');

            // Verify prestasi content
            $prestasi = PortalPapuaTengah::where('kategori', 'prestasi')->first();
            $this->assertNotNull($prestasi);
            $this->assertStringContainsString('Juara 2', $prestasi->isi);
            $this->assertStringContainsString('WBK', $prestasi->isi);
        });
    }

    /**
     * Test profile section breadcrumb navigation
     */
    public function testProfileSectionBreadcrumbNavigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/visi-misi')
                ->waitFor('.breadcrumb', 10)
                ->assertSee('Beranda')
                ->assertSee('Profil')
                ->assertSee('Visi dan Misi')
                ->click('.breadcrumb a[href="/profil"]')
                ->waitFor('.profile-container', 10)
                ->assertSee('Profil Inspektorat')
                ->screenshot('profile-breadcrumb-navigation');
        });
    }

    /**
     * Test profile search functionality
     */
    public function testProfileSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->waitFor('.search-form', 10)
                ->type('search', 'pengawasan')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Visi dan Misi')
                ->assertSee('Tugas dan Fungsi')
                ->assertSee('Program Kerja')
                ->screenshot('profile-search-results');

            // Verify search results
            $searchResults = PortalPapuaTengah::where('isi', 'like', '%pengawasan%')
                ->where('status', 'published')
                ->whereIn('kategori', ['sejarah', 'visi_misi', 'struktur_organisasi', 'tugas_fungsi', 'program_kerja', 'prestasi'])
                ->count();
            $this->assertGreaterThan(0, $searchResults);
        });
    }

    /**
     * Test profile meta information display
     */
    public function testProfileMetaInformationDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/sejarah')
                ->waitFor('.profile-section', 10)
                ->assertSourceHas('meta name="description" content="Sejarah pembentukan dan perkembangan Inspektorat Papua Tengah"')
                ->assertSourceHas('meta name="keywords" content="sejarah, inspektorat, papua tengah, pengawasan"')
                ->screenshot('profile-meta-information');

            // Verify meta information in database
            $sejarah = PortalPapuaTengah::where('kategori', 'sejarah')->first();
            $this->assertNotNull($sejarah->meta_description);
            $this->assertNotNull($sejarah->meta_keywords);
        });
    }

    /**
     * Test profile featured image display
     */
    public function testProfileFeaturedImageDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/visi-misi')
                ->waitFor('.profile-section', 10)
                ->assertPresent('.featured-image')
                ->assertAttribute('.featured-image img', 'alt', 'Visi dan Misi Inspektorat')
                ->screenshot('profile-featured-image');

            // Verify featured image exists in database
            $visiMisi = PortalPapuaTengah::where('kategori', 'visi_misi')->first();
            $this->assertNotNull($visiMisi->featured_image);
            $this->assertEquals('visi-misi.jpg', $visiMisi->featured_image);
        });
    }

    /**
     * Test profile social media sharing
     */
    public function testProfileSocialMediaSharing()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/sejarah')
                ->waitFor('.social-sharing', 10)
                ->assertPresent('.share-facebook')
                ->assertPresent('.share-twitter')
                ->assertPresent('.share-linkedin')
                ->assertPresent('.share-whatsapp')
                ->screenshot('profile-social-sharing');

            // Test Facebook share
            $browser->click('.share-facebook')
                ->pause(2000)
                ->screenshot('profile-facebook-share');
        });
    }

    /**
     * Test profile print functionality
     */
    public function testProfilePrintFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/struktur-organisasi')
                ->waitFor('.print-button', 10)
                ->click('.print-button')
                ->pause(2000)
                ->screenshot('profile-print-dialog');

            // Verify print styles are applied
            $browser->assertPresent('.print-styles');
        });
    }

    /**
     * Test profile mobile responsiveness
     */
    public function testProfileMobileResponsiveness()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 dimensions
                ->visit('/profil')
                ->waitFor('.profile-container', 10)
                ->assertSee('Profil Inspektorat')
                ->click('.mobile-menu-toggle')
                ->waitFor('.mobile-menu', 5)
                ->assertSee('Sejarah')
                ->assertSee('Visi dan Misi')
                ->screenshot('profile-mobile-responsive');

            // Reset browser size
            $browser->resize(1920, 1080);
        });
    }

    /**
     * Test profile accessibility features
     */
    public function testProfileAccessibilityFeatures()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/sejarah')
                ->waitFor('.profile-section', 10)
                ->assertPresent('.skip-to-content')
                ->assertAttribute('.main-content', 'role', 'main')
                ->assertAttribute('.profile-navigation', 'role', 'navigation')
                ->assertAttribute('.profile-navigation', 'aria-label', 'Profile navigation')
                ->screenshot('profile-accessibility-features');

            // Test keyboard navigation
            $browser->keys('.profile-navigation a', ['{tab}'])
                ->pause(1000)
                ->screenshot('profile-keyboard-navigation');
        });
    }

    /**
     * Test profile last updated information
     */
    public function testProfileLastUpdatedInformation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/program-kerja')
                ->waitFor('.last-updated', 10)
                ->assertSee('Terakhir diperbarui:')
                ->screenshot('profile-last-updated');

            // Verify last updated information
            $programKerja = PortalPapuaTengah::where('kategori', 'program_kerja')->first();
            $this->assertNotNull($programKerja->updated_at);
        });
    }

    /**
     * Test profile statistics tracking
     */
    public function testProfileStatisticsTracking()
    {
        $this->browse(function (Browser $browser) {
            $visiMisi = PortalPapuaTengah::where('kategori', 'visi_misi')->first();
            $initialViewCount = $visiMisi->view_count;

            // Visit profile section multiple times
            for ($i = 1; $i <= 3; $i++) {
                $browser->visit('/profil/visi-misi')
                    ->waitFor('.profile-section', 10)
                    ->pause(1000);
            }

            $browser->screenshot('profile-statistics-tracking');

            // Verify view count increased
            $visiMisi->refresh();
            $this->assertEquals($initialViewCount + 3, $visiMisi->view_count);
        });
    }

    /**
     * Test profile content validation
     */
    public function testProfileContentValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/sejarah')
                ->waitFor('.profile-section', 10)
                ->assertSee('Inspektorat Papua Tengah didirikan pada tahun 2002')
                ->assertSee('pembentukan Provinsi Papua Tengah')
                ->assertSee('pengawasan internal')
                ->screenshot('profile-content-validation');

            // Verify content accuracy
            $sejarah = PortalPapuaTengah::where('kategori', 'sejarah')->first();
            $this->assertStringContainsString('2002', $sejarah->isi);
            $this->assertStringContainsString('Papua Tengah', $sejarah->isi);
        });
    }

    /**
     * Test profile error handling
     */
    public function testProfileErrorHandling()
    {
        $this->browse(function (Browser $browser) {
            // Test non-existent profile section
            $browser->visit('/profil/non-existent-section')
                ->waitFor('.error-message', 10)
                ->assertSee('Halaman tidak ditemukan')
                ->screenshot('profile-error-handling');

            // Test invalid profile URL
            $browser->visit('/profil/invalid-url-123')
                ->waitFor('.error-message', 10)
                ->assertSee('Halaman tidak ditemukan')
                ->screenshot('profile-invalid-url');
        });
    }

    /**
     * Test profile download functionality
     */
    public function testProfileDownloadFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil/struktur-organisasi')
                ->waitFor('.download-button', 10)
                ->click('.download-pdf')
                ->pause(3000)
                ->screenshot('profile-download-pdf');

            // Verify download tracking
            $this->assertDatabaseHas('download_logs', [
                'content_type' => 'profile',
                'content_id' => PortalPapuaTengah::where('kategori', 'struktur_organisasi')->first()->id,
                'download_type' => 'pdf'
            ]);
        });
    }
}
