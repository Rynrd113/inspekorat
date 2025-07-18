<?php

namespace Tests\Browser\Concerns;

use Laravel\Dusk\Browser;

trait LoginHelpers
{
    /**
     * Standardized login form submission helper
     * Tries multiple button texts commonly used in login forms
     */
    protected function submitLoginForm(Browser $browser): void
    {
        try {
            $browser->press('Login');
        } catch (\Exception $e) {
            try {
                $browser->press('Masuk');
            } catch (\Exception $e) {
                try {
                    $browser->press('Sign In');
                } catch (\Exception $e) {
                    try {
                        $browser->press('Kirim');
                    } catch (\Exception $e) {
                        try {
                            $browser->press('Submit');
                        } catch (\Exception $e) {
                            // Fallback to generic submit button
                            $browser->click('button[type="submit"]');
                        }
                    }
                }
            }
        }
    }

    /**
     * Standardized form submission helper for CRUD operations
     * Tries multiple button texts commonly used in forms
     */
    protected function submitForm(Browser $browser): void
    {
        try {
            $browser->press('Simpan');
        } catch (\Exception $e) {
            try {
                $browser->press('Save');
            } catch (\Exception $e) {
                try {
                    $browser->press('Submit');
                } catch (\Exception $e) {
                    try {
                        $browser->press('Kirim');
                    } catch (\Exception $e) {
                        try {
                            $browser->press('Update');
                        } catch (\Exception $e) {
                            try {
                                $browser->press('Create');
                            } catch (\Exception $e) {
                                // Fallback to generic submit button
                                $browser->click('button[type="submit"]');
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Login as specific admin role
     */
    protected function loginAsAdmin(Browser $browser, string $email, string $password = 'password123'): void
    {
        $browser->visit('/admin/logout')->pause(1000);
        $browser->visit('/admin/login')
                ->pause(2000)
                ->type('email', $email)
                ->type('password', $password);
        
        $this->submitLoginForm($browser);
        $browser->pause(3000);
    }

    /**
     * Login as regular user
     */
    protected function loginAsUser(Browser $browser, string $email, string $password = 'password'): void
    {
        $browser->visit('/logout')->pause(1000);
        $browser->visit('/login')
                ->pause(2000)
                ->type('email', $email)
                ->type('password', $password);
        
        $this->submitLoginForm($browser);
        $browser->pause(3000);
    }

    /**
     * Logout from admin
     */
    protected function logoutAdmin(Browser $browser): void
    {
        $browser->visit('/admin/logout')->pause(1000);
    }

    /**
     * Logout from user
     */
    protected function logoutUser(Browser $browser): void
    {
        $browser->visit('/logout')->pause(1000);
    }

    /**
     * Check if login was successful by looking for common success indicators
     */
    protected function assertLoginSuccess(Browser $browser): void
    {
        $browser->waitFor('body', 10);
        
        // Check for common success indicators
        $pageSource = $browser->driver->getPageSource();
        
        // Should NOT contain login form elements
        $hasLoginElements = strpos($pageSource, 'type="email"') !== false ||
                           strpos($pageSource, 'type="password"') !== false ||
                           strpos($pageSource, 'login') !== false;
        
        // Should contain dashboard/admin elements
        $hasDashboardElements = strpos($pageSource, 'Dashboard') !== false ||
                               strpos($pageSource, 'Admin') !== false ||
                               strpos($pageSource, 'dashboard') !== false;
        
        if ($hasLoginElements && !$hasDashboardElements) {
            throw new \Exception('Login failed - still showing login form');
        }
    }

    /**
     * Fill form field with error handling
     */
    protected function fillField(Browser $browser, string $fieldName, string $value): void
    {
        try {
            $browser->type($fieldName, $value);
        } catch (\Exception $e) {
            // Try alternative selectors
            try {
                $browser->type("input[name='{$fieldName}']", $value);
            } catch (\Exception $e) {
                try {
                    $browser->type("#{$fieldName}", $value);
                } catch (\Exception $e) {
                    throw new \Exception("Could not find form field: {$fieldName}");
                }
            }
        }
    }

    /**
     * Select form option with error handling
     */
    protected function selectOption(Browser $browser, string $fieldName, string $value): void
    {
        try {
            $browser->select($fieldName, $value);
        } catch (\Exception $e) {
            // Try alternative selectors
            try {
                $browser->select("select[name='{$fieldName}']", $value);
            } catch (\Exception $e) {
                try {
                    $browser->select("#{$fieldName}", $value);
                } catch (\Exception $e) {
                    throw new \Exception("Could not find select field: {$fieldName}");
                }
            }
        }
    }

    /**
     * Check form checkbox with error handling
     */
    protected function checkField(Browser $browser, string $fieldName): void
    {
        try {
            $browser->check($fieldName);
        } catch (\Exception $e) {
            // Try alternative selectors
            try {
                $browser->check("input[name='{$fieldName}']");
            } catch (\Exception $e) {
                try {
                    $browser->check("#{$fieldName}");
                } catch (\Exception $e) {
                    throw new \Exception("Could not find checkbox field: {$fieldName}");
                }
            }
        }
    }
}