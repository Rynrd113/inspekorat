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
        $categories = ['Umum', 'Audit', 'Pelayanan', 'WBS', 'Teknis'];
        
        for ($i = 1; $i <= 15; $i++) {
            Faq::create([
                'pertanyaan' => 'Pertanyaan FAQ Test ' . $i . '?',
                'jawaban' => 'Jawaban untuk pertanyaan FAQ test ' . $i . '. Ini adalah jawaban yang menjelaskan dengan detail mengenai pertanyaan yang diajukan.',
                'kategori' => $categories[($i - 1) % 5],
                'urutan' => $i,
                'status' => 'published',
                'is_featured' => $i <= 3,
                'view_count' => rand(10, 100),
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
                ->assertSee('Pertanyaan FAQ Test 3');
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
                ->assertPresent('select[name="kategori"]')
                ->assertPresent('input[name="urutan"]')
                ->assertPresent('select[name="status"]')
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
                ->type('pertanyaan', 'Bagaimana cara melaporkan dugaan korupsi?')
                ->type('jawaban', 'Anda dapat melaporkan dugaan korupsi melalui sistem WBS (Whistleblowing System) yang tersedia di website inspektorat atau datang langsung ke kantor inspektorat dengan membawa bukti-bukti yang kuat.')
                ->select('kategori', 'WBS')
                ->type('urutan', '1')
                ->select('status', 'published')
                ->check('is_featured')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/faq')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Bagaimana cara melaporkan dugaan korupsi?');
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
                ->assertSee('The kategori field is required')
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
                ->assertSee($faq->kategori)
                ->assertSee('Urutan: ' . $faq->urutan)
                ->assertSee('Views: ' . $faq->view_count);
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
                ->visit('/admin/faq')
                ->click('a[href="/admin/faq/' . $faq->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/faq/' . $faq->id . '/edit')
                ->assertSee('Edit FAQ')
                ->assertInputValue('pertanyaan', $faq->pertanyaan)
                ->assertInputValue('urutan', $faq->urutan)
                ->assertSee($faq->jawaban);
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
                ->type('pertanyaan', 'Pertanyaan FAQ Updated?')
                ->clear('jawaban')
                ->type('jawaban', 'Jawaban FAQ yang sudah diupdate dengan informasi terbaru.')
                ->select('kategori', 'Audit')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/faq')
                ->assertSee('Data berhasil diperbarui')
                ->assertSee('Pertanyaan FAQ Updated?');
        });
    }

    /**
     * Test FAQ delete functionality
     */
    public function testFaqDelete()
    {
        $faq = Faq::first();
        $faqQuestion = $faq->pertanyaan;
        
        $this->browse(function (Browser $browser) use ($faq, $faqQuestion) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus FAQ ini?\')) { document.getElementById(\'delete-form-' . $faq->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/faq')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($faqQuestion);
        });
    }

    /**
     * Test FAQ category filter
     */
    public function testFaqCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->select('kategori', 'Umum')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 1')
                ->assertSee('Pertanyaan FAQ Test 6')
                ->select('kategori', 'Audit')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 2')
                ->assertSee('Pertanyaan FAQ Test 7');
        });
    }

    /**
     * Test FAQ status filter
     */
    public function testFaqStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->select('status', 'published')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 1')
                ->assertSee('Pertanyaan FAQ Test 2')
                ->select('status', 'draft')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Tidak ada data');
        });
    }

    /**
     * Test FAQ featured toggle
     */
    public function testFaqFeaturedToggle()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('input[name="is_featured"][data-id="' . $faq->id . '"]')
                ->pause(1000)
                ->assertSee('Featured status berhasil diubah');
        });
    }

    /**
     * Test FAQ reorder functionality
     */
    public function testFaqReorder()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->clickLink('Reorder')
                ->pause(1000)
                ->assertPathIs('/admin/faq/reorder')
                ->assertSee('Reorder FAQ')
                ->assertPresent('.sortable-list')
                ->drag('.faq-item:first-child', '.faq-item:nth-child(3)')
                ->pause(1000)
                ->press('Simpan Urutan')
                ->pause(1000)
                ->assertSee('Urutan berhasil disimpan');
        });
    }

    /**
     * Test FAQ move up functionality
     */
    public function testFaqMoveUp()
    {
        $faq = Faq::where('urutan', '>', 1)->first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('button[onclick="moveUp(' . $faq->id . ')"]')
                ->pause(1000)
                ->assertSee('Urutan berhasil diubah');
        });
    }

    /**
     * Test FAQ move down functionality
     */
    public function testFaqMoveDown()
    {
        $faq = Faq::where('urutan', '<', 15)->first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('button[onclick="moveDown(' . $faq->id . ')"]')
                ->pause(1000)
                ->assertSee('Urutan berhasil diubah');
        });
    }

    /**
     * Test FAQ bulk actions
     */
    public function testFaqBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->check('select-all')
                ->select('bulk-action', 'publish')
                ->press('Apply')
                ->pause(1000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test FAQ responsive design
     */
    public function testFaqResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->admin)
                ->visit('/admin/faq')
                ->assertSee('FAQ')
                ->assertSee('Tambah FAQ')
                ->resize(768, 1024) // iPad size
                ->assertSee('FAQ')
                ->assertSee('Tambah FAQ')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test FAQ rich text editor
     */
    public function testFaqRichTextEditor()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq/create')
                ->assertPresent('.rich-text-editor')
                ->click('.rich-text-editor')
                ->keys('.rich-text-editor', 'Jawaban dengan {bold}text tebal{/bold} dan {italic}text miring{/italic}')
                ->pause(1000)
                ->assertSee('Jawaban dengan text tebal dan text miring');
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
                ->click('button[onclick="duplicateFaq(' . $faq->id . ')"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertSee('FAQ berhasil diduplikasi')
                ->assertSee('Copy of ' . $faq->pertanyaan);
        });
    }

    /**
     * Test FAQ statistics
     */
    public function testFaqStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->assertSee('Total FAQ')
                ->assertSee('FAQ Aktif')
                ->assertSee('FAQ Featured')
                ->assertSee('Total Views')
                ->assertSee('FAQ per Kategori');
        });
    }

    /**
     * Test FAQ export functionality
     */
    public function testFaqExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('a[href="/admin/faq/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test FAQ import functionality
     */
    public function testFaqImport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->clickLink('Import')
                ->pause(1000)
                ->attach('file', __DIR__ . '/../../fixtures/faq-import.xlsx')
                ->press('Import')
                ->pause(2000)
                ->assertSee('Import berhasil');
        });
    }

    /**
     * Test FAQ preview functionality
     */
    public function testFaqPreview()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq/' . $faq->id . '/edit')
                ->click('button[onclick="previewFaq()"]')
                ->pause(1000)
                ->assertSee('Preview FAQ')
                ->assertSee($faq->pertanyaan)
                ->assertSee($faq->jawaban);
        });
    }

    /**
     * Test FAQ search by category
     */
    public function testFaqSearchByCategory()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->type('search', 'Audit')
                ->select('search_type', 'category')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 2')
                ->assertSee('Pertanyaan FAQ Test 7');
        });
    }

    /**
     * Test FAQ advanced search
     */
    public function testFaqAdvancedSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->clickLink('Advanced Search')
                ->pause(1000)
                ->type('pertanyaan', 'Test')
                ->type('jawaban', 'jawaban')
                ->select('kategori', 'Umum')
                ->select('status', 'published')
                ->check('is_featured')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Pertanyaan FAQ Test 1');
        });
    }

    /**
     * Test FAQ toggle status
     */
    public function testFaqToggleStatus()
    {
        $faq = Faq::first();
        
        $this->browse(function (Browser $browser) use ($faq) {
            $browser->loginAs($this->admin)
                ->visit('/admin/faq')
                ->click('button[onclick="toggleStatus(' . $faq->id . ')"]')
                ->pause(1000)
                ->assertSee('Status berhasil diubah');
        });
    }
}
