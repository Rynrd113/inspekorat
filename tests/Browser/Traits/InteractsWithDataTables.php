<?php

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;

trait InteractsWithDataTables
{
    /**
     * Test search functionality dalam data table
     */
    protected function testDataTableSearch(Browser $browser, string $searchInput, string $searchTerm, string $expectedResult)
    {
        $browser->type($searchInput, $searchTerm)
                ->pause(1000) // Wait for search to execute
                ->assertSee($expectedResult)
                ->assertDontSee('No data available');

        return $browser;
    }

    /**
     * Test empty search results
     */
    protected function testDataTableEmptySearch(Browser $browser, string $searchInput, string $nonExistentTerm)
    {
        $browser->type($searchInput, $nonExistentTerm)
                ->pause(1000)
                ->assertSee('No data available')
                ->assertDontSee('Showing');

        return $browser;
    }

    /**
     * Test pagination functionality
     */
    protected function testDataTablePagination(Browser $browser, string $nextButtonSelector = '.pagination .next', string $prevButtonSelector = '.pagination .prev')
    {
        // Test next page
        $browser->click($nextButtonSelector)
                ->waitForReload()
                ->assertPresent('.datatable tbody tr')
                ->assertSee('Page 2');

        // Test previous page
        $browser->click($prevButtonSelector)
                ->waitForReload()
                ->assertPresent('.datatable tbody tr')
                ->assertSee('Page 1');

        return $browser;
    }

    /**
     * Test pagination dengan page numbers
     */
    protected function testDataTablePageNumbers(Browser $browser, int $pageNumber)
    {
        $browser->click(".pagination .page-{$pageNumber}")
                ->waitForReload()
                ->assertPresent('.datatable tbody tr')
                ->assertSee("Page {$pageNumber}");

        return $browser;
    }

    /**
     * Test entries per page selection
     */
    protected function testDataTableEntriesPerPage(Browser $browser, string $entriesSelector, int $entriesCount)
    {
        $browser->select($entriesSelector, $entriesCount)
                ->waitForReload()
                ->assertPresent('.datatable tbody tr')
                ->assertSee("Showing 1 to {$entriesCount}");

        return $browser;
    }

    /**
     * Test column sorting
     */
    protected function testDataTableSorting(Browser $browser, string $columnHeader, string $sortDirection = 'asc')
    {
        $browser->click($columnHeader)
                ->waitForReload()
                ->assertPresent('.datatable tbody tr')
                ->assertPresent("{$columnHeader} .sort-{$sortDirection}");

        return $browser;
    }

    /**
     * Test filter functionality
     */
    protected function testDataTableFilter(Browser $browser, string $filterSelector, string $filterValue, string $expectedResult)
    {
        $browser->select($filterSelector, $filterValue)
                ->waitForReload()
                ->assertPresent('.datatable tbody tr')
                ->assertSee($expectedResult);

        return $browser;
    }

    /**
     * Test bulk actions
     */
    protected function testDataTableBulkActions(Browser $browser, string $actionSelector, string $actionType)
    {
        // Select multiple rows
        $browser->check('.datatable tbody tr:first-child .row-checkbox')
                ->check('.datatable tbody tr:nth-child(2) .row-checkbox')
                ->select($actionSelector, $actionType)
                ->press('Apply')
                ->waitFor('.confirmation-modal', 5)
                ->press('Ya')
                ->waitForReload()
                ->assertSee('Bulk action completed');

        return $browser;
    }

    /**
     * Test select all functionality
     */
    protected function testDataTableSelectAll(Browser $browser)
    {
        $browser->check('.datatable thead .select-all-checkbox')
                ->assertChecked('.datatable tbody .row-checkbox')
                ->uncheck('.datatable thead .select-all-checkbox')
                ->assertNotChecked('.datatable tbody .row-checkbox');

        return $browser;
    }

    /**
     * Test row actions (edit, delete, view)
     */
    protected function testDataTableRowActions(Browser $browser, string $actionSelector, string $actionType)
    {
        $browser->click(".datatable tbody tr:first-child {$actionSelector}")
                ->waitForReload();

        switch ($actionType) {
            case 'edit':
                $browser->assertSee('Edit')
                        ->assertPresent('form');
                break;
            case 'delete':
                $browser->waitFor('.confirmation-modal', 5)
                        ->assertSee('Are you sure?');
                break;
            case 'view':
                $browser->assertSee('Detail')
                        ->assertPresent('.detail-view');
                break;
        }

        return $browser;
    }

