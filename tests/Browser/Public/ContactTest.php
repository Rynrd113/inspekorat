<?php

namespace Tests\Browser\Public;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ContactTest extends DuskTestCase
{
    /**
     * Test contact page loads successfully
     */
    public function test_contact_page_loads_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertSee('Kontak Kami')
                ->assertSee('Hubungi kami untuk informasi lebih lanjut')
                ->screenshot('contact_page_main');
        });
    }

    /**
     * Test contact page displays contact information
     */
    public function test_contact_page_displays_contact_info()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertSee('Informasi Kontak')
                ->assertSee('Alamat')
                ->assertSee('Telepon')
                ->assertSee('Email')
                ->assertSee('Jam Operasional')
                ->screenshot('contact_page_info');
        });
    }

    /**
     * Test contact page form display
     */
    public function test_contact_page_form_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->assertPresent('form')
                ->assertPresent('input[name="nama"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('textarea[name="pesan"]')
                ->assertPresent('button[type="submit"]')
                ->screenshot('contact_page_form');
        });
    }

    /**
     * Test contact page form submission with valid data
     */
    public function test_contact_page_form_submission_valid()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->type('nama', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('pesan', 'Ini adalah pesan test untuk form kontak.')
                ->press('Kirim Pesan')
                ->pause(2000)
                ->screenshot('contact_form_submission_valid');
        });
    }

    /**
     * Test contact page form validation - empty fields
     */
    public function test_contact_page_form_validation_empty_fields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->press('Kirim Pesan')
                ->waitFor('.invalid-feedback', 3)
                ->screenshot('contact_form_validation_empty');
        });
    }

    /**
     * Test contact page form validation - invalid email
     */
    public function test_contact_page_form_validation_invalid_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->type('nama', 'John Doe')
                ->type('email', 'invalid-email')
                ->type('pesan', 'Test message')
                ->press('Kirim Pesan')
                ->waitFor('.invalid-feedback', 3)
                ->screenshot('contact_form_validation_invalid_email');
        });
    }

    /**
     * Test contact page navigation breadcrumb
     */
    public function test_contact_page_breadcrumb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertSee('Beranda')
                ->assertSee('Kontak')
                ->assertPresent('.breadcrumb')
                ->screenshot('contact_breadcrumb');
        });
    }

    /**
     * Test contact page responsive design on mobile
     */
    public function test_contact_page_responsive_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertSee('Kontak Kami')
                ->assertPresent('form')
                ->screenshot('contact_mobile_responsive');
        });
    }

    /**
     * Test contact page responsive design on tablet
     */
    public function test_contact_page_responsive_tablet()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(768, 1024) // iPad dimensions
                ->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertSee('Kontak Kami')
                ->assertPresent('form')
                ->screenshot('contact_tablet_responsive');
        });
    }

    /**
     * Test contact page map display (if available)
     */
    public function test_contact_page_map_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->whenAvailable('.map, #map', function ($map) {
                    $map->assertPresent('iframe, .leaflet-container');
                })
                ->screenshot('contact_map_display');
        });
    }

    /**
     * Test contact page office hours display
     */
    public function test_contact_page_office_hours()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertSee('Jam Operasional')
                ->screenshot('contact_office_hours');
        });
    }

    /**
     * Test contact page phone number clickable
     */
    public function test_contact_page_phone_clickable()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->whenAvailable('a[href*="tel:"]', function ($phone) {
                    $phone->assertSee('(0984) 21234');
                })
                ->screenshot('contact_phone_clickable');
        });
    }

    /**
     * Test contact page email clickable
     */
    public function test_contact_page_email_clickable()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->whenAvailable('a[href*="mailto:"]', function ($email) {
                    $email->assertSee('inspektorat@paputengah.go.id');
                })
                ->screenshot('contact_email_clickable');
        });
    }

    /**
     * Test contact page social media links (if available)
     */
    public function test_contact_page_social_media_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->whenAvailable('.social-media, .social-links', function ($social) {
                    $social->assertPresent('a');
                })
                ->screenshot('contact_social_media_links');
        });
    }

    /**
     * Test contact page form reset functionality
     */
    public function test_contact_page_form_reset()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->type('nama', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('pesan', 'Test message')
                ->whenAvailable('button[type="reset"]', function ($reset) {
                    $reset->click();
                })
                ->assertInputValue('nama', '')
                ->assertInputValue('email', '')
                ->assertInputValue('pesan', '')
                ->screenshot('contact_form_reset');
        });
    }

    /**
     * Test contact page SEO elements
     */
    public function test_contact_page_seo_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertTitleContains('Kontak Kami')
                ->assertTitleContains('Portal Inspektorat Papua Tengah')
                ->screenshot('contact_seo_check');
        });
    }

    /**
     * Test contact page performance
     */
    public function test_contact_page_performance()
    {
        $startTime = microtime(true);
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami');
        });
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        // Assert page loads within 10 seconds
        $this->assertLessThan(10, $loadTime, 'Contact page should load within 10 seconds');
    }

    /**
     * Test contact page back to homepage
     */
    public function test_contact_page_back_to_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->click('.breadcrumb-item a')
                ->waitForText('Inspektorat Provinsi')
                ->assertPathIs('/')
                ->screenshot('contact_back_to_homepage');
        });
    }

    /**
     * Test contact page footer links
     */
    public function test_contact_page_footer_links()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->scrollIntoView('footer')
                ->assertSee('Layanan')
                ->assertSee('Informasi')
                ->assertSee('Kontak')
                ->screenshot('contact_footer_links');
        });
    }

    /**
     * Test contact page navigation menu consistency
     */
    public function test_contact_page_navigation_consistency()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertPresent('nav')
                ->screenshot('contact_navigation_consistency');
        });
    }

    /**
     * Test contact page additional contact methods
     */
    public function test_contact_page_additional_contact_methods()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->whenAvailable('.contact-methods, .cara-kontak', function ($methods) {
                    $methods->assertPresent('.phone, .email, .address');
                })
                ->screenshot('contact_additional_methods');
        });
    }

    /**
     * Test contact page address display
     */
    public function test_contact_page_address_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak Kami')
                ->assertSee('Alamat')
                ->screenshot('contact_address_display');
        });
    }

    /**
     * Test contact page form character limits
     */
    public function test_contact_page_form_character_limits()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->type('nama', str_repeat('a', 300)) // Test name length
                ->type('pesan', str_repeat('b', 3000)) // Test message length
                ->press('Kirim')
                ->waitFor('body', 3)
                ->screenshot('contact_form_character_limits');
        });
    }
}
