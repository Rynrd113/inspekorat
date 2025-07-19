<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\PortalPapuaTengah;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BeritaCrudTest extends DuskTestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::where('email', 'admin.berita@inspektorat.go.id')->first();
        if (!$this->admin) {
            $this->admin = User::where('role', 'superadmin')->first();
        }
    }

    /**
     * Test Berita Create functionality
     */
    public function testBeritaCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->assertSee('Berita')
                ->click('a[href*="create"]')
                ->pause(1000)
                ->assertPathBeginsWith('/admin/portal-papua-tengah/create')
                ->type('judul', 'Inspektorat Papua Tengah Luncurkan Program Inovasi Pelayanan Publik')
                ->type('isi', 'Papua Tengah - Inspektorat Daerah Papua Tengah meluncurkan program inovasi pelayanan publik yang bertujuan meningkatkan kualitas layanan kepada masyarakat. Program ini mencakup digitalisasi proses administrasi dan peningkatan transparansi.')
                ->select('kategori', 'berita')
                ->select('status', 'published')
                ->screenshot('berita-create-form');
                
            // Only try to upload if the field exists
            if ($browser->element('input[type="file"]')) {
                $browser->attach('gambar', __DIR__ . '/../../fixtures/test-image.png');
            }
            
            $browser->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/portal-papua-tengah')
                ->assertSee('Inspektorat Papua Tengah Luncurkan Program Inovasi')
                ->screenshot('berita-after-create');
        });
    }

    /**
     * Test Berita Read functionality
     */
    public function testBeritaRead()
    {
        $berita = PortalPapuaTengah::first();
        if (!$berita) {
            $this->markTestSkipped('No Berita data available');
        }

        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->assertSee('Berita')
                ->assertSee($berita->judul)
                ->screenshot('berita-list-view')
                ->click('a[href*="/admin/portal-papua-tengah/' . $berita->id . '"]')
                ->pause(1000)
                ->assertSee($berita->judul)
                ->assertSee($berita->isi)
                ->screenshot('berita-detail-view');
        });
    }

    /**
     * Test Berita Update functionality
     */
    public function testBeritaUpdate()
    {
        $berita = PortalPapuaTengah::first();
        if (!$berita) {
            $this->markTestSkipped('No Berita data available');
        }

        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/' . $berita->id . '/edit')
                ->assertSee('Edit')
                ->clear('judul')
                ->type('judul', 'Berita Updated Test')
                ->clear('isi')
                ->type('isi', 'Isi berita yang telah diperbarui untuk testing')
                ->screenshot('berita-edit-form')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/portal-papua-tengah')
                ->assertSee('Berita Updated Test')
                ->screenshot('berita-after-update');
        });
    }

    /**
     * Test Berita Search functionality
     */
    public function testBeritaSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->type('search', 'Inspektorat')
                ->press('Search')
                ->pause(1000)
                ->screenshot('berita-search-results');
        });
    }

    /**
     * Test Berita Status Toggle
     */
    public function testBeritaStatusToggle()
    {
        $berita = PortalPapuaTengah::first();
        if (!$berita) {
            $this->markTestSkipped('No Berita data available');
        }

        $this->browse(function (Browser $browser) use ($berita) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->screenshot('berita-before-status-toggle');
                
            // Look for status toggle button/switch
            if ($browser->element('input[name="status"]')) {
                $browser->click('input[name="status"][data-id="' . $berita->id . '"]')
                    ->pause(1000)
                    ->screenshot('berita-after-status-toggle');
            }
        });
    }

    /**
     * Test Berita Filter by Category
     */
    public function testBeritaFilterByCategory()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->screenshot('berita-filter-category');
                
            if ($browser->element('select[name="kategori"]')) {
                $browser->select('kategori', 'berita')
                    ->press('Filter')
                    ->pause(1000)
                    ->screenshot('berita-filtered-results');
            }
        });
    }

    /**
     * Test Berita Form Validation
     */
    public function testBeritaFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->press('Simpan')
                ->pause(1000)
                ->screenshot('berita-validation-errors');
        });
    }

    /**
     * Test Berita Mobile Responsive
     */
    public function testBeritaMobileResponsive()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone size
                ->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah')
                ->assertSee('Berita')
                ->screenshot('berita-mobile-view')
                ->resize(1280, 720); // Reset to desktop
        });
    }

    /**
     * Test Berita Image Upload
     */
    public function testBeritaImageUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita dengan Gambar')
                ->type('isi', 'Berita yang dilengkapi dengan gambar untuk testing upload')
                ->select('kategori', 'berita')
                ->select('status', 'published');
                
            // Only try to upload if the field exists
            if ($browser->element('input[type="file"]')) {
                $browser->attach('gambar', __DIR__ . '/../../fixtures/test-image.png');
            }
            
            $browser->screenshot('berita-with-image-upload')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Berita dengan Gambar')
                ->screenshot('berita-image-upload-success');
        });
    }
}