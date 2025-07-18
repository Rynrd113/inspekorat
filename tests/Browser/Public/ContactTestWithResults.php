<?php

namespace Tests\Browser\Public;

use App\Models\Kontak;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Contact Test With Results
 * Test contact form functionality with database result verification
 */
class ContactTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test contact form submission with database verification
     */
    public function testContactFormSubmissionWithDatabaseVerification()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('subjek', 'Test Contact Subject')
                ->type('pesan', 'This is a test contact message from automated test')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('contact-form-submission-success');

            // Verify data was saved to database
            $this->assertDatabaseHas('kontaks', [
                'nama' => 'John Doe',
                'email' => 'john@example.com',
                'subjek' => 'Test Contact Subject',
                'pesan' => 'This is a test contact message from automated test'
            ]);
        });
    }

    /**
     * Test contact form validation with error display
     */
    public function testContactFormValidationWithErrors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->press('Send')
                ->waitFor('.error-message', 10)
                ->assertSee('Nama wajib diisi')
                ->assertSee('Email wajib diisi')
                ->assertSee('Subjek wajib diisi')
                ->assertSee('Pesan wajib diisi')
                ->screenshot('contact-form-validation-errors');

            // Verify no data was saved to database
            $this->assertDatabaseMissing('kontaks', [
                'nama' => null,
                'email' => null
            ]);
        });
    }

    /**
     * Test contact form with invalid email format
     */
    public function testContactFormInvalidEmailFormat()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'John Doe')
                ->type('email', 'invalid-email-format')
                ->type('subjek', 'Test Subject')
                ->type('pesan', 'Test message')
                ->press('Send')
                ->waitFor('.error-message', 10)
                ->assertSee('Format email tidak valid')
                ->screenshot('contact-form-invalid-email');

            // Verify no data was saved to database
            $this->assertDatabaseMissing('kontaks', [
                'email' => 'invalid-email-format'
            ]);
        });
    }

    /**
     * Test contact form with maximum length validation
     */
    public function testContactFormMaxLengthValidation()
    {
        $this->browse(function (Browser $browser) {
            $longText = str_repeat('a', 1001); // Exceed maximum length

            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('subjek', 'Test Subject')
                ->type('pesan', $longText)
                ->press('Send')
                ->waitFor('.error-message', 10)
                ->assertSee('Pesan tidak boleh lebih dari 1000 karakter')
                ->screenshot('contact-form-max-length');

            // Verify no data was saved to database
            $this->assertDatabaseMissing('kontaks', [
                'pesan' => $longText
            ]);
        });
    }

    /**
     * Test multiple contact form submissions
     */
    public function testMultipleContactFormSubmissions()
    {
        $this->browse(function (Browser $browser) {
            // First submission
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('subjek', 'First Contact')
                ->type('pesan', 'This is the first contact message')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('contact-form-first-submission');

            // Second submission
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'Jane Smith')
                ->type('email', 'jane@example.com')
                ->type('subjek', 'Second Contact')
                ->type('pesan', 'This is the second contact message')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('contact-form-second-submission');

            // Verify both submissions were saved
            $this->assertDatabaseHas('kontaks', [
                'nama' => 'John Doe',
                'subjek' => 'First Contact'
            ]);

            $this->assertDatabaseHas('kontaks', [
                'nama' => 'Jane Smith',
                'subjek' => 'Second Contact'
            ]);
        });
    }

    /**
     * Test contact form with special characters
     */
    public function testContactFormWithSpecialCharacters()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'José María')
                ->type('email', 'jose@example.com')
                ->type('subjek', 'Test with Special Characters: áéíóú')
                ->type('pesan', 'Message with special characters: ñÑ @#$%^&*()')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('contact-form-special-characters');

            // Verify data with special characters was saved
            $this->assertDatabaseHas('kontaks', [
                'nama' => 'José María',
                'subjek' => 'Test with Special Characters: áéíóú'
            ]);
        });
    }

    /**
     * Test contact form timestamps
     */
    public function testContactFormTimestamps()
    {
        $this->browse(function (Browser $browser) {
            $beforeSubmission = now();

            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'Timestamp Test')
                ->type('email', 'timestamp@example.com')
                ->type('subjek', 'Timestamp Test')
                ->type('pesan', 'Testing timestamp functionality')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('contact-form-timestamp-test');

            $afterSubmission = now();

            // Verify contact was created within the expected timeframe
            $contact = Kontak::where('email', 'timestamp@example.com')->first();
            $this->assertNotNull($contact);
            $this->assertTrue($contact->created_at->between($beforeSubmission, $afterSubmission));
        });
    }

    /**
     * Test contact form rate limiting
     */
    public function testContactFormRateLimiting()
    {
        $this->browse(function (Browser $browser) {
            // Submit multiple forms rapidly
            for ($i = 1; $i <= 3; $i++) {
                $browser->visit('/kontak')
                    ->waitFor('.contact-form', 10)
                    ->type('nama', "User {$i}")
                    ->type('email', "user{$i}@example.com")
                    ->type('subjek', "Subject {$i}")
                    ->type('pesan', "Message {$i}")
                    ->press('Send')
                    ->waitFor('.message', 10);
            }

            // Fourth submission should be rate limited
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'Rate Limited User')
                ->type('email', 'ratelimited@example.com')
                ->type('subjek', 'Rate Limited Subject')
                ->type('pesan', 'This should be rate limited')
                ->press('Send')
                ->waitFor('.error-message', 10)
                ->assertSee('Too many requests')
                ->screenshot('contact-form-rate-limited');

            // Verify rate limited submission was not saved
            $this->assertDatabaseMissing('kontaks', [
                'email' => 'ratelimited@example.com'
            ]);
        });
    }

    /**
     * Test contact form admin notification
     */
    public function testContactFormAdminNotification()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'Admin Notification Test')
                ->type('email', 'notification@example.com')
                ->type('subjek', 'Test Admin Notification')
                ->type('pesan', 'This should trigger admin notification')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('contact-form-admin-notification');

            // Verify contact was saved
            $this->assertDatabaseHas('kontaks', [
                'email' => 'notification@example.com',
                'subjek' => 'Test Admin Notification'
            ]);

            // Verify notification was queued (check jobs table)
            $this->assertDatabaseHas('jobs', [
                'queue' => 'notifications'
            ]);
        });
    }

    /**
     * Test contact form auto-reply functionality
     */
    public function testContactFormAutoReply()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'Auto Reply Test')
                ->type('email', 'autoreply@example.com')
                ->type('subjek', 'Test Auto Reply')
                ->type('pesan', 'This should trigger auto reply')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->assertSee('Auto reply sent to your email')
                ->screenshot('contact-form-auto-reply');

            // Verify auto reply was queued
            $this->assertDatabaseHas('jobs', [
                'queue' => 'emails'
            ]);
        });
    }

    /**
     * Test contact form status tracking
     */
    public function testContactFormStatusTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitFor('.contact-form', 10)
                ->type('nama', 'Status Tracking Test')
                ->type('email', 'status@example.com')
                ->type('subjek', 'Test Status Tracking')
                ->type('pesan', 'This message should have status tracking')
                ->press('Send')
                ->waitForText('Message sent successfully', 10)
                ->screenshot('contact-form-status-tracking');

            // Verify contact was saved with initial status
            $contact = Kontak::where('email', 'status@example.com')->first();
            $this->assertNotNull($contact);
            $this->assertEquals('pending', $contact->status);
        });
    }
}