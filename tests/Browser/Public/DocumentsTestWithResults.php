<?php

namespace Tests\Browser\Public;

use App\Models\Dokumen;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Documents Test With Results
 * Test document management functionality with database result verification
 */
class DocumentsTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestDocuments();
    }

    private function createTestDocuments()
    {
        // Create test documents
        Dokumen::create([
            'judul' => 'Peraturan Inspektorat No. 1',
            'deskripsi' => 'Peraturan mengenai tata kelola inspektorat',
            'kategori' => 'peraturan',
            'file_path' => 'documents/peraturan-1.pdf',
            'file_size' => 1024000,
            'file_type' => 'application/pdf',
            'status' => 'active',
            'download_count' => 0,
            'created_at' => now()->subDays(1)
        ]);

        Dokumen::create([
            'judul' => 'Panduan Pelayanan Publik',
            'deskripsi' => 'Panduan lengkap untuk pelayanan publik',
            'kategori' => 'panduan',
            'file_path' => 'documents/panduan-pelayanan.pdf',
            'file_size' => 2048000,
            'file_type' => 'application/pdf',
            'status' => 'active',
            'download_count' => 5,
            'created_at' => now()->subDays(2)
        ]);

        Dokumen::create([
            'judul' => 'Laporan Keuangan 2024',
            'deskripsi' => 'Laporan keuangan tahunan tahun 2024',
            'kategori' => 'laporan',
            'file_path' => 'documents/laporan-keuangan-2024.pdf',
            'file_size' => 5120000,
            'file_type' => 'application/pdf',
            'status' => 'active',
            'download_count' => 10,
            'created_at' => now()->subDays(3)
        ]);

        Dokumen::create([
            'judul' => 'Formulir Pengaduan',
            'deskripsi' => 'Formulir untuk pengaduan masyarakat',
            'kategori' => 'formulir',
            'file_path' => 'documents/formulir-pengaduan.docx',
            'file_size' => 512000,
            'file_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'status' => 'active',
            'download_count' => 15,
            'created_at' => now()->subDays(4)
        ]);

        Dokumen::create([
            'judul' => 'Presentasi Sosialisasi',
            'deskripsi' => 'Materi presentasi untuk sosialisasi program',
            'kategori' => 'presentasi',
            'file_path' => 'documents/presentasi-sosialisasi.pptx',
            'file_size' => 3072000,
            'file_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'status' => 'active',
            'download_count' => 8,
            'created_at' => now()->subDays(5)
        ]);
    }

    /**
     * Test document download functionality with counter increment
     */
    public function testDocumentDownloadWithCounter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->assertSee('Peraturan Inspektorat No. 1')
                ->screenshot('documents-page-loaded');

            // Check initial download count
            $document = Dokumen::where('judul', 'Peraturan Inspektorat No. 1')->first();
            $initialDownloadCount = $document->download_count;

            // Click download button
            $browser->click('.download-btn[data-document-id="' . $document->id . '"]')
                ->waitFor('.download-started', 10)
                ->screenshot('document-download-started');

            // Verify download count increased
            $document->refresh();
            $this->assertEquals($initialDownloadCount + 1, $document->download_count);
        });
    }

    /**
     * Test document search functionality with results
     */
    public function testDocumentSearchWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.search-form', 10)
                ->type('search', 'Peraturan')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Peraturan Inspektorat No. 1')
                ->assertDontSee('Formulir Pengaduan')
                ->screenshot('document-search-results');

            // Verify search results count
            $browser->assertSee('1 dokumen ditemukan');
        });
    }

    /**
     * Test document category filter with results
     */
    public function testDocumentCategoryFilterWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.category-filter', 10)
                ->select('kategori', 'peraturan')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Peraturan Inspektorat No. 1')
                ->assertDontSee('Panduan Pelayanan Publik')
                ->screenshot('document-category-filter');

            // Verify filter results count
            $browser->assertSee('1 dokumen kategori peraturan');
        });
    }

    /**
     * Test document file type filter with results
     */
    public function testDocumentFileTypeFilterWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.file-type-filter', 10)
                ->select('file_type', 'pdf')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Peraturan Inspektorat No. 1')
                ->assertSee('Panduan Pelayanan Publik')
                ->assertSee('Laporan Keuangan 2024')
                ->assertDontSee('Formulir Pengaduan')
                ->screenshot('document-file-type-filter');

            // Verify PDF filter results count
            $browser->assertSee('3 dokumen PDF');
        });
    }

    /**
     * Test document pagination with results
     */
    public function testDocumentPaginationWithResults()
    {
        // Create additional documents for pagination testing
        for ($i = 6; $i <= 15; $i++) {
            Dokumen::create([
                'judul' => "Test Document $i",
                'deskripsi' => "Test description $i",
                'kategori' => 'test',
                'file_path' => "documents/test-$i.pdf",
                'file_size' => 1024000,
                'file_type' => 'application/pdf',
                'status' => 'active',
                'download_count' => 0
            ]);
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->assertSee('Test Document 6')
                ->assertSee('Test Document 10')
                ->screenshot('document-pagination-page-1');

            // Test pagination
            $browser->click('.pagination .next')
                ->waitFor('.document-list', 10)
                ->assertSee('Test Document 11')
                ->assertSee('Test Document 15')
                ->screenshot('document-pagination-page-2');

            // Verify pagination info
            $browser->assertSee('Halaman 2 dari 2');
        });
    }

    /**
     * Test document sorting with results
     */
    public function testDocumentSortingWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.sort-options', 10)
                ->select('sort', 'newest')
                ->press('Sort')
                ->waitFor('.sorted-results', 10)
                ->screenshot('document-sort-newest');

            // Verify newest document appears first
            $browser->assertSeeIn('.document-item:first-child', 'Peraturan Inspektorat No. 1');

            // Test sort by most downloaded
            $browser->select('sort', 'most_downloaded')
                ->press('Sort')
                ->waitFor('.sorted-results', 10)
                ->screenshot('document-sort-most-downloaded');

            // Verify most downloaded document appears first
            $browser->assertSeeIn('.document-item:first-child', 'Formulir Pengaduan');
        });
    }

    /**
     * Test document preview functionality with results
     */
    public function testDocumentPreviewWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->click('.preview-btn')
                ->waitFor('.document-preview', 10)
                ->assertSee('Peraturan Inspektorat No. 1')
                ->assertSee('Peraturan mengenai tata kelola inspektorat')
                ->screenshot('document-preview-modal');

            // Verify preview modal content
            $browser->assertPresent('.preview-content')
                ->assertPresent('.download-from-preview')
                ->assertPresent('.close-preview');
        });
    }

    /**
     * Test document statistics tracking
     */
    public function testDocumentStatisticsTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-statistics', 10)
                ->assertSee('Total Dokumen: 5')
                ->assertSee('Total Download: 38')
                ->assertSee('Dokumen Terbaru: 5')
                ->screenshot('document-statistics');

            // Test statistics after download
            $browser->click('.download-btn:first')
                ->waitFor('.download-started', 10)
                ->refresh()
                ->waitFor('.document-statistics', 10)
                ->assertSee('Total Download: 39')
                ->screenshot('document-statistics-after-download');
        });
    }

    /**
     * Test document access tracking
     */
    public function testDocumentAccessTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->click('.document-item:first')
                ->waitFor('.document-detail', 10)
                ->screenshot('document-access-tracking');

            // Verify document view was tracked
            $document = Dokumen::where('judul', 'Peraturan Inspektorat No. 1')->first();
            $this->assertGreaterThan(0, $document->view_count);
        });
    }

    /**
     * Test document bulk operations with results
     */
    public function testDocumentBulkOperationsWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->check('.document-checkbox[value="1"]')
                ->check('.document-checkbox[value="2"]')
                ->select('bulk_action', 'add_to_favorites')
                ->press('Apply')
                ->waitFor('.bulk-action-result', 10)
                ->assertSee('2 dokumen ditambahkan ke favorit')
                ->screenshot('document-bulk-operations');

            // Verify bulk action results
            $this->assertDatabaseHas('user_favorites', [
                'document_id' => 1,
                'user_id' => null // For anonymous users
            ]);
        });
    }

    /**
     * Test document file size display with results
     */
    public function testDocumentFileSizeDisplayWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->assertSee('1.00 MB') // Peraturan Inspektorat No. 1
                ->assertSee('2.00 MB') // Panduan Pelayanan Publik
                ->assertSee('5.00 MB') // Laporan Keuangan 2024
                ->assertSee('500.00 KB') // Formulir Pengaduan
                ->assertSee('3.00 MB') // Presentasi Sosialisasi
                ->screenshot('document-file-sizes');
        });
    }

    /**
     * Test document download history tracking
     */
    public function testDocumentDownloadHistoryTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->click('.download-btn:first')
                ->waitFor('.download-started', 10)
                ->screenshot('document-download-history');

            // Verify download history was recorded
            $this->assertDatabaseHas('document_downloads', [
                'document_id' => 1,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Dusk'
            ]);
        });
    }

    /**
     * Test document search with no results
     */
    public function testDocumentSearchWithNoResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.search-form', 10)
                ->type('search', 'NonExistentDocument')
                ->press('Search')
                ->waitFor('.no-results', 10)
                ->assertSee('Tidak ada dokumen yang ditemukan')
                ->assertSee('Coba kata kunci yang berbeda')
                ->screenshot('document-search-no-results');
        });
    }

    /**
     * Test document recent downloads tracking
     */
    public function testDocumentRecentDownloadsTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dokumen')
                ->waitFor('.document-list', 10)
                ->click('.download-btn:first')
                ->waitFor('.download-started', 10)
                ->visit('/dokumen')
                ->waitFor('.recent-downloads', 10)
                ->assertSee('Peraturan Inspektorat No. 1')
                ->assertSee('Baru saja didownload')
                ->screenshot('document-recent-downloads');
        });
    }
}