<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component;

class DataTable extends Component
{
    /**
     * Get the root selector for the component.
     */
    public function selector()
    {
        return '.datatable';
    }

    /**
     * Assert that the browser page contains the component.
     */
    public function assert(Browser $browser)
    {
        $browser->assertPresent($this->selector())
                ->assertPresent($this->selector() . ' thead')
                ->assertPresent($this->selector() . ' tbody');
    }

    /**
     * Get the element shortcuts for the component.
     */
    public function elements()
    {
        return [
            '@search' => '.datatable-search input',
            '@entries-select' => '.datatable-entries select',
            '@pagination' => '.datatable-pagination',
            '@next-page' => '.datatable-pagination .next',
            '@prev-page' => '.datatable-pagination .prev',
            '@page-info' => '.datatable-info',
            '@select-all' => '.datatable thead .select-all',
            '@bulk-actions' => '.datatable-bulk-actions',
            '@export-button' => '.datatable-export',
            '@refresh-button' => '.datatable-refresh',
            '@column-filter' => '.datatable-column-filter',
            '@advanced-search' => '.datatable-advanced-search',
        ];
    }

    /**
     * Search dalam datatable
     */
    public function search(Browser $browser, string $term)
    {
        $browser->type('@search', $term)
                ->pause(1000) // Wait for search to execute
                ->waitFor('.datatable tbody tr', 10);

        return $browser;
    }

    /**
     * Clear search
     */
    public function clearSearch(Browser $browser)
    {
        $browser->clear('@search')
                ->pause(1000);

        return $browser;
    }

    /**
     * Set entries per page
     */
    public function setEntriesPerPage(Browser $browser, int $entries)
    {
        $browser->select('@entries-select', $entries)
                ->waitForReload()
                ->waitFor('.datatable tbody tr', 10);

        return $browser;
    }

    /**
     * Go to next page
     */
    public function nextPage(Browser $browser)
    {
        $browser->click('@next-page')
                ->waitForReload()
                ->waitFor('.datatable tbody tr', 10);

        return $browser;
    }

    /**
     * Go to previous page
     */
    public function previousPage(Browser $browser)
    {
        $browser->click('@prev-page')
                ->waitForReload()
                ->waitFor('.datatable tbody tr', 10);

        return $browser;
    }

    /**
     * Go to specific page
     */
    public function goToPage(Browser $browser, int $page)
    {
        $browser->click("@pagination .page-{$page}")
                ->waitForReload()
                ->waitFor('.datatable tbody tr', 10);

        return $browser;
    }

    /**
     * Sort by column
     */
    public function sortBy(Browser $browser, string $column)
    {
        $browser->click(".datatable thead th[data-column='{$column}']")
                ->waitForReload()
                ->waitFor('.datatable tbody tr', 10);

        return $browser;
    }

    /**
     * Select all rows
     */
    public function selectAll(Browser $browser)
    {
        $browser->check('@select-all')
                ->waitFor('.datatable tbody .row-selected', 5);

        return $browser;
    }

    /**
     * Deselect all rows
     */
    public function deselectAll(Browser $browser)
    {
        $browser->uncheck('@select-all')
                ->waitUntil('.datatable tbody .row-selected', 5);

        return $browser;
    }

    /**
     * Select specific row
     */
    public function selectRow(Browser $browser, int $rowIndex)
    {
        $browser->check(".datatable tbody tr:nth-child({$rowIndex}) .row-checkbox")
                ->waitFor(".datatable tbody tr:nth-child({$rowIndex}).row-selected", 5);

        return $browser;
    }

    /**
     * Perform bulk action
     */
    public function bulkAction(Browser $browser, string $action)
    {
        $browser->select('@bulk-actions', $action)
                ->press('Apply')
                ->waitFor('.confirmation-modal', 5);

        return $browser;
    }

    /**
     * Export data
     */
    public function export(Browser $browser, string $format = 'excel')
    {
        $browser->click('@export-button')
                ->waitFor('.export-modal', 5)
                ->select('.export-format', $format)
                ->press('Export')
                ->waitFor('.export-success', 10);

        return $browser;
    }

    /**
     * Refresh datatable
     */
    public function refresh(Browser $browser)
    {
        $browser->click('@refresh-button')
                ->waitFor('.datatable-loading', 5)
                ->waitUntil('.datatable-loading', 30);

        return $browser;
    }

