<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\PortalPapuaTengah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BeritaTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin Berita',
            'email' => 'admin.berita@inspektorat.id',
            'password' => bcrypt('adminberita123'),
            'role' => 'admin_berita',
            'is_active' => true,
        ]);

        // Create test berita data
        $this->createTestBeritaData();
    }

    private function createTestBeritaData()
    {
        $categories = ['Berita', 'Pengumuman', 'Kegiatan', 'Audit', 'Sosialisasi'];
        
        for ($i = 1; $i <= 15; $i++) {
            PortalPapuaTengah::create([
                'judul' => 'Berita Test ' . $i,
                'slug' => 'berita-test-' . $i,
                'konten' => 'Ini adalah konten berita test ' . $i . '. Konten ini berisi informasi penting mengenai kegiatan inspektorat Papua Tengah.',
                'isi' => 'Isi berita test ' . $i . ' yang lebih lengkap dan detail mengenai kegiatan inspektorat Papua Tengah. Berita ini membahas tentang pelaksanaan audit internal di berbagai OPD.',
                'penulis' => $this->admin->name,
                'kategori' => $categories[($i - 1) % 5],
                'thumbnail' => 'berita/thumbnails/berita-' . $i . '.jpg',
                'gambar' => 'berita/berita-' . $i . '.jpg',
                'status' => 'published',
                'is_featured' => $i <= 5,
                'views' => rand(50, 500),
                'published_at' => now()->subDays($i),
            ]);
        }
    }

    /**
     * Test Berita index page
     */
    public function testBeritaIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->assertSee('Berita')
                ->assertSee('Tambah Berita')
                ->assertSee('Berita Test 1')
                ->assertSee('Berita Test 2')
                ->assertSee('Berita Test 3');
        });
    }

    /**
     * Test Berita pagination
     */
    public function testBeritaPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->assertSee('Berita')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Berita Test 11')
                ->assertSee('Berita Test 12');
        });
    }

    /**
     * Test Berita search functionality
     */
    public function testBeritaSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->type('search', 'Berita Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Berita Test 5')
                ->assertDontSee('Berita Test 1')
                ->assertDontSee('Berita Test 2');
        });
    }

    /**
     * Test Berita create page
     */
    public function testBeritaCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->clickLink('Tambah Berita')
                ->pause(1000)
                ->assertPathIs('/admin/portal-papua-tengah/create')
                ->assertSee('Tambah Berita')
                ->assertPresent('input[name="judul"]')
                ->assertPresent('input[name="slug"]')
                ->assertPresent('textarea[name="konten"]')
                ->assertPresent('textarea[name="isi"]')
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('input[name="thumbnail"]')
                ->assertPresent('input[name="gambar"]')
                ->assertPresent('select[name="status"]')
                ->assertPresent('input[name="is_featured"]')
                ->assertPresent('input[name="published_at"]');
        });
    }

    /**
     * Test Berita store functionality
     */
    public function testBeritaStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Pelaksanaan Audit Internal di OPD Kabupaten Papua Tengah')
                ->type('slug', 'pelaksanaan-audit-internal-opd-kabupaten-papua-tengah')
                ->type('konten', 'Inspektorat Papua Tengah melaksanakan audit internal di berbagai OPD untuk memastikan akuntabilitas dan transparansi pengelolaan keuangan daerah.')
                ->type('isi', 'Nabire - Inspektorat Papua Tengah melaksanakan audit internal di berbagai Organisasi Perangkat Daerah (OPD) untuk memastikan akuntabilitas dan transparansi pengelolaan keuangan daerah. Kegiatan ini dilaksanakan dalam rangka peningkatan kualitas tata kelola pemerintahan.')
                ->select('kategori', 'Audit')
                ->attach('thumbnail', __DIR__ . '/../../fixtures/berita-thumbnail.jpg')
                ->attach('gambar', __DIR__ . '/../../fixtures/berita-image.jpg')
                ->select('status', 'published')
                ->check('is_featured')
                ->type('published_at', now()->format('Y-m-d H:i'))
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/portal-papua-tengah')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Pelaksanaan Audit Internal di OPD Kabupaten Papua Tengah');
        });
    }

    /**
     * Test Berita store validation
     */
    public function testBeritaStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papa-tengah/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The judul field is required')
                ->assertSee('The slug field is required')
                ->assertSee('The konten field is required')
                ->assertSee('The isi field is required')
                ->assertSee('The kategori field is required');
        });
    }

    /**
     * Test Berita show page
     */
    public function testBeritaShowPage()
    {
        $berita = PortalPapuaTengah::first();
        
        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->click('a[href="/admin/portal-papua-tengah/' . $berita->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/portal-papua-tengah/' . $berita->id)
                ->assertSee($berita->judul)
                ->assertSee($berita->konten)
                ->assertSee($berita->isi)
                ->assertSee($berita->kategori)
                ->assertSee('Views: ' . $berita->view_count)
                ->assertPresent('img[src*="' . $berita->gambar . '"]');
        });
    }

    /**
     * Test Berita edit page
     */
    public function testBeritaEditPage()
    {
        $berita = PortalPapuaTengah::first();
        
        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->click('a[href="/admin/portal-papua-tengah/' . $berita->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/portal-papua-tengah/' . $berita->id . '/edit')
                ->assertSee('Edit Berita')
                ->assertInputValue('judul', $berita->judul)
                ->assertInputValue('slug', $berita->slug)
                ->assertSee($berita->konten)
                ->assertSee($berita->isi);
        });
    }

    /**
     * Test Berita update functionality
     */
    public function testBeritaUpdate()
    {
        $berita = PortalPapuaTengah::first();
        
        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/' . $berita->id . '/edit')
                ->clear('judul')
                ->type('judul', 'Berita Updated')
                ->clear('slug')
                ->type('slug', 'berita-updated')
                ->clear('konten')
                ->type('konten', 'Konten berita yang sudah diupdate')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/portal-papua-tengah')
                ->assertSee('Data berhasil diperbarui')
                ->assertSee('Berita Updated');
        });
    }

    /**
     * Test Berita delete functionality
     */
    public function testBeritaDelete()
    {
        $berita = PortalPapuaTengah::first();
        $beritaTitle = $berita->judul;
        
        $this->browse(function (Browser $browser) use ($berita, $beritaTitle) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus berita ini?\')) { document.getElementById(\'delete-form-' . $berita->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/portal-papua-tengah')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($beritaTitle);
        });
    }

    /**
     * Test Berita category filter
     */
    public function testBeritaCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->select('kategori', 'Berita')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Berita Test 1')
                ->assertSee('Berita Test 6')
                ->select('kategori', 'Audit')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Berita Test 4')
                ->assertSee('Berita Test 9');
        });
    }

    /**
     * Test Berita status filter
     */
    public function testBeritaStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->select('status', 'published')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Berita Test 1')
                ->assertSee('Berita Test 2')
                ->select('status', 'draft')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Tidak ada data');
        });
    }

    /**
     * Test Berita featured toggle
     */
    public function testBeritaFeaturedToggle()
    {
        $berita = PortalPapuaTengah::first();
        
        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->click('input[name="is_featured"][data-id="' . $berita->id . '"]')
                ->pause(1000)
                ->assertSee('Featured status berhasil diubah');
        });
    }

    /**
     * Test Berita slug auto-generation
     */
    public function testBeritaSlugAutoGeneration()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita dengan Slug Otomatis')
                ->pause(1000)
                ->assertInputValue('slug', 'berita-dengan-slug-otomatis');
        });
    }

    /**
     * Test Berita rich text editor
     */
    public function testBeritaRichTextEditor()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->assertPresent('.rich-text-editor')
                ->click('.rich-text-editor')
                ->keys('.rich-text-editor', 'Konten dengan {bold}text tebal{/bold} dan {italic}text miring{/italic}')
                ->pause(1000)
                ->assertSee('Konten dengan text tebal dan text miring');
        });
    }

    /**
     * Test Berita image upload
     */
    public function testBeritaImageUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->attach('gambar', __DIR__ . '/../../fixtures/berita-image.jpg')
                ->pause(1000)
                ->assertSee('Image uploaded successfully')
                ->assertPresent('img[src*="berita-image"]');
        });
    }

    /**
     * Test Berita thumbnail generation
     */
    public function testBeritaThumbnailGeneration()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->attach('gambar', __DIR__ . '/../../fixtures/berita-image.jpg')
                ->pause(1000)
                ->assertSee('Thumbnail generated automatically')
                ->assertPresent('img[src*="thumbnail"]');
        });
    }

    /**
     * Test Berita duplicate functionality
     */
    public function testBeritaDuplicate()
    {
        $berita = PortalPapuaTengah::first();
        
        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->click('button[onclick="duplicateBerita(' . $berita->id . ')"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertSee('Berita berhasil diduplikasi')
                ->assertSee('Copy of ' . $berita->judul);
        });
    }

    /**
     * Test Berita bulk actions
     */
    public function testBeritaBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->check('select-all')
                ->select('bulk-action', 'publish')
                ->press('Apply')
                ->pause(1000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test Berita responsive design
     */
    public function testBeritaResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->assertSee('Berita')
                ->assertSee('Tambah Berita')
                ->resize(768, 1024) // iPad size
                ->assertSee('Berita')
                ->assertSee('Tambah Berita')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test Berita preview functionality
     */
    public function testBeritaPreview()
    {
        $berita = PortalPapuaTengah::first();
        
        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/' . $berita->id . '/edit')
                ->click('button[onclick="previewBerita()"]')
                ->pause(1000)
                ->assertSee('Preview Berita')
                ->assertSee($berita->judul)
                ->assertSee($berita->konten);
        });
    }

    /**
     * Test Berita statistics
     */
    public function testBeritaStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->assertSee('Total Berita')
                ->assertSee('Berita Published')
                ->assertSee('Berita Draft')
                ->assertSee('Berita Featured')
                ->assertSee('Total Views')
                ->assertSee('Berita per Kategori');
        });
    }

    /**
     * Test Berita export functionality
     */
    public function testBeritaExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->click('a[href="/admin/portal-papua-tengah/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test Berita scheduled publishing
     */
    public function testBeritaScheduledPublishing()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita Terjadwal')
                ->type('slug', 'berita-terjadwal')
                ->type('konten', 'Konten berita terjadwal')
                ->type('isi', 'Isi berita terjadwal')
                ->select('kategori', 'Berita')
                ->select('status', 'scheduled')
                ->type('published_at', now()->addDays(1)->format('Y-m-d H:i'))
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Berita berhasil dijadwalkan')
                ->assertSee('Berita Terjadwal');
        });
    }

    /**
     * Test Berita SEO optimization
     */
    public function testBeritaSeoOptimization()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita SEO Friendly')
                ->type('meta_description', 'Deskripsi meta untuk SEO')
                ->type('meta_keywords', 'berita,inspektorat,audit,papua tengah')
                ->type('og_title', 'Berita SEO Friendly - Inspektorat Papua Tengah')
                ->type('og_description', 'Deskripsi Open Graph untuk media sosial')
                ->attach('og_image', __DIR__ . '/../../fixtures/og-image.jpg')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('SEO data berhasil disimpan');
        });
    }

    /**
     * Test Berita advanced search
     */
    public function testBeritaAdvancedSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->clickLink('Advanced Search')
                ->pause(1000)
                ->type('judul', 'Test')
                ->type('konten', 'konten')
                ->select('kategori', 'Berita')
                ->select('status', 'published')
                ->check('is_featured')
                ->type('published_from', '2024-01-01')
                ->type('published_to', '2024-12-31')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Berita Test 1');
        });
    }

    /**
     * Test Berita content approval workflow
     */
    public function testBeritaContentApprovalWorkflow()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita Perlu Approval')
                ->type('slug', 'berita-perlu-approval')
                ->type('konten', 'Konten berita perlu approval')
                ->type('isi', 'Isi berita perlu approval')
                ->select('kategori', 'Berita')
                ->select('status', 'pending_approval')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Berita submitted for approval')
                ->assertSee('Berita Perlu Approval');
        });
    }
}
