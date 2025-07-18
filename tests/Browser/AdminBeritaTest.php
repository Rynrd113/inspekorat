<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\PortalPapuaTengah;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminBeritaTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test admin can access news management page.
     */
    public function test_admin_can_access_news_management_page()
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertSee('Manajemen Berita')
                    ->assertSee('Tambah Berita')
                    ->assertVisible('.btn-create')
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test admin can create new news article successfully.
     */
    public function test_admin_can_create_news_article_successfully()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->click('.btn-create')
                    ->waitForLocation('/admin/portal-papua-tengah/create')
                    ->assertSee('Tambah Berita Baru');

            // Fill form with valid data
            $newsData = [
                'judul' => 'Berita Test Admin',
                'isi' => 'Konten berita lengkap untuk testing admin functionality.',
                'meta_description' => 'Meta description untuk SEO testing',
                'tags' => 'test, admin, berita',
            ];

            $browser->type('judul', $newsData['judul'])
                    ->type('isi', $newsData['isi'])
                    ->type('meta_description', $newsData['meta_description'])
                    ->type('tags', $newsData['tags'])
                    ->select('status', 'published')
                    ->check('is_featured');

            // Upload featured image
            $this->uploadFile($browser, 'gambar_utama', 'test-image.jpg', 'fake-image-content');

            $browser->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);

            // Verify database has the record
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'judul' => $newsData['judul'],
                'isi' => $newsData['isi'],
                'status' => 'published',
                'is_featured' => true,
                'created_by' => $admin->id,
            ]);

            // Verify news appears in public frontend
            $browser->visit('/berita')
                    ->waitForText($newsData['judul'], 10)
                    ->assertSee($newsData['judul']);
        });
    }

    /**
     * Test admin can edit existing news article.
     */
    public function test_admin_can_edit_existing_news_article()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Original',
            'isi' => 'Konten original',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->click("a[href*='/admin/portal-papua-tengah/{$news->id}/edit']")
                    ->waitForLocation("/admin/portal-papua-tengah/{$news->id}/edit")
                    ->assertSee('Edit Berita')
                    ->assertInputValue('judul', $news->judul);

            // Update news data
            $updatedData = [
                'judul' => 'Berita Updated',
                'isi' => 'Konten yang sudah diupdate',
            ];

            $browser->clear('judul')
                    ->type('judul', $updatedData['judul'])
                    ->clear('isi')
                    ->type('isi', $updatedData['isi'])
                    ->select('status', 'published')
                    ->press('Update')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil diupdate', 10);

            // Verify database is updated
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'id' => $news->id,
                'judul' => $updatedData['judul'],
                'isi' => $updatedData['isi'],
                'status' => 'published',
            ]);

            // Verify updated news appears in frontend
            $browser->visit('/berita')
                    ->waitForText($updatedData['judul'], 10)
                    ->assertSee($updatedData['judul'])
                    ->assertDontSee('Berita Original');
        });
    }

    /**
     * Test admin can delete news article.
     */
    public function test_admin_can_delete_news_article()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita untuk Dihapus',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertSee($news->judul);

            // Delete news with confirmation
            $browser->click("button[data-delete-id='{$news->id}']")
                    ->waitFor('.confirmation-modal')
                    ->assertSee('Apakah Anda yakin?')
                    ->click('.btn-confirm-delete')
                    ->waitForText('Berita berhasil dihapus', 10);

            // Verify database record is deleted
            $this->assertDatabaseMissing('portal_papua_tengahs', [
                'id' => $news->id,
            ]);

            // Verify news is removed from frontend
            $browser->visit('/berita')
                    ->assertDontSee($news->judul);
        });
    }

    /**
     * Test form validation for required fields.
     */
    public function test_form_validation_for_required_fields()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->press('Simpan') // Submit without filling required fields
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The judul field is required')
                    ->assertSee('The isi field is required');
        });
    }

    /**
     * Test image upload validation.
     */
    public function test_image_upload_validation()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'Test Berita')
                    ->type('isi', 'Test konten');

            // Upload invalid file type
            $this->uploadFile($browser, 'gambar_utama', 'test.txt', 'not-an-image');

            $browser->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The gambar utama field must be an image');
        });
    }

    /**
     * Test draft functionality.
     */
    public function test_draft_functionality()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'Berita Draft Test')
                    ->type('isi', 'Konten berita draft')
                    ->select('status', 'draft')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);

            // Verify draft is in database
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'judul' => 'Berita Draft Test',
                'status' => 'draft',
            ]);

            // Verify draft does NOT appear in public frontend
            $browser->visit('/berita')
                    ->assertDontSee('Berita Draft Test');

            // Verify draft appears in admin with draft label
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertSee('Berita Draft Test')
                    ->assertSee('Draft');
        });
    }

    /**
     * Test featured news functionality.
     */
    public function test_featured_news_functionality()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'Berita Featured Test')
                    ->type('isi', 'Konten berita featured')
                    ->select('status', 'published')
                    ->check('is_featured')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);

            // Verify featured news appears on homepage
            $browser->visit('/')
                    ->waitForText('Berita Featured Test', 10)
                    ->assertSee('Berita Featured Test')
                    ->assertVisible('.featured-news');
        });
    }

    /**
     * Test news search and filtering in admin.
     */
    public function test_news_search_and_filtering_in_admin()
    {
        $admin = $this->createContentManager();
        
        $publishedNews = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Published Cari',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);
        
        $draftNews = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Draft Cari',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $publishedNews, $draftNews) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah');

            // Test search functionality
            $browser->type('search', 'Cari')
                    ->press('Cari')
                    ->waitFor('.search-results')
                    ->assertSee($publishedNews->judul)
                    ->assertSee($draftNews->judul);

            // Test status filter
            $browser->select('status_filter', 'published')
                    ->press('Filter')
                    ->waitFor('.filtered-results')
                    ->assertSee($publishedNews->judul)
                    ->assertDontSee($draftNews->judul);
        });
    }

    /**
     * Test bulk actions functionality.
     */
    public function test_bulk_actions_functionality()
    {
        $admin = $this->createContentManager();
        
        $news1 = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Bulk 1',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);
        
        $news2 = PortalPapuaTengah::factory()->create([
            'judul' => 'Berita Bulk 2',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news1, $news2) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->check("input[value='{$news1->id}']")
                    ->check("input[value='{$news2->id}']")
                    ->select('bulk_action', 'publish')
                    ->press('Apply')
                    ->waitForText('Bulk action berhasil diterapkan', 10);

            // Verify both articles are now published
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'id' => $news1->id,
                'status' => 'published',
            ]);
            
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'id' => $news2->id,
                'status' => 'published',
            ]);

            // Verify they appear in frontend
            $browser->visit('/berita')
                    ->assertSee($news1->judul)
                    ->assertSee($news2->judul);
        });
    }

    /**
     * Test news categories functionality.
     */
    public function test_news_categories_functionality()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'Berita dengan Kategori')
                    ->type('isi', 'Konten berita dengan kategori')
                    ->select('kategori', 'pengumuman')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);

            // Verify category is saved
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'judul' => 'Berita dengan Kategori',
                'kategori' => 'pengumuman',
            ]);

            // Test category filtering in frontend
            $browser->visit('/berita?kategori=pengumuman')
                    ->assertSee('Berita dengan Kategori');
        });
    }

    /**
     * Test content management permissions between roles.
     */
    public function test_content_management_permissions_between_roles()
    {
        $contentManager = $this->createContentManager();
        $opdManager = $this->createOpdManager();
        
        $news = PortalPapuaTengah::factory()->create([
            'created_by' => $contentManager->id,
        ]);

        $this->browse(function (Browser $browser) use ($contentManager, $opdManager, $news) {
            // Content manager should be able to manage news
            $this->loginAs($contentManager, $browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertSee($news->judul)
                    ->assertVisible("a[href*='/admin/portal-papua-tengah/{$news->id}/edit']");
                    
            $this->logout($browser);

            // OPD manager should NOT be able to access news management
            $this->loginAs($opdManager, $browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertSee('403')
                    ->orAssertSee('Unauthorized');
        });
    }
}