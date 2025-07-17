<?php

namespace Tests\Browser\Validation;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Tests\Browser\Traits\InteractsWithForms;
use Tests\Browser\Traits\InteractsWithFiles;
use Tests\DuskTestCase;

class FormValidationTest extends DuskTestCase
{
    use InteractsWithAuthentication, InteractsWithForms, InteractsWithFiles;

    /**
     * Test required field validation
     */
    public function test_required_field_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test pada form login
            $browser->logout()
                    ->visit('/login')
                    ->press('Login')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Email wajib diisi')
                    ->assertSee('Password wajib diisi');
            
            // Test pada form create berita
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('judul wajib diisi')
                    ->assertSee('konten wajib diisi');
        });
    }

    /**
     * Test email format validation
     */
    public function test_email_format_validation()
    {
        $this->browse(function (Browser $browser) {
            // Test pada form login
            $browser->visit('/login')
                    ->type('email', 'invalid-email')
                    ->type('password', 'password')
                    ->press('Login')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format email tidak valid');
            
            // Test pada form WBS
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm([
                        'nama_pelapor' => 'Test User',
                        'email_pelapor' => 'invalid-email-format',
                        'telepon_pelapor' => '081234567890',
                        'kategori' => 'korupsi',
                        'judul_laporan' => 'Test WBS',
                        'deskripsi' => 'Test deskripsi WBS.',
                        'lokasi_kejadian' => 'Jakarta',
                        'tanggal_kejadian' => now()->format('Y-m-d'),
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format email tidak valid');
        });
    }

    /**
     * Test phone number validation
     */
    public function test_phone_number_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test pada form WBS
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm([
                        'nama_pelapor' => 'Test User',
                        'email_pelapor' => 'test@example.com',
                        'telepon_pelapor' => '123', // Invalid phone number
                        'kategori' => 'korupsi',
                        'judul_laporan' => 'Test WBS',
                        'deskripsi' => 'Test deskripsi WBS.',
                        'lokasi_kejadian' => 'Jakarta',
                        'tanggal_kejadian' => now()->format('Y-m-d'),
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format nomor telepon tidak valid');
        });
    }

    /**
     * Test password strength validation
     */
    public function test_password_strength_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Test pada form create user
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm([
                        'name' => 'Test User',
                        'email' => 'test@example.com',
                        'password' => '123', // Weak password
                        'password_confirmation' => '123',
                        'role' => 'admin',
                        'nip' => '198001011234567890',
                        'jabatan' => 'Staff',
                        'unit_kerja' => 'Inspektorat',
                        'telepon' => '081234567890',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Password minimal 6 karakter');
        });
    }

    /**
     * Test password confirmation validation
     */
    public function test_password_confirmation_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Test pada form create user
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm([
                        'name' => 'Test User',
                        'email' => 'test@example.com',
                        'password' => 'password123',
                        'password_confirmation' => 'differentpassword',
                        'role' => 'admin',
                        'nip' => '198001011234567890',
                        'jabatan' => 'Staff',
                        'unit_kerja' => 'Inspektorat',
                        'telepon' => '081234567890',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Password confirmation tidak cocok');
        });
    }

    /**
     * Test file type validation
     */
    public function test_file_type_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $invalidFile = $this->createTestFile('invalid.exe', 'invalid-content', 'application/octet-stream');
            
            // Test pada form create berita
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Berita',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $invalidFile)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format file tidak valid');
        });
    }

    /**
     * Test file size validation
     */
    public function test_file_size_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Create oversized file (simulated)
            $oversizedFile = $this->createTestFile('oversized.jpg', str_repeat('a', 1024*1024*6), 'image/jpeg'); // 6MB
            
            // Test pada form create berita
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Berita',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->attach('input[name="gambar"]', $oversizedFile)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Ukuran file terlalu besar');
        });
    }

    /**
     * Test date validation
     */
    public function test_date_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test pada form WBS
            $browser->visit('/admin/wbs')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/wbs/create', 30)
                    ->fillForm([
                        'nama_pelapor' => 'Test User',
                        'email_pelapor' => 'test@example.com',
                        'telepon_pelapor' => '081234567890',
                        'kategori' => 'korupsi',
                        'judul_laporan' => 'Test WBS',
                        'deskripsi' => 'Test deskripsi WBS.',
                        'lokasi_kejadian' => 'Jakarta',
                        'tanggal_kejadian' => 'invalid-date',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format tanggal tidak valid');
        });
    }

    /**
     * Test numeric validation
     */
    public function test_numeric_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            // Test pada form create user (NIP harus numerik)
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm([
                        'name' => 'Test User',
                        'email' => 'test@example.com',
                        'password' => 'password123',
                        'password_confirmation' => 'password123',
                        'role' => 'admin',
                        'nip' => 'invalid-nip', // Non-numeric NIP
                        'jabatan' => 'Staff',
                        'unit_kerja' => 'Inspektorat',
                        'telepon' => '081234567890',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('NIP harus berupa angka');
        });
    }

    /**
     * Test string length validation
     */
    public function test_string_length_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test pada form create berita (judul terlalu panjang)
            $longTitle = str_repeat('a', 256); // 256 characters
            
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => $longTitle,
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Judul maksimal 255 karakter');
        });
    }

    /**
     * Test unique validation
     */
    public function test_unique_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            
            $existingEmail = 'unique@example.com';
            
            // Create first user
            $browser->visit('/admin/users')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm([
                        'name' => 'First User',
                        'email' => $existingEmail,
                        'password' => 'password123',
                        'password_confirmation' => 'password123',
                        'role' => 'admin',
                        'nip' => '198001011234567890',
                        'jabatan' => 'Staff',
                        'unit_kerja' => 'Inspektorat',
                        'telepon' => '081234567890',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/users', 30);
            
            // Try to create second user with same email
            $browser->click('a[href*="create"]')
                    ->waitForLocation('/admin/users/create', 30)
                    ->fillForm([
                        'name' => 'Second User',
                        'email' => $existingEmail,
                        'password' => 'password123',
                        'password_confirmation' => 'password123',
                        'role' => 'admin',
                        'nip' => '198001011234567891',
                        'jabatan' => 'Staff',
                        'unit_kerja' => 'Inspektorat',
                        'telepon' => '081234567891',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Email sudah digunakan');
        });
    }

    /**
     * Test URL validation
     */
    public function test_url_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test pada form yang memiliki field URL
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Berita',
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                        'external_link' => 'invalid-url',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Format URL tidak valid');
        });
    }

    /**
     * Test client-side validation
     */
    public function test_client_side_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test HTML5 validation
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->assertAttribute('input[name="judul"]', 'required', 'true')
                    ->assertAttribute('textarea[name="konten"]', 'required', 'true')
                    ->assertAttribute('input[name="email"]', 'type', 'email');
        });
    }

    /**
     * Test server-side validation
     */
    public function test_server_side_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Disable JavaScript to test server-side validation
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->script('document.querySelector("form").noValidate = true;')
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('judul wajib diisi')
                    ->assertSee('konten wajib diisi');
        });
    }

    /**
     * Test CSRF protection
     */
    public function test_csrf_protection()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test CSRF token presence
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->assertPresent('input[name="_token"]');
        });
    }

    /**
     * Test XSS protection
     */
    public function test_xss_protection()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $xssPayload = '<script>alert("XSS")</script>';
            
            // Test XSS dalam form input
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => $xssPayload,
                        'konten' => 'Test konten berita',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertDontSee('<script>')
                    ->assertSee('&lt;script&gt;'); // Should be escaped
        });
    }

    /**
     * Test SQL injection protection
     */
    public function test_sql_injection_protection()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $sqlPayload = "'; DROP TABLE users; --";
            
            // Test SQL injection dalam search
            $browser->visit('/admin/berita')
                    ->type('input[name="search"]', $sqlPayload)
                    ->press('button[type="submit"]')
                    ->waitForReload()
                    ->assertDontSee('Database error');
        });
    }

    /**
     * Test form validation with AJAX
     */
    public function test_form_validation_with_ajax()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test real-time validation via AJAX
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->type('input[name="judul"]', 'Test')
                    ->keys('input[name="judul"]', ['{tab}'])
                    ->pause(1000) // Wait for AJAX validation
                    ->assertSee('Judul minimal 5 karakter');
        });
    }

    /**
     * Test form validation error display
     */
    public function test_form_validation_error_display()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test error display styling
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertVisible('.alert-danger')
                    ->assertSeeIn('.alert-danger', 'judul wajib diisi');
        });
    }

    /**
     * Test form validation success feedback
     */
    public function test_form_validation_success_feedback()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test success feedback
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Berita Valid',
                        'konten' => 'Test konten berita yang valid',
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/berita', 30)
                    ->assertSee('Berita berhasil dibuat');
        });
    }

    /**
     * Test form validation with multiple errors
     */
    public function test_form_validation_with_multiple_errors()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test multiple validation errors
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('judul wajib diisi')
                    ->assertSee('konten wajib diisi')
                    ->assertSee('kategori wajib dipilih');
        });
    }

    /**
     * Test form validation persistence after error
     */
    public function test_form_validation_persistence_after_error()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test form values persist after validation error
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Berita',
                        'konten' => '', // Missing required field
                        'status' => 'draft',
                        'kategori' => 'umum',
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('konten wajib diisi')
                    ->assertInputValue('input[name="judul"]', 'Test Berita')
                    ->assertSelected('select[name="kategori"]', 'umum');
        });
    }

    /**
     * Test conditional validation
     */
    public function test_conditional_validation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test conditional validation based on another field
            $browser->visit('/admin/berita')
                    ->click('a[href*="create"]')
                    ->waitForLocation('/admin/berita/create', 30)
                    ->fillForm([
                        'judul' => 'Test Berita',
                        'konten' => 'Test konten berita',
                        'status' => 'published',
                        'kategori' => 'umum',
                        'tanggal_publish' => '', // Required when status is published
                    ])
                    ->press('button[type="submit"]')
                    ->waitFor('.alert-danger', 10)
                    ->assertSee('Tanggal publish wajib diisi untuk status published');
        });
    }
}
