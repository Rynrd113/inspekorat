<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class BeritaCreatePage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url()
    {
        return '/admin/portal-papua-tengah/create';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertSee('Tambah Berita')
                ->assertPresent('form');
    }

    /**
     * Get the element shortcuts for the page.
     */
    public function elements()
    {
        return [
            '@judul' => 'input[name="judul"]',
            '@konten' => 'textarea[name="konten"]',
            '@kategori' => 'select[name="kategori"]',
            '@status' => 'select[name="status"]',
            '@gambar' => 'input[name="gambar"]',
            '@tags' => 'input[name="tags"]',
            '@excerpt' => 'textarea[name="excerpt"]',
            '@meta-description' => 'textarea[name="meta_description"]',
            '@publish-date' => 'input[name="publish_date"]',
            '@submit-btn' => 'button[type="submit"]',
            '@cancel-btn' => 'a[href*="admin/portal-papua-tengah"]',
            '@preview-btn' => 'button[data-action="preview"]',
            '@save-draft-btn' => 'button[data-action="save-draft"]',
            '@error-message' => '.alert-danger',
            '@success-message' => '.alert-success',
        ];
    }

    /**
     * Fill form with data
     */
    public function fillForm(Browser $browser, array $data)
    {
        if (isset($data['judul'])) {
            $browser->type('@judul', $data['judul']);
        }

        if (isset($data['konten'])) {
            $browser->type('@konten', $data['konten']);
        }

        if (isset($data['kategori'])) {
            $browser->select('@kategori', $data['kategori']);
        }

        if (isset($data['status'])) {
            $browser->select('@status', $data['status']);
        }

        if (isset($data['tags'])) {
            $browser->type('@tags', $data['tags']);
        }

        if (isset($data['excerpt'])) {
            $browser->type('@excerpt', $data['excerpt']);
        }

        if (isset($data['meta_description'])) {
            $browser->type('@meta-description', $data['meta_description']);
        }

        if (isset($data['publish_date'])) {
            $browser->type('@publish-date', $data['publish_date']);
        }

        return $browser;
    }

    /**
     * Upload image
     */
    public function uploadImage(Browser $browser, string $imagePath)
    {
        $browser->attach('@gambar', $imagePath);
        return $browser;
    }

    /**
     * Submit form
     */
    public function submitForm(Browser $browser)
    {
        $browser->press('@submit-btn')
                ->waitForLocation('/admin/portal-papua-tengah', 30);

        return $browser;
    }

    /**
     * Save as draft
     */
    public function saveDraft(Browser $browser)
    {
        $browser->press('@save-draft-btn')
                ->waitForLocation('/admin/portal-papua-tengah', 30);

        return $browser;
    }

    /**
     * Preview content
     */
    public function preview(Browser $browser)
    {
        $browser->press('@preview-btn')
                ->waitFor('.preview-modal', 10);

        return $browser;
    }

    /**
     * Cancel form
     */
    public function cancel(Browser $browser)
    {
        $browser->click('@cancel-btn')
                ->waitForLocation('/admin/portal-papua-tengah', 30);

        return $browser;
    }

    /**
     * Assert success message
     */
    public function assertSuccess(Browser $browser, string $message = null)
    {
        $browser->waitFor('@success-message', 10);
        
        if ($message) {
            $browser->assertSee($message);
        }

        return $browser;
    }

    /**
     * Assert validation errors
     */
    public function assertValidationErrors(Browser $browser, array $fields)
    {
        foreach ($fields as $field) {
            $browser->assertSee("The {$field} field is required");
        }

        return $browser;
    }

    /**
     * Test form validation
     */
    public function testFormValidation(Browser $browser)
    {
        // Test required fields
        $browser->press('@submit-btn')
                ->waitFor('@error-message', 10)
                ->assertSee('Judul wajib diisi')
                ->assertSee('Konten wajib diisi');

        return $browser;
    }

    /**
     * Test image upload validation
     */
    public function testImageUploadValidation(Browser $browser, string $invalidFilePath)
    {
        $browser->attach('@gambar', $invalidFilePath)
                ->press('@submit-btn')
                ->waitFor('@error-message', 10)
                ->assertSee('Format gambar tidak valid');

        return $browser;
    }

    /**
     * Fill rich text editor content
     */
    public function fillRichTextEditor(Browser $browser, string $content)
    {
        $browser->script("
            if (typeof tinymce !== 'undefined') {
                tinymce.get('konten').setContent('{$content}');
            } else {
                document.querySelector('textarea[name=\"konten\"]').value = '{$content}';
            }
        ");

        return $browser;
    }

    /**
     * Assert form fields are visible
     */
    public function assertFormFieldsVisible(Browser $browser)
    {
        $browser->assertVisible('@judul')
                ->assertVisible('@konten')
                ->assertVisible('@kategori')
                ->assertVisible('@status')
                ->assertVisible('@gambar')
                ->assertVisible('@submit-btn');

        return $browser;
    }
}
