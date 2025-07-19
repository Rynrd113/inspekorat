<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Pelayanan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PelayananCrudTest extends DuskTestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::where('email', 'admin.pelayanan@inspektorat.go.id')->first();
        if (!$this->admin) {
            $this->admin = User::where('role', 'superadmin')->first();
        }
    }

    /**
     * Test Pelayanan Create functionality
     */
    public function testPelayananCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->assertSee('Pelayanan')
                ->click('a[href*="create"]')
                ->pause(1000)
                ->assertPathBeginsWith('/admin/pelayanan/create')
                ->type('nama_layanan', 'Surat Izin Usaha Perdagangan')
                ->type('deskripsi', 'Layanan penerbitan surat izin usaha perdagangan untuk pelaku UMKM')
                ->type('persyaratan', 'KTP,KK,Surat Domisili,NPWP,Pas Foto')
                ->type('biaya', '50000')
                ->type('waktu_proses', '3 hari kerja')
                ->screenshot('pelayanan-create-form')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/pelayanan')
                ->assertSee('Surat Izin Usaha Perdagangan')
                ->screenshot('pelayanan-after-create');
        });
    }

    /**
     * Test Pelayanan Read functionality
     */
    public function testPelayananRead()
    {
        $pelayanan = Pelayanan::first();
        if (!$pelayanan) {
            $this->markTestSkipped('No Pelayanan data available');
        }

        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->assertSee('Pelayanan')
                ->assertSee($pelayanan->nama_layanan)
                ->screenshot('pelayanan-list-view')
                ->click('a[href*="/admin/pelayanan/' . $pelayanan->id . '"]')
                ->pause(1000)
                ->assertSee($pelayanan->nama_layanan)
                ->assertSee($pelayanan->deskripsi)
                ->screenshot('pelayanan-detail-view');
        });
    }

    /**
     * Test Pelayanan Update functionality
     */
    public function testPelayananUpdate()
    {
        $pelayanan = Pelayanan::first();
        if (!$pelayanan) {
            $this->markTestSkipped('No Pelayanan data available');
        }

        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan/' . $pelayanan->id . '/edit')
                ->assertSee('Edit')
                ->clear('nama_layanan')
                ->type('nama_layanan', 'Layanan Updated Test')
                ->clear('biaya')
                ->type('biaya', '75000')
                ->screenshot('pelayanan-edit-form')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/pelayanan')
                ->assertSee('Layanan Updated Test')
                ->screenshot('pelayanan-after-update');
        });
    }

    /**
     * Test Pelayanan Search functionality
     */
    public function testPelayananSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->type('search', 'Surat')
                ->press('Search')
                ->pause(1000)
                ->screenshot('pelayanan-search-results');
        });
    }

    /**
     * Test Pelayanan Form Validation
     */
    public function testPelayananFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan/create')
                ->press('Simpan')
                ->pause(1000)
                ->screenshot('pelayanan-validation-errors');
        });
    }

    /**
     * Test Pelayanan Mobile Responsive
     */
    public function testPelayananMobileResponsive()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone size
                ->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->assertSee('Pelayanan')
                ->screenshot('pelayanan-mobile-view')
                ->resize(1280, 720); // Reset to desktop
        });
    }
}