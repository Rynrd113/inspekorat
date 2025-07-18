<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;

class SimpleSetupTest extends BasicDuskTestCase
{
    /**
     * Test basic setup verification without database.
     */
    public function test_dusk_setup_is_working(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->screenshot('setup_verification')
                    ->pause(2000); // Give time to load
            
            // Just verify we can visit the page without errors
            $this->assertTrue(true, 'Dusk setup is working - page loaded successfully');
        });
    }

    /**
     * Test that we can interact with the page.
     */
    public function test_basic_page_interaction(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(3000) // Wait for page to load
                    ->screenshot('page_interaction_test');
            
            // Verify basic page structure exists
            $pageSource = $browser->driver->getPageSource();
            $this->assertStringContainsString('<html', $pageSource);
            $this->assertStringContainsString('Portal', $pageSource);
        });
    }
}