<?php

namespace Tests\Browser\Modules\Document;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithForms;
use Tests\Browser\Traits\InteractsWithFiles;
use Tests\DuskTestCase;

class DocumentManagementTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithForms, InteractsWithFiles;

    /**
     * Test admin dapat melihat daftar dokumen
     */
    public function test_admin_dapat_melihat_daftar_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->assertSee('Documents')
                    ->assertVisible('.data-table')
                    ->assertVisible('a[href*="create"]');
        });
    }

    /**
     * Test admin dapat mengunggah dokumen baru
     */
    public function test_admin_dapat_mengunggah_dokumen_baru()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('document.pdf', 'fake-pdf-content', 'application/pdf');
            
            $documentData = [
                'title' => 'Document Test ' . time(),
                'description' => 'Document untuk testing upload.',
                'category' => 'peraturan',
                'tags' => 'test, document, upload',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
        });
    }

    /**
     * Test validasi form upload dokumen
     */
    public function test_validasi_form_upload_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->press('button[type="submit"]') // Submit empty form
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('title wajib diisi')
                    ->assertSee('description wajib diisi')
                    ->assertSee('category wajib dipilih')
                    ->assertSee('file wajib diupload');
        });
    }

    /**
     * Test validasi jenis file dokumen
     */
    public function test_validasi_jenis_file_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $invalidFile = $this->createTestFile('invalid.exe', 'invalid-content', 'application/octet-stream');
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm([
                        'title' => 'Test Document',
                        'description' => 'Test document description',
                        'category' => 'peraturan',
                        'tags' => 'test',
                        'is_public' => 1,
                    ])
                    ->attach('input[name="file"]', $invalidFile)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format file tidak valid');
        });
    }

    /**
     * Test validasi ukuran file dokumen
     */
    public function test_validasi_ukuran_file_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Create a large file (simulated)
            $largeFile = $this->createTestFile('large.pdf', str_repeat('a', 1024*1024*11), 'application/pdf'); // 11MB file
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm([
                        'title' => 'Large Document',
                        'description' => 'Large document for testing',
                        'category' => 'peraturan',
                        'tags' => 'test',
                        'is_public' => 1,
                    ])
                    ->attach('input[name="file"]', $largeFile)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Ukuran file terlalu besar');
        });
    }

    /**
     * Test admin dapat mengedit metadata dokumen
     */
    public function test_admin_dapat_mengedit_metadata_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('document.pdf', 'fake-pdf-content', 'application/pdf');
            
            // Create document first
            $originalData = [
                'title' => 'Original Document ' . time(),
                'description' => 'Original document description',
                'category' => 'peraturan',
                'tags' => 'original',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($originalData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($originalData['title']);
            
            // Edit document
            $updatedData = [
                'title' => 'Updated Document ' . time(),
                'description' => 'Updated document description',
                'category' => 'laporan',
                'tags' => 'updated, test',
                'is_public' => 0,
            ];
            
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/documents/*/edit', 30)
                    ->fillForm($updatedData)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($updatedData['title']);
        });
    }

    /**
     * Test admin dapat mengganti file dokumen
     */
    public function test_admin_dapat_mengganti_file_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $originalFile = $this->createTestFile('original.pdf', 'original-content', 'application/pdf');
            $newFile = $this->createTestFile('new.pdf', 'new-content', 'application/pdf');
            
            // Create document first
            $documentData = [
                'title' => 'Replace File Test ' . time(),
                'description' => 'Document for file replacement testing',
                'category' => 'peraturan',
                'tags' => 'test',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $originalFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // Replace file
            $browser->click('a[href*="edit"]')
                    ->waitForLocation('/admin/documents/*/edit', 30)
                    ->attach('input[name="file"]', $newFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee('File berhasil diganti');
        });
    }

    /**
     * Test admin dapat menghapus dokumen
     */
    public function test_admin_dapat_menghapus_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('delete.pdf', 'delete-content', 'application/pdf');
            
            // Create document first
            $documentData = [
                'title' => 'Delete Test Document ' . time(),
                'description' => 'Document for deletion testing',
                'category' => 'peraturan',
                'tags' => 'test',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // Delete document
            $browser->click('button[data-action="delete"]')
                    ->waitFor('.modal', 10)
                    ->press('Ya')
                    ->waitForReload()
                    ->assertDontSee($documentData['title']);
        });
    }

    /**
     * Test download dokumen
     */
    public function test_download_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('download.pdf', 'download-content', 'application/pdf');
            
            // Create document first
            $documentData = [
                'title' => 'Download Test Document ' . time(),
                'description' => 'Document for download testing',
                'category' => 'peraturan',
                'tags' => 'test',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // Download document
            $browser->click('a[href*="download"]')
                    ->pause(2000); // Wait for download
        });
    }

    /**
     * Test preview dokumen
     */
    public function test_preview_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('preview.pdf', 'preview-content', 'application/pdf');
            
            // Create document first
            $documentData = [
                'title' => 'Preview Test Document ' . time(),
                'description' => 'Document for preview testing',
                'category' => 'peraturan',
                'tags' => 'test',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // Preview document
            $browser->click('a[href*="preview"]')
                    ->waitFor('.document-preview', 10)
                    ->assertVisible('.document-preview');
        });
    }

    /**
     * Test search dokumen functionality
     */
    public function test_search_dokumen_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('search.pdf', 'search-content', 'application/pdf');
            
            // Create document first
            $documentData = [
                'title' => 'Search Unique Document ' . time(),
                'description' => 'Document for search testing',
                'category' => 'peraturan',
                'tags' => 'unique, search',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->type('input[name="search"]', 'Unique')
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertSee($documentData['title']);
        });
    }

    /**
     * Test filter dokumen by category
     */
    public function test_filter_dokumen_by_category()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->select('select[name="category"]', 'peraturan')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertSee('Peraturan');
        });
    }

    /**
     * Test filter dokumen by visibility
     */
    public function test_filter_dokumen_by_visibility()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->select('select[name="visibility"]', 'public')
                    ->press('Filter')
                    ->waitForReload()
                    ->assertSee('Public');
        });
    }

    /**
     * Test pagination dokumen
     */
    public function test_pagination_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->assertVisible('.pagination');
        });
    }

    /**
     * Test sort dokumen by date
     */
    public function test_sort_dokumen_by_date()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="sort=date"]')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test sort dokumen by title
     */
    public function test_sort_dokumen_by_title()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="sort=title"]')
                    ->waitForReload()
                    ->assertVisible('.data-table');
        });
    }

    /**
     * Test bulk actions dokumen
     */
    public function test_bulk_actions_dokumen()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->check('input[name="select_all"]')
                    ->select('select[name="bulk_action"]', 'change_visibility')
                    ->press('Apply')
                    ->waitForReload();
        });
    }

    /**
     * Test document versioning
     */
    public function test_document_versioning()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $originalFile = $this->createTestFile('v1.pdf', 'version-1-content', 'application/pdf');
            $newFile = $this->createTestFile('v2.pdf', 'version-2-content', 'application/pdf');
            
            // Create document first
            $documentData = [
                'title' => 'Version Test Document ' . time(),
                'description' => 'Document for versioning testing',
                'category' => 'peraturan',
                'tags' => 'test',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $originalFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // Add new version
            $browser->click('a[href*="versions"]')
                    ->waitForLocation('/admin/documents/*/versions', 30)
                    ->click('a[href*="add-version"]')
                    ->attach('input[name="file"]', $newFile)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents/*/versions', 30)
                    ->assertSee('Version 2');
        });
    }

    /**
     * Test document access control
     */
    public function test_document_access_control()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('private.pdf', 'private-content', 'application/pdf');
            
            // Create private document
            $documentData = [
                'title' => 'Private Document ' . time(),
                'description' => 'Private document for access control testing',
                'category' => 'peraturan',
                'tags' => 'private',
                'is_public' => 0,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // Test access control
            $browser->logout()
                    ->visit('/documents')
                    ->assertDontSee($documentData['title']);
        });
    }

    /**
     * Test document analytics
     */
    public function test_document_analytics()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('analytics.pdf', 'analytics-content', 'application/pdf');
            
            // Create document first
            $documentData = [
                'title' => 'Analytics Document ' . time(),
                'description' => 'Document for analytics testing',
                'category' => 'peraturan',
                'tags' => 'analytics',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // View analytics
            $browser->click('a[href*="analytics"]')
                    ->waitForLocation('/admin/documents/*/analytics', 30)
                    ->assertSee('Downloads')
                    ->assertSee('Views');
        });
    }

    /**
     * Test document tags management
     */
    public function test_document_tags_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('tags.pdf', 'tags-content', 'application/pdf');
            
            // Create document with tags
            $documentData = [
                'title' => 'Tags Document ' . time(),
                'description' => 'Document for tags testing',
                'category' => 'peraturan',
                'tags' => 'tag1, tag2, tag3',
                'is_public' => 1,
            ];
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/documents/create', 30)
                    ->fillForm($documentData)
                    ->attach('input[name="file"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/documents', 30)
                    ->assertSee($documentData['title']);
            
            // Filter by tag
            $browser->click('a[href*="tag=tag1"]')
                    ->waitForReload()
                    ->assertSee($documentData['title']);
        });
    }

    /**
     * Test responsive design pada document module
     */
    public function test_responsive_design_document_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents');
            
            $this->testResponsiveDesign($browser, function ($browser, $device) {
                $browser->assertVisible('.data-table')
                        ->assertVisible('a[href*="create"]');
            });
        });
    }

    /**
     * Test performance pada document module
     */
    public function test_performance_document_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/documents')
                    ->waitForLoadingToFinish($browser);
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Page should load within 3 seconds
            $this->assertLessThan(3, $loadTime, 'Documents index page took too long to load: ' . $loadTime . ' seconds');
        });
    }

    /**
     * Test security protection document module
     */
    public function test_security_protection_document_module()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents/create')
                    ->assertPresent('input[name="_token"]'); // CSRF protection
        });
    }

    /**
     * Test document backup functionality
     */
    public function test_document_backup_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/documents')
                    ->click('a[href*="backup"]')
                    ->waitFor('.modal', 10)
                    ->press('Ya')
                    ->waitForReload()
                    ->assertSee('Backup berhasil dibuat');
        });
    }
}
