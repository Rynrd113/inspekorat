<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class BeritaEditPage extends Page
{
    protected $beritaId;

    public function __construct($beritaId)
    {
        $this->beritaId = $beritaId;
    }

    /**
     * Get the URL for the page.
     */
    public function url()
    {
        return "/admin/portal-papua-tengah/{$this->beritaId}/edit";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertSee('Edit Berita')
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
            '@current-image' => '.current-image',
            '@tags' => 'input[name="tags"]',
            '@excerpt' => 'textarea[name="excerpt"]',
            '@meta-description' => 'textarea[name="meta_description"]',
            '@publish-date' => 'input[name="publish_date"]',
            '@submit-btn' => 'button[type="submit"]',
            '@cancel-btn' => 'a[href*="admin/portal-papua-tengah"]',
            '@delete-btn' => 'button[data-action="delete"]',
            '@error-message' => '.alert-danger',
            '@success-message' => '.alert-success',
        ];
    }

    /**
     * Update form with data
     */
    public function updateForm(Browser $browser, array $data)
    {
        if (isset($data['judul'])) {
            $browser->clear('@judul')->type('@judul', $data['judul']);
        }

        if (isset($data['konten'])) {
            $browser->clear('@konten')->type('@konten', $data['konten']);
        }

        if (isset($data['kategori'])) {
            $browser->select('@kategori', $data['kategori']);
        }

        if (isset($data['status'])) {
            $browser->select('@status', $data['status']);
        }

        if (isset($data['tags'])) {
            $browser->clear('@tags')->type('@tags', $data['tags']);
        }

        if (isset($data['excerpt'])) {
            $browser->clear('@excerpt')->type('@excerpt', $data['excerpt']);
        }

        if (isset($data['meta_description'])) {
            $browser->clear('@meta-description')->type('@meta-description', $data['meta_description']);
        }

        if (isset($data['publish_date'])) {
            $browser->clear('@publish-date')->type('@publish-date', $data['publish_date']);
        }

        return $browser;
    }

    /**
     * Replace image
     */
    public function replaceImage(Browser $browser, string $imagePath)
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
     * Delete berita
     */
    public function deleteBerita(Browser $browser)
    {
        $browser->press('@delete-btn')
                ->waitFor('.modal', 10)
                ->press('Ya')
                ->waitForLocation('/admin/portal-papua-tengah', 30);

        return $browser;
    }

    /**
     * Cancel edit
     */
    public function cancel(Browser $browser)
    {
        $browser->click('@cancel-btn')
                ->waitForLocation('/admin/portal-papua-tengah', 30);

        return $browser;
    }

    /**
     * Assert form is populated with existing data
     */
    public function assertFormPopulated(Browser $browser, array $expectedData)
    {
        if (isset($expectedData['judul'])) {
            $browser->assertInputValue('@judul', $expectedData['judul']);
        }

        if (isset($expectedData['konten'])) {
            $browser->assertInputValue('@konten', $expectedData['konten']);
        }

        if (isset($expectedData['kategori'])) {
            $browser->assertSelected('@kategori', $expectedData['kategori']);
        }

        if (isset($expectedData['status'])) {
            $browser->assertSelected('@status', $expectedData['status']);
        }

        return $browser;
    }

    /**
     * Assert current image is displayed
     */
    public function assertCurrentImageDisplayed(Browser $browser)
    {
        $browser->assertVisible('@current-image');
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
}
