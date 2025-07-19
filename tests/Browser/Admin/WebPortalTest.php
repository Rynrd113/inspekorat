<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\WebPortal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WebPortalTest extends DuskTestCase
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
        
        $this->createTestWebPortalData();
    }

    private function createTestWebPortalData()
    {
        $categories = ['portal', 'website', 'aplikasi', 'sistem', 'layanan'];
        
        for ($i = 1; $i <= 15; $i++) {
            WebPortal::create([
                'nama_portal' => 'Web Portal Test ' . $i,
                'deskripsi' => 'Deskripsi web portal test ' . $i . '. Portal web untuk kebutuhan inspektorat Papua Tengah.',
                'url' => 'https://portal' . $i . '.inspektorat.id',
                'kategori' => $categories[($i - 1) % 5],
                'icon' => 'fas fa-globe',
                'status' => ($i % 3 !== 0), // 2/3 active
                'urutan' => $i,
                'created_by' => $this->admin->id,
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }

    /**
     * Test Web Portal index page
     */
    public function testWebPortalIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->assertSee('Web Portal')
                ->assertSee('Tambah Web Portal')
                ->screenshot('admin_web_portal_index');
        });
    }

    /**
     * Test Web Portal pagination
     */
    public function testWebPortalPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->assertSee('Web Portal Test 1')
                ->assertSee('Web Portal Test 10')
                ->screenshot('admin_web_portal_pagination');
        });
    }

    /**
     * Test Web Portal search functionality
     */
    public function testWebPortalSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->type('search', 'Test 1')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Web Portal Test 1')
                ->assertDontSee('Web Portal Test 2')
                ->screenshot('admin_web_portal_search');
        });
    }

    /**
     * Test Web Portal create page
     */
    public function testWebPortalCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal/create')
                ->assertSee('Tambah Web Portal')
                ->assertSee('Nama Portal')
                ->assertSee('URL')
                ->assertSee('Kategori')
                ->screenshot('admin_web_portal_create');
        });
    }

    /**
     * Test Web Portal store functionality
     */
    public function testWebPortalStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal/create')
                ->type('nama_portal', 'Portal Baru')
                ->type('deskripsi', 'Deskripsi portal web yang baru dibuat untuk testing')
                ->type('url', 'https://portalbaru.inspektorat.id')
                ->select('kategori', 'portal')
                ->type('icon', 'fas fa-home')
                ->check('status')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/web-portal')
                ->assertSee('Web Portal berhasil ditambahkan')
                ->assertSee('Portal Baru')
                ->screenshot('admin_web_portal_store');
        });
    }

    /**
     * Test Web Portal store validation
     */
    public function testWebPortalStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('Nama portal harus diisi')
                ->assertSee('URL harus diisi')
                ->assertSee('Kategori harus diisi')
                ->screenshot('admin_web_portal_validation');
        });
    }

    /**
     * Test Web Portal show page
     */
    public function testWebPortalShowPage()
    {
        $webPortal = WebPortal::first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->loginAs($this->admin)
                ->visit("/admin/web-portal/{$webPortal->id}")
                ->assertSee($webPortal->nama_portal)
                ->assertSee($webPortal->deskripsi)
                ->assertSee('Edit')
                ->assertSee('Kembali')
                ->screenshot('admin_web_portal_show');
        });
    }

    /**
     * Test Web Portal edit page
     */
    public function testWebPortalEditPage()
    {
        $webPortal = WebPortal::first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->loginAs($this->admin)
                ->visit("/admin/web-portal/{$webPortal->id}/edit")
                ->assertSee('Edit Web Portal')
                ->assertInputValue('nama_portal', $webPortal->nama_portal)
                ->assertSee($webPortal->deskripsi)
                ->assertSelected('kategori', $webPortal->kategori)
                ->screenshot('admin_web_portal_edit');
        });
    }

    /**
     * Test Web Portal update functionality
     */
    public function testWebPortalUpdate()
    {
        $webPortal = WebPortal::first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->loginAs($this->admin)
                ->visit("/admin/web-portal/{$webPortal->id}/edit")
                ->clear('nama_portal')
                ->type('nama_portal', 'Portal Updated')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/web-portal')
                ->assertSee('Web Portal berhasil diperbarui')
                ->assertSee('Portal Updated')
                ->screenshot('admin_web_portal_update');
        });
    }

    /**
     * Test Web Portal delete functionality
     */
    public function testWebPortalDelete()
    {
        $webPortal = WebPortal::first();
        $namaPortal = $webPortal->nama_portal;
        
        $this->browse(function (Browser $browser) use ($webPortal, $namaPortal) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->click("button[onclick=\"deleteItem({$webPortal->id})\"]")
                ->pause(500)
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Ya, Hapus');
                })
                ->pause(2000)
                ->assertDontSee($namaPortal)
                ->assertSee('Web Portal berhasil dihapus')
                ->screenshot('admin_web_portal_delete');
        });
    }

    /**
     * Test Web Portal category filter
     */
    public function testWebPortalCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->select('kategori', 'portal')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('portal')
                ->screenshot('admin_web_portal_category_filter');
        });
    }

    /**
     * Test Web Portal status toggle
     */
    public function testWebPortalStatusToggle()
    {
        $webPortal = WebPortal::first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->click("button[onclick=\"toggleStatus({$webPortal->id})\"]")
                ->pause(2000)
                ->assertSee('Status berhasil diubah')
                ->screenshot('admin_web_portal_status_toggle');
        });
    }

    /**
     * Test Web Portal ordering
     */
    public function testWebPortalOrdering()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->assertSee('Urutan')
                ->screenshot('admin_web_portal_ordering');
        });
    }
}
