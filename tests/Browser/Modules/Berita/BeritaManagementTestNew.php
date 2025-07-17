<?php

namespace Tests\Browser\Modules\Berita;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\BeritaIndexPage;
use Tests\Browser\Pages\BeritaCreatePage;
use Tests\Browser\Pages\BeritaEditPage;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithForms;
use Tests\Browser\Traits\InteractsWithFiles;
use Tests\DuskTestCase;

class BeritaManagementTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithForms, InteractsWithFiles;

    /**
     * Test content manager dapat melihat daftar berita
     */
    public function test_content_manager_dapat_melihat_daftar_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new BeritaIndexPage)
                    ->assertSee('Berita')
                    ->assertVisible('@data-table')
                    ->assertVisible('@create-btn');
        });
    }

    /**
     * Test content manager dapat membuat berita baru
     */
    public function test_content_manager_dapat_membuat_berita_baru()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $beritaData = [
                'judul' => 'Test Berita ' . time(),
                'konten' => 'Ini adalah konten test berita yang dibuat untuk testing.',
                'kategori' => 'pengumuman',
                'status' => 'published',
                'tags' => 'test, berita, pengumuman',
                'excerpt' => 'Excerpt singkat untuk test berita',
                'meta_description' => 'Meta description untuk SEO',
                'publish_date' => now()->format('Y-m-d'),
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($beritaData)
                    ->submitForm()
                    ->on(new BeritaIndexPage)
                    ->assertItemExists($beritaData['judul']);
        });
    }

    /**
     * Test content manager dapat membuat berita dengan gambar
     */
    public function test_content_manager_dapat_membuat_berita_dengan_gambar()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $imagePath = $this->createTestFile('test-image.jpg', 'fake-image-content', 'image/jpeg');
            
            $beritaData = [
                'judul' => 'Test Berita dengan Gambar ' . time(),
                'konten' => 'Ini adalah konten test berita dengan gambar.',
                'kategori' => 'berita',
                'status' => 'published',
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($beritaData)
                    ->uploadImage($imagePath)
                    ->submitForm()
                    ->assertSuccess('Berita berhasil dibuat');
        });
    }

    /**
     * Test validasi form create berita
     */
    public function test_validasi_form_create_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->submitForm() // Submit empty form
                    ->assertValidationErrors(['judul', 'konten']);
        });
    }

    /**
     * Test validasi upload gambar
     */
    public function test_validasi_upload_gambar_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $invalidFile = $this->createTestFile('invalid.txt', 'invalid-content', 'text/plain');
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm([
                        'judul' => 'Test Berita',
                        'konten' => 'Test konten',
                        'kategori' => 'berita',
                        'status' => 'published',
                    ])
                    ->uploadImage($invalidFile)
                    ->submitForm()
                    ->assertValidationErrors(['gambar']);
        });
    }

    /**
     * Test content manager dapat mengedit berita
     */
    public function test_content_manager_dapat_mengedit_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            // Create berita first
            $originalData = [
                'judul' => 'Original Berita ' . time(),
                'konten' => 'Original konten berita.',
                'kategori' => 'berita',
                'status' => 'draft',
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($originalData)
                    ->submitForm()
                    ->on(new BeritaIndexPage)
                    ->assertItemExists($originalData['judul']);
            
            // Edit berita
            $updatedData = [
                'judul' => 'Updated Berita ' . time(),
                'konten' => 'Updated konten berita.',
                'status' => 'published',
            ];
            
            $browser->clickEdit($originalData['judul'])
                    ->on(new BeritaEditPage(1))
                    ->updateForm($updatedData)
                    ->submitForm()
                    ->on(new BeritaIndexPage)
                    ->assertItemExists($updatedData['judul'])
                    ->assertItemNotExists($originalData['judul']);
        });
    }

    /**
     * Test content manager dapat menghapus berita
     */
    public function test_content_manager_dapat_menghapus_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            // Create berita first
            $beritaData = [
                'judul' => 'Berita akan dihapus ' . time(),
                'konten' => 'Konten berita yang akan dihapus.',
                'kategori' => 'berita',
                'status' => 'published',
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($beritaData)
                    ->submitForm()
                    ->on(new BeritaIndexPage)
                    ->assertItemExists($beritaData['judul']);
            
            // Delete berita
            $browser->clickDelete($beritaData['judul'])
                    ->assertItemNotExists($beritaData['judul']);
        });
    }

    /**
     * Test search functionality
     */
    public function test_search_berita_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            // Create test berita
            $beritaData = [
                'judul' => 'Berita Pencarian Unik ' . time(),
                'konten' => 'Konten berita untuk testing pencarian.',
                'kategori' => 'berita',
                'status' => 'published',
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($beritaData)
                    ->submitForm()
                    ->on(new BeritaIndexPage)
                    ->search('Pencarian Unik')
                    ->assertItemExists($beritaData['judul']);
        });
    }

    /**
     * Test filter by status
     */
    public function test_filter_berita_by_status()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            // Create published berita
            $publishedBerita = [
                'judul' => 'Published Berita ' . time(),
                'konten' => 'Published konten.',
                'kategori' => 'berita',
                'status' => 'published',
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($publishedBerita)
                    ->submitForm()
                    ->on(new BeritaIndexPage)
                    ->filterByStatus('published')
                    ->assertItemExists($publishedBerita['judul']);
        });
    }

    /**
     * Test pagination
     */
    public function test_pagination_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new BeritaIndexPage)
                    ->assertPaginationVisible();
        });
    }

    /**
     * Test bulk actions
     */
    public function test_bulk_actions_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new BeritaIndexPage)
                    ->selectAllItems()
                    ->performBulkAction('delete');
        });
    }

    /**
     * Test rich text editor functionality
     */
    public function test_rich_text_editor_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $richContent = '<p>Ini adalah <strong>konten</strong> dengan <em>formatting</em>.</p>';
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm(['judul' => 'Test Rich Text ' . time()])
                    ->fillRichTextEditor($richContent)
                    ->submitForm()
                    ->assertSuccess();
        });
    }

    /**
     * Test save as draft functionality
     */
    public function test_save_as_draft_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $draftData = [
                'judul' => 'Draft Berita ' . time(),
                'konten' => 'Draft konten berita.',
                'kategori' => 'berita',
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($draftData)
                    ->saveDraft()
                    ->on(new BeritaIndexPage)
                    ->assertItemExists($draftData['judul']);
        });
    }

    /**
     * Test preview functionality
     */
    public function test_preview_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm([
                        'judul' => 'Test Preview',
                        'konten' => 'Test konten preview.',
                        'kategori' => 'berita',
                    ])
                    ->preview()
                    ->assertVisible('.preview-modal');
        });
    }

    /**
     * Test form cancel functionality
     */
    public function test_cancel_form_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm([
                        'judul' => 'Test Cancel',
                        'konten' => 'Test konten cancel.',
                    ])
                    ->cancel()
                    ->on(new BeritaIndexPage);
        });
    }

    /**
     * Test role-based access untuk berita
     */
    public function test_admin_berita_dapat_akses_semua_fungsi()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminBerita($browser);
            
            $browser->visit(new BeritaIndexPage)
                    ->assertVisible('@create-btn')
                    ->assertVisible('@data-table');
        });
    }

    /**
     * Test role lain tidak dapat akses berita
     */
    public function test_admin_pelayanan_tidak_dapat_akses_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdminPelayanan($browser);
            
            $browser->visit('/admin/portal-papua-tengah')
                    ->assertSee('403')
                    ->orWhere('assertSee', 'Forbidden');
        });
    }

    /**
     * Test responsive design pada berita module
     */
    public function test_responsive_design_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $browser->visit(new BeritaIndexPage);
            
            $this->testResponsiveDesign($browser, function ($browser, $device) {
                $browser->assertVisible('@data-table')
                        ->assertVisible('@create-btn');
            });
        });
    }

    /**
     * Test performance pada berita module
     */
    public function test_performance_berita_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $startTime = microtime(true);
            
            $browser->visit(new BeritaIndexPage)
                    ->waitForLoadingToFinish($browser);
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Page should load within 3 seconds
            $this->assertLessThan(3, $loadTime, 'Berita index page took too long to load: ' . $loadTime . ' seconds');
        });
    }

    /**
     * Test SEO fields pada berita
     */
    public function test_seo_fields_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $seoData = [
                'judul' => 'SEO Test Berita ' . time(),
                'konten' => 'SEO test konten berita.',
                'kategori' => 'berita',
                'status' => 'published',
                'meta_description' => 'Meta description untuk SEO testing',
                'tags' => 'seo, testing, berita',
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($seoData)
                    ->submitForm()
                    ->assertSuccess();
        });
    }

    /**
     * Test image replacement pada edit berita
     */
    public function test_image_replacement_edit_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $originalImage = $this->createTestFile('original.jpg', 'original-content', 'image/jpeg');
            $newImage = $this->createTestFile('new.jpg', 'new-content', 'image/jpeg');
            
            $beritaData = [
                'judul' => 'Test Image Replacement ' . time(),
                'konten' => 'Test konten with image replacement.',
                'kategori' => 'berita',
                'status' => 'published',
            ];
            
            // Create berita with original image
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($beritaData)
                    ->uploadImage($originalImage)
                    ->submitForm()
                    ->on(new BeritaIndexPage)
                    ->assertItemExists($beritaData['judul']);
            
            // Edit and replace image
            $browser->clickEdit($beritaData['judul'])
                    ->on(new BeritaEditPage(1))
                    ->assertCurrentImageDisplayed()
                    ->replaceImage($newImage)
                    ->submitForm()
                    ->assertSuccess();
        });
    }

    /**
     * Test publish date functionality
     */
    public function test_publish_date_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $futureDate = now()->addDay()->format('Y-m-d');
            
            $beritaData = [
                'judul' => 'Future Publish Berita ' . time(),
                'konten' => 'Berita dengan tanggal publish masa depan.',
                'kategori' => 'berita',
                'status' => 'scheduled',
                'publish_date' => $futureDate,
            ];
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm($beritaData)
                    ->submitForm()
                    ->assertSuccess();
        });
    }

    /**
     * Test character limit untuk excerpt
     */
    public function test_character_limit_excerpt_berita()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsContentManager($browser);
            
            $longExcerpt = str_repeat('a', 300); // Assuming 250 char limit
            
            $browser->visit(new BeritaIndexPage)
                    ->clickCreate()
                    ->on(new BeritaCreatePage)
                    ->fillForm([
                        'judul' => 'Test Long Excerpt',
                        'konten' => 'Test konten.',
                        'kategori' => 'berita',
                        'excerpt' => $longExcerpt,
                    ])
                    ->submitForm()
                    ->assertValidationErrors(['excerpt']);
        });
    }
}
