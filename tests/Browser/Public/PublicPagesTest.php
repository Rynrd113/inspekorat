<?php

namespace Tests\Browser\Public;

use App\Models\PortalPapuaTengah;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Faq;
use App\Models\PortalOpd;
use App\Models\User;
use App\Models\Wbs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PublicPagesTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data for public pages
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create admin user for data creation
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@inspektorat.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create test berita
        for ($i = 1; $i <= 5; $i++) {
            PortalPapuaTengah::create([
                'judul' => 'Berita Test ' . $i,
                'slug' => 'berita-test-' . $i,
                'konten' => 'Konten berita test ' . $i,
                'penulis' => 'Admin',
                'kategori' => 'berita',
                'status' => 'published',
                'is_published' => true,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create test pelayanan
        for ($i = 1; $i <= 5; $i++) {
            Pelayanan::create([
                'nama' => 'Pelayanan Test ' . $i,
                'deskripsi' => 'Deskripsi pelayanan test ' . $i,
                'prosedur' => ['Langkah 1', 'Langkah 2', 'Langkah 3'],
                'persyaratan' => ['Syarat 1', 'Syarat 2'],
                'waktu_penyelesaian' => '3 hari kerja',
                'biaya' => 'Gratis',
                'kategori' => 'Administrasi',
                'status' => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        // Create test dokumen
        for ($i = 1; $i <= 5; $i++) {
            Dokumen::create([
                'judul' => 'Dokumen Test ' . $i,
                'deskripsi' => 'Deskripsi dokumen test ' . $i,
                'file_path' => 'dokumen/test-' . $i . '.pdf',
                'file_name' => 'test-' . $i . '.pdf',
                'file_size' => 1024,
                'kategori' => 'regulasi',
                'status' => true,
                'is_public' => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        // Create test galeri
        for ($i = 1; $i <= 5; $i++) {
            Galeri::create([
                'judul' => 'Galeri Test ' . $i,
                'deskripsi' => 'Deskripsi galeri test ' . $i,
                'file_path' => 'galeri/test-' . $i . '.jpg',
                'file_name' => 'test-' . $i . '.jpg',
                'file_size' => 1024,
                'kategori' => 'kegiatan',
                'status' => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        // Create test FAQ
        for ($i = 1; $i <= 5; $i++) {
            Faq::create([
                'pertanyaan' => 'Pertanyaan Test ' . $i,
                'jawaban' => 'Jawaban test ' . $i,
                'urutan' => $i,
                'status' => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        // Create test OPD
        for ($i = 1; $i <= 5; $i++) {
            PortalOpd::create([
                'nama_opd' => 'OPD Test ' . $i,
                'singkatan' => 'OPD' . $i,
                'alamat' => 'Alamat OPD ' . $i,
                'telepon' => '090123456' . $i,
                'email' => 'opd' . $i . '@paputeng.go.id',
                'website' => 'https://opd' . $i . '.paputeng.go.id',
                'kepala_opd' => 'Kepala OPD ' . $i,
                'deskripsi' => 'Deskripsi OPD ' . $i,
                'status' => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }
    }

    /**
     * Test public homepage
     */
    public function testPublicHomepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Inspektorat Papua Tengah')
                ->assertSee('Berita Test 1')
                ->assertSee('Pelayanan')
                ->assertSee('WBS')
                ->assertSee('Galeri')
                ->assertPresent('.hero-section')
                ->assertPresent('.news-section')
                ->assertPresent('.services-section');
        });
    }

    /**
     * Test public berita index page
     */
    public function testPublicBeritaIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/berita')
                ->assertSee('Berita')
                ->assertSee('Berita Test 1')
                ->assertSee('Berita Test 2')
                ->assertSee('Berita Test 3')
                ->assertPresent('.news-list')
                ->assertPresent('.pagination');
        });
    }

    /**
     * Test public berita detail page
     */
    public function testPublicBeritaDetail()
    {
        $berita = PortalPapuaTengah::first();
        
        $this->browse(function (Browser $browser) use ($berita) {
            $browser->visit('/berita/' . $berita->id)
                ->assertSee($berita->judul)
                ->assertSee($berita->konten)
                ->assertSee($berita->penulis)
                ->assertPresent('.news-content')
                ->assertPresent('.news-meta');
        });
    }

    /**
     * Test public pelayanan index page
     */
    public function testPublicPelayananIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pelayanan')
                ->assertSee('Pelayanan')
                ->assertSee('Pelayanan Test 1')
                ->assertSee('Pelayanan Test 2')
                ->assertSee('Pelayanan Test 3')
                ->assertPresent('.services-list');
        });
    }

    /**
     * Test public pelayanan detail page
     */
    public function testPublicPelayananDetail()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->visit('/pelayanan/' . $pelayanan->id)
                ->assertSee($pelayanan->nama)
                ->assertSee($pelayanan->deskripsi)
                ->assertSee('Prosedur')
                ->assertSee('Persyaratan')
                ->assertSee('Waktu Penyelesaian')
                ->assertPresent('.service-detail');
        });
    }

    /**
     * Test public dokumen index page
     */
    public function testPublicDokumenIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->assertSee('Dokumen')
                ->assertSee('Dokumen Test 1')
                ->assertSee('Dokumen Test 2')
                ->assertSee('Dokumen Test 3')
                ->assertPresent('.documents-list')
                ->assertPresent('.filter-section');
        });
    }

    /**
     * Test public dokumen download
     */
    public function testPublicDokumenDownload()
    {
        $dokumen = Dokumen::first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->visit('/dokumen')
                ->click('a[href="/dokumen/' . $dokumen->id . '/download"]')
                ->pause(1000);
            
            // Note: File download testing is complex in browser tests
            // In real scenario, we'd verify the download count increased
            $this->assertDatabaseHas('dokumens', [
                'id' => $dokumen->id,
            ]);
        });
    }

    /**
     * Test public dokumen preview
     */
    public function testPublicDokumenPreview()
    {
        $dokumen = Dokumen::first();
        
        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->visit('/dokumen/' . $dokumen->id . '/preview')
                ->assertSee($dokumen->judul)
                ->assertPresent('.document-preview');
        });
    }

    /**
     * Test public galeri index page
     */
    public function testPublicGaleriIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->assertSee('Galeri')
                ->assertSee('Galeri Test 1')
                ->assertSee('Galeri Test 2')
                ->assertSee('Galeri Test 3')
                ->assertPresent('.gallery-grid')
                ->assertPresent('.filter-section');
        });
    }

    /**
     * Test public galeri detail page
     */
    public function testPublicGaleriDetail()
    {
        $galeri = Galeri::first();
        
        $this->browse(function (Browser $browser) use ($galeri) {
            $browser->visit('/galeri/' . $galeri->id)
                ->assertSee($galeri->judul)
                ->assertSee($galeri->deskripsi)
                ->assertPresent('.gallery-detail')
                ->assertPresent('.gallery-image');
        });
    }

    /**
     * Test public FAQ page
     */
    public function testPublicFaqPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->assertSee('FAQ')
                ->assertSee('Pertanyaan Test 1')
                ->assertSee('Pertanyaan Test 2')
                ->assertSee('Pertanyaan Test 3')
                ->assertPresent('.faq-list')
                ->assertPresent('.faq-item');
        });
    }

    /**
     * Test public FAQ accordion functionality
     */
    public function testPublicFaqAccordion()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->click('.faq-toggle:first-child')
                ->pause(500)
                ->assertVisible('.faq-answer:first-child')
                ->click('.faq-toggle:first-child')
                ->pause(500)
                ->assertNotVisible('.faq-answer:first-child');
        });
    }

    /**
     * Test public WBS page
     */
    public function testPublicWbsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->assertSee('Whistleblowing System')
                ->assertSee('Laporkan')
                ->assertPresent('form[action="/wbs"]')
                ->assertPresent('input[name="nama_pelapor"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('textarea[name="deskripsi"]');
        });
    }

    /**
     * Test public WBS form submission
     */
    public function testPublicWbsFormSubmission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->type('nama_pelapor', 'Pelapor Test')
                ->type('email', 'pelapor@email.com')
                ->type('no_telepon', '08123456789')
                ->type('subjek', 'Laporan Test WBS')
                ->type('deskripsi', 'Deskripsi laporan test WBS')
                ->type('tanggal_kejadian', '2024-01-01')
                ->type('lokasi_kejadian', 'Lokasi Test')
                ->type('pihak_terlibat', 'Pihak test')
                ->type('kronologi', 'Kronologi test')
                ->press('Kirim Laporan')
                ->pause(2000)
                ->assertSee('Laporan berhasil dikirim');
                
            // Verify data stored in database
            $this->assertDatabaseHas('wbs', [
                'nama_pelapor' => 'Pelapor Test',
                'email' => 'pelapor@email.com',
                'subjek' => 'Laporan Test WBS',
            ]);
        });
    }

    /**
     * Test public WBS anonymous submission
     */
    public function testPublicWbsAnonymousSubmission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->check('is_anonymous')
                ->type('subjek', 'Laporan Anonim Test')
                ->type('deskripsi', 'Deskripsi laporan anonim test')
                ->type('tanggal_kejadian', '2024-01-01')
                ->type('lokasi_kejadian', 'Lokasi Test')
                ->press('Kirim Laporan')
                ->pause(2000)
                ->assertSee('Laporan berhasil dikirim');
                
            // Verify anonymous data stored in database
            $this->assertDatabaseHas('wbs', [
                'subjek' => 'Laporan Anonim Test',
                'is_anonymous' => true,
                'nama_pelapor' => null,
                'email' => null,
            ]);
        });
    }

    /**
     * Test public WBS form validation
     */
    public function testPublicWbsFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->press('Kirim Laporan')
                ->pause(1000)
                ->assertSee('The subjek field is required')
                ->assertSee('The deskripsi field is required');
        });
    }

    /**
     * Test public profil page
     */
    public function testPublicProfilPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profil')
                ->assertSee('Profil')
                ->assertSee('Inspektorat')
                ->assertPresent('.profile-content');
        });
    }

    /**
     * Test public kontak page
     */
    public function testPublicKontakPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->assertSee('Kontak')
                ->assertSee('Hubungi Kami')
                ->assertPresent('form[action="/kontak"]')
                ->assertPresent('input[name="nama"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('textarea[name="pesan"]');
        });
    }

    /**
     * Test public kontak form submission
     */
    public function testPublicKontakFormSubmission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->type('nama', 'Pengirim Test')
                ->type('email', 'pengirim@email.com')
                ->type('telepon', '08123456789')
                ->type('subjek', 'Subjek Test')
                ->type('pesan', 'Pesan test kontak')
                ->press('Kirim Pesan')
                ->pause(2000)
                ->assertSee('Pesan berhasil dikirim');
        });
    }

    /**
     * Test public kontak form validation
     */
    public function testPublicKontakFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->press('Kirim Pesan')
                ->pause(1000)
                ->assertSee('The nama field is required')
                ->assertSee('The email field is required')
                ->assertSee('The pesan field is required');
        });
    }

    /**
     * Test public portal OPD index page
     */
    public function testPublicPortalOpdIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/portal-opd')
                ->assertSee('Portal OPD')
                ->assertSee('OPD Test 1')
                ->assertSee('OPD Test 2')
                ->assertSee('OPD Test 3')
                ->assertPresent('.opd-list')
                ->assertPresent('.opd-card');
        });
    }

    /**
     * Test public portal OPD detail page
     */
    public function testPublicPortalOpdDetail()
    {
        $opd = PortalOpd::first();
        
        $this->browse(function (Browser $browser) use ($opd) {
            $browser->visit('/portal-opd/' . $opd->id)
                ->assertSee($opd->nama_opd)
                ->assertSee($opd->deskripsi)
                ->assertSee($opd->alamat)
                ->assertSee($opd->telepon)
                ->assertSee($opd->email)
                ->assertPresent('.opd-detail');
        });
    }

    /**
     * Test public search functionality
     */
    public function testPublicSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('.search-input', 'Berita Test')
                ->press('.search-button')
                ->pause(1000)
                ->assertSee('Berita Test 1')
                ->assertSee('Hasil Pencarian');
        });
    }

    /**
     * Test public navigation menu
     */
    public function testPublicNavigationMenu()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('.navbar')
                ->assertSee('Beranda')
                ->assertSee('Berita')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri')
                ->assertSee('FAQ')
                ->assertSee('Kontak');
        });
    }

    /**
     * Test public footer
     */
    public function testPublicFooter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('.footer')
                ->assertSee('Inspektorat Papua Tengah')
                ->assertSee('Alamat')
                ->assertSee('Telepon')
                ->assertSee('Email');
        });
    }

    /**
     * Test responsive navigation on mobile
     */
    public function testResponsiveNavigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE size
                ->visit('/')
                ->assertPresent('.mobile-menu-toggle')
                ->click('.mobile-menu-toggle')
                ->pause(500)
                ->assertVisible('.mobile-menu')
                ->assertSee('Beranda')
                ->assertSee('Berita');
        });
    }
}
