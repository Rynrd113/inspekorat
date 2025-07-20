<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\PortalOpd;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PortalOpdCrudTest extends DuskTestCase
{
    protected $admin;
    protected $testOpd;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::where('email', 'admin.opd@inspektorat.go.id')->first();
        if (!$this->admin) {
            $this->admin = User::where('role', 'super_admin')->first();
        }
    }

    /**
     * Test Portal OPD Create functionality
     */
    public function testPortalOpdCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->assertSee('Portal OPD')
                ->click('a[href*="create"]')
                ->pause(1000)
                ->assertPathBeginsWith('/admin/portal-opd/create')
                ->type('nama_opd', 'Dinas Pendidikan Test')
                ->type('singkatan', 'DIKNAS')
                ->type('alamat', 'Jalan Pendidikan No. 123, Nabire')
                ->type('telepon', '08123456789')
                ->type('email', 'diknas@paputeng.go.id')
                ->type('website', 'https://diknas.paputeng.go.id')
                ->type('kepala_opd', 'Dr. John Doe, M.Pd')
                ->type('nip_kepala', '19800101 198001 1 001')
                ->type('deskripsi', 'Dinas Pendidikan Papua Tengah bertugas mengelola pendidikan')
                ->type('visi', 'Mewujudkan pendidikan berkualitas di Papua Tengah')
                ->type('misi', 'Meningkatkan kualitas pendidikan,Mengembangkan SDM pendidik,Memperluas akses pendidikan')
                ->screenshot('portal-opd-create-form')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/portal-opd')
                ->assertSee('Dinas Pendidikan Test')
                ->screenshot('portal-opd-after-create');
        });
    }

    /**
     * Test Portal OPD Read functionality
     */
    public function testPortalOpdRead()
    {
        $opd = PortalOpd::first();
        if (!$opd) {
            $this->markTestSkipped('No Portal OPD data available');
        }

        $this->browse(function (Browser $browser) use ($opd) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->assertSee('Portal OPD')
                ->assertSee($opd->nama_opd)
                ->screenshot('portal-opd-list-view')
                ->click('a[href*="/admin/portal-opd/' . $opd->id . '"]')
                ->pause(1000)
                ->assertSee($opd->nama_opd)
                ->assertSee($opd->alamat)
                ->assertSee($opd->telepon)
                ->assertSee($opd->email)
                ->screenshot('portal-opd-detail-view');
        });
    }

    /**
     * Test Portal OPD Update functionality
     */
    public function testPortalOpdUpdate()
    {
        $opd = PortalOpd::first();
        if (!$opd) {
            $this->markTestSkipped('No Portal OPD data available');
        }

        $this->browse(function (Browser $browser) use ($opd) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/' . $opd->id . '/edit')
                ->assertSee('Edit')
                ->clear('nama_opd')
                ->type('nama_opd', 'Dinas Updated Test')
                ->clear('alamat')
                ->type('alamat', 'Jalan Updated No. 456')
                ->screenshot('portal-opd-edit-form')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/portal-opd')
                ->assertSee('Dinas Updated Test')
                ->screenshot('portal-opd-after-update');
        });
    }

    /**
     * Test Portal OPD Delete functionality
     */
    public function testPortalOpdDelete()
    {
        // Create a test OPD specifically for deletion
        $testOpd = PortalOpd::create([
            'nama_opd' => 'OPD For Delete Test',
            'singkatan' => 'DELETE',
            'alamat' => 'Test Address',
            'telepon' => '081234567890',
            'email' => 'delete@test.com',
            'website' => 'https://delete.test.com',
            'kepala_opd' => 'Test Kepala',
            'nip_kepala' => '19800101 198001 1 001',
            'deskripsi' => 'Test Description',
            'visi' => 'Test Vision',
            'misi' => 'Test Mission',
            'status' => true,
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($testOpd) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->assertSee('OPD For Delete Test')
                ->screenshot('portal-opd-before-delete')
                ->press('Delete') // Adjust selector as needed for delete button
                ->acceptDialog()
                ->pause(2000)
                ->assertDontSee('OPD For Delete Test')
                ->screenshot('portal-opd-after-delete');
        });
    }

    /**
     * Test Portal OPD Search functionality
     */
    public function testPortalOpdSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->type('search', 'Dinas')
                ->press('Search')
                ->pause(1000)
                ->screenshot('portal-opd-search-results');
        });
    }

    /**
     * Test Portal OPD Pagination
     */
    public function testPortalOpdPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->screenshot('portal-opd-pagination');
                
            // Check if pagination exists
            if ($browser->element('.pagination')) {
                $browser->click('.pagination .page-link')
                    ->pause(1000)
                    ->screenshot('portal-opd-pagination-page-2');
            }
        });
    }

    /**
     * Test Portal OPD Form Validation
     */
    public function testPortalOpdFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/create')
                ->press('Simpan')
                ->pause(1000)
                ->screenshot('portal-opd-validation-errors');
        });
    }

    /**
     * Test Portal OPD Mobile Responsive
     */
    public function testPortalOpdMobileResponsive()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone size
                ->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->assertSee('Portal OPD')
                ->screenshot('portal-opd-mobile-view')
                ->resize(1280, 720); // Reset to desktop
        });
    }

    /**
     * Test Portal OPD with File Upload
     */
    public function testPortalOpdFileUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/create')
                ->type('nama_opd', 'OPD With Logo')
                ->type('singkatan', 'LOGO')
                ->type('alamat', 'Test Address')
                ->type('telepon', '081234567890')
                ->type('email', 'logo@test.com')
                ->type('website', 'https://logo.test.com')
                ->type('kepala_opd', 'Test Kepala')
                ->type('nip_kepala', '19800101 198001 1 001')
                ->type('deskripsi', 'Test Description')
                ->type('visi', 'Test Vision')
                ->type('misi', 'Test Mission');
                
            // Only try to upload if the field exists
            if ($browser->element('input[type="file"]')) {
                $browser->attach('logo', __DIR__ . '/../../fixtures/test-logo.png');
            }
            
            $browser->screenshot('portal-opd-with-upload')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('OPD With Logo')
                ->screenshot('portal-opd-upload-success');
        });
    }
}