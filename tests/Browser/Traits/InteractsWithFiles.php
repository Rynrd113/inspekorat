<?php

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;

trait InteractsWithFiles
{
    /**
     * Upload file dan tunggu processing
     */
    protected function uploadFile(Browser $browser, string $inputSelector, string $filePath)
    {
        $browser->attach($inputSelector, $filePath)
                ->waitFor('.upload-progress, .file-preview', 10)
                ->pause(1000);

        return $browser;
    }

    /**
     * Create temporary test file
     */
    protected function createTestFile(string $filename, string $content = 'Test content', string $directory = null)
    {
        $directory = $directory ?? sys_get_temp_dir();
        $filepath = $directory . '/' . $filename;
        
        file_put_contents($filepath, $content);
        
        return $filepath;
    }

    /**
     * Create test image file
     */
    protected function createTestImage(string $filename = 'test.jpg', int $width = 100, int $height = 100)
    {
        $filepath = sys_get_temp_dir() . '/' . $filename;
        
        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, 255, 0, 0);
        imagefill($image, 0, 0, $color);
        
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $filepath);
                break;
            case 'png':
                imagepng($image, $filepath);
                break;
            case 'gif':
                imagegif($image, $filepath);
                break;
        }
        
        imagedestroy($image);
        
        return $filepath;
    }

    /**
     * Create test PDF file
     */
    protected function createTestPdf(string $filename = 'test.pdf')
    {
        $filepath = sys_get_temp_dir() . '/' . $filename;
        
        // Simple PDF content
        $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>\nendobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000058 00000 n\n0000000115 00000 n\ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n184\n%%EOF";
        
        file_put_contents($filepath, $pdfContent);
        
        return $filepath;
    }

    /**
     * Test file upload dengan berbagai ukuran
     */
    protected function testFileUploadSizes(Browser $browser, string $inputSelector, array $sizes)
    {
        foreach ($sizes as $size => $shouldPass) {
            $filename = "test_{$size}.jpg";
            $filepath = $this->createTestImage($filename, 100, 100);
            
            // Modify file size artificially for testing
            if ($size === 'large') {
                $content = str_repeat('x', 5 * 1024 * 1024); // 5MB
                file_put_contents($filepath, $content);
            }
            
            $browser->attach($inputSelector, $filepath);
            
            if ($shouldPass) {
                $browser->waitFor('.upload-success', 10)
                        ->assertSee('Upload successful');
            } else {
                $browser->waitFor('.upload-error', 10)
                        ->assertSee('File size too large');
            }
            
            unlink($filepath);
        }

        return $browser;
    }

    /**
     * Test file upload dengan berbagai format
     */
    protected function testFileUploadFormats(Browser $browser, string $inputSelector, array $formats)
    {
        foreach ($formats as $format => $shouldPass) {
            $filename = "test.{$format}";
            
            if (in_array($format, ['jpg', 'jpeg', 'png', 'gif'])) {
                $filepath = $this->createTestImage($filename);
            } elseif ($format === 'pdf') {
                $filepath = $this->createTestPdf($filename);
            } else {
                $filepath = $this->createTestFile($filename);
            }
            
            $browser->attach($inputSelector, $filepath);
            
            if ($shouldPass) {
                $browser->waitFor('.upload-success', 10)
                        ->assertSee('Upload successful');
            } else {
                $browser->waitFor('.upload-error', 10)
                        ->assertSee('Invalid file format');
            }
            
            unlink($filepath);
        }

        return $browser;
    }

    /**
     * Test image preview functionality
     */
    protected function testImagePreview(Browser $browser, string $inputSelector, string $previewSelector)
    {
        $filepath = $this->createTestImage();
        
        $browser->attach($inputSelector, $filepath)
                ->waitFor($previewSelector, 10)
                ->assertPresent($previewSelector)
                ->assertAttribute($previewSelector, 'src', 'data:image');
        
        unlink($filepath);
        
        return $browser;
    }

    /**
     * Test file download functionality
     */
    protected function testFileDownload(Browser $browser, string $downloadLink, string $expectedFilename)
    {
        $browser->click($downloadLink)
                ->pause(2000);
        
        // Verify download started (this might need adjustment based on browser behavior)
        $browser->assertSee('Download started')
                ->orWhere(function ($browser) use ($expectedFilename) {
                    // Alternative check - verify download in browser downloads
                    $browser->visit('chrome://downloads/')
                            ->assertSee($expectedFilename);
                });

        return $browser;
    }

    /**
     * Test bulk file upload
     */
    protected function testBulkFileUpload(Browser $browser, string $inputSelector, int $fileCount)
    {
        $files = [];
        
        for ($i = 1; $i <= $fileCount; $i++) {
            $files[] = $this->createTestImage("test_{$i}.jpg");
        }
        
        // Upload multiple files
        foreach ($files as $file) {
            $browser->attach($inputSelector, $file);
        }
        
        // Wait for all uploads to complete
        $browser->waitFor('.upload-progress', 10)
                ->waitUntil('.upload-progress', 30)
                ->assertSee("{$fileCount} files uploaded");
        
        // Cleanup
        foreach ($files as $file) {
            unlink($file);
        }

        return $browser;
    }

    /**
     * Test file replacement functionality
     */
    protected function testFileReplacement(Browser $browser, string $inputSelector, string $replaceButtonSelector)
    {
        // Upload first file
        $firstFile = $this->createTestImage('first.jpg');
        $browser->attach($inputSelector, $firstFile)
                ->waitFor('.upload-success', 10);
        
        // Click replace button
        $browser->click($replaceButtonSelector)
                ->waitFor($inputSelector, 5);
        
        // Upload replacement file
        $secondFile = $this->createTestImage('second.jpg');
        $browser->attach($inputSelector, $secondFile)
                ->waitFor('.upload-success', 10)
                ->assertSee('File replaced successfully');
        
        // Cleanup
        unlink($firstFile);
        unlink($secondFile);

        return $browser;
    }

    /**
     * Test file deletion
     */
    protected function testFileDeletion(Browser $browser, string $deleteButtonSelector)
    {
        $browser->click($deleteButtonSelector)
                ->waitFor('.confirmation-modal', 5)
                ->press('Ya')
                ->waitFor('.delete-success', 10)
                ->assertSee('File deleted successfully');

        return $browser;
    }

    /**
     * Test drag and drop file upload
     */
    protected function testDragAndDropUpload(Browser $browser, string $dropZoneSelector)
    {
        $filepath = $this->createTestImage();
        
        // Simulate drag and drop (this might need adjustment based on implementation)
        $browser->script("
            var dropZone = document.querySelector('{$dropZoneSelector}');
            var event = new DragEvent('drop', {
                dataTransfer: new DataTransfer()
            });
            dropZone.dispatchEvent(event);
        ");
        
        $browser->waitFor('.upload-success', 10)
                ->assertSee('Upload successful');
        
        unlink($filepath);

        return $browser;
    }

    /**
     * Assert file upload error dengan pesan spesifik
     */
    protected function assertFileUploadError(Browser $browser, string $expectedMessage)
    {
        $browser->waitFor('.upload-error, .alert-danger', 10)
                ->assertSee($expectedMessage);

        return $browser;
    }

    /**
     * Assert file upload success dengan pesan spesifik
     */
    protected function assertFileUploadSuccess(Browser $browser, string $expectedMessage = 'Upload successful')
    {
        $browser->waitFor('.upload-success, .alert-success', 10)
                ->assertSee($expectedMessage);

        return $browser;
    }

    /**
     * Clean up test files
     */
    protected function cleanupTestFiles(array $files)
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Test file upload dengan progress bar
     */
    protected function testFileUploadWithProgress(Browser $browser, string $inputSelector, string $progressSelector)
    {
        $filepath = $this->createTestImage();
        
        $browser->attach($inputSelector, $filepath)
                ->waitFor($progressSelector, 10)
                ->assertPresent($progressSelector)
                ->waitUntil("{$progressSelector}.complete", 30)
                ->assertSee('Upload complete');
        
        unlink($filepath);

        return $browser;
    }
}
