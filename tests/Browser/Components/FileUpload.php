<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component;

class FileUpload extends Component
{
    /**
     * Get the root selector for the component.
     */
    public function selector()
    {
        return '.file-upload-component';
    }

    /**
     * Assert that the browser page contains the component.
     */
    public function assert(Browser $browser)
    {
        $browser->assertPresent($this->selector())
                ->assertPresent($this->selector() . ' .upload-area');
    }

    /**
     * Get the element shortcuts for the component.
     */
    public function elements()
    {
        return [
            '@upload-area' => '.upload-area',
            '@file-input' => '.file-input',
            '@browse-button' => '.browse-button',
            '@upload-button' => '.upload-button',
            '@progress-bar' => '.progress-bar',
            '@progress-text' => '.progress-text',
            '@file-preview' => '.file-preview',
            '@file-list' => '.file-list',
            '@remove-button' => '.remove-file',
            '@replace-button' => '.replace-file',
            '@upload-status' => '.upload-status',
            '@error-message' => '.error-message',
            '@success-message' => '.success-message',
        ];
    }

    /**
     * Upload file menggunakan file input
     */
    public function uploadFile(Browser $browser, string $filePath)
    {
        $browser->attach('@file-input', $filePath)
                ->waitFor('@file-preview', 10)
                ->pause(1000);

        return $browser;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultipleFiles(Browser $browser, array $filePaths)
    {
        foreach ($filePaths as $filePath) {
            $browser->attach('@file-input', $filePath)
                    ->pause(500);
        }

        $browser->waitFor('@file-list', 10);

        return $browser;
    }

    /**
     * Click browse button
     */
    public function clickBrowse(Browser $browser)
    {
        $browser->click('@browse-button');

        return $browser;
    }

    /**
     * Click upload button
     */
    public function clickUpload(Browser $browser)
    {
        $browser->click('@upload-button')
                ->waitFor('@progress-bar', 10);

        return $browser;
    }

    /**
     * Wait for upload completion
     */
    public function waitForUploadComplete(Browser $browser)
    {
        $browser->waitFor('@progress-bar', 10)
                ->waitUntil('@progress-bar', 60)
                ->waitFor('@success-message', 10);

        return $browser;
    }

    /**
     * Remove uploaded file
     */
    public function removeFile(Browser $browser, int $fileIndex = 0)
    {
        $browser->click("@file-list .file-item:nth-child(" . ($fileIndex + 1) . ") @remove-button")
                ->waitFor('.confirmation-modal', 5)
                ->press('Yes')
                ->waitUntil(".file-list .file-item:nth-child(" . ($fileIndex + 1) . ")", 5);

        return $browser;
    }

    /**
     * Replace uploaded file
     */
    public function replaceFile(Browser $browser, string $newFilePath, int $fileIndex = 0)
    {
        $browser->click("@file-list .file-item:nth-child(" . ($fileIndex + 1) . ") @replace-button")
                ->waitFor('@file-input', 5)
                ->attach('@file-input', $newFilePath)
                ->waitFor('@success-message', 10);

        return $browser;
    }

    /**
     * Test drag and drop functionality
     */
    public function testDragAndDrop(Browser $browser, string $filePath)
    {
        // Simulate drag and drop
        $browser->script("
            var uploadArea = document.querySelector('{$this->selector()} @upload-area');
            var event = new DragEvent('drop', {
                dataTransfer: new DataTransfer()
            });
            uploadArea.dispatchEvent(event);
        ");

        $browser->waitFor('@file-preview', 10);

        return $browser;
    }

    /**
     * Test file size validation
     */
    public function testFileSizeValidation(Browser $browser, string $largeFilePath)
    {
        $browser->attach('@file-input', $largeFilePath)
                ->waitFor('@error-message', 10)
                ->assertSee('File size too large');

        return $browser;
    }

    /**
     * Test file type validation
     */
    public function testFileTypeValidation(Browser $browser, string $invalidFilePath)
    {
        $browser->attach('@file-input', $invalidFilePath)
                ->waitFor('@error-message', 10)
                ->assertSee('Invalid file type');

        return $browser;
    }

    /**
     * Test image preview
     */
    public function testImagePreview(Browser $browser, string $imageFilePath)
    {
        $browser->attach('@file-input', $imageFilePath)
                ->waitFor('@file-preview', 10)
                ->assertPresent('@file-preview img')
                ->assertAttribute('@file-preview img', 'src', 'data:image');

        return $browser;
    }

    /**
     * Test PDF preview
     */
    public function testPdfPreview(Browser $browser, string $pdfFilePath)
    {
        $browser->attach('@file-input', $pdfFilePath)
                ->waitFor('@file-preview', 10)
                ->assertPresent('@file-preview .pdf-preview')
                ->assertSee('PDF Document');

        return $browser;
    }

    /**
     * Test upload progress
     */
    public function testUploadProgress(Browser $browser, string $filePath)
    {
        $browser->attach('@file-input', $filePath)
                ->click('@upload-button')
                ->waitFor('@progress-bar', 10)
                ->assertPresent('@progress-text')
                ->assertSee('Uploading...');

        return $browser;
    }

    /**
     * Test upload cancellation
     */
    public function testUploadCancellation(Browser $browser, string $filePath)
    {
        $browser->attach('@file-input', $filePath)
                ->click('@upload-button')
                ->waitFor('@progress-bar', 10)
                ->click('.cancel-upload')
                ->waitFor('@error-message', 10)
                ->assertSee('Upload cancelled');

        return $browser;
    }

    /**
     * Test bulk upload
     */
    public function testBulkUpload(Browser $browser, array $filePaths)
    {
        $this->uploadMultipleFiles($browser, $filePaths);

        $browser->click('@upload-button')
                ->waitFor('@progress-bar', 10)
                ->waitUntil('@progress-bar', 60)
                ->waitFor('@success-message', 10)
                ->assertSee(count($filePaths) . ' files uploaded');

        return $browser;
    }

    /**
     * Test upload retry
     */
    public function testUploadRetry(Browser $browser, string $filePath)
    {
        $browser->attach('@file-input', $filePath)
                ->click('@upload-button')
                ->waitFor('@error-message', 10)
                ->assertSee('Upload failed')
                ->click('.retry-upload')
                ->waitFor('@progress-bar', 10)
                ->waitUntil('@progress-bar', 60)
                ->waitFor('@success-message', 10);

        return $browser;
    }

    /**
     * Assert file uploaded successfully
     */
    public function assertFileUploaded(Browser $browser, string $filename)
    {
        $browser->assertPresent('@success-message')
                ->assertSee('Upload successful')
                ->assertSeeIn('@file-list', $filename);

        return $browser;
    }

    /**
     * Assert upload failed
     */
    public function assertUploadFailed(Browser $browser, string $expectedError)
    {
        $browser->assertPresent('@error-message')
                ->assertSee($expectedError);

        return $browser;
    }

    /**
     * Assert file preview visible
     */
    public function assertFilePreviewVisible(Browser $browser, string $filename)
    {
        $browser->assertPresent('@file-preview')
                ->assertSeeIn('@file-preview', $filename);

        return $browser;
    }

    /**
     * Assert multiple files uploaded
     */
    public function assertMultipleFilesUploaded(Browser $browser, array $filenames)
    {
        $browser->assertPresent('@file-list');

        foreach ($filenames as $filename) {
            $browser->assertSeeIn('@file-list', $filename);
        }

        return $browser;
    }

    /**
     * Clear all uploaded files
     */
    public function clearAllFiles(Browser $browser)
    {
        $browser->click('.clear-all-files')
                ->waitFor('.confirmation-modal', 5)
                ->press('Yes')
                ->waitUntil('@file-list', 5);

        return $browser;
    }

    /**
     * Test file validation messages
     */
    public function testFileValidationMessages(Browser $browser, array $testCases)
    {
        foreach ($testCases as $case) {
            $browser->attach('@file-input', $case['file'])
                    ->waitFor('@error-message', 10)
                    ->assertSee($case['expectedError']);
        }

        return $browser;
    }

    /**
     * Test responsive upload component
     */
    public function testResponsiveUpload(Browser $browser)
    {
        // Test mobile view
        $browser->resize(375, 667)
                ->assertPresent('.upload-mobile')
                ->assertMissing('.upload-desktop');

        // Test desktop view
        $browser->resize(1920, 1080)
                ->assertPresent('.upload-desktop')
                ->assertMissing('.upload-mobile');

        return $browser;
    }

    /**
     * Test accessibility features
     */
    public function testAccessibility(Browser $browser)
    {
        $browser->assertAttribute('@file-input', 'aria-label', 'File upload')
                ->assertAttribute('@upload-button', 'aria-describedby', 'upload-help')
                ->assertPresent('[role="status"]'); // For screen readers

        return $browser;
    }

    /**
     * Get uploaded file count
     */
    public function getUploadedFileCount(Browser $browser): int
    {
        return count($browser->elements('@file-list .file-item'));
    }

    /**
     * Get upload progress percentage
     */
    public function getUploadProgress(Browser $browser): int
    {
        $progressText = $browser->text('@progress-text');
        preg_match('/(\d+)%/', $progressText, $matches);
        
        return isset($matches[1]) ? (int) $matches[1] : 0;
    }

    /**
     * Test keyboard navigation
     */
    public function testKeyboardNavigation(Browser $browser)
    {
        $browser->keys('@browse-button', '{tab}')
                ->assertFocused('@upload-button')
                ->keys('@upload-button', '{enter}');

        return $browser;
    }
}
