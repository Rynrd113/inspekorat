<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\WebPortal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WebPortalEnhancedTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin Web Portal',
            'email' => 'admin.webportal@inspektorat.id',
            'password' => bcrypt('adminwebportal123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create test web portal data
        $this->createTestWebPortalData();
    }

    private function createTestWebPortalData()
    {
        $categories = ['pemerintahan', 'layanan', 'informasi', 'berita', 'pengumuman'];
        
        for ($i = 1; $i <= 15; $i++) {
            WebPortal::create([
                'nama_portal' => 'Web Portal Test ' . $i,
                'url' => 'https://portal' . $i . '.paputeng.go.id',
                'deskripsi' => 'Deskripsi web portal test ' . $i,
                'kategori' => $categories[($i - 1) % 5],
                'logo' => 'logos/portal-' . $i . '.png',
                'status' => true,
                'is_featured' => ($i <= 5),
                'urutan' => $i,
                'kontak_person' => 'Admin Portal ' . $i,
                'email_kontak' => 'portal' . $i . '@paputeng.go.id',
                'telepon_kontak' => '0901234567' . $i,
                'created_by' => $this->admin->id,
                'updated_by' => $this->admin->id,
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
                ->assertSee('Web Portal Test 1')
                ->assertSee('Web Portal Test 2')
                ->assertSee('Web Portal Test 3')
                ->assertSee('URL')
                ->assertSee('Status');
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
                ->assertSee('Web Portal')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Web Portal Test 11')
                ->assertSee('Web Portal Test 12');
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
                ->type('search', 'Web Portal Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Web Portal Test 5')
                ->assertDontSee('Web Portal Test 1')
                ->assertDontSee('Web Portal Test 2');
        });
    }

    /**
     * Test Web Portal filter by category
     */
    public function testWebPortalFilterByCategory()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->select('kategori', 'pemerintahan')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Web Portal Test 1'); // Should show 'pemerintahan' portals
        });
    }

    /**
     * Test Web Portal filter by status
     */
    public function testWebPortalFilterByStatus()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->select('status', '1')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Web Portal Test 1'); // Should show active portals
        });
    }

    /**
     * Test Web Portal create page
     */
    public function testWebPortalCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->clickLink('Tambah Web Portal')
                ->pause(1000)
                ->assertPathIs('/admin/web-portal/create')
                ->assertSee('Tambah Web Portal')
                ->assertPresent('input[name="nama_portal"]')
                ->assertPresent('input[name="url"]')
                ->assertPresent('textarea[name="deskripsi"]')
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('input[name="logo"]')
                ->assertPresent('input[name="kontak_person"]')
                ->assertPresent('input[name="email_kontak"]')
                ->assertPresent('input[name="telepon_kontak"]')
                ->assertPresent('input[name="status"]')
                ->assertPresent('input[name="is_featured"]');
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
                ->type('nama_portal', 'Portal Transparansi')
                ->type('url', 'https://transparansi.paputeng.go.id')
                ->type('deskripsi', 'Portal transparansi Pemerintah Papua Tengah')
                ->select('kategori', 'pemerintahan')
                ->type('kontak_person', 'Admin Transparansi')
                ->type('email_kontak', 'transparansi@paputeng.go.id')
                ->type('telepon_kontak', '09012345678')
                ->check('status')
                ->check('is_featured')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/web-portal')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Portal Transparansi');
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
                ->assertSee('The nama portal field is required')
                ->assertSee('The url field is required')
                ->assertSee('The deskripsi field is required')
                ->assertSee('The kategori field is required');
        });
    }

    /**
     * Test Web Portal URL validation
     */
    public function testWebPortalUrlValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal/create')
                ->type('nama_portal', 'Test Portal')
                ->type('url', 'invalid-url')
                ->type('deskripsi', 'Test description')
                ->select('kategori', 'layanan')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The url must be a valid URL');
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
                ->visit('/admin/web-portal')
                ->click('a[href="/admin/web-portal/' . $webPortal->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/web-portal/' . $webPortal->id)
                ->assertSee($webPortal->nama_portal)
                ->assertSee($webPortal->url)
                ->assertSee($webPortal->deskripsi)
                ->assertSee($webPortal->kategori)
                ->assertSee('Edit')
                ->assertSee('Hapus');
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
                ->visit('/admin/web-portal/' . $webPortal->id . '/edit')
                ->assertSee('Edit Web Portal')
                ->assertInputValue('nama_portal', $webPortal->nama_portal)
                ->assertInputValue('url', $webPortal->url)
                ->assertInputValue('deskripsi', $webPortal->deskripsi)
                ->assertSelected('kategori', $webPortal->kategori)
                ->assertInputValue('kontak_person', $webPortal->kontak_person)
                ->assertInputValue('email_kontak', $webPortal->email_kontak)
                ->assertInputValue('telepon_kontak', $webPortal->telepon_kontak);
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
                ->visit('/admin/web-portal/' . $webPortal->id . '/edit')
                ->clear('nama_portal')
                ->type('nama_portal', 'Web Portal Updated Test')
                ->clear('url')
                ->type('url', 'https://updated.paputeng.go.id')
                ->clear('deskripsi')
                ->type('deskripsi', 'Deskripsi web portal yang telah diupdate')
                ->select('kategori', 'layanan')
                ->check('status')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/web-portal')
                ->assertSee('Data berhasil diupdate')
                ->assertSee('Web Portal Updated Test');
        });
    }

    /**
     * Test Web Portal update validation
     */
    public function testWebPortalUpdateValidation()
    {
        $webPortal = WebPortal::first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal/' . $webPortal->id . '/edit')
                ->clear('nama_portal')
                ->clear('url')
                ->press('Update')
                ->pause(1000)
                ->assertSee('The nama portal field is required')
                ->assertSee('The url field is required');
        });
    }

    /**
     * Test Web Portal delete functionality
     */
    public function testWebPortalDelete()
    {
        $webPortal = WebPortal::latest()->first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->press('.btn-delete[data-id="' . $webPortal->id . '"]')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Hapus');
                })
                ->pause(2000)
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($webPortal->nama_portal);
        });
    }

    /**
     * Test Web Portal status toggle
     */
    public function testWebPortalStatusToggle()
    {
        $webPortal = WebPortal::first();
        $originalStatus = $webPortal->status;
        
        $this->browse(function (Browser $browser) use ($webPortal, $originalStatus) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->click('.toggle-status[data-id="' . $webPortal->id . '"]')
                ->pause(1000)
                ->assertSee('Status berhasil diupdate');
                
            // Verify status changed in database
            $this->assertDatabaseHas('web_portals', [
                'id' => $webPortal->id,
                'status' => !$originalStatus,
            ]);
        });
    }

    /**
     * Test Web Portal featured toggle
     */
    public function testWebPortalFeaturedToggle()
    {
        $webPortal = WebPortal::first();
        $originalFeatured = $webPortal->is_featured;
        
        $this->browse(function (Browser $browser) use ($webPortal, $originalFeatured) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->click('.toggle-featured[data-id="' . $webPortal->id . '"]')
                ->pause(1000)
                ->assertSee('Featured berhasil diupdate');
                
            // Verify featured status changed in database
            $this->assertDatabaseHas('web_portals', [
                'id' => $webPortal->id,
                'is_featured' => !$originalFeatured,
            ]);
        });
    }

    /**
     * Test Web Portal URL checker
     */
    public function testWebPortalUrlChecker()
    {
        $webPortal = WebPortal::first();
        
        $this->browse(function (Browser $browser) use ($webPortal) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->click('.check-url[data-id="' . $webPortal->id . '"]')
                ->pause(2000)
                ->assertSee('URL status checked');
        });
    }

    /**
     * Test Web Portal sorting functionality
     */
    public function testWebPortalSorting()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->click('th[data-sort="nama_portal"]')
                ->pause(1000)
                ->assertSee('Web Portal Test'); // Should show sorted results
        });
    }

    /**
     * Test Web Portal bulk operations
     */
    public function testWebPortalBulkOperations()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal')
                ->check('input[name="select_all"]')
                ->select('bulk_action', 'activate')
                ->press('Jalankan')
                ->pause(1000)
                ->assertSee('Operasi bulk berhasil dijalankan');
        });
    }

    /**
     * Test Web Portal logo upload
     */
    public function testWebPortalLogoUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/web-portal/create')
                ->type('nama_portal', 'Portal dengan Logo')
                ->type('url', 'https://logo.paputeng.go.id')
                ->type('deskripsi', 'Portal test dengan upload logo')
                ->select('kategori', 'layanan')
                ->attach('logo', __DIR__.'/../../fixtures/test-logo.png')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Portal dengan Logo');
        });
    }

    /**
     * Test access denied for non-authorized user
     */
    public function testAccessDeniedForNonAuthorizedUser()
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@inspektorat.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/web-portal')
                ->assertSee('403')
                ->assertSee('Forbidden');
        });
    }
}
