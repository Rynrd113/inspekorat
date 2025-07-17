<?php

namespace Tests\Browser\Email;

use App\Models\User;
use App\Models\Berita;
use App\Models\Wbs;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class EmailTestingComprehensiveTest extends DuskTestCase
{
    use DatabaseMigrations, InteractsWithAuthentication;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Queue::fake();
    }

    /**
     * Test Email Configuration
     */
    public function test_email_configuration()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/configurations')
                ->waitForText('Email Configuration')
                ->assertSee('Email Configuration')
                ->click('a[href*="email-settings"]')
                ->waitForText('SMTP Settings')
                ->assertSee('SMTP Settings')
                ->assertSee('Mail Driver')
                ->assertSee('SMTP Host')
                ->assertSee('SMTP Port');
        });
    }

    /**
     * Test Email Template Management
     */
    public function test_email_template_management()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-templates')
                ->waitForText('Email Templates')
                ->assertSee('Email Templates')
                ->click('a[href*="create"]')
                ->waitForText('Create Email Template')
                ->type('name', 'Test Email Template')
                ->type('subject', 'Test Subject')
                ->type('body', 'This is a test email template with {{name}} placeholder')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
    }

    /**
     * Test User Registration Email
     */
    public function test_user_registration_email()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        $this->browse(function (Browser $browser) use ($superAdmin) {
            $browser->loginAs($superAdmin)
                ->visit('/admin/users/create')
                ->type('name', 'New User Test')
                ->type('email', 'newuser@example.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->select('role', 'content_manager')
                ->check('send_welcome_email')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
        
        // Verify email was sent
        Mail::assertSent(\App\Mail\UserRegistrationMail::class, function ($mail) {
            return $mail->hasTo('newuser@example.com');
        });
    }

    /**
     * Test Password Reset Email
     */
    public function test_password_reset_email()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->click('a[href*="password/reset"]')
                ->waitForText('Reset Password')
                ->type('email', 'test@example.com')
                ->press('Send Password Reset Link')
                ->waitForText('Password reset link sent')
                ->assertSee('Password reset link sent');
        });
        
        // Verify email was sent
        Mail::assertSent(\App\Mail\PasswordResetMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    /**
     * Test WBS Report Notification Email
     */
    public function test_wbs_report_notification_email()
    {
        $wbsManager = User::factory()->create(['role' => 'wbs_manager']);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->type('nama', 'Test Reporter')
                ->type('email', 'reporter@example.com')
                ->type('judul', 'Test WBS Report')
                ->type('keterangan', 'This is a test WBS report')
                ->press('Kirim Laporan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
        
        // Verify notification email was sent to WBS manager
        Mail::assertSent(\App\Mail\WbsReportNotificationMail::class, function ($mail) use ($wbsManager) {
            return $mail->hasTo($wbsManager->email);
        });
        
        // Verify confirmation email was sent to reporter
        Mail::assertSent(\App\Mail\WbsReportConfirmationMail::class, function ($mail) {
            return $mail->hasTo('reporter@example.com');
        });
    }

    /**
     * Test Content Publication Email
     */
    public function test_content_publication_email()
    {
        $contentManager = User::factory()->create(['role' => 'content_manager']);
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($contentManager, $admin) {
            $browser->loginAs($contentManager)
                ->visit('/admin/berita/create')
                ->type('judul', 'Test Article for Publication')
                ->type('konten', 'This is a test article')
                ->select('status', 'draft')
                ->check('notify_admin')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
        
        // Verify notification email was sent to admin
        Mail::assertSent(\App\Mail\ContentDraftNotificationMail::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });
    }

    /**
     * Test Bulk Email Sending
     */
    public function test_bulk_email_sending()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(5)->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/emails')
                ->waitForText('Bulk Email')
                ->click('a[href*="bulk-email"]')
                ->waitForText('Send Bulk Email')
                ->type('subject', 'Test Bulk Email')
                ->type('message', 'This is a test bulk email message')
                ->check('send_to_content_managers')
                ->press('Send Email')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
        
        // Verify bulk emails were queued
        Queue::assertPushed(\App\Jobs\SendBulkEmailJob::class);
    }

    /**
     * Test Email Newsletter Subscription
     */
    public function test_email_newsletter_subscription()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->scrollIntoView('footer')
                ->type('newsletter_email', 'subscriber@example.com')
                ->press('Subscribe')
                ->waitForText('Berhasil berlangganan')
                ->assertSee('Berhasil berlangganan');
        });
        
        // Verify confirmation email was sent
        Mail::assertSent(\App\Mail\NewsletterConfirmationMail::class, function ($mail) {
            return $mail->hasTo('subscriber@example.com');
        });
    }

    /**
     * Test Email Unsubscribe
     */
    public function test_email_unsubscribe()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/unsubscribe?email=subscriber@example.com&token=valid_token')
                ->waitForText('Unsubscribe')
                ->press('Unsubscribe')
                ->waitForText('Berhasil berhenti berlangganan')
                ->assertSee('Berhasil berhenti berlangganan');
        });
        
        // Verify unsubscribe confirmation email was sent
        Mail::assertSent(\App\Mail\UnsubscribeConfirmationMail::class, function ($mail) {
            return $mail->hasTo('subscriber@example.com');
        });
    }

    /**
     * Test Email Verification
     */
    public function test_email_verification()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->waitForText('Verifikasi Email')
                ->assertSee('Verifikasi Email')
                ->click('a[href*="email/resend"]')
                ->waitForText('Link verifikasi dikirim')
                ->assertSee('Link verifikasi dikirim');
        });
        
        // Verify verification email was sent
        Mail::assertSent(\App\Mail\EmailVerificationMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     * Test Email Queue Processing
     */
    public function test_email_queue_processing()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-queue')
                ->waitForText('Email Queue')
                ->assertSee('Email Queue')
                ->assertSee('Pending')
                ->assertSee('Processing')
                ->assertSee('Completed')
                ->assertSee('Failed');
        });
        
        // Test queue processing
        Queue::assertPushed(\App\Jobs\ProcessEmailQueueJob::class);
    }

    /**
     * Test Email Delivery Status
     */
    public function test_email_delivery_status()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-logs')
                ->waitForText('Email Logs')
                ->assertSee('Email Logs')
                ->assertSee('Sent')
                ->assertSee('Failed')
                ->assertSee('Bounced')
                ->assertSee('Delivered');
        });
    }

    /**
     * Test Email Template Variables
     */
    public function test_email_template_variables()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-templates/1/edit')
                ->waitForText('Edit Template')
                ->clear('body')
                ->type('body', 'Hello {{user.name}}, your role is {{user.role}}')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->click('a[href*="preview"]')
                ->waitForText('Template Preview')
                ->assertSee('Hello ' . $user->name)
                ->assertSee('your role is ' . $user->role);
        });
    }

    /**
     * Test Email Attachments
     */
    public function test_email_attachments()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        // Create test attachment
        $testFile = tempnam(sys_get_temp_dir(), 'test_attachment') . '.pdf';
        file_put_contents($testFile, 'Test attachment content');
        
        $this->browse(function (Browser $browser) use ($user, $testFile) {
            $browser->loginAs($user)
                ->visit('/admin/emails/compose')
                ->waitForText('Compose Email')
                ->type('to', 'recipient@example.com')
                ->type('subject', 'Test Email with Attachment')
                ->type('message', 'This email has an attachment')
                ->attach('attachment', $testFile)
                ->press('Send Email')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
        
        // Verify email with attachment was sent
        Mail::assertSent(\App\Mail\CustomEmailMail::class, function ($mail) {
            return $mail->hasTo('recipient@example.com') && 
                   count($mail->attachments) > 0;
        });
        
        unlink($testFile);
    }

    /**
     * Test Email Bounce Handling
     */
    public function test_email_bounce_handling()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-bounces')
                ->waitForText('Email Bounces')
                ->assertSee('Email Bounces')
                ->assertSee('Hard Bounce')
                ->assertSee('Soft Bounce')
                ->assertSee('Complaint');
        });
    }

    /**
     * Test Email Scheduling
     */
    public function test_email_scheduling()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/emails/schedule')
                ->waitForText('Schedule Email')
                ->type('to', 'recipient@example.com')
                ->type('subject', 'Scheduled Email Test')
                ->type('message', 'This is a scheduled email')
                ->script('
                    let dateInput = document.querySelector("input[name=\"scheduled_at\"]");
                    if (dateInput) {
                        let futureDate = new Date();
                        futureDate.setHours(futureDate.getHours() + 1);
                        dateInput.value = futureDate.toISOString().slice(0, 16);
                    }
                ')
                ->press('Schedule Email')
                ->waitForText('Email dijadwalkan')
                ->assertSee('Email dijadwalkan');
        });
        
        // Verify scheduled email job was queued
        Queue::assertPushed(\App\Jobs\SendScheduledEmailJob::class);
    }

    /**
     * Test Email Analytics
     */
    public function test_email_analytics()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-analytics')
                ->waitForText('Email Analytics')
                ->assertSee('Email Analytics')
                ->assertSee('Open Rate')
                ->assertSee('Click Rate')
                ->assertSee('Bounce Rate')
                ->assertSee('Unsubscribe Rate')
                ->assertSee('Total Sent')
                ->assertSee('Total Delivered');
        });
    }

    /**
     * Test Email Spam Filter Testing
     */
    public function test_email_spam_filter_testing()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/emails/spam-test')
                ->waitForText('Spam Filter Test')
                ->type('subject', 'Test Email Subject')
                ->type('content', 'This is a test email content for spam checking')
                ->press('Test Spam Score')
                ->waitForText('Spam Score')
                ->assertSee('Spam Score');
        });
    }

    /**
     * Test Email Blacklist Management
     */
    public function test_email_blacklist_management()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-blacklist')
                ->waitForText('Email Blacklist')
                ->assertSee('Email Blacklist')
                ->type('email', 'blocked@example.com')
                ->type('reason', 'Spam complaints')
                ->press('Add to Blacklist')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->assertSee('blocked@example.com');
        });
    }

    /**
     * Test Email Whitelist Management
     */
    public function test_email_whitelist_management()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-whitelist')
                ->waitForText('Email Whitelist')
                ->assertSee('Email Whitelist')
                ->type('email', 'trusted@example.com')
                ->type('reason', 'Trusted sender')
                ->press('Add to Whitelist')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil')
                ->assertSee('trusted@example.com');
        });
    }

    /**
     * Test Email Campaign Management
     */
    public function test_email_campaign_management()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-campaigns')
                ->waitForText('Email Campaigns')
                ->assertSee('Email Campaigns')
                ->click('a[href*="create"]')
                ->waitForText('Create Campaign')
                ->type('name', 'Test Campaign')
                ->type('subject', 'Test Campaign Subject')
                ->type('content', 'This is a test campaign content')
                ->select('audience', 'all_users')
                ->press('Create Campaign')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
    }

    /**
     * Test Email A/B Testing
     */
    public function test_email_ab_testing()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-ab-test')
                ->waitForText('A/B Test')
                ->assertSee('A/B Test')
                ->type('test_name', 'Subject Line Test')
                ->type('subject_a', 'Subject A')
                ->type('subject_b', 'Subject B')
                ->type('content', 'Test content')
                ->select('split_percentage', '50')
                ->press('Start A/B Test')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
    }

    /**
     * Test Email Automation Rules
     */
    public function test_email_automation_rules()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-automation')
                ->waitForText('Email Automation')
                ->assertSee('Email Automation')
                ->click('a[href*="create-rule"]')
                ->waitForText('Create Automation Rule')
                ->type('name', 'Welcome Email Rule')
                ->select('trigger', 'user_registration')
                ->select('template', 'welcome_email')
                ->type('delay_minutes', '5')
                ->press('Create Rule')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
        });
    }

    /**
     * Test Email Performance Monitoring
     */
    public function test_email_performance_monitoring()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-performance')
                ->waitForText('Email Performance')
                ->assertSee('Email Performance')
                ->assertSee('Delivery Rate')
                ->assertSee('Open Rate')
                ->assertSee('Click Rate')
                ->assertSee('Response Time')
                ->assertSee('Queue Length');
        });
    }

    /**
     * Test Email Error Handling
     */
    public function test_email_error_handling()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/emails/compose')
                ->waitForText('Compose Email')
                ->type('to', 'invalid-email-address')
                ->type('subject', 'Test Email')
                ->type('message', 'Test content')
                ->press('Send Email')
                ->waitForText('Format email tidak valid')
                ->assertSee('Format email tidak valid');
        });
    }

    /**
     * Test Email Retry Mechanism
     */
    public function test_email_retry_mechanism()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/email-failed')
                ->waitForText('Failed Emails')
                ->assertSee('Failed Emails')
                ->click('a[href*="retry"]')
                ->waitForText('Email dijadwalkan ulang')
                ->assertSee('Email dijadwalkan ulang');
        });
        
        // Verify retry job was queued
        Queue::assertPushed(\App\Jobs\RetryFailedEmailJob::class);
    }
}