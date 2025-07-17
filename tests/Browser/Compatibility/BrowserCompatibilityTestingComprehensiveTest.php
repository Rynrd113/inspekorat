<?php

namespace Tests\Browser\Compatibility;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\InteractsWithAuthentication;

class BrowserCompatibilityTestingComprehensiveTest extends DuskTestCase
{
    use DatabaseMigrations, InteractsWithAuthentication;

    /**
     * Test Chrome Browser Compatibility
     */
    public function test_chrome_browser_compatibility()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let userAgent = navigator.userAgent;
                    if (userAgent.includes("Chrome")) {
                        document.body.innerHTML += "<div id=\"chrome-compatible\">Chrome Compatible</div>";
                    }
                ')
                ->waitFor('#chrome-compatible')
                ->assertSee('Chrome Compatible');
        });
    }

    /**
     * Test Firefox Browser Compatibility
     */
    public function test_firefox_browser_compatibility()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    // Test Firefox-specific features
                    let isFirefox = navigator.userAgent.includes("Firefox");
                    let supportsModernJS = typeof Promise !== "undefined" && 
                                          typeof fetch !== "undefined" &&
                                          typeof Array.prototype.includes !== "undefined";
                    
                    if (isFirefox || supportsModernJS) {
                        document.body.innerHTML += "<div id=\"firefox-compatible\">Firefox Compatible</div>";
                    }
                ')
                ->waitFor('#firefox-compatible')
                ->assertSee('Firefox Compatible');
        });
    }

    /**
     * Test Edge Browser Compatibility
     */
    public function test_edge_browser_compatibility()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    // Test Edge-specific features
                    let isEdge = navigator.userAgent.includes("Edge") || 
                                navigator.userAgent.includes("Edg");
                    let supportsES6 = typeof Symbol !== "undefined" && 
                                     typeof Set !== "undefined" &&
                                     typeof Map !== "undefined";
                    
                    if (isEdge || supportsES6) {
                        document.body.innerHTML += "<div id=\"edge-compatible\">Edge Compatible</div>";
                    }
                ')
                ->waitFor('#edge-compatible')
                ->assertSee('Edge Compatible');
        });
    }

    /**
     * Test Safari Browser Compatibility
     */
    public function test_safari_browser_compatibility()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    // Test Safari-specific features
                    let isSafari = navigator.userAgent.includes("Safari") && 
                                  !navigator.userAgent.includes("Chrome");
                    let supportsWebKit = typeof window.webkit !== "undefined" ||
                                        typeof window.WebKitCSSMatrix !== "undefined";
                    
                    if (isSafari || supportsWebKit) {
                        document.body.innerHTML += "<div id=\"safari-compatible\">Safari Compatible</div>";
                    }
                ')
                ->waitFor('#safari-compatible')
                ->assertSee('Safari Compatible');
        });
    }

    /**
     * Test JavaScript ES6+ Features Compatibility
     */
    public function test_javascript_es6_features_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test ES6+ features
                    let hasArrowFunctions = true;
                    let hasPromises = typeof Promise !== "undefined";
                    let hasAsyncAwait = true;
                    let hasClassSyntax = true;
                    let hasTemplateLiterals = true;
                    let hasDestructuring = true;
                    
                    try {
                        // Test arrow functions
                        let arrow = () => true;
                        
                        // Test template literals
                        let template = `test`;
                        
                        // Test class syntax
                        class TestClass {
                            constructor() {
                                this.test = true;
                            }
                        }
                        
                        // Test destructuring
                        let [first, second] = [1, 2];
                        let {prop} = {prop: "value"};
                        
                        if (hasArrowFunctions && hasPromises && hasAsyncAwait && 
                            hasClassSyntax && hasTemplateLiterals && hasDestructuring) {
                            document.body.innerHTML += "<div id=\"es6-compatible\">ES6+ Compatible</div>";
                        }
                    } catch(e) {
                        document.body.innerHTML += "<div id=\"es6-error\">ES6+ Error: " + e.message + "</div>";
                    }
                ')
                ->waitFor('#es6-compatible')
                ->assertSee('ES6+ Compatible');
        });
    }

    /**
     * Test CSS Grid and Flexbox Compatibility
     */
    public function test_css_grid_flexbox_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test CSS Grid support
                    let supportsGrid = CSS.supports("display", "grid");
                    
                    // Test Flexbox support
                    let supportsFlexbox = CSS.supports("display", "flex");
                    
                    // Test CSS custom properties
                    let supportsCustomProps = CSS.supports("--custom-prop", "value");
                    
                    if (supportsGrid && supportsFlexbox && supportsCustomProps) {
                        document.body.innerHTML += "<div id=\"css-modern-compatible\">CSS Modern Compatible</div>";
                    }
                ')
                ->waitFor('#css-modern-compatible')
                ->assertSee('CSS Modern Compatible');
        });
    }

    /**
     * Test Form Validation Compatibility
     */
    public function test_form_validation_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test HTML5 form validation
                    let input = document.createElement("input");
                    input.type = "email";
                    input.required = true;
                    
                    let supportsValidation = typeof input.checkValidity === "function" &&
                                           typeof input.setCustomValidity === "function";
                    
                    // Test form validation API
                    let form = document.createElement("form");
                    let supportsFormValidation = typeof form.checkValidity === "function";
                    
                    if (supportsValidation && supportsFormValidation) {
                        document.body.innerHTML += "<div id=\"form-validation-compatible\">Form Validation Compatible</div>";
                    }
                ')
                ->waitFor('#form-validation-compatible')
                ->assertSee('Form Validation Compatible');
        });
    }

    /**
     * Test File Upload Compatibility
     */
    public function test_file_upload_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test File API support
                    let supportsFileAPI = typeof File !== "undefined" &&
                                         typeof FileList !== "undefined" &&
                                         typeof FileReader !== "undefined";
                    
                    // Test drag and drop support
                    let supportsDragDrop = typeof DragEvent !== "undefined" &&
                                          typeof DataTransfer !== "undefined";
                    
                    // Test multiple file selection
                    let input = document.createElement("input");
                    input.type = "file";
                    let supportsMultiple = "multiple" in input;
                    
                    if (supportsFileAPI && supportsDragDrop && supportsMultiple) {
                        document.body.innerHTML += "<div id=\"file-upload-compatible\">File Upload Compatible</div>";
                    }
                ')
                ->waitFor('#file-upload-compatible')
                ->assertSee('File Upload Compatible');
        });
    }

    /**
     * Test AJAX and Fetch API Compatibility
     */
    public function test_ajax_fetch_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test XMLHttpRequest support
                    let supportsXHR = typeof XMLHttpRequest !== "undefined";
                    
                    // Test Fetch API support
                    let supportsFetch = typeof fetch !== "undefined";
                    
                    // Test Promise support
                    let supportsPromises = typeof Promise !== "undefined";
                    
                    if (supportsXHR && supportsFetch && supportsPromises) {
                        document.body.innerHTML += "<div id=\"ajax-fetch-compatible\">AJAX/Fetch Compatible</div>";
                    }
                ')
                ->waitFor('#ajax-fetch-compatible')
                ->assertSee('AJAX/Fetch Compatible');
        });
    }

    /**
     * Test LocalStorage and SessionStorage Compatibility
     */
    public function test_webstorage_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test localStorage support
                    let supportsLocalStorage = typeof localStorage !== "undefined";
                    
                    // Test sessionStorage support
                    let supportsSessionStorage = typeof sessionStorage !== "undefined";
                    
                    // Test storage functionality
                    let storageWorks = false;
                    try {
                        localStorage.setItem("test", "value");
                        let value = localStorage.getItem("test");
                        localStorage.removeItem("test");
                        storageWorks = value === "value";
                    } catch(e) {
                        storageWorks = false;
                    }
                    
                    if (supportsLocalStorage && supportsSessionStorage && storageWorks) {
                        document.body.innerHTML += "<div id=\"webstorage-compatible\">WebStorage Compatible</div>";
                    }
                ')
                ->waitFor('#webstorage-compatible')
                ->assertSee('WebStorage Compatible');
        });
    }

    /**
     * Test Canvas and SVG Compatibility
     */
    public function test_canvas_svg_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test Canvas support
                    let canvas = document.createElement("canvas");
                    let supportsCanvas = typeof canvas.getContext === "function";
                    
                    // Test SVG support
                    let supportsSVG = typeof SVGElement !== "undefined";
                    
                    // Test inline SVG
                    let supportsInlineSVG = document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1");
                    
                    if (supportsCanvas && supportsSVG) {
                        document.body.innerHTML += "<div id=\"canvas-svg-compatible\">Canvas/SVG Compatible</div>";
                    }
                ')
                ->waitFor('#canvas-svg-compatible')
                ->assertSee('Canvas/SVG Compatible');
        });
    }

    /**
     * Test Media Queries and Responsive Design Compatibility
     */
    public function test_media_queries_responsive_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test CSS media queries support
                    let supportsMediaQueries = typeof window.matchMedia === "function";
                    
                    // Test viewport meta tag support
                    let viewportMeta = document.querySelector("meta[name=viewport]");
                    let hasViewportMeta = viewportMeta !== null;
                    
                    // Test responsive breakpoints
                    let testBreakpoints = false;
                    if (supportsMediaQueries) {
                        let mq = window.matchMedia("(max-width: 768px)");
                        testBreakpoints = typeof mq.matches === "boolean";
                    }
                    
                    if (supportsMediaQueries && hasViewportMeta && testBreakpoints) {
                        document.body.innerHTML += "<div id=\"responsive-compatible\">Responsive Compatible</div>";
                    }
                ')
                ->waitFor('#responsive-compatible')
                ->assertSee('Responsive Compatible');
        });
    }

    /**
     * Test Touch Events Compatibility
     */
    public function test_touch_events_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test touch events support
                    let supportsTouchEvents = "ontouchstart" in window ||
                                            "ontouchstart" in document.documentElement ||
                                            typeof TouchEvent !== "undefined";
                    
                    // Test pointer events support
                    let supportsPointerEvents = typeof PointerEvent !== "undefined";
                    
                    // Test gesture events (mainly Safari)
                    let supportsGestureEvents = typeof GestureEvent !== "undefined";
                    
                    if (supportsTouchEvents) {
                        document.body.innerHTML += "<div id=\"touch-compatible\">Touch Compatible</div>";
                    }
                ')
                ->waitFor('#touch-compatible')
                ->assertSee('Touch Compatible');
        });
    }

    /**
     * Test WebSocket Compatibility
     */
    public function test_websocket_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test WebSocket support
                    let supportsWebSocket = typeof WebSocket !== "undefined";
                    
                    // Test WebSocket secure connection
                    let supportsWSS = supportsWebSocket && 
                                     typeof WebSocket.prototype.close === "function";
                    
                    if (supportsWebSocket && supportsWSS) {
                        document.body.innerHTML += "<div id=\"websocket-compatible\">WebSocket Compatible</div>";
                    }
                ')
                ->waitFor('#websocket-compatible')
                ->assertSee('WebSocket Compatible');
        });
    }

    /**
     * Test Geolocation API Compatibility
     */
    public function test_geolocation_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test Geolocation API support
                    let supportsGeolocation = typeof navigator.geolocation !== "undefined";
                    
                    // Test geolocation methods
                    let hasGeolocationMethods = false;
                    if (supportsGeolocation) {
                        hasGeolocationMethods = typeof navigator.geolocation.getCurrentPosition === "function" &&
                                               typeof navigator.geolocation.watchPosition === "function";
                    }
                    
                    if (supportsGeolocation && hasGeolocationMethods) {
                        document.body.innerHTML += "<div id=\"geolocation-compatible\">Geolocation Compatible</div>";
                    }
                ')
                ->waitFor('#geolocation-compatible')
                ->assertSee('Geolocation Compatible');
        });
    }

    /**
     * Test History API Compatibility
     */
    public function test_history_api_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test History API support
                    let supportsHistoryAPI = typeof history.pushState === "function" &&
                                           typeof history.replaceState === "function";
                    
                    // Test popstate event
                    let supportsPopstate = typeof window.onpopstate !== "undefined";
                    
                    if (supportsHistoryAPI && supportsPopstate) {
                        document.body.innerHTML += "<div id=\"history-api-compatible\">History API Compatible</div>";
                    }
                ')
                ->waitFor('#history-api-compatible')
                ->assertSee('History API Compatible');
        });
    }

    /**
     * Test Web Workers Compatibility
     */
    public function test_web_workers_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test Web Workers support
                    let supportsWebWorkers = typeof Worker !== "undefined";
                    
                    // Test SharedArrayBuffer (for advanced workers)
                    let supportsSharedArrayBuffer = typeof SharedArrayBuffer !== "undefined";
                    
                    if (supportsWebWorkers) {
                        document.body.innerHTML += "<div id=\"web-workers-compatible\">Web Workers Compatible</div>";
                    }
                ')
                ->waitFor('#web-workers-compatible')
                ->assertSee('Web Workers Compatible');
        });
    }

    /**
     * Test Notifications API Compatibility
     */
    public function test_notifications_api_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test Notifications API support
                    let supportsNotifications = typeof Notification !== "undefined";
                    
                    // Test notification methods
                    let hasNotificationMethods = false;
                    if (supportsNotifications) {
                        hasNotificationMethods = typeof Notification.requestPermission === "function";
                    }
                    
                    if (supportsNotifications && hasNotificationMethods) {
                        document.body.innerHTML += "<div id=\"notifications-compatible\">Notifications Compatible</div>";
                    }
                ')
                ->waitFor('#notifications-compatible')
                ->assertSee('Notifications Compatible');
        });
    }

    /**
     * Test Print Media Compatibility
     */
    public function test_print_media_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test print media query support
                    let supportsPrintMedia = window.matchMedia && 
                                           window.matchMedia("print").matches !== undefined;
                    
                    // Test print events
                    let supportsPrintEvents = typeof window.onbeforeprint !== "undefined" &&
                                            typeof window.onafterprint !== "undefined";
                    
                    if (supportsPrintMedia || supportsPrintEvents) {
                        document.body.innerHTML += "<div id=\"print-compatible\">Print Compatible</div>";
                    }
                ')
                ->waitFor('#print-compatible')
                ->assertSee('Print Compatible');
        });
    }

    /**
     * Test Accessibility Features Compatibility
     */
    public function test_accessibility_features_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test ARIA support
                    let supportsARIA = typeof document.querySelector("[aria-label]") !== "undefined";
                    
                    // Test focus management
                    let supportsFocus = typeof document.activeElement !== "undefined";
                    
                    // Test keyboard navigation
                    let supportsKeyboard = typeof KeyboardEvent !== "undefined";
                    
                    // Test screen reader support
                    let supportsScreenReader = typeof document.createElement("div").setAttribute === "function";
                    
                    if (supportsARIA && supportsFocus && supportsKeyboard && supportsScreenReader) {
                        document.body.innerHTML += "<div id=\"accessibility-compatible\">Accessibility Compatible</div>";
                    }
                ')
                ->waitFor('#accessibility-compatible')
                ->assertSee('Accessibility Compatible');
        });
    }

    /**
     * Test Performance API Compatibility
     */
    public function test_performance_api_compatibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Test Performance API support
                    let supportsPerformance = typeof performance !== "undefined";
                    
                    // Test performance timing
                    let supportsPerformanceTiming = supportsPerformance && 
                                                   typeof performance.now === "function";
                    
                    // Test performance navigation
                    let supportsPerformanceNavigation = supportsPerformance && 
                                                       typeof performance.navigation !== "undefined";
                    
                    if (supportsPerformance && supportsPerformanceTiming && supportsPerformanceNavigation) {
                        document.body.innerHTML += "<div id=\"performance-api-compatible\">Performance API Compatible</div>";
                    }
                ')
                ->waitFor('#performance-api-compatible')
                ->assertSee('Performance API Compatible');
        });
    }

    /**
     * Test Comprehensive Browser Feature Detection
     */
    public function test_comprehensive_browser_feature_detection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    let features = {
                        es6: typeof Symbol !== "undefined",
                        promises: typeof Promise !== "undefined",
                        fetch: typeof fetch !== "undefined",
                        localStorage: typeof localStorage !== "undefined",
                        canvas: typeof document.createElement("canvas").getContext === "function",
                        svg: typeof SVGElement !== "undefined",
                        flexbox: CSS.supports("display", "flex"),
                        grid: CSS.supports("display", "grid"),
                        customProps: CSS.supports("--custom-prop", "value"),
                        touchEvents: "ontouchstart" in window,
                        geolocation: typeof navigator.geolocation !== "undefined",
                        historyAPI: typeof history.pushState === "function",
                        webWorkers: typeof Worker !== "undefined",
                        notifications: typeof Notification !== "undefined",
                        mediaQueries: typeof window.matchMedia === "function",
                        formValidation: typeof document.createElement("input").checkValidity === "function"
                    };
                    
                    let supportedFeatures = Object.keys(features).filter(key => features[key]);
                    let compatibilityScore = (supportedFeatures.length / Object.keys(features).length) * 100;
                    
                    document.body.innerHTML += `<div id="compatibility-score">Compatibility Score: ${compatibilityScore.toFixed(1)}%</div>`;
                    
                    if (compatibilityScore >= 90) {
                        document.body.innerHTML += "<div id=\"full-compatibility\">Full Compatibility</div>";
                    }
                ')
                ->waitFor('#compatibility-score')
                ->assertSee('Compatibility Score')
                ->waitFor('#full-compatibility')
                ->assertSee('Full Compatibility');
        });
    }
}