<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\PortalPapuaTengah;
use App\Models\Galeri;
use App\Models\Dokumen;
use App\Models\PortalOpd;
use App\Models\Pelayanan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;

class FrontendBackendSyncTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test news creation sync between admin and public frontend.
     */
    public function test_news_creation_sync_between_admin_and_public()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            // Create news in admin
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'News Sync Test')
                    ->type('isi', 'Content for sync testing')
                    ->select('status', 'published')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);

            // Verify in database
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'judul' => 'News Sync Test',
                'status' => 'published',
            ]);

            // Immediately check public frontend
            $browser->visit('/berita')
                    ->waitForText('News Sync Test', 10)
                    ->assertSee('News Sync Test');

            // Check homepage also shows the news
            $browser->visit('/')
                    ->waitForText('News Sync Test', 10)
                    ->assertSee('News Sync Test');
        });
    }

    /**
     * Test news update sync reflects immediately in frontend.
     */
    public function test_news_update_sync_reflects_immediately()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Original Title',
            'isi' => 'Original Content',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            // First verify original content is visible
            $browser->visit('/berita')
                    ->assertSee('Original Title');

            $this->loginAs($admin, $browser);
            
            // Update the news
            $browser->visit("/admin/portal-papua-tengah/{$news->id}/edit")
                    ->clear('judul')
                    ->type('judul', 'Updated Title')
                    ->clear('isi')
                    ->type('isi', 'Updated Content')
                    ->press('Update')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil diupdate', 10);

            // Immediately check frontend reflects changes
            $browser->visit('/berita')
                    ->waitForText('Updated Title', 10)
                    ->assertSee('Updated Title')
                    ->assertDontSee('Original Title');

            // Check detail page also reflects changes
            $browser->visit("/berita/{$news->id}")
                    ->assertSee('Updated Title')
                    ->assertSee('Updated Content')
                    ->assertDontSee('Original Content');
        });
    }

    /**
     * Test news deletion removes from frontend immediately.
     */
    public function test_news_deletion_removes_from_frontend_immediately()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'News to Delete',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            // First verify news is visible
            $browser->visit('/berita')
                    ->assertSee('News to Delete');

            $this->loginAs($admin, $browser);
            
            // Delete the news
            $browser->visit('/admin/portal-papua-tengah')
                    ->click("button[data-delete-id='{$news->id}']")
                    ->waitFor('.confirmation-modal')
                    ->click('.btn-confirm-delete')
                    ->waitForText('Berita berhasil dihapus', 10);

            // Verify database deletion
            $this->assertDatabaseMissing('portal_papua_tengahs', [
                'id' => $news->id,
            ]);

            // Immediately check frontend
            $browser->visit('/berita')
                    ->assertDontSee('News to Delete');

            // Check direct access returns 404
            $browser->visit("/berita/{$news->id}")
                    ->assertSee('404')
                    ->orAssertSee('Not Found');
        });
    }

    /**
     * Test draft to publish status change sync.
     */
    public function test_draft_to_publish_status_change_sync()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Draft News Test',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            // Verify draft is not visible in public
            $browser->visit('/berita')
                    ->assertDontSee('Draft News Test');

            $this->loginAs($admin, $browser);
            
            // Change status to published
            $browser->visit("/admin/portal-papua-tengah/{$news->id}/edit")
                    ->select('status', 'published')
                    ->press('Update')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil diupdate', 10);

            // Now it should appear in public
            $browser->visit('/berita')
                    ->waitForText('Draft News Test', 10)
                    ->assertSee('Draft News Test');
        });
    }

    /**
     * Test gallery management sync between admin and public.
     */
    public function test_gallery_management_sync()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            // Create gallery in admin
            $browser->visit('/admin/galeri/create')
                    ->type('judul', 'Gallery Sync Test')
                    ->type('deskripsi', 'Gallery description for sync testing')
                    ->select('status', 'published');

            // Upload gallery images
            $this->uploadFile($browser, 'images[]', 'gallery1.jpg', 'fake-image-1');
            $this->uploadFile($browser, 'images[]', 'gallery2.jpg', 'fake-image-2');

            $browser->press('Simpan')
                    ->waitForLocation('/admin/galeri')
                    ->waitForText('Galeri berhasil dibuat', 10);

            // Verify in database
            $this->assertDatabaseHas('galeris', [
                'judul' => 'Gallery Sync Test',
                'status' => 'published',
            ]);

            // Check public frontend
            $browser->visit('/galeri')
                    ->waitForText('Gallery Sync Test', 10)
                    ->assertSee('Gallery Sync Test');
        });
    }

    /**
     * Test document upload sync between admin and public.
     */
    public function test_document_upload_sync()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            // Upload document in admin
            $browser->visit('/admin/dokumen/create')
                    ->type('judul', 'Document Sync Test')
                    ->type('deskripsi', 'Document for sync testing')
                    ->select('kategori', 'peraturan')
                    ->select('status', 'published');

            // Upload document file
            $this->uploadFile($browser, 'file', 'document.pdf', 'fake-pdf-content');

            $browser->press('Simpan')
                    ->waitForLocation('/admin/dokumen')
                    ->waitForText('Dokumen berhasil diupload', 10);

            // Verify in database
            $this->assertDatabaseHas('dokumens', [
                'judul' => 'Document Sync Test',
                'status' => 'published',
            ]);

            // Check public frontend
            $browser->visit('/dokumen')
                    ->waitForText('Document Sync Test', 10)
                    ->assertSee('Document Sync Test');
        });
    }

    /**
     * Test OPD portal sync between admin and public.
     */
    public function test_opd_portal_sync()
    {
        $admin = $this->createOpdManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            // Create OPD portal in admin
            $browser->visit('/admin/portal-opd/create')
                    ->type('nama_opd', 'OPD Sync Test')
                    ->type('deskripsi', 'OPD description for sync testing')
                    ->type('alamat', 'Jl. Test No. 123')
                    ->type('telepon', '021-12345678')
                    ->type('email', 'opd@test.com')
                    ->select('status', 'active')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-opd')
                    ->waitForText('Portal OPD berhasil dibuat', 10);

            // Verify in database
            $this->assertDatabaseHas('portal_opds', [
                'nama_opd' => 'OPD Sync Test',
                'status' => 'active',
            ]);

            // Check public frontend
            $browser->visit('/portal-opd')
                    ->waitForText('OPD Sync Test', 10)
                    ->assertSee('OPD Sync Test');
        });
    }

    /**
     * Test services management sync.
     */
    public function test_services_management_sync()
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            // Create service in admin
            $browser->visit('/admin/pelayanan/create')
                    ->type('nama_layanan', 'Service Sync Test')
                    ->type('deskripsi', 'Service description for sync testing')
                    ->type('syarat', 'Requirements for service')
                    ->type('waktu_pelayanan', '3 hari kerja')
                    ->type('biaya', 'Gratis')
                    ->select('status', 'active')
                    ->press('Simpan')
                    ->waitForLocation('/admin/pelayanan')
                    ->waitForText('Layanan berhasil dibuat', 10);

            // Verify in database
            $this->assertDatabaseHas('pelayanans', [
                'nama_layanan' => 'Service Sync Test',
                'status' => 'active',
            ]);

            // Check public frontend
            $browser->visit('/pelayanan')
                    ->waitForText('Service Sync Test', 10)
                    ->assertSee('Service Sync Test');
        });
    }

    /**
     * Test bulk operations sync with frontend.
     */
    public function test_bulk_operations_sync_with_frontend()
    {
        $admin = $this->createContentManager();
        
        // Create multiple draft news
        $news1 = PortalPapuaTengah::factory()->create([
            'judul' => 'Bulk News 1',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);
        
        $news2 = PortalPapuaTengah::factory()->create([
            'judul' => 'Bulk News 2',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news1, $news2) {
            // Verify drafts are not visible in public
            $browser->visit('/berita')
                    ->assertDontSee('Bulk News 1')
                    ->assertDontSee('Bulk News 2');

            $this->loginAs($admin, $browser);
            
            // Bulk publish news
            $browser->visit('/admin/portal-papua-tengah')
                    ->check("input[value='{$news1->id}']")
                    ->check("input[value='{$news2->id}']")
                    ->select('bulk_action', 'publish')
                    ->press('Apply')
                    ->waitForText('Bulk action berhasil diterapkan', 10);

            // Verify both are now published in database
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'id' => $news1->id,
                'status' => 'published',
            ]);
            
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'id' => $news2->id,
                'status' => 'published',
            ]);

            // Check both now appear in public frontend
            $browser->visit('/berita')
                    ->waitForText('Bulk News 1', 10)
                    ->assertSee('Bulk News 1')
                    ->assertSee('Bulk News 2');
        });
    }

    /**
     * Test featured news toggle sync with homepage.
     */
    public function test_featured_news_toggle_sync_with_homepage()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Featured Toggle Test',
            'status' => 'published',
            'is_featured' => false,
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            // Verify not featured on homepage
            $browser->visit('/')
                    ->assertDontSee('Featured Toggle Test'); // Assuming featured section doesn't show non-featured

            $this->loginAs($admin, $browser);
            
            // Make it featured
            $browser->visit("/admin/portal-papua-tengah/{$news->id}/edit")
                    ->check('is_featured')
                    ->press('Update')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil diupdate', 10);

            // Verify database update
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'id' => $news->id,
                'is_featured' => true,
            ]);

            // Now should appear on homepage
            $browser->visit('/')
                    ->waitForText('Featured Toggle Test', 10)
                    ->assertSee('Featured Toggle Test')
                    ->assertVisible('.featured-news');
        });
    }

    /**
     * Test cache invalidation on content updates.
     */
    public function test_cache_invalidation_on_content_updates()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Cache Test News',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            // First visit to cache the page
            $browser->visit('/berita')
                    ->assertSee('Cache Test News');

            $this->loginAs($admin, $browser);
            
            // Update the news
            $browser->visit("/admin/portal-papua-tengah/{$news->id}/edit")
                    ->clear('judul')
                    ->type('judul', 'Cache Updated News')
                    ->press('Update')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil diupdate', 10);

            // Should immediately reflect changes despite caching
            $browser->visit('/berita')
                    ->waitForText('Cache Updated News', 10)
                    ->assertSee('Cache Updated News')
                    ->assertDontSee('Cache Test News');
        });
    }

    /**
     * Test real-time sync verification with multiple browser windows.
     */
    public function test_realtime_sync_with_multiple_browsers()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $adminBrowser, Browser $publicBrowser) use ($admin) {
            // Setup public browser on news page
            $publicBrowser->visit('/berita');
            
            // Setup admin browser
            $this->loginAs($admin, $adminBrowser);
            
            // Create news in admin browser
            $adminBrowser->visit('/admin/portal-papua-tengah/create')
                         ->type('judul', 'Real-time Sync Test')
                         ->type('isi', 'Testing real-time synchronization')
                         ->select('status', 'published')
                         ->press('Simpan')
                         ->waitForLocation('/admin/portal-papua-tengah')
                         ->waitForText('Berita berhasil dibuat', 10);

            // Verify database has the record
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'judul' => 'Real-time Sync Test',
                'status' => 'published',
            ]);

            // Check public browser immediately reflects the change
            $publicBrowser->refresh()
                          ->waitForText('Real-time Sync Test', 10)
                          ->assertSee('Real-time Sync Test');
        });
    }

    /**
     * Test API endpoints sync with frontend data.
     */
    public function test_api_endpoints_sync_with_frontend()
    {
        $admin = $this->createContentManager();
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'API Sync Test',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $news) {
            // Check API endpoint returns the data
            $response = $this->get('/api/v1/berita');
            $response->assertStatus(200);
            $response->assertJsonFragment(['judul' => 'API Sync Test']);

            $this->loginAs($admin, $browser);
            
            // Update via admin interface
            $browser->visit("/admin/portal-papua-tengah/{$news->id}/edit")
                    ->clear('judul')
                    ->type('judul', 'API Updated Test')
                    ->press('Update')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil diupdate', 10);

            // API should immediately reflect the change
            $response = $this->get('/api/v1/berita');
            $response->assertStatus(200);
            $response->assertJsonFragment(['judul' => 'API Updated Test']);
            $response->assertJsonMissing(['judul' => 'API Sync Test']);
        });
    }
}