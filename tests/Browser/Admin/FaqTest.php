<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Faq;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FaqTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin FAQ',
            'email' => 'admin.faq@inspektorat.id',
            'password' => bcrypt('adminfaq123'),
            'role' => 'admin_faq',
            'is_active' => true,
        ]);

        // Create test FAQ data
        $this->createTestFaqData();
    }

    private function createTestFaqData()
    {
        for ($i = 1; $i <= 15; $i++) {
            Faq::create([
                'pertanyaan' => 'Pertanyaan FAQ Test ' . $i,
                'jawaban' => 'Jawaban untuk pertanyaan FAQ test ' . $i . '. Jawaban ini menjelaskan secara detail mengenai pertanyaan yang diajukan.',
                'urutan' => $i,
                'status' => true,
                'is_featured' => ($i <= 5),
                'created_by' => $this->admin->id,
                'updated_by' => $this->admin->id,
            ]);
        }
    }

    /**
     * Test FAQ index page
     */
    public function testFaqIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->assertSee('FAQ')
                ->assertSee('Tambah FAQ')
                ->assertSee('Pertanyaan FAQ Test 1')
                ->assertSee('Pertanyaan FAQ Test 2')
                ->assertSee('Pertanyaan FAQ Test 3')
                ->assertSee('Urutan')
                ->assertSee('Status');
        });
    }

    /**
     * Test FAQ pagination
     */
    public function testFaqPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->assertSee('FAQ')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 11')
                ->assertSee('Pertanyaan FAQ Test 12');
        });
    }

    /**
     * Test FAQ search functionality
     */
    public function testFaqSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->type('search', 'FAQ Test 5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 5')
                ->assertDontSee('Pertanyaan FAQ Test 1')
                ->assertDontSee('Pertanyaan FAQ Test 2');
        });
    }

    /**
     * Test FAQ filter by status
     */
    public function testFaqFilterByStatus()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->select('status', '1')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 1'); // Should show active FAQs
        });
    }

    /**
     * Test FAQ create page
     */
    public function testFaqCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->clickLink('Tambah FAQ')
                ->pause(1000)
                ->assertPathIs('/admin/faq/create')
                ->assertSee('Tambah FAQ')
                ->assertPresent('input[name="pertanyaan"]')
                ->assertPresent('textarea[name="jawaban"]')
                ->assertPresent('input[name="urutan"]')
                ->assertPresent('input[name="status"]')
                ->assertPresent('input[name="is_featured"]');
        });
    }

    /**
     * Test FAQ store functionality
     */
    public function testFaqStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq/create')
                ->type('pertanyaan', 'Bagaimana cara mengakses layanan inspektorat?')
                ->type('jawaban', 'Anda dapat mengakses layanan inspektorat melalui website resmi atau datang langsung ke kantor.')
                ->type('urutan', '1')
                ->check('status')
                ->check('is_featured')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/faq')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Bagaimana cara mengakses layanan inspektorat?');
        });
    }

    /**
     * Test FAQ store validation
     */
    public function testFaqStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The pertanyaan field is required')
                ->assertSee('The jawaban field is required')
                ->assertSee('The urutan field is required');
        });
    }

    /**
     * Test FAQ show page
     */
    public function testFaqShowPage()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('a[href="/admin/faq/' . $faq->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/faq/' . $faq->id)
                ->assertSee($faq->pertanyaan)
                ->assertSee($faq->jawaban)
                ->assertSee('Edit')
                ->assertSee('Hapus');
        });
    }

    /**
     * Test FAQ edit page
     */
    public function testFaqEditPage()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq/' . $faq->id . '/edit')
                ->assertSee('Edit FAQ')
                ->assertInputValue('pertanyaan', $faq->pertanyaan)
                ->assertInputValue('jawaban', $faq->jawaban)
                ->assertInputValue('urutan', (string)$faq->urutan);
        });
    }

    /**
     * Test FAQ update functionality
     */
    public function testFaqUpdate()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq/' . $faq->id . '/edit')
                ->clear('pertanyaan')
                ->type('pertanyaan', 'Pertanyaan FAQ Updated Test')
                ->clear('jawaban')
                ->type('jawaban', 'Jawaban FAQ yang telah diupdate')
                ->clear('urutan')
                ->type('urutan', '2')
                ->check('status')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/faq')
                ->assertSee('Data berhasil diupdate')
                ->assertSee('Pertanyaan FAQ Updated Test');
        });
    }

    /**
     * Test FAQ update validation
     */
    public function testFaqUpdateValidation()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq/' . $faq->id . '/edit')
                ->clear('pertanyaan')
                ->clear('jawaban')
                ->press('Update')
                ->pause(1000)
                ->assertSee('The pertanyaan field is required')
                ->assertSee('The jawaban field is required');
        });
    }

    /**
     * Test FAQ delete functionality
     */
    public function testFaqDelete()
    {
        $faq = Faq::latest()->first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->press('.btn-delete[data-id="' . $faq->id . '"]')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Hapus');
                })
                ->pause(2000)
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($faq->pertanyaan);
        });
    }

    /**
     * Test FAQ status toggle
     */
    public function testFaqStatusToggle()
    {
        $faq = Faq::first();
        $originalStatus = $faq->status;
        
        $this->browse(function (Browser $browser) use ($faq, $originalStatus) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('.toggle-status[data-id="' . $faq->id . '"]')
                ->pause(1000)
                ->assertSee('Status berhasil diupdate');
                
            // Verify status changed in database
            $this->assertDatabaseHas('faqs', [
                'id' => $faq->id,
                'status' => !$originalStatus,
            ]);
        });
    }

    /**
     * Test FAQ move up functionality
     */
    public function testFaqMoveUp()
    {
        $faq = Faq::where('urutan', 2)->first(); // Get FAQ with order 2
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('.move-up[data-id="' . $faq->id . '"]')
                ->pause(1000)
                ->assertSee('Urutan berhasil diupdate');
                
            // Verify order changed in database
            $this->assertDatabaseHas('faqs', [
                'id' => $faq->id,
                'urutan' => 1,
            ]);
        });
    }

    /**
     * Test FAQ move down functionality
     */
    public function testFaqMoveDown()
    {
        $faq = Faq::where('urutan', 1)->first(); // Get FAQ with order 1
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('.move-down[data-id="' . $faq->id . '"]')
                ->pause(1000)
                ->assertSee('Urutan berhasil diupdate');
                
            // Verify order changed in database
            $this->assertDatabaseHas('faqs', [
                'id' => $faq->id,
                'urutan' => 2,
            ]);
        });
    }

    /**
     * Test FAQ drag and drop reordering
     */
    public function testFaqDragDropReordering()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->dragLeft('.faq-item[data-id="1"]', 100) // Simulate drag and drop
                ->pause(1000)
                ->assertSee('Urutan berhasil diupdate');
        });
    }

    /**
     * Test FAQ bulk reorder functionality
     */
    public function testFaqBulkReorder()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->script([
                    'window.faqReorder = [3, 1, 2];' // New order
                ])
                ->press('Simpan Urutan')
                ->pause(1000)
                ->assertSee('Urutan berhasil diupdate');
        });
    }

    /**
     * Test FAQ featured toggle
     */
    public function testFaqFeaturedToggle()
    {
        $faq = Faq::first();
        $originalFeatured = $faq->is_featured;
        
        $this->browse(function (Browser $browser) use ($faq, $originalFeatured) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('.toggle-featured[data-id="' . $faq->id . '"]')
                ->pause(1000)
                ->assertSee('Featured berhasil diupdate');
                
            // Verify featured status changed in database
            $this->assertDatabaseHas('faqs', [
                'id' => $faq->id,
                'is_featured' => !$originalFeatured,
            ]);
        });
    }

    /**
     * Test FAQ sorting functionality
     */
    public function testFaqSorting()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('th[data-sort="pertanyaan"]')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test'); // Should show sorted results
        });
    }

    /**
     * Test FAQ bulk operations
     */
    public function testFaqBulkOperations()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->check('input[name="select_all"]')
                ->select('bulk_action', 'activate')
                ->press('Jalankan')
                ->pause(1000)
                ->assertSee('Operasi bulk berhasil dijalankan');
        });
    }

    /**
     * Test FAQ duplicate functionality
     */
    public function testFaqDuplicate()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('.duplicate[data-id="' . $faq->id . '"]')
                ->pause(1000)
                ->assertSee('FAQ berhasil diduplikasi')
                ->assertSee($faq->pertanyaan);
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
                ->visit('/admin/faq')
                ->assertSee('403')
                ->assertSee('Forbidden');
        });
    }
}