    /**
     * Filter by column
     */
    public function filterBy(Browser $browser, string $column, string $value)
    {
        $browser->click('@column-filter')
                ->waitFor('.column-filter-dropdown', 5)
                ->select(".filter-{$column}", $value)
                ->press('Apply Filter')
                ->waitForReload();

        return $browser;
    }

    /**
     * Open advanced search
     */
    public function openAdvancedSearch(Browser $browser)
    {
        $browser->click('@advanced-search')
                ->waitFor('.advanced-search-modal', 5);

        return $browser;
    }

    /**
     * Perform advanced search
     */
    public function advancedSearch(Browser $browser, array $criteria)
    {
        $this->openAdvancedSearch($browser);

        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $browser->select($field, $value['value']);
            } else {
                $browser->type($field, $value);
            }
        }

        $browser->press('Search')
                ->waitForReload();

        return $browser;
    }

    /**
     * Get row count
     */
    public function getRowCount(Browser $browser): int
    {
        return count($browser->elements('.datatable tbody tr'));
    }

    /**
     * Get cell value
     */
    public function getCellValue(Browser $browser, int $row, int $column): string
    {
        return $browser->text(".datatable tbody tr:nth-child({$row}) td:nth-child({$column})");
    }

    /**
     * Click row action
     */
    public function clickRowAction(Browser $browser, int $row, string $action)
    {
        $browser->click(".datatable tbody tr:nth-child({$row}) .action-{$action}")
                ->waitFor('.action-modal, .action-page', 5);

        return $browser;
    }

    /**
     * Assert table has data
     */
    public function assertHasData(Browser $browser)
    {
        $browser->assertPresent('.datatable tbody tr')
                ->assertDontSee('No data available');

        return $browser;
    }

    /**
     * Assert table is empty
     */
    public function assertEmpty(Browser $browser)
    {
        $browser->assertSee('No data available')
                ->assertMissing('.datatable tbody tr');

        return $browser;
    }

    /**
     * Assert specific row exists
     */
    public function assertRowExists(Browser $browser, array $data)
    {
        $rowSelector = '.datatable tbody tr';
        
        foreach ($data as $column => $value) {
            $browser->assertPresent("{$rowSelector} td[data-column='{$column}']:contains('{$value}')");
        }

        return $browser;
    }

    /**
     * Assert pagination info
     */
    public function assertPaginationInfo(Browser $browser, int $showing, int $total)
    {
        $browser->assertSeeIn('@page-info', "Showing {$showing} of {$total}");

        return $browser;
    }

    /**
     * Assert search results
     */
    public function assertSearchResults(Browser $browser, string $term, int $expectedCount)
    {
        $browser->assertSeeIn('@page-info', "Found {$expectedCount} results for '{$term}'");

        return $browser;
    }

    /**
     * Wait for datatable to load
     */
    public function waitForLoad(Browser $browser)
    {
        $browser->waitUntil('.datatable-loading', 30)
                ->waitFor('.datatable tbody tr', 10);

        return $browser;
    }

    /**
     * Test responsive behavior
     */
    public function testResponsive(Browser $browser)
    {
        // Test mobile view
        $browser->resize(375, 667)
                ->assertPresent('.datatable-responsive')
                ->assertMissing('.datatable-desktop');

        // Test desktop view
        $browser->resize(1920, 1080)
                ->assertPresent('.datatable-desktop')
                ->assertMissing('.datatable-responsive');

        return $browser;
    }

    /**
     * Test infinite scroll
     */
    public function testInfiniteScroll(Browser $browser)
    {
        $initialCount = $this->getRowCount($browser);

        // Scroll to bottom
        $browser->script('window.scrollTo(0, document.body.scrollHeight);');
        
        // Wait for new items to load
        $browser->waitFor('.loading-more', 10)
                ->waitUntil('.loading-more', 30);

        $newCount = $this->getRowCount($browser);
        $browser->assertTrue($newCount > $initialCount);

        return $browser;
    }

    /**
     * Test column resize
     */
    public function testColumnResize(Browser $browser, string $column)
    {
        $browser->drag(".datatable thead th[data-column='{$column}'] .resize-handle", 100, 0)
                ->pause(1000);

        return $browser;
    }

    /**
     * Test column reorder
     */
    public function testColumnReorder(Browser $browser, string $fromColumn, string $toColumn)
    {
        $browser->dragAndDrop(
            ".datatable thead th[data-column='{$fromColumn}']",
            ".datatable thead th[data-column='{$toColumn}']"
        )->pause(1000);

        return $browser;
    }
}