    /**
     * Test export functionality
     */
    protected function testDataTableExport(Browser $browser, string $exportSelector, string $exportType)
    {
        $browser->click($exportSelector)
                ->select('.export-format', $exportType)
                ->press('Export')
                ->waitFor('.export-success', 10)
                ->assertSee('Export completed');

        return $browser;
    }

    /**
     * Test advanced search/filter
     */
    protected function testDataTableAdvancedSearch(Browser $browser, array $searchCriteria)
    {
        $browser->click('.advanced-search-toggle')
                ->waitFor('.advanced-search-form', 5);

        foreach ($searchCriteria as $field => $value) {
            if (is_array($value)) {
                $browser->select($field, $value['value']);
            } else {
                $browser->type($field, $value);
            }
        }

        $browser->press('Search')
                ->waitForReload()
                ->assertPresent('.datatable tbody tr');

        return $browser;
    }

    /**
     * Test column visibility toggle
     */
    protected function testDataTableColumnVisibility(Browser $browser, string $columnToggleSelector)
    {
        $browser->click('.column-visibility-toggle')
                ->waitFor('.column-visibility-dropdown', 5)
                ->uncheck($columnToggleSelector)
                ->click('.column-visibility-toggle') // Close dropdown
                ->assertMissing('.datatable th.hidden-column');

        return $browser;
    }

    /**
     * Test responsive table behavior
     */
    protected function testDataTableResponsive(Browser $browser)
    {
        // Test on mobile
        $browser->resize(375, 667)
                ->assertPresent('.datatable-responsive')
                ->assertMissing('.datatable-desktop');

        // Test on desktop
        $browser->resize(1920, 1080)
                ->assertPresent('.datatable-desktop')
                ->assertMissing('.datatable-responsive');

        return $browser;
    }

    /**
     * Test infinite scroll atau lazy loading
     */
    protected function testDataTableInfiniteScroll(Browser $browser)
    {
        // Get initial row count
        $initialRows = count($browser->elements('.datatable tbody tr'));

        // Scroll to bottom
        $browser->script('window.scrollTo(0, document.body.scrollHeight);');
        
        // Wait for new content to load
        $browser->waitFor('.loading-more', 10)
                ->waitUntil('.loading-more', 30);

        // Check if more rows loaded
        $newRows = count($browser->elements('.datatable tbody tr'));
        $browser->assertTrue($newRows > $initialRows);

        return $browser;
    }

    /**
     * Assert data table has data
     */
    protected function assertDataTableHasData(Browser $browser, int $minRows = 1)
    {
        $browser->assertPresent('.datatable tbody tr')
                ->assertElementCount('.datatable tbody tr', $minRows);

        return $browser;
    }

    /**
     * Assert data table is empty
     */
    protected function assertDataTableIsEmpty(Browser $browser)
    {
        $browser->assertSee('No data available')
                ->assertMissing('.datatable tbody tr');

        return $browser;
    }

    /**
     * Get data table row count
     */
    protected function getDataTableRowCount(Browser $browser)
    {
        return count($browser->elements('.datatable tbody tr'));
    }

    /**
     * Get data table cell value
     */
    protected function getDataTableCellValue(Browser $browser, int $row, int $column)
    {
        return $browser->text(".datatable tbody tr:nth-child({$row}) td:nth-child({$column})");
    }

    /**
     * Test data table refresh functionality
     */
    protected function testDataTableRefresh(Browser $browser, string $refreshSelector = '.refresh-button')
    {
        $browser->click($refreshSelector)
                ->waitFor('.loading', 5)
                ->waitUntil('.loading', 30)
                ->assertPresent('.datatable tbody tr');

        return $browser;
    }

    /**
     * Test data table with real-time updates
     */
    protected function testDataTableRealTime(Browser $browser, callable $dataChangeCallback)
    {
        // Get initial data
        $initialRowCount = $this->getDataTableRowCount($browser);

        // Trigger data change
        $dataChangeCallback();

        // Wait for real-time update
        $browser->waitUntil(function () use ($initialRowCount) {
            return $this->getDataTableRowCount($browser) !== $initialRowCount;
        }, 30);

        return $browser;
    }
}
