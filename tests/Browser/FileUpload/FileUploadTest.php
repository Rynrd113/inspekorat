<?php

namespace Tests\Browser\FileUpload;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithFiles;
use Tests\DuskTestCase;

class FileUploadTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithFiles;

    /**
     * Test upload image dengan format yang valid
     */
    public function test_upload_image_format_valid()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $validFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            foreach ($validFormats as $format) {
                $filePath = $this->createTestFile("test.$format", 'fake-image-content', "image/$format");
                
                $browser->visit('/admin/berita')
                        ->click('a[href*="create"]')
                        ->waitForLocation('/admin/berita/create', 30)
                        ->fillForm([
                            'judul' => "Test Upload $format",
                            'konten' => 'Test konten berita',
                            'status' => 'draft',
                            'kategori' => 'umum',
                        ])
                        ->attach('input[name="gambar"]', $filePath)
                        ->press('button[type="submit"]')
                        ->waitForLocation('/admin/berita', 30)
                        ->assertSee("Test Upload $format");
            }
        });
    }

    /**
     * Test upload image dengan format yang tidak valid
     */
    public function test_upload_image_format_invalid()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $invalidFormats = ['bmp', 'tiff', 'svg', 'ico', 'psd'];
            
            foreach ($invalidFormats as $format) {
                $filePath = $this->createTestFile("test.$format", 'fake-content', "image/$format");
                
                $browser->visit('/admin/berita')
                        ->click('a[href*="create"]')
                        ->waitForLocation('/admin/berita/create', 30)
                        ->fillForm([
                            'judul' => "Test Upload $format",
                            'konten' => 'Test konten berita',
                            'status' => 'draft',
                            'kategori' => 'umum',
                        ])
                        ->attach('input[name="gambar"]', $filePath)
                        ->press('button[type="submit"]')
                        ->waitFor('.alert-danger', 10)
                        ->assertSee('Format gambar tidak didukung');
            }
        });
    }

    /**
     * Test upload dokumen dengan format yang valid
     */
    public function test_upload_document_format_valid()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $validFormats = [
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt' => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ];
            
            foreach ($validFormats as $extension => $mimeType) {
                $filePath = $this->createTestFile("document.$extension", 'fake-document-content', $mimeType);
                
                $browser->visit('/admin/documents')
                        ->click('a[href*="create"]')
                        ->waitForLocation('/admin/documents/create', 30)
                        ->fillForm([
                            'title' => "Test Document $extension",
                            'description' => 'Test document description',
                            'category' => 'peraturan',
                            'tags' => 'test',
                            'is_public' => 1,
                        ])
                        ->attach('input[name="file"]', $filePath)
                        ->press('button[type="submit"]')
                        ->waitForLocation('/admin/documents', 30)
                        ->assertSee("Test Document $extension");
            }
        });
    }

    /**
     * Test upload file dengan ukuran berbeda
     */
    public function test_upload_file_different_sizes()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $fileSizes = [
                'small' => 1024, // 1KB
                'medium' => 1024 * 100, // 100KB
                'large' => 1024 * 1024, // 1MB
            ];
            
            foreach ($fileSizes as $sizeName => $sizeBytes) {
                $content = str_repeat('a', $sizeBytes);
                $filePath = $this->createTestFile("$sizeName.jpg", $content, 'image/jpeg');
                
                $browser->visit('/admin/berita')
                        ->click('a[href*="create"]')
                        ->waitForLocation('/admin/berita/create', 30)
                        ->fillForm([
                            'judul' => "Test Upload $sizeName",
                            'konten' => 'Test konten berita',
                            'status' => 'draft',
                            'kategori' => 'umum',
                        ])
                        ->attach('input[name="gambar"]', $filePath)
                        ->press('button[type="submit"]')
                        ->waitForLocation('/admin/berita', 30)
                        ->assertSee("Test Upload $sizeName");
            }
        });
    }

    /**
     * Test upload file dengan ukuran melebihi limit
     */
    public function test_upload_file_exceed_size_limit()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Create file larger than 5MB
            $oversizedContent = str_repeat('a', 1024 * 1024 * 6); // 6MB
            $filePath = $this->createTestFile('oversized.jpg', $oversizedContent, 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Upload Oversized',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Ukuran file terlalu besar');
        });
    }

    /**
     * Test upload multiple files
     */
    public function test_upload_multiple_files()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $file1 = $this->createTestFile('image1.jpg', 'content1', 'image/jpeg');
            $file2 = $this->createTestFile('image2.jpg', 'content2', 'image/jpeg');
            $file3 = $this->createTestFile('image3.jpg', 'content3', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Upload Multiple',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar_gallery[]"]', [$file1, $file2, $file3])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Upload Multiple');
        });
    }

    /**
     * Test upload file dengan nama yang mengandung karakter khusus
     */
    public function test_upload_file_special_characters()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $specialNames = [
                'test file with spaces.jpg',
                'test-file-with-dashes.jpg',
                'test_file_with_underscores.jpg',
                'test.file.with.dots.jpg',
                'test[file]with[brackets].jpg',
                'test(file)with(parentheses).jpg',
            ];
            
            foreach ($specialNames as $filename) {
                $filePath = $this->createTestFile($filename, 'content', 'image/jpeg');
                
                $browser->visit('/admin/berita')
                        ->click('a[href*="create"]')
                        ->waitForLocation('/admin/berita/create', 30)
                        ->fillForm([
                            'judul' => "Test Upload Special: $filename",
                            'konten' => 'Test konten berita',
                            'status' => 'draft',
                            'kategori' => 'umum',
                        ])
                        ->attach('input[name="gambar"]', $filePath)
                        ->press('button[type="submit"]')
                        ->waitForLocation('/admin/berita', 30)
                        ->assertSee("Test Upload Special: $filename");
            }
        });
    }

    /**
     * Test upload file dengan virus scan simulation
     */
    public function test_upload_file_virus_scan()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Create file with suspicious content
            $suspiciousContent = 'X5O!P%@AP[4\PZX54(P^)7CC)7}$EICAR-STANDARD-ANTIVIRUS-TEST-FILE!$H+H*';
            $filePath = $this->createTestFile('virus_test.jpg', $suspiciousContent, 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Upload Virus',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('File terdeteksi mengandung virus');
        });
    }

    /**
     * Test upload progress indicator
     */
    public function test_upload_progress_indicator()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $largeFile = $this->createTestFile('large.jpg', str_repeat('a', 1024 * 1024), 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Upload Progress',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $largeFile)
                    ->press('button[type="submit"]')
                    ->waitFor('.upload-progress', 5)
                    ->assertVisible('.upload-progress')
                    ->waitUntilMissing('.upload-progress', 30)
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Upload Progress');
        });
    }

    /**
     * Test upload file dengan drag and drop
     */
    public function test_upload_file_drag_drop()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('dragdrop.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Drag Drop',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->script('
                        const dropZone = document.querySelector(".drop-zone");
                        const fileInput = document.querySelector("input[name=\"gambar\"]");
                        if (dropZone && fileInput) {
                            dropZone.classList.add("drag-over");
                        }
                    ')
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Drag Drop');
        });
    }

    /**
     * Test upload file dengan image preview
     */
    public function test_upload_file_image_preview()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('preview.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Image Preview',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->waitFor('.image-preview', 5)
                    ->assertVisible('.image-preview')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Image Preview');
        });
    }

    /**
     * Test upload file dengan image resize
     */
    public function test_upload_file_image_resize()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('resize.jpg', 'large-image-content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Image Resize',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Image Resize');
        });
    }

    /**
     * Test upload file dengan watermark
     */
    public function test_upload_file_watermark()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('watermark.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Watermark',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->check('input[name="add_watermark"]')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Watermark');
        });
    }

    /**
     * Test upload file dengan EXIF data removal
     */
    public function test_upload_file_exif_removal()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('exif.jpg', 'content-with-exif', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test EXIF Removal',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test EXIF Removal');
        });
    }

    /**
     * Test upload file dengan image optimization
     */
    public function test_upload_file_image_optimization()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('optimize.jpg', 'unoptimized-content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Image Optimization',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Image Optimization');
        });
    }

    /**
     * Test upload file dengan backup storage
     */
    public function test_upload_file_backup_storage()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('backup.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Backup Storage',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Backup Storage');
        });
    }

    /**
     * Test upload file dengan CDN integration
     */
    public function test_upload_file_cdn_integration()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('cdn.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test CDN Integration',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test CDN Integration');
        });
    }

    /**
     * Test upload file dengan temporary storage cleanup
     */
    public function test_upload_file_temp_cleanup()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('temp.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Temp Cleanup',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Temp Cleanup');
        });
    }

    /**
     * Test upload file dengan metadata extraction
     */
    public function test_upload_file_metadata_extraction()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('metadata.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Metadata Extraction',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Metadata Extraction');
        });
    }

    /**
     * Test upload file dengan duplicate detection
     */
    public function test_upload_file_duplicate_detection()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('duplicate.jpg', 'same-content', 'image/jpeg');
            
            // Upload first file
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Duplicate 1',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Duplicate 1');
            
            // Upload same file again
            $browser->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Duplicate 2',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-warning', 10)
                    ->assertSee('File yang sama sudah pernah diupload');
        });
    }

    /**
     * Test upload file dengan automatic alt text generation
     */
    public function test_upload_file_alt_text_generation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $filePath = $this->createTestFile('alttext.jpg', 'content', 'image/jpeg');
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Alt Text Generation',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $filePath)
                    ->waitFor('input[name="alt_text"]', 5)
                    ->assertInputValue('input[name="alt_text"]', 'alttext.jpg')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Test Alt Text Generation');
        });
    }
}
