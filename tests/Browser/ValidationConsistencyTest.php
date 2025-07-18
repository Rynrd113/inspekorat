<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;

class ValidationConsistencyTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test news form validation consistency between frontend and backend.
     */
    public function test_news_form_validation_consistency()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create');

            // Test required field validation
            $browser->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The judul field is required')
                    ->assertSee('The isi field is required');

            // Test minimum length validation
            $browser->type('judul', 'ab') // Too short
                    ->type('isi', 'short')
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The judul field must be at least')
                    ->assertSee('The isi field must be at least');

            // Test maximum length validation
            $longTitle = str_repeat('a', 256); // Assuming max 255 chars
            $browser->clear('judul')
                    ->type('judul', $longTitle)
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The judul field must not be greater than');

            // Test valid data passes validation
            $browser->clear('judul')
                    ->type('judul', 'Valid News Title')
                    ->clear('isi')
                    ->type('isi', 'Valid news content that meets minimum requirements')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);
        });
    }

    /**
     * Test WBS form validation consistency.
     */
    public function test_wbs_form_validation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs');

            // Test required fields
            $browser->press('Kirim Laporan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The judul laporan field is required')
                    ->assertSee('The isi laporan field is required')
                    ->assertSee('The lokasi kejadian field is required');

            // Test named report requires reporter info
            $browser->type('judul_laporan', 'Test Report')
                    ->type('isi_laporan', 'Test content')
                    ->type('lokasi_kejadian', 'Test location')
                    ->uncheck('is_anonymous')
                    ->waitFor('.reporter-info')
                    ->press('Kirim Laporan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The nama pelapor field is required')
                    ->assertSee('The email pelapor field is required');

            // Test email format validation
            $browser->type('nama_pelapor', 'John Doe')
                    ->type('email_pelapor', 'invalid-email')
                    ->press('Kirim Laporan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The email pelapor field must be a valid email address');

            // Test valid submission
            $browser->clear('email_pelapor')
                    ->type('email_pelapor', 'john@example.com')
                    ->type('telepon_pelapor', '08123456789')
                    ->press('Kirim Laporan')
                    ->waitForText('Laporan berhasil dikirim', 10);
        });
    }

    /**
     * Test file upload validation consistency.
     */
    public function test_file_upload_validation_consistency()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'File Upload Test')
                    ->type('isi', 'Testing file upload validation');

            // Test invalid file type
            $this->uploadFile($browser, 'gambar_utama', 'test.txt', 'not-an-image');
            
            $browser->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The gambar utama field must be an image');

            // Test file size validation (simulate large file)
            $largeImageContent = str_repeat('x', 5 * 1024 * 1024); // 5MB
            $this->uploadFile($browser, 'gambar_utama', 'large.jpg', $largeImageContent);
            
            $browser->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The gambar utama field must not be greater than');

            // Test valid image upload
            $this->uploadFile($browser, 'gambar_utama', 'valid.jpg', 'valid-image-content');
            
            $browser->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);
        });
    }

    /**
     * Test user management validation consistency.
     */
    public function test_user_management_validation_consistency()
    {
        $superAdmin = $this->createSuperAdmin();

        $this->browse(function (Browser $browser) use ($superAdmin) {
            $this->loginAs($superAdmin, $browser);
            
            $browser->visit('/admin/users/create');

            // Test required fields
            $browser->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The name field is required')
                    ->assertSee('The email field is required')
                    ->assertSee('The password field is required');

            // Test email format validation
            $browser->type('name', 'Test User')
                    ->type('email', 'invalid-email')
                    ->type('password', 'password123')
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The email field must be a valid email address');

            // Test unique email validation
            $existingUser = $this->createUserWithRole('user', ['email' => 'existing@test.com']);
            
            $browser->clear('email')
                    ->type('email', 'existing@test.com')
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The email has already been taken');

            // Test password strength validation
            $browser->clear('email')
                    ->type('email', 'newuser@test.com')
                    ->clear('password')
                    ->type('password', '123') // Too weak
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The password field must be at least');

            // Test valid user creation
            $browser->clear('password')
                    ->type('password', 'strongpassword123')
                    ->type('password_confirmation', 'strongpassword123')
                    ->select('role', 'content_manager')
                    ->press('Simpan')
                    ->waitForLocation('/admin/users')
                    ->waitForText('User berhasil dibuat', 10);
        });
    }

    /**
     * Test contact form validation consistency.
     */
    public function test_contact_form_validation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                    ->scrollTo('.contact-form');

            // Test required fields
            $browser->press('Kirim Pesan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The name field is required')
                    ->assertSee('The email field is required')
                    ->assertSee('The message field is required');

            // Test email format validation
            $browser->type('name', 'Test User')
                    ->type('email', 'invalid-email')
                    ->type('subject', 'Test Subject')
                    ->type('message', 'Test message')
                    ->press('Kirim Pesan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The email field must be a valid email address');

            // Test message length validation
            $browser->clear('email')
                    ->type('email', 'test@example.com')
                    ->clear('message')
                    ->type('message', 'ab') // Too short
                    ->press('Kirim Pesan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The message field must be at least');

            // Test valid submission
            $browser->clear('message')
                    ->type('message', 'This is a valid message with sufficient length')
                    ->press('Kirim Pesan')
                    ->waitForText('Pesan berhasil dikirim', 10);
        });
    }

    /**
     * Test validation error messages are consistent across languages.
     */
    public function test_validation_error_messages_language_consistency()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            // Test Indonesian error messages
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('required'); // Should be in Indonesian: 'wajib diisi' or similar

            // Test English error messages for API
            $response = $this->postJson('/api/admin/portal-papua-tengah', []);
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['judul', 'isi']);
        });
    }

    /**
     * Test AJAX validation vs form submission validation consistency.
     */
    public function test_ajax_vs_form_validation_consistency()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create');

            // Test real-time validation (if implemented)
            $browser->type('judul', 'ab') // Below minimum
                    ->click('isi') // Trigger blur event
                    ->waitFor('.field-error', 5)
                    ->assertSee('minimum');

            // Test form submission validation matches real-time
            $browser->clear('judul')
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('required');
        });
    }

    /**
     * Test client-side and server-side validation consistency.
     */
    public function test_client_server_validation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs');

            // Disable JavaScript to test server-side validation
            $browser->script('window.addEventListener("submit", function(e) { e.preventDefault(); });');

            // Fill form with invalid data
            $browser->type('judul_laporan', 'a') // Too short
                    ->type('isi_laporan', 'ab') // Too short
                    ->type('lokasi_kejadian', 'Test')
                    ->uncheck('is_anonymous')
                    ->waitFor('.reporter-info')
                    ->type('nama_pelapor', 'Test')
                    ->type('email_pelapor', 'invalid-email');

            // Re-enable form submission
            $browser->script('window.removeEventListener("submit", arguments[0]);');

            $browser->press('Kirim Laporan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('at least') // Minimum length error
                    ->assertSee('valid email'); // Email format error
        });
    }

    /**
     * Test validation consistency across different user roles.
     */
    public function test_validation_consistency_across_roles()
    {
        $contentManager = $this->createContentManager();
        $opdManager = $this->createOpdManager();

        $this->browse(function (Browser $browser) use ($contentManager, $opdManager) {
            // Test content manager news validation
            $this->loginAs($contentManager, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The judul field is required');
                    
            $this->logout($browser);

            // Test OPD manager portal validation
            $this->loginAs($opdManager, $browser);
            
            $browser->visit('/admin/portal-opd/create')
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertSee('The nama opd field is required');
        });
    }

    /**
     * Test validation behavior with special characters and edge cases.
     */
    public function test_validation_with_special_characters_and_edge_cases()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create');

            // Test special characters in title
            $browser->type('judul', '<script>alert("xss")</script>')
                    ->type('isi', 'Valid content')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah');

            // Verify XSS is properly escaped in database and frontend
            $this->assertDatabaseHas('portal_papua_tengahs', [
                'judul' => '&lt;script&gt;alert("xss")&lt;/script&gt;', // Should be escaped
            ]);

            $browser->visit('/berita')
                    ->assertDontSee('<script>') // Script tags should not be visible
                    ->assertSee('alert'); // But content should be visible (escaped)
        });
    }

    /**
     * Test validation performance under load.
     */
    public function test_validation_performance_under_load()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $startTime = microtime(true);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'Performance Test')
                    ->type('isi', 'Testing validation performance under load')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah');
            
            $endTime = microtime(true);
            $duration = $endTime - $startTime;
            
            // Validation and form submission should complete within reasonable time
            $this->assertLessThan(5, $duration, 'Form validation and submission took too long');
        });
    }

    /**
     * Test validation state persistence across page navigation.
     */
    public function test_validation_state_persistence()
    {
        $admin = $this->createContentManager();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->loginAs($admin, $browser);
            
            $browser->visit('/admin/portal-papua-tengah/create')
                    ->type('judul', 'Partial Form Data')
                    // Don't fill required 'isi' field
                    ->press('Simpan')
                    ->waitFor('.invalid-feedback')
                    ->assertInputValue('judul', 'Partial Form Data') // Value should persist
                    ->assertSee('The isi field is required');

            // Fill remaining field and submit
            $browser->type('isi', 'Now complete content')
                    ->press('Simpan')
                    ->waitForLocation('/admin/portal-papua-tengah')
                    ->waitForText('Berita berhasil dibuat', 10);
        });
    }
}