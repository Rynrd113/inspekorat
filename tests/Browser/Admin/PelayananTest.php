<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Pelayanan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PelayananTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin Pelayanan',
            'email' => 'admin.pelayanan@inspektorat.id',
            'password' => bcrypt('adminpelayanan123'),
            'role' => 'admin_pelayanan',
            'is_active' => true,
        ]);

        // Create test pelayanan data
        $this->createTestPelayananData();
    }

    private function createTestPelayananData()
    {
        for ($i = 1; $i <= 15; $i++) {
            Pelayanan::create([
                'nama' => 'Pelayanan Test ' . $i,
                'deskripsi' => 'Deskripsi pelayanan test ' . $i,
                'prosedur' => [
                    'Langkah 1: Persiapan dokumen',
                    'Langkah 2: Pengajuan permohonan',
                    'Langkah 3: Verifikasi dokumen',
                    'Langkah 4: Proses pelayanan',
                    'Langkah 5: Penyerahan hasil',
                ],
                'persyaratan' => [
                    'KTP asli dan fotokopi',
                    'Surat pengantar dari instansi',
                    'Dokumen pendukung lainnya',
                ],
                'waktu_penyelesaian' => '3 hari kerja',
                'biaya' => 'Gratis',
                'kategori' => 'Administrasi',
                'status' => true,
                'urutan' => $i,
                'kontak_pic' => 'PIC Pelayanan ' . $i,
                'email_pic' => 'pic' . $i . '@inspektorat.id',
                'telepon_pic' => '0901234567' . $i,
                'created_by' => $this->admin->id,
                'updated_by' => $this->admin->id,
            ]);
        }
    }

    /**
     * Test Pelayanan index page
     */
    public function testPelayananIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->assertSee('Pelayanan')
                ->assertSee('Tambah Pelayanan')
                ->assertSee('Pelayanan Test 1')
                ->assertSee('Pelayanan Test 2')
                ->assertSee('Pelayanan Test 3');
        });
    }

    /**
     * Test Pelayanan pagination
     */
    public function testPelayananPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->assertSee('Pelayanan')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Pelayanan Test 11')
                ->assertSee('Pelayanan Test 12');
        });
    }

    /**
     * Test Pelayanan search functionality
     */
    public function testPelayananSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->type('search', 'Pelayanan Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Pelayanan Test 5')
                ->assertDontSee('Pelayanan Test 1')
                ->assertDontSee('Pelayanan Test 2');
        });
    }

    /**
     * Test Pelayanan create page
     */
    public function testPelayananCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->clickLink('Tambah Pelayanan')
                ->pause(1000)
                ->assertPathIs('/admin/pelayanan/create')
                ->assertSee('Tambah Pelayanan')
                ->assertPresent('input[name="nama"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('textarea[name="prosedur"]')
                ->assertPresent('textarea[name="persyaratan"]')
                ->assertPresent('input[name="waktu_penyelesaian"]')
                ->assertPresent('input[name="biaya"]')
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('input[name="kontak_pic"]')
                ->assertPresent('input[name="email_pic"]')
                ->assertPresent('input[name="telepon_pic"]');
        });
    }

    /**
     * Test Pelayanan store functionality
     */
    public function testPelayananStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan/create')
                ->type('nama', 'Pelayanan Audit Internal')
                ->type('deskripsi', 'Pelayanan audit internal untuk OPD')
                ->type('prosedur', 'Langkah 1: Pengajuan permohonan\nLangkah 2: Verifikasi dokumen\nLangkah 3: Pelaksanaan audit\nLangkah 4: Penyerahan hasil')
                ->type('persyaratan', 'Surat permohonan audit\nData-data yang akan diaudit\nKoordinasi dengan auditor')
                ->type('waktu_penyelesaian', '14 hari kerja')
                ->type('biaya', 'Gratis')
                ->select('kategori', 'Audit')
                ->type('kontak_pic', 'Tim Audit Internal')
                ->type('email_pic', 'audit@inspektorat.id')
                ->type('telepon_pic', '09012345678')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/pelayanan')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Pelayanan Audit Internal');
        });
    }

    /**
     * Test Pelayanan store validation
     */
    public function testPelayananStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The nama field is required')
                ->assertSee('The deskripsi field is required')
                ->assertSee('The prosedur field is required')
                ->assertSee('The persyaratan field is required')
                ->assertSee('The waktu penyelesaian field is required');
        });
    }

    /**
     * Test Pelayanan show page
     */
    public function testPelayananShowPage()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->click('a[href="/admin/pelayanan/' . $pelayanan->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/pelayanan/' . $pelayanan->id)
                ->assertSee($pelayanan->nama)
                ->assertSee($pelayanan->deskripsi)
                ->assertSee($pelayanan->waktu_penyelesaian)
                ->assertSee($pelayanan->biaya)
                ->assertSee($pelayanan->kontak_pic)
                ->assertSee($pelayanan->email_pic)
                ->assertSee($pelayanan->telepon_pic);
        });
    }

    /**
     * Test Pelayanan edit page
     */
    public function testPelayananEditPage()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->click('a[href="/admin/pelayanan/' . $pelayanan->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/pelayanan/' . $pelayanan->id . '/edit')
                ->assertSee('Edit Pelayanan')
                ->assertInputValue('nama', $pelayanan->nama)
                ->assertInputValue('waktu_penyelesaian', $pelayanan->waktu_penyelesaian)
                ->assertInputValue('biaya', $pelayanan->biaya)
                ->assertInputValue('kontak_pic', $pelayanan->kontak_pic)
                ->assertInputValue('email_pic', $pelayanan->email_pic)
                ->assertInputValue('telepon_pic', $pelayanan->telepon_pic);
        });
    }

    /**
     * Test Pelayanan update functionality
     */
    public function testPelayananUpdate()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan/' . $pelayanan->id . '/edit')
                ->clear('nama')
                ->type('nama', 'Pelayanan Audit Eksternal')
                ->clear('waktu_penyelesaian')
                ->type('waktu_penyelesaian', '21 hari kerja')
                ->clear('biaya')
                ->type('biaya', 'Sesuai tarif yang berlaku')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/pelayanan')
                ->assertSee('Data berhasil diperbarui')
                ->assertSee('Pelayanan Audit Eksternal')
                ->assertSee('21 hari kerja');
        });
    }

    /**
     * Test Pelayanan delete functionality
     */
    public function testPelayananDelete()
    {
        $pelayanan = Pelayanan::first();
        $pelayananName = $pelayanan->nama;
        
        $this->browse(function (Browser $browser) use ($pelayanan, $pelayananName) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus data ini?\')) { document.getElementById(\'delete-form-' . $pelayanan->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/pelayanan')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($pelayananName);
        });
    }

    /**
     * Test Pelayanan category filter
     */
    public function testPelayananCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->select('kategori', 'Administrasi')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Pelayanan Test 1')
                ->assertSee('Pelayanan Test 2')
                ->select('kategori', 'Audit')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Tidak ada data');
        });
    }

    /**
     * Test Pelayanan status toggle
     */
    public function testPelayananStatusToggle()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->click('input[name="status"][data-id="' . $pelayanan->id . '"]')
                ->pause(1000)
                ->assertSee('Status berhasil diubah');
        });
    }

    /**
     * Test Pelayanan ordering
     */
    public function testPelayananOrdering()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->click('button[onclick="moveUp(' . $pelayanan->id . ')"]')
                ->pause(1000)
                ->assertSee('Urutan berhasil diubah');
        });
    }

    /**
     * Test Pelayanan responsive design
     */
    public function testPelayananResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->assertSee('Pelayanan')
                ->assertSee('Tambah Pelayanan')
                ->resize(768, 1024) // iPad size
                ->assertSee('Pelayanan')
                ->assertSee('Tambah Pelayanan')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test Pelayanan bulk actions
     */
    public function testPelayananBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->check('select-all')
                ->select('bulk-action', 'activate')
                ->press('Apply')
                ->pause(1000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test Pelayanan export functionality
     */
    public function testPelayananExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->click('a[href="/admin/pelayanan/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test Pelayanan with file attachment
     */
    public function testPelayananWithFileAttachment()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan/create')
                ->type('nama', 'Pelayanan dengan Dokumen')
                ->type('deskripsi', 'Pelayanan yang memiliki dokumen pendukung')
                ->type('prosedur', 'Langkah 1: Download formulir\nLangkah 2: Isi formulir\nLangkah 3: Submit formulir')
                ->type('persyaratan', 'Formulir yang sudah diisi\nKTP\nSurat keterangan')
                ->type('waktu_penyelesaian', '7 hari kerja')
                ->type('biaya', 'Gratis')
                ->select('kategori', 'Administrasi')
                ->type('kontak_pic', 'Admin Dokumen')
                ->type('email_pic', 'admin@inspektorat.id')
                ->type('telepon_pic', '09012345678')
                ->attach('dokumen_pendukung', __DIR__ . '/../../fixtures/formulir-pelayanan.pdf')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/pelayanan')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Pelayanan dengan Dokumen');
        });
    }

    /**
     * Test Pelayanan duplicate functionality
     */
    public function testPelayananDuplicate()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->click('button[onclick="duplicateService(' . $pelayanan->id . ')"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertSee('Pelayanan berhasil diduplikasi')
                ->assertSee('Copy of ' . $pelayanan->nama);
        });
    }

    /**
     * Test Pelayanan advanced search
     */
    public function testPelayananAdvancedSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan')
                ->clickLink('Advanced Search')
                ->pause(1000)
                ->type('nama', 'Test')
                ->select('kategori', 'Administrasi')
                ->select('status', 'active')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Pelayanan Test 1')
                ->assertSee('Pelayanan Test 2');
        });
    }

    /**
     * Test Pelayanan print functionality
     */
    public function testPelayananPrint()
    {
        $pelayanan = Pelayanan::first();
        
        $this->browse(function (Browser $browser) use ($pelayanan) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pelayanan/' . $pelayanan->id)
                ->click('button[onclick="window.print()"]')
                ->pause(1000)
                ->assertSee($pelayanan->nama);
        });
    }
}
