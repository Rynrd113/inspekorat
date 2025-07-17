<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class DashboardPage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url()
    {
        return '/admin/dashboard';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertSee('Dashboard')
                ->assertPresent('.dashboard-content');
    }

    /**
     * Get the element shortcuts for the page.
     */
    public function elements()
    {
        return [
            '@sidebar' => '.sidebar',
            '@main-content' => '.main-content',
            '@user-menu' => '.user-menu',
            '@logout-btn' => 'a[href*="logout"]',
            '@stats-cards' => '.stats-cards',
            '@navigation' => '.navigation',
            '@menu-toggle' => '.menu-toggle',
            '@breadcrumb' => '.breadcrumb',
            '@dashboard-widgets' => '.dashboard-widgets',
        ];
    }

    /**
     * Navigate to specific module
     */
    public function navigateToModule(Browser $browser, string $module)
    {
        $moduleRoutes = [
            'wbs' => '/admin/wbs',
            'berita' => '/admin/portal-papua-tengah',
            'pelayanan' => '/admin/pelayanan',
            'dokumen' => '/admin/dokumen',
            'galeri' => '/admin/galeri',
            'faq' => '/admin/faq',
            'users' => '/admin/users',
            'portal-opd' => '/admin/portal-opd',
            'profil' => '/admin/profil',
            'configurations' => '/admin/configurations',
        ];

        if (isset($moduleRoutes[$module])) {
            $browser->visit($moduleRoutes[$module]);
        }

        return $browser;
    }

    /**
     * Assert user is logged in
     */
    public function assertUserLoggedIn(Browser $browser, string $userName = null)
    {
        $browser->assertVisible('@user-menu');
        
        if ($userName) {
            $browser->assertSee($userName);
        }

        return $browser;
    }

    /**
     * Logout user
     */
    public function logout(Browser $browser)
    {
        $browser->click('@logout-btn')
                ->waitForLocation('/admin/login', 30);

        return $browser;
    }

    /**
     * Assert sidebar navigation is visible
     */
    public function assertSidebarVisible(Browser $browser)
    {
        $browser->assertVisible('@sidebar')
                ->assertVisible('@navigation');

        return $browser;
    }

    /**
     * Test sidebar menu items based on role
     */
    public function assertMenuItemsForRole(Browser $browser, string $role)
    {
        $menuItems = $this->getMenuItemsForRole($role);

        foreach ($menuItems as $menuItem) {
            $browser->assertSee($menuItem);
        }

        return $browser;
    }

    /**
     * Get menu items for specific role
     */
    private function getMenuItemsForRole(string $role): array
    {
        $menuItems = [
            'super_admin' => [
                'Dashboard', 'WBS', 'Berita', 'Pelayanan', 'Dokumen', 
                'Galeri', 'FAQ', 'Portal OPD', 'Profil', 'Users', 'Configurations'
            ],
            'admin' => [
                'Dashboard', 'WBS', 'Berita', 'Pelayanan', 'Dokumen', 
                'Galeri', 'FAQ', 'Portal OPD', 'Profil'
            ],
            'content_manager' => [
                'Dashboard', 'Berita', 'Galeri', 'FAQ'
            ],
            'service_manager' => [
                'Dashboard', 'Pelayanan', 'Dokumen'
            ],
            'opd_manager' => [
                'Dashboard', 'Portal OPD'
            ],
            'wbs_manager' => [
                'Dashboard', 'WBS'
            ],
            'admin_wbs' => [
                'Dashboard', 'WBS'
            ],
            'admin_berita' => [
                'Dashboard', 'Berita'
            ],
            'admin_pelayanan' => [
                'Dashboard', 'Pelayanan'
            ],
            'admin_dokumen' => [
                'Dashboard', 'Dokumen'
            ],
            'admin_galeri' => [
                'Dashboard', 'Galeri'
            ],
            'admin_faq' => [
                'Dashboard', 'FAQ'
            ],
            'admin_portal_opd' => [
                'Dashboard', 'Portal OPD'
            ],
        ];

        return $menuItems[$role] ?? ['Dashboard'];
    }

    /**
     * Assert stats cards are visible
     */
    public function assertStatsCardsVisible(Browser $browser)
    {
        $browser->assertVisible('@stats-cards');
        return $browser;
    }

    /**
     * Click on menu item
     */
    public function clickMenuItem(Browser $browser, string $menuItem)
    {
        $browser->clickLink($menuItem);
        return $browser;
    }

    /**
     * Test responsive navigation
     */
    public function testResponsiveNavigation(Browser $browser)
    {
        // Test mobile menu toggle
        $browser->resize(375, 667)
                ->pause(1000)
                ->click('@menu-toggle')
                ->assertVisible('@sidebar');

        // Test desktop navigation
        $browser->resize(1920, 1080)
                ->pause(1000)
                ->assertVisible('@sidebar');

        return $browser;
    }

    /**
     * Search in dashboard
     */
    public function search(Browser $browser, string $query)
    {
        $browser->type('input[name="search"]', $query)
                ->press('Search');

        return $browser;
    }

    /**
     * Assert breadcrumb is visible
     */
    public function assertBreadcrumbVisible(Browser $browser)
    {
        $browser->assertVisible('@breadcrumb');
        return $browser;
    }

    /**
     * Assert dashboard widgets are loaded
     */
    public function assertWidgetsLoaded(Browser $browser)
    {
        $browser->assertVisible('@dashboard-widgets');
        return $browser;
    }
}
