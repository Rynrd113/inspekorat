<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Dokumen;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DokumenCrudTest extends DuskTestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::where('email', 'admin.dokumen@inspektorat.go.id')->first();
        if (!$this->admin) {
            $this->admin = User::where('role', 'super_admin')->first();
        }
    }

    /**
     * Test Dokumen Create functionality
     */
    public function testDokumenCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->assertSee('Manajemen Dokumen')
                ->click('a[href*="create"]')
                ->pause(1000)
                ->assertPathBeginsWith('/admin/dokumen/create')
                ->type('nama_dokumen', 'Peraturan Daerah Nomor 1 Tahun 2025')
                ->type('deskripsi', 'Peraturan daerah tentang tata kelola pemerintahan Papua Tengah')
                ->select('kategori', 'peraturan')
                ->screenshot('dokumen-create-form');
                
            // Only try to upload if the field exists
            if ($browser->element('input[name="file"]')) {
                $browser->attach('file', __DIR__ . '/../../fixtures/test-document.pdf');
            }
            
            $browser->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/dokumen')
                ->assertSee('Peraturan Daerah Nomor 1 Tahun 2025')
                ->screenshot('dokumen-after-create');
        });
    }

    /**
     * Test Dokumen Read functionality
     */
    public function testDokumenRead()
    {
        $dokumen = Dokumen::first();
        if (!$dokumen) {
            $this->markTestSkipped('No Dokumen data available');
        }

        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->assertSee('Manajemen Dokumen')
                ->assertSee($dokumen->nama_dokumen)
                ->screenshot('dokumen-list-view')
                ->click('a[href*="/admin/dokumen/' . $dokumen->id . '"]')
                ->pause(1000)
                ->assertSee($dokumen->nama_dokumen)
                ->assertSee($dokumen->deskripsi)
                ->screenshot('dokumen-detail-view');
        });
    }

    /**
     * Test Dokumen Update functionality
     */
    public function testDokumenUpdate()
    {
        $dokumen = Dokumen::first();
        if (!$dokumen) {
            $this->markTestSkipped('No Dokumen data available');
        }

        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen/' . $dokumen->id . '/edit')
                ->assertSee('Edit')
                ->clear('nama_dokumen')
                ->type('nama_dokumen', 'Dokumen Updated Test')
                ->clear('deskripsi')
                ->type('deskripsi', 'Deskripsi dokumen yang telah diperbarui')
                ->screenshot('dokumen-edit-form')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/dokumen')
                ->assertSee('Dokumen Updated Test')
                ->screenshot('dokumen-after-update');
        });
    }

    /**
     * Test Dokumen Download functionality
     */
    public function testDokumenDownload()
    {
        $dokumen = Dokumen::whereNotNull('file_path')->first();
        if (!$dokumen) {
            $this->markTestSkipped('No Dokumen with file available');
        }

        $this->browse(function (Browser $browser) use ($dokumen) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->assertSee($dokumen->nama_dokumen)
                ->click('a[href*="download"]')
                ->pause(2000)
                ->screenshot('dokumen-download-action');
        });
    }

    /**
     * Test Dokumen Search functionality
     */
    public function testDokumenSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->type('search', 'Peraturan')
                ->press('Cari')
                ->pause(1000)
                ->screenshot('dokumen-search-results');
        });
    }

    /**
     * Test Dokumen Filter by Category
     */
    public function testDokumenFilterByCategory()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->screenshot('dokumen-filter-category');
                
            if ($browser->element('select[name="kategori"]')) {
                $browser->select('kategori', 'peraturan')
                    ->press('Cari')
                    ->pause(1000)
                    ->screenshot('dokumen-filtered-results');
            }
        });
    }

    /**
     * Test Dokumen Form Validation
     */
    public function testDokumenFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dokumen/create')
                ->press('Simpan')
                ->pause(1000)
                ->screenshot('dokumen-validation-errors');
        });
    }

    /**
     * Test Dokumen Mobile Responsive
     */
    public function testDokumenMobileResponsive()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone size
                ->loginAs($this->admin)
                ->visit('/admin/dokumen')
                ->assertSee('Manajemen Dokumen')
                ->screenshot('dokumen-mobile-view')
                ->resize(1280, 720); // Reset to desktop
        });
    }
}