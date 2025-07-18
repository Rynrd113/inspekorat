<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\PortalOpd;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Http\UploadedFile;

class PortalOpdTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin Portal OPD',
            'email' => 'admin.opd@inspektorat.id',
            'password' => bcrypt('adminopd123'),
            'role' => 'admin_opd',
            'is_active' => true,
        ]);

        // Create test OPD data
        $this->createTestOpdData();
    }

    private function createTestOpdData()
    {
        for ($i = 1; $i <= 15; $i++) {
            PortalOpd::create([
                'nama_opd' => 'OPD Test ' . $i,
                'singkatan' => 'OPD' . $i,
                'alamat' => 'Jalan Test ' . $i . ', Papua Tengah',
                'telepon' => '0901234567' . $i,
                'email' => 'opd' . $i . '@paputeng.go.id',
                'website' => 'https://opd' . $i . '.paputeng.go.id',
                'kepala_opd' => 'Kepala OPD ' . $i,
                'nip_kepala' => '19800101 198001 1 00' . $i,
                'deskripsi' => 'Deskripsi OPD Test ' . $i,
                'visi' => 'Visi OPD Test ' . $i,
                'misi' => [
                    'Misi 1 OPD ' . $i,
                    'Misi 2 OPD ' . $i,
                    'Misi 3 OPD ' . $i,
                ],
                'status' => true,
                'created_by' => $this->admin->id,
                'updated_by' => $this->admin->id,
            ]);
        }
    }

    /**
     * Test Portal OPD index page
     */
    public function testPortalOpdIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->assertSee('Portal OPD')
                ->assertSee('Tambah OPD')
                ->assertSee('OPD Test 1')
                ->assertSee('OPD Test 2')
                ->assertSee('OPD Test 3');
        });
    }

    /**
     * Test Portal OPD pagination
     */
    public function testPortalOpdPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->assertSee('Portal OPD')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('OPD Test 11')
                ->assertSee('OPD Test 12');
        });
    }

    /**
     * Test Portal OPD search functionality
     */
    public function testPortalOpdSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->type('search', 'OPD Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('OPD Test 5')
                ->assertDontSee('OPD Test 1')
                ->assertDontSee('OPD Test 2');
        });
    }

    /**
     * Test Portal OPD create page
     */
    public function testPortalOpdCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->clickLink('Tambah OPD')
                ->pause(1000)
                ->assertPathIs('/admin/portal-opd/create')
                ->assertSee('Tambah Portal OPD')
                ->assertPresent('input[name="nama_opd"]')
                ->assertPresent('input[name="singkatan"]')
                ->assertPresent('input[name="alamat"]')
                ->assertPresent('input[name="telepon"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="website"]')
                ->assertPresent('input[name="kepala_opd"]')
                ->assertPresent('input[name="nip_kepala"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('textarea[name="visi"]')
                ->assertPresent('textarea[name="misi"]');
        });
    }

    /**
     * Test Portal OPD store functionality
     */
    public function testPortalOpdStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/create')
                ->type('nama_opd', 'Dinas Kesehatan')
                ->type('singkatan', 'DINKES')
                ->type('alamat', 'Jalan Kesehatan No. 1, Nabire')
                ->type('telepon', '09012345678')
                ->type('email', 'dinkes@paputeng.go.id')
                ->type('website', 'https://dinkes.paputeng.go.id')
                ->type('kepala_opd', 'Dr. John Doe')
                ->type('nip_kepala', '19800101 198001 1 001')
                ->type('deskripsi', 'Dinas Kesehatan Papua Tengah')
                ->type('visi', 'Mewujudkan masyarakat Papua Tengah yang sehat')
                ->type('misi', 'Meningkatkan pelayanan kesehatan yang berkualitas')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/portal-opd')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Dinas Kesehatan');
        });
    }

    /**
     * Test Portal OPD store validation
     */
    public function testPortalOpdStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The nama opd field is required')
                ->assertSee('The singkatan field is required')
                ->assertSee('The alamat field is required')
                ->assertSee('The telepon field is required')
                ->assertSee('The email field is required');
        });
    }

    /**
     * Test Portal OPD show page
     */
    public function testPortalOpdShowPage()
    {
        $opd = PortalOpd::first();
        
        $this->browse(function (Browser $browser) use ($opd) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->click('a[href="/admin/portal-opd/' . $opd->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/portal-opd/' . $opd->id)
                ->assertSee($opd->nama_opd)
                ->assertSee($opd->singkatan)
                ->assertSee($opd->alamat)
                ->assertSee($opd->telepon)
                ->assertSee($opd->email)
                ->assertSee($opd->website)
                ->assertSee($opd->kepala_opd)
                ->assertSee($opd->nip_kepala);
        });
    }

    /**
     * Test Portal OPD edit page
     */
    public function testPortalOpdEditPage()
    {
        $opd = PortalOpd::first();
        
        $this->browse(function (Browser $browser) use ($opd) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->click('a[href="/admin/portal-opd/' . $opd->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/portal-opd/' . $opd->id . '/edit')
                ->assertSee('Edit Portal OPD')
                ->assertInputValue('nama_opd', $opd->nama_opd)
                ->assertInputValue('singkatan', $opd->singkatan)
                ->assertInputValue('alamat', $opd->alamat)
                ->assertInputValue('telepon', $opd->telepon)
                ->assertInputValue('email', $opd->email)
                ->assertInputValue('website', $opd->website)
                ->assertInputValue('kepala_opd', $opd->kepala_opd)
                ->assertInputValue('nip_kepala', $opd->nip_kepala);
        });
    }

    /**
     * Test Portal OPD update functionality
     */
    public function testPortalOpdUpdate()
    {
        $opd = PortalOpd::first();
        
        $this->browse(function (Browser $browser) use ($opd) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/' . $opd->id . '/edit')
                ->clear('nama_opd')
                ->type('nama_opd', 'Dinas Pendidikan Updated')
                ->clear('singkatan')
                ->type('singkatan', 'DIKNAS')
                ->clear('alamat')
                ->type('alamat', 'Jalan Pendidikan No. 2, Nabire')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/portal-opd')
                ->assertSee('Data berhasil diperbarui')
                ->assertSee('Dinas Pendidikan Updated')
                ->assertSee('DIKNAS');
        });
    }

    /**
     * Test Portal OPD delete functionality
     */
    public function testPortalOpdDelete()
    {
        $opd = PortalOpd::first();
        $opdName = $opd->nama_opd;
        
        $this->browse(function (Browser $browser) use ($opd, $opdName) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus data ini?\')) { document.getElementById(\'delete-form-' . $opd->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/portal-opd')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($opdName);
        });
    }

    /**
     * Test Portal OPD status toggle
     */
    public function testPortalOpdStatusToggle()
    {
        $opd = PortalOpd::first();
        
        $this->browse(function (Browser $browser) use ($opd) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->click('input[name="status"][data-id="' . $opd->id . '"]')
                ->pause(1000)
                ->assertSee('Status berhasil diubah');
        });
    }

    /**
     * Test Portal OPD logo upload
     */
    public function testPortalOpdLogoUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/create')
                ->type('nama_opd', 'Dinas Dengan Logo')
                ->type('singkatan', 'DWL')
                ->type('alamat', 'Jalan Logo No. 1')
                ->type('telepon', '09012345678')
                ->type('email', 'logo@paputeng.go.id')
                ->type('website', 'https://logo.paputeng.go.id')
                ->type('kepala_opd', 'Kepala Logo')
                ->type('nip_kepala', '19800101 198001 1 001')
                ->type('deskripsi', 'Dinas dengan logo')
                ->type('visi', 'Visi logo')
                ->type('misi', 'Misi logo')
                ->attach('logo', __DIR__ . '/../../fixtures/test-logo.png')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/portal-opd')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Dinas Dengan Logo');
        });
    }

    /**
     * Test Portal OPD banner upload
     */
    public function testPortalOpdBannerUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd/create')
                ->type('nama_opd', 'Dinas Dengan Banner')
                ->type('singkatan', 'DWB')
                ->type('alamat', 'Jalan Banner No. 1')
                ->type('telepon', '09012345678')
                ->type('email', 'banner@paputeng.go.id')
                ->type('website', 'https://banner.paputeng.go.id')
                ->type('kepala_opd', 'Kepala Banner')
                ->type('nip_kepala', '19800101 198001 1 001')
                ->type('deskripsi', 'Dinas dengan banner')
                ->type('visi', 'Visi banner')
                ->type('misi', 'Misi banner')
                ->attach('banner', __DIR__ . '/../../fixtures/test-banner.jpg')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/portal-opd')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Dinas Dengan Banner');
        });
    }

    /**
     * Test Portal OPD responsive design
     */
    public function testPortalOpdResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->assertSee('Portal OPD')
                ->assertSee('Tambah OPD')
                ->resize(768, 1024) // iPad size
                ->assertSee('Portal OPD')
                ->assertSee('Tambah OPD')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test Portal OPD filter functionality
     */
    public function testPortalOpdFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->select('status', 'active')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('OPD Test 1')
                ->assertSee('OPD Test 2')
                ->select('status', 'inactive')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Tidak ada data');
        });
    }

    /**
     * Test Portal OPD bulk actions
     */
    public function testPortalOpdBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->check('select-all')
                ->select('bulk-action', 'delete')
                ->press('Apply')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test Portal OPD export functionality
     */
    public function testPortalOpdExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->click('a[href="/admin/portal-opd/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test Portal OPD import functionality
     */
    public function testPortalOpdImport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-opd')
                ->clickLink('Import')
                ->pause(1000)
                ->attach('file', __DIR__ . '/../../fixtures/opd-import.xlsx')
                ->press('Import')
                ->pause(2000)
                ->assertSee('Import berhasil');
        });
    }
}
