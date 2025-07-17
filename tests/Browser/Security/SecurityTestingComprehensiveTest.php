<?php

namespace Tests\Browser\Security;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\InteractsWithAuthentication;

class SecurityTestingComprehensiveTest extends DuskTestCase
{
    use DatabaseMigrations, InteractsWithAuthentication;

    /**
     * Test XSS Protection pada form input
     */
    public function test_xss_protection_pada_form_berita()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', '<script>alert("XSS")</script>Test Title')
                ->type('konten', '<script>alert("XSS")</script>Test Content')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->visit('/admin/berita')
                ->assertDontSee('<script>')
                ->assertDontSee('alert("XSS")')
                ->assertSee('Test Title')
                ->assertSee('Test Content');
        });
    }

    /**
     * Test XSS Protection pada textarea rich editor
     */
    public function test_xss_protection_pada_rich_editor()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Test Article')
                ->script('
                    if (window.tinymce) {
                        tinymce.activeEditor.setContent("<script>alert(\"XSS\")</script><p>Safe content</p>");
                    }
                ')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->visit('/admin/berita')
                ->assertDontSee('<script>')
                ->assertDontSee('alert("XSS")')
                ->assertSee('Safe content');
        });
    }

    /**
     * Test CSRF Protection pada form POST
     */
    public function test_csrf_protection_pada_form_post()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->assertSourceHas('csrf-token')
                ->assertSourceHas('_token')
                ->script('
                    // Hapus CSRF token
                    document.querySelector("meta[name=csrf-token]").remove();
                    document.querySelector("input[name=_token]").value = "invalid";
                ')
                ->type('judul', 'Test CSRF')
                ->press('Simpan')
                ->waitForText('419')
                ->assertSee('Page Expired');
        });
    }

    /**
     * Test CSRF Protection pada AJAX requests
     */
    public function test_csrf_protection_pada_ajax_requests()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->script('
                    // Simulate AJAX request without CSRF token
                    fetch("/admin/berita/1", {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json"
                        }
                    }).then(response => {
                        if (response.status === 419) {
                            document.body.innerHTML += "<div id=\"csrf-error\">CSRF Protected</div>";
                        }
                    });
                ')
                ->waitFor('#csrf-error')
                ->assertSee('CSRF Protected');
        });
    }

    /**
     * Test SQL Injection Protection pada search form
     */
    public function test_sql_injection_protection_pada_search()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->type('search', "'; DROP TABLE users; --")
                ->press('Cari')
                ->waitForText('Tidak ada data')
                ->assertDontSee('SQL Error')
                ->assertDontSee('syntax error');
        });
    }

    /**
     * Test SQL Injection Protection pada URL parameters
     */
    public function test_sql_injection_protection_pada_url_parameters()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita?id=1\' OR 1=1 --')
                ->assertDontSee('SQL Error')
                ->assertDontSee('syntax error')
                ->assertSee('Berita'); // Normal page content
        });
    }

    /**
     * Test File Upload Security - Malicious file prevention
     */
    public function test_file_upload_security_malicious_files()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        // Create malicious PHP file
        $maliciousFile = tempnam(sys_get_temp_dir(), 'malicious') . '.php';
        file_put_contents($maliciousFile, '<?php system($_GET["cmd"]); ?>');
        
        $this->browse(function (Browser $browser) use ($user, $maliciousFile) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Test Security')
                ->attach('gambar', $maliciousFile)
                ->press('Simpan')
                ->waitForText('File tidak diizinkan')
                ->assertSee('File tidak diizinkan')
                ->assertDontSee('Berhasil');
        });
        
        unlink($maliciousFile);
    }

    /**
     * Test File Upload Security - Oversized file protection
     */
    public function test_file_upload_security_oversized_files()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        // Create oversized file (> 2MB)
        $oversizedFile = tempnam(sys_get_temp_dir(), 'oversized') . '.jpg';
        file_put_contents($oversizedFile, str_repeat('A', 3 * 1024 * 1024)); // 3MB
        
        $this->browse(function (Browser $browser) use ($user, $oversizedFile) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Test File Size')
                ->attach('gambar', $oversizedFile)
                ->press('Simpan')
                ->waitForText('File terlalu besar')
                ->assertSee('File terlalu besar')
                ->assertDontSee('Berhasil');
        });
        
        unlink($oversizedFile);
    }

    /**
     * Test Session Security - Session fixation protection
     */
    public function test_session_security_fixation_protection()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            // Get session ID before login
            $browser->visit('/admin/login');
            $sessionBefore = $browser->script('return document.cookie;')[0];
            
            // Login
            $browser->type('email', $user->email)
                ->type('password', 'password')
                ->press('Masuk')
                ->waitForText('Dashboard');
            
            // Get session ID after login
            $sessionAfter = $browser->script('return document.cookie;')[0];
            
            // Session should be regenerated
            $this->assertNotEquals($sessionBefore, $sessionAfter);
        });
    }

    /**
     * Test Session Security - Session timeout
     */
    public function test_session_security_timeout()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->assertSee('Dashboard')
                ->script('
                    // Simulate session timeout
                    localStorage.setItem("session_timeout", Date.now() - 7200000); // 2 hours ago
                ')
                ->refresh()
                ->waitForText('Sesi Anda telah berakhir')
                ->assertSee('Sesi Anda telah berakhir');
        });
    }

    /**
     * Test Authorization Security - Role escalation protection
     */
    public function test_authorization_security_role_escalation()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/users')
                ->assertSee('403')
                ->assertSee('Forbidden')
                ->visit('/admin/users/create')
                ->assertSee('403')
                ->assertSee('Forbidden');
        });
    }

    /**
     * Test Authorization Security - Direct URL access protection
     */
    public function test_authorization_security_direct_url_access()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/users/1/edit')
                ->assertSee('403')
                ->assertSee('Forbidden')
                ->visit('/admin/configurations')
                ->assertSee('403')
                ->assertSee('Forbidden');
        });
    }

    /**
     * Test Input Validation Security - HTML injection prevention
     */
    public function test_input_validation_security_html_injection()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', '<img src="x" onerror="alert(\'XSS\')">')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->visit('/admin/berita')
                ->assertDontSee('<img src="x"')
                ->assertDontSee('onerror=');
        });
    }

    /**
     * Test Password Security - Password strength validation
     */
    public function test_password_security_strength_validation()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/users/create')
                ->type('name', 'Test User')
                ->type('email', 'test@example.com')
                ->type('password', '123') // Weak password
                ->type('password_confirmation', '123')
                ->press('Simpan')
                ->waitForText('Password terlalu lemah')
                ->assertSee('Password terlalu lemah')
                ->assertDontSee('Berhasil');
        });
    }

    /**
     * Test Data Sanitization - Output encoding
     */
    public function test_data_sanitization_output_encoding()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Test & "Quotes" <Tags>')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->visit('/admin/berita')
                ->assertSee('Test &amp; &quot;Quotes&quot; &lt;Tags&gt;')
                ->assertDontSee('Test & "Quotes" <Tags>');
        });
    }

    /**
     * Test API Security - Rate limiting
     */
    public function test_api_security_rate_limiting()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->script('
                    // Simulate rapid API calls
                    let promises = [];
                    for (let i = 0; i < 100; i++) {
                        promises.push(fetch("/admin/api/berita"));
                    }
                    Promise.all(promises).then(responses => {
                        let rateLimited = responses.some(r => r.status === 429);
                        if (rateLimited) {
                            document.body.innerHTML += "<div id=\"rate-limited\">Rate Limited</div>";
                        }
                    });
                ')
                ->waitFor('#rate-limited', 10)
                ->assertSee('Rate Limited');
        });
    }

    /**
     * Test Content Security Policy (CSP) Headers
     */
    public function test_content_security_policy_headers()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    let csp = document.querySelector("meta[http-equiv=\"Content-Security-Policy\"]");
                    if (csp) {
                        document.body.innerHTML += "<div id=\"csp-enabled\">CSP Enabled</div>";
                    }
                ')
                ->waitFor('#csp-enabled')
                ->assertSee('CSP Enabled');
        });
    }

    /**
     * Test Secure Headers - X-Frame-Options
     */
    public function test_secure_headers_x_frame_options()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    fetch("/admin/login")
                    .then(response => {
                        let xFrame = response.headers.get("X-Frame-Options");
                        if (xFrame === "DENY" || xFrame === "SAMEORIGIN") {
                            document.body.innerHTML += "<div id=\"x-frame-ok\">X-Frame-Options OK</div>";
                        }
                    });
                ')
                ->waitFor('#x-frame-ok')
                ->assertSee('X-Frame-Options OK');
        });
    }

    /**
     * Test Secure Cookies - HttpOnly and Secure flags
     */
    public function test_secure_cookies_httponly_secure()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let cookies = document.cookie.split(";");
                    let hasSecureCookie = cookies.some(cookie => 
                        cookie.includes("laravel_session") && 
                        cookie.includes("HttpOnly") && 
                        cookie.includes("Secure")
                    );
                    if (hasSecureCookie) {
                        document.body.innerHTML += "<div id=\"secure-cookie\">Secure Cookie</div>";
                    }
                ')
                ->waitFor('#secure-cookie')
                ->assertSee('Secure Cookie');
        });
    }

    /**
     * Test Clickjacking Protection
     */
    public function test_clickjacking_protection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    // Try to embed in iframe
                    let iframe = document.createElement("iframe");
                    iframe.src = "/admin/login";
                    document.body.appendChild(iframe);
                    
                    iframe.onload = function() {
                        if (iframe.contentDocument === null) {
                            document.body.innerHTML += "<div id=\"clickjack-protected\">Clickjacking Protected</div>";
                        }
                    };
                ')
                ->waitFor('#clickjack-protected')
                ->assertSee('Clickjacking Protected');
        });
    }
}