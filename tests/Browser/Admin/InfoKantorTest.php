<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\InfoKantor;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InfoKantorTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@inspektorat.id'
        ]);
        
        $this->createTestInfoKantorData();
    }

    private function createTestInfoKantorData()
    {
        $categories = ['pengumuman', 'informasi', 'berita', 'kegiatan', 'layanan'];
        
        for ($i = 1; $i <= 15; $i++) {
            InfoKantor::create([
                'judul' => 'Info Kantor Test ' . $i,
                'konten' => 'Konten informasi kantor test ' . $i . '. Informasi penting mengenai kegiatan kantor inspektorat Papua Tengah.',
                'kategori' => $categories[($i - 1) % 5],
                'gambar' => 'info-kantor/info-' . $i . '.jpg',
                'status' => ($i % 3 !== 0), // 2/3 active
                'created_by' => $this->admin->id,
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }

    /**
     * Test Info Kantor index page
     */
    public function testInfoKantorIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->assertSee('Info Kantor')
                ->assertSee('Tambah Info Kantor')
                ->screenshot('admin_info_kantor_index');
        });
    }

    /**
     * Test Info Kantor pagination
     */
    public function testInfoKantorPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->assertSee('Info Kantor Test 1')
                ->assertSee('Info Kantor Test 10')
                ->screenshot('admin_info_kantor_pagination');
        });
    }

    /**
     * Test Info Kantor search functionality
     */
    public function testInfoKantorSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->type('search', 'Test 1')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Info Kantor Test 1')
                ->assertDontSee('Info Kantor Test 2')
                ->screenshot('admin_info_kantor_search');
        });
    }

    /**
     * Test Info Kantor create page
     */
    public function testInfoKantorCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor/create')
                ->assertSee('Tambah Info Kantor')
                ->assertSee('Judul')
                ->assertSee('Konten')
                ->assertSee('Kategori')
                ->screenshot('admin_info_kantor_create');
        });
    }

    /**
     * Test Info Kantor store functionality
     */
    public function testInfoKantorStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor/create')
                ->type('judul', 'Info Kantor Baru')
                ->type('konten', 'Konten informasi kantor yang baru dibuat untuk testing')
                ->select('kategori', 'pengumuman')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/info-kantor')
                ->assertSee('Info Kantor berhasil ditambahkan')
                ->assertSee('Info Kantor Baru')
                ->screenshot('admin_info_kantor_store');
        });
    }

    /**
     * Test Info Kantor store validation
     */
    public function testInfoKantorStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('Judul harus diisi')
                ->assertSee('Konten harus diisi')
                ->assertSee('Kategori harus diisi')
                ->screenshot('admin_info_kantor_validation');
        });
    }

    /**
     * Test Info Kantor show page
     */
    public function testInfoKantorShowPage()
    {
        $infoKantor = InfoKantor::first();
        
        $this->browse(function (Browser $browser) use ($infoKantor) {
            $browser->loginAs($this->admin)
                ->visit("/admin/info-kantor/{$infoKantor->id}")
                ->assertSee($infoKantor->judul)
                ->assertSee($infoKantor->konten)
                ->assertSee('Edit')
                ->assertSee('Kembali')
                ->screenshot('admin_info_kantor_show');
        });
    }

    /**
     * Test Info Kantor edit page
     */
    public function testInfoKantorEditPage()
    {
        $infoKantor = InfoKantor::first();
        
        $this->browse(function (Browser $browser) use ($infoKantor) {
            $browser->loginAs($this->admin)
                ->visit("/admin/info-kantor/{$infoKantor->id}/edit")
                ->assertSee('Edit Info Kantor')
                ->assertInputValue('judul', $infoKantor->judul)
                ->assertSee($infoKantor->konten)
                ->assertSelected('kategori', $infoKantor->kategori)
                ->screenshot('admin_info_kantor_edit');
        });
    }

    /**
     * Test Info Kantor update functionality
     */
    public function testInfoKantorUpdate()
    {
        $infoKantor = InfoKantor::first();
        
        $this->browse(function (Browser $browser) use ($infoKantor) {
            $browser->loginAs($this->admin)
                ->visit("/admin/info-kantor/{$infoKantor->id}/edit")
                ->clear('judul')
                ->type('judul', 'Info Kantor Updated')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/info-kantor')
                ->assertSee('Info Kantor berhasil diperbarui')
                ->assertSee('Info Kantor Updated')
                ->screenshot('admin_info_kantor_update');
        });
    }

    /**
     * Test Info Kantor delete functionality
     */
    public function testInfoKantorDelete()
    {
        $infoKantor = InfoKantor::first();
        $judulInfoKantor = $infoKantor->judul;
        
        $this->browse(function (Browser $browser) use ($infoKantor, $judulInfoKantor) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->click("button[onclick=\"deleteItem({$infoKantor->id})\"]")
                ->pause(500)
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Ya, Hapus');
                })
                ->pause(2000)
                ->assertDontSee($judulInfoKantor)
                ->assertSee('Info Kantor berhasil dihapus')
                ->screenshot('admin_info_kantor_delete');
        });
    }

    /**
     * Test Info Kantor category filter
     */
    public function testInfoKantorCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->select('kategori', 'pengumuman')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('pengumuman')
                ->screenshot('admin_info_kantor_category_filter');
        });
    }

    /**
     * Test Info Kantor status filter
     */
    public function testInfoKantorStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->select('status', '1')
                ->press('Filter')
                ->pause(1000)
                ->screenshot('admin_info_kantor_status_filter');
        });
    }

    /**
     * Test Info Kantor bulk actions
     */
    public function testInfoKantorBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/info-kantor')
                ->check('select_all')
                ->select('bulk_action', 'activate')
                ->press('Terapkan')
                ->pause(2000)
                ->assertSee('Aksi berhasil diterapkan')
                ->screenshot('admin_info_kantor_bulk_actions');
        });
    }
}
