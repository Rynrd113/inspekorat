<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class BeritaIndexPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url()
    {
        return '/admin/portal-papua-tengah';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertSee('Berita')
                ->assertPresent('.data-table');
    }

    /**
     * Get the element shortcuts for the page.
     */
    public function elements()
    {
        return [
            '@create-btn' => 'a[href*="create"]',
            '@data-table' => '.data-table',
            '@search-input' => 'input[name="search"]',
            '@search-btn' => 'button[type="submit"]',
            '@filter-status' => 'select[name="status"]',
            '@edit-btn' => 'a[href*="edit"]',
            '@delete-btn' => 'button[data-action="delete"]',
            '@pagination' => '.pagination',
            '@no-data' => '.no-data',
            '@bulk-actions' => '.bulk-actions',
            '@select-all' => 'input[type="checkbox"][name="select_all"]',
        ];
    }

    /**
     * Click create button
     */
    public function clickCreate(Browser $browser)
    {
        $browser->click('@create-btn')
                ->waitForLocation('/admin/portal-papua-tengah/create', 30);

        return $browser;
    }

    /**
     * Search for items
     */
    public function search(Browser $browser, string $query)
    {
        $browser->type('@search-input', $query)
                ->press('@search-btn')
                ->waitForReload();

        return $browser;
    }

    /**
     * Filter by status
     */
    public function filterByStatus(Browser $browser, string $status)
    {
        $browser->select('@filter-status', $status)
                ->waitForReload();

        return $browser;
    }

    /**
     * Click edit button for specific item
     */
    public function clickEdit(Browser $browser, string $itemTitle)
    {
        $browser->assertSee($itemTitle)
                ->click("@edit-btn")
                ->waitForLocation('/admin/portal-papua-tengah/*/edit', 30);

        return $browser;
    }

    /**
     * Click delete button for specific item
     */
    public function clickDelete(Browser $browser, string $itemTitle)
    {
        $browser->assertSee($itemTitle)
                ->click('@delete-btn')
                ->waitFor('.modal', 10)
                ->press('Ya')
                ->waitForReload();

        return $browser;
    }

    /**
     * Assert item exists in table
     */
    public function assertItemExists(Browser $browser, string $itemTitle)
    {
        $browser->assertSee($itemTitle);
        return $browser;
    }

    /**
     * Assert item does not exist in table
     */
    public function assertItemNotExists(Browser $browser, string $itemTitle)
    {
        $browser->assertDontSee($itemTitle);
        return $browser;
    }

    /**
     * Assert table is empty
     */
    public function assertTableEmpty(Browser $browser)
    {
        $browser->assertVisible('@no-data')
                ->assertSee('Tidak ada data');

        return $browser;
    }

    /**
     * Select all items for bulk action
     */
    public function selectAllItems(Browser $browser)
    {
        $browser->check('@select-all');
        return $browser;
    }

    /**
     * Perform bulk action
     */
    public function performBulkAction(Browser $browser, string $action)
    {
        $browser->select('@bulk-actions', $action)
                ->press('Apply')
                ->waitForReload();

        return $browser;
    }

    /**
     * Go to next page
     */
    public function nextPage(Browser $browser)
    {
        $browser->click('.pagination .next')
                ->waitForReload();

        return $browser;
    }

    /**
     * Go to previous page
     */
    public function previousPage(Browser $browser)
    {
        $browser->click('.pagination .prev')
                ->waitForReload();

        return $browser;
    }

    /**
     * Assert pagination is visible
     */
    public function assertPaginationVisible(Browser $browser)
    {
        $browser->assertVisible('@pagination');
        return $browser;
    }
}
