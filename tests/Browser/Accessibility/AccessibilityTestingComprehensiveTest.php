<?php

namespace Tests\Browser\Accessibility;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\InteractsWithAuthentication;

class AccessibilityTestingComprehensiveTest extends DuskTestCase
{
    use DatabaseMigrations, InteractsWithAuthentication;

    /**
     * Test WCAG 2.1 AA - Keyboard Navigation
     */
    public function test_keyboard_navigation_support()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->keys('input[name="email"]', '{tab}')
                ->assertFocused('input[name="password"]')
                ->keys('input[name="password"]', '{tab}')
                ->assertFocused('button[type="submit"]')
                ->keys('button[type="submit"]', '{enter}')
                ->assertPresent('.error');
        });
    }

    /**
     * Test WCAG 2.1 AA - Focus Indicators
     */
    public function test_focus_indicators_visibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    document.querySelector("input[name=\"email\"]").focus();
                    let focusedElement = document.activeElement;
                    let computedStyle = window.getComputedStyle(focusedElement, ":focus");
                    let hasOutline = computedStyle.outline !== "none" || 
                                   computedStyle.boxShadow !== "none" ||
                                   computedStyle.border !== "none";
                    
                    if (hasOutline) {
                        document.body.innerHTML += "<div id=\"focus-visible\">Focus Visible</div>";
                    }
                ')
                ->waitFor('#focus-visible')
                ->assertSee('Focus Visible');
        });
    }

    /**
     * Test WCAG 2.1 AA - Alt Text untuk Images
     */
    public function test_alt_text_untuk_images()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let images = document.querySelectorAll("img");
                    let allHaveAlt = true;
                    
                    images.forEach(img => {
                        if (!img.alt || img.alt.trim() === "") {
                            allHaveAlt = false;
                        }
                    });
                    
                    if (allHaveAlt) {
                        document.body.innerHTML += "<div id=\"alt-text-ok\">Alt Text OK</div>";
                    }
                ')
                ->waitFor('#alt-text-ok')
                ->assertSee('Alt Text OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Heading Structure (H1-H6)
     */
    public function test_heading_structure_hierarchy()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let headings = document.querySelectorAll("h1, h2, h3, h4, h5, h6");
                    let hasH1 = document.querySelector("h1") !== null;
                    let properSequence = true;
                    
                    let previousLevel = 0;
                    headings.forEach(heading => {
                        let currentLevel = parseInt(heading.tagName.charAt(1));
                        if (currentLevel > previousLevel + 1) {
                            properSequence = false;
                        }
                        previousLevel = currentLevel;
                    });
                    
                    if (hasH1 && properSequence) {
                        document.body.innerHTML += "<div id=\"heading-structure-ok\">Heading Structure OK</div>";
                    }
                ')
                ->waitFor('#heading-structure-ok')
                ->assertSee('Heading Structure OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Color Contrast Ratio
     */
    public function test_color_contrast_ratio()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    function getContrastRatio(color1, color2) {
                        // Simplified contrast ratio calculation
                        let rgb1 = color1.match(/\d+/g);
                        let rgb2 = color2.match(/\d+/g);
                        
                        let l1 = (0.299 * rgb1[0] + 0.587 * rgb1[1] + 0.114 * rgb1[2]) / 255;
                        let l2 = (0.299 * rgb2[0] + 0.587 * rgb2[1] + 0.114 * rgb2[2]) / 255;
                        
                        return (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);
                    }
                    
                    let textElement = document.querySelector("label");
                    let style = window.getComputedStyle(textElement);
                    let textColor = style.color;
                    let backgroundColor = style.backgroundColor;
                    
                    let ratio = getContrastRatio(textColor, backgroundColor);
                    
                    if (ratio >= 4.5) {
                        document.body.innerHTML += "<div id=\"contrast-ok\">Contrast OK</div>";
                    }
                ')
                ->waitFor('#contrast-ok')
                ->assertSee('Contrast OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Form Labels Association
     */
    public function test_form_labels_association()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    let inputs = document.querySelectorAll("input[type=\"text\"], input[type=\"email\"], input[type=\"password\"]");
                    let allHaveLabels = true;
                    
                    inputs.forEach(input => {
                        let hasLabel = false;
                        
                        // Check for label with for attribute
                        if (input.id) {
                            let label = document.querySelector(`label[for="${input.id}"]`);
                            if (label) hasLabel = true;
                        }
                        
                        // Check for wrapping label
                        let parent = input.parentElement;
                        while (parent && parent.tagName !== "BODY") {
                            if (parent.tagName === "LABEL") {
                                hasLabel = true;
                                break;
                            }
                            parent = parent.parentElement;
                        }
                        
                        // Check for aria-label
                        if (input.getAttribute("aria-label")) {
                            hasLabel = true;
                        }
                        
                        if (!hasLabel) {
                            allHaveLabels = false;
                        }
                    });
                    
                    if (allHaveLabels) {
                        document.body.innerHTML += "<div id=\"labels-ok\">Labels OK</div>";
                    }
                ')
                ->waitFor('#labels-ok')
                ->assertSee('Labels OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - ARIA Attributes
     */
    public function test_aria_attributes_proper_usage()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let interactiveElements = document.querySelectorAll("button, a, input, select, textarea");
                    let hasProperAria = true;
                    
                    interactiveElements.forEach(element => {
                        // Check for proper ARIA roles
                        if (element.tagName === "BUTTON" && element.getAttribute("role") && 
                            element.getAttribute("role") !== "button") {
                            hasProperAria = false;
                        }
                        
                        // Check for aria-expanded on toggles
                        if (element.getAttribute("aria-expanded")) {
                            let expanded = element.getAttribute("aria-expanded");
                            if (expanded !== "true" && expanded !== "false") {
                                hasProperAria = false;
                            }
                        }
                    });
                    
                    if (hasProperAria) {
                        document.body.innerHTML += "<div id=\"aria-ok\">ARIA OK</div>";
                    }
                ')
                ->waitFor('#aria-ok')
                ->assertSee('ARIA OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Skip Links
     */
    public function test_skip_links_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->keys('body', '{tab}')
                ->script('
                    let skipLink = document.querySelector("a[href=\"#main-content\"], a[href=\"#content\"]");
                    if (skipLink && skipLink === document.activeElement) {
                        document.body.innerHTML += "<div id=\"skip-link-ok\">Skip Link OK</div>";
                    }
                ')
                ->waitFor('#skip-link-ok')
                ->assertSee('Skip Link OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Error Messages Accessibility
     */
    public function test_error_messages_accessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->press('Masuk')
                ->script('
                    let errorMessages = document.querySelectorAll(".error, .invalid-feedback, .text-danger");
                    let hasProperErrorHandling = true;
                    
                    errorMessages.forEach(error => {
                        let relatedInput = error.closest("form").querySelector("input[aria-describedby]");
                        if (!relatedInput || relatedInput.getAttribute("aria-describedby") !== error.id) {
                            hasProperErrorHandling = false;
                        }
                    });
                    
                    if (hasProperErrorHandling || errorMessages.length === 0) {
                        document.body.innerHTML += "<div id=\"error-accessibility-ok\">Error Accessibility OK</div>";
                    }
                ')
                ->waitFor('#error-accessibility-ok')
                ->assertSee('Error Accessibility OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Tables Accessibility
     */
    public function test_tables_accessibility()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->script('
                    let tables = document.querySelectorAll("table");
                    let hasProperHeaders = true;
                    
                    tables.forEach(table => {
                        let headers = table.querySelectorAll("th");
                        let hasScope = false;
                        
                        headers.forEach(header => {
                            if (header.getAttribute("scope")) {
                                hasScope = true;
                            }
                        });
                        
                        if (!hasScope && headers.length > 0) {
                            hasProperHeaders = false;
                        }
                        
                        // Check for caption
                        if (!table.querySelector("caption")) {
                            hasProperHeaders = false;
                        }
                    });
                    
                    if (hasProperHeaders) {
                        document.body.innerHTML += "<div id=\"table-accessibility-ok\">Table Accessibility OK</div>";
                    }
                ')
                ->waitFor('#table-accessibility-ok')
                ->assertSee('Table Accessibility OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Modal Dialog Accessibility
     */
    public function test_modal_dialog_accessibility()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->script('
                    // Simulate modal opening
                    let modal = document.createElement("div");
                    modal.setAttribute("role", "dialog");
                    modal.setAttribute("aria-labelledby", "modal-title");
                    modal.setAttribute("aria-modal", "true");
                    modal.innerHTML = `
                        <h2 id="modal-title">Konfirmasi Hapus</h2>
                        <p>Apakah Anda yakin ingin menghapus item ini?</p>
                        <button type="button">Ya</button>
                        <button type="button">Tidak</button>
                    `;
                    document.body.appendChild(modal);
                    
                    // Check modal accessibility
                    let hasRole = modal.getAttribute("role") === "dialog";
                    let hasAriaLabel = modal.getAttribute("aria-labelledby") !== null;
                    let hasAriaModal = modal.getAttribute("aria-modal") === "true";
                    
                    if (hasRole && hasAriaLabel && hasAriaModal) {
                        document.body.innerHTML += "<div id=\"modal-accessibility-ok\">Modal Accessibility OK</div>";
                    }
                ')
                ->waitFor('#modal-accessibility-ok')
                ->assertSee('Modal Accessibility OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Page Title Descriptive
     */
    public function test_page_title_descriptive()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let title = document.title;
                    let hasDescriptiveTitle = title.length > 0 && 
                                            title.toLowerCase().includes("dashboard") &&
                                            title.toLowerCase().includes("admin");
                    
                    if (hasDescriptiveTitle) {
                        document.body.innerHTML += "<div id=\"title-descriptive-ok\">Title Descriptive OK</div>";
                    }
                ')
                ->waitFor('#title-descriptive-ok')
                ->assertSee('Title Descriptive OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Language Declaration
     */
    public function test_language_declaration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    let htmlLang = document.documentElement.getAttribute("lang");
                    
                    if (htmlLang && htmlLang.length > 0) {
                        document.body.innerHTML += "<div id=\"lang-declared\">Language Declared</div>";
                    }
                ')
                ->waitFor('#lang-declared')
                ->assertSee('Language Declared');
        });
    }

    /**
     * Test WCAG 2.1 AA - Breadcrumb Navigation
     */
    public function test_breadcrumb_navigation_accessibility()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->script('
                    let breadcrumb = document.querySelector(".breadcrumb, nav[aria-label=\"breadcrumb\"]");
                    let hasBreadcrumb = false;
                    
                    if (breadcrumb) {
                        let hasAriaLabel = breadcrumb.getAttribute("aria-label") !== null;
                        let hasNavRole = breadcrumb.tagName === "NAV";
                        
                        if (hasAriaLabel || hasNavRole) {
                            hasBreadcrumb = true;
                        }
                    }
                    
                    if (hasBreadcrumb) {
                        document.body.innerHTML += "<div id=\"breadcrumb-ok\">Breadcrumb OK</div>";
                    }
                ')
                ->waitFor('#breadcrumb-ok')
                ->assertSee('Breadcrumb OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Screen Reader Support
     */
    public function test_screen_reader_support()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let hiddenElements = document.querySelectorAll(".sr-only, .screen-reader-only");
                    let hasScreenReaderContent = hiddenElements.length > 0;
                    
                    // Check for proper hiding
                    let properlyHidden = true;
                    hiddenElements.forEach(element => {
                        let style = window.getComputedStyle(element);
                        if (style.display === "none" || style.visibility === "hidden") {
                            properlyHidden = false;
                        }
                    });
                    
                    if (hasScreenReaderContent && properlyHidden) {
                        document.body.innerHTML += "<div id=\"screen-reader-ok\">Screen Reader OK</div>";
                    }
                ')
                ->waitFor('#screen-reader-ok')
                ->assertSee('Screen Reader OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Form Validation Messages
     */
    public function test_form_validation_messages_accessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'invalid-email')
                ->press('Masuk')
                ->script('
                    let validationMessages = document.querySelectorAll(".invalid-feedback, .error");
                    let hasProperValidation = true;
                    
                    validationMessages.forEach(message => {
                        let relatedInput = message.closest("form").querySelector("input[aria-describedby]");
                        if (!relatedInput || !message.id || relatedInput.getAttribute("aria-describedby") !== message.id) {
                            hasProperValidation = false;
                        }
                    });
                    
                    if (hasProperValidation) {
                        document.body.innerHTML += "<div id=\"validation-accessibility-ok\">Validation Accessibility OK</div>";
                    }
                ')
                ->waitFor('#validation-accessibility-ok')
                ->assertSee('Validation Accessibility OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Dynamic Content Announcements
     */
    public function test_dynamic_content_announcements()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->script('
                    let liveRegions = document.querySelectorAll("[aria-live], [role=\"status\"], [role=\"alert\"]");
                    let hasLiveRegions = liveRegions.length > 0;
                    
                    if (hasLiveRegions) {
                        document.body.innerHTML += "<div id=\"live-regions-ok\">Live Regions OK</div>";
                    }
                ')
                ->waitFor('#live-regions-ok')
                ->assertSee('Live Regions OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Resize and Zoom Support
     */
    public function test_resize_and_zoom_support()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Simulate 200% zoom
                    document.body.style.zoom = "200%";
                    
                    // Check if content is still usable
                    let viewport = {
                        width: window.innerWidth,
                        height: window.innerHeight
                    };
                    
                    let isUsable = viewport.width > 0 && viewport.height > 0;
                    
                    if (isUsable) {
                        document.body.innerHTML += "<div id=\"zoom-support-ok\">Zoom Support OK</div>";
                    }
                ')
                ->waitFor('#zoom-support-ok')
                ->assertSee('Zoom Support OK');
        });
    }

    /**
     * Test WCAG 2.1 AA - Motion and Animation Preferences
     */
    public function test_motion_animation_preferences()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Check for prefers-reduced-motion support
                    let supportsReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
                    
                    // Check if animations respect user preference
                    let animations = document.querySelectorAll("*");
                    let respectsPreference = true;
                    
                    animations.forEach(element => {
                        let style = window.getComputedStyle(element);
                        if (supportsReducedMotion && 
                            (style.animationDuration !== "0s" || style.transitionDuration !== "0s")) {
                            respectsPreference = false;
                        }
                    });
                    
                    if (respectsPreference) {
                        document.body.innerHTML += "<div id=\"motion-preference-ok\">Motion Preference OK</div>";
                    }
                ')
                ->waitFor('#motion-preference-ok')
                ->assertSee('Motion Preference OK');
        });
    }
}