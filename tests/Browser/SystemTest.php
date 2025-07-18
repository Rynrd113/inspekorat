<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SystemTest extends DuskTestCase
{
    /**
     * Test complete system navigation flow
     */
    public function test_complete_system_navigation_flow()
    {
        $this->browse(function (Browser $browser) {
            // Start from homepage
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->screenshot('system_homepage');

            // Navigate to Profile
            $browser->click('a[href*="profil"]')
                ->waitForText('Profil')
                ->screenshot('system_profile');

            // Navigate to News
            $browser->click('a[href*="berita"]')
                ->waitForText('Berita')
                ->screenshot('system_news');

            // Navigate to Services
            $browser->click('a[href*="pelayanan"]')
                ->waitForText('Pelayanan')
                ->screenshot('system_services');

            // Navigate to Documents
            $browser->click('a[href*="dokumen"]')
                ->waitForText('Dokumen')
                ->screenshot('system_documents');

            // Navigate to Gallery
            $browser->click('a[href*="galeri"]')
                ->waitForText('Galeri')
                ->screenshot('system_gallery');

            // Navigate to FAQ
            $browser->click('a[href*="faq"]')
                ->waitForText('FAQ')
                ->screenshot('system_faq');

            // Navigate to Contact
            $browser->click('a[href*="kontak"]')
                ->waitForText('Kontak')
                ->screenshot('system_contact');

            // Navigate to WBS
            $browser->click('a[href*="wbs"]')
                ->waitForText('WBS')
                ->screenshot('system_wbs');

            // Navigate to Portal OPD
            $browser->click('a[href*="portal-opd"]')
                ->waitForText('Portal OPD')
                ->screenshot('system_portal_opd');

            // Return to homepage
            $browser->click('a[href="/"]')
                ->waitForText('Statistik Portal Papua Tengah')
                ->screenshot('system_return_home');
        });
    }

    /**
     * Test all public pages accessibility
     */
    public function test_all_public_pages_accessibility()
    {
        $pages = [
            '/' => 'Inspektorat Provinsi Papua Tengah',
            '/profil' => 'Profil',
            '/berita' => 'Berita',
            '/pelayanan' => 'Pelayanan',
            '/dokumen' => 'Dokumen',
            '/galeri' => 'Galeri',
            '/faq' => 'FAQ',
            '/kontak' => 'Kontak',
            '/wbs' => 'WBS',
            '/portal-opd' => 'Portal OPD'
        ];

        foreach ($pages as $url => $expectedText) {
            $this->browse(function (Browser $browser) use ($url, $expectedText) {
                $browser->visit($url)
                    ->waitForText($expectedText, 10)
                    ->assertSee($expectedText)
                    ->screenshot('system_accessibility_' . str_replace('/', '_', $url));
            });
        }
    }

    /**
     * Test responsive design across all pages
     */
    public function test_responsive_design_all_pages()
    {
        $pages = ['/', '/profil', '/berita', '/pelayanan', '/dokumen', '/galeri', '/faq', '/kontak', '/wbs', '/portal-opd'];
        $devices = [
            'mobile' => [375, 667],
            'tablet' => [768, 1024],
            'desktop' => [1920, 1080]
        ];

        foreach ($devices as $device => $dimensions) {
            foreach ($pages as $page) {
                $this->browse(function (Browser $browser) use ($page, $device, $dimensions) {
                    $browser->resize($dimensions[0], $dimensions[1])
                        ->visit($page)
                        ->waitFor('body', 10)
                        ->screenshot('system_responsive_' . $device . '_' . str_replace('/', '_', $page));
                });
            }
        }
    }

    /**
     * Test navigation menu consistency across all pages
     */
    public function test_navigation_menu_consistency()
    {
        $pages = ['/', '/profil', '/berita', '/pelayanan', '/dokumen', '/galeri', '/faq', '/kontak', '/wbs', '/portal-opd'];
        $expectedMenuItems = ['Beranda', 'Profil', 'Berita', 'Pelayanan', 'Dokumen', 'Galeri', 'FAQ', 'Kontak', 'WBS', 'Portal OPD'];

        foreach ($pages as $page) {
            $this->browse(function (Browser $browser) use ($page, $expectedMenuItems) {
                $browser->visit($page)
                    ->waitFor('body', 10);

                foreach ($expectedMenuItems as $menuItem) {
                    $browser->assertSee($menuItem);
                }

                $browser->screenshot('system_navigation_consistency_' . str_replace('/', '_', $page));
            });
        }
    }

    /**
     * Test footer consistency across all pages
     */
    public function test_footer_consistency()
    {
        $pages = ['/', '/profil', '/berita', '/pelayanan', '/dokumen', '/galeri', '/faq', '/kontak', '/wbs', '/portal-opd'];
        $expectedFooterItems = ['Layanan', 'Informasi', 'Kontak'];

        foreach ($pages as $page) {
            $this->browse(function (Browser $browser) use ($page, $expectedFooterItems) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->scrollIntoView('footer');

                foreach ($expectedFooterItems as $footerItem) {
                    $browser->assertSee($footerItem);
                }

                $browser->screenshot('system_footer_consistency_' . str_replace('/', '_', $page));
            });
        }
    }

    /**
     * Test search functionality across applicable pages
     */
    public function test_search_functionality_across_pages()
    {
        $searchPages = [
            '/berita' => 'inspektorat',
            '/dokumen' => 'peraturan',
            '/faq' => 'layanan',
            '/portal-opd' => 'inspektorat'
        ];

        foreach ($searchPages as $page => $searchTerm) {
            $this->browse(function (Browser $browser) use ($page, $searchTerm) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->whenAvailable('input[name="search"]', function ($input) use ($searchTerm) {
                        $input->type($searchTerm)
                            ->press('Enter');
                    })
                    ->waitFor('body', 5)
                    ->screenshot('system_search_' . str_replace('/', '_', $page));
            });
        }
    }

    /**
     * Test contact information consistency
     */
    public function test_contact_information_consistency()
    {
        $expectedContactInfo = [
            'Jl. Raya Nabire No. 123',
            '(0984) 21234',
            'inspektorat@paputengah.go.id'
        ];

        $this->browse(function (Browser $browser) use ($expectedContactInfo) {
            // Test homepage contact info
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->scrollIntoView('footer');

            foreach ($expectedContactInfo as $info) {
                $browser->assertSee($info);
            }

            // Test contact page info
            $browser->visit('/kontak')
                ->waitForText('Kontak');

            foreach ($expectedContactInfo as $info) {
                $browser->assertSee($info);
            }

            $browser->screenshot('system_contact_consistency');
        });
    }

    /**
     * Test page load performance
     */
    public function test_page_load_performance()
    {
        $pages = ['/', '/profil', '/berita', '/pelayanan', '/dokumen', '/galeri', '/faq', '/kontak', '/wbs', '/portal-opd'];
        
        foreach ($pages as $page) {
            $startTime = microtime(true);
            
            $this->browse(function (Browser $browser) use ($page) {
                $browser->visit($page)
                    ->waitFor('body', 10);
            });
            
            $endTime = microtime(true);
            $loadTime = $endTime - $startTime;
            
            // Assert page loads within 5 seconds
            $this->assertLessThan(5, $loadTime, "Page $page should load within 5 seconds");
        }
    }

    /**
     * Test form functionality across applicable pages
     */
    public function test_form_functionality_across_pages()
    {
        // Test contact form
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->type('nama', 'Test User')
                ->type('email', 'test@example.com')
                ->type('pesan', 'Test message for system testing')
                ->press('Kirim')
                ->waitFor('body', 5)
                ->screenshot('system_contact_form');
        });

        // Test WBS form
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                ->waitForText('WBS')
                ->type('judul', 'Test WBS Report')
                ->type('isi', 'Test WBS content for system testing')
                ->select('kategori', 'korupsi')
                ->type('nama_pelapor', 'Test Reporter')
                ->type('email', 'reporter@example.com')
                ->press('Kirim Laporan')
                ->waitFor('body', 5)
                ->screenshot('system_wbs_form');
        });
    }

    /**
     * Test breadcrumb navigation
     */
    public function test_breadcrumb_navigation()
    {
        $pages = [
            '/profil' => ['Beranda', 'Profil'],
            '/berita' => ['Beranda', 'Berita'],
            '/pelayanan' => ['Beranda', 'Pelayanan'],
            '/dokumen' => ['Beranda', 'Dokumen'],
            '/galeri' => ['Beranda', 'Galeri'],
            '/faq' => ['Beranda', 'FAQ'],
            '/kontak' => ['Beranda', 'Kontak'],
            '/wbs' => ['Beranda', 'WBS'],
            '/portal-opd' => ['Beranda', 'Portal OPD']
        ];

        foreach ($pages as $page => $breadcrumbs) {
            $this->browse(function (Browser $browser) use ($page, $breadcrumbs) {
                $browser->visit($page)
                    ->waitFor('body', 10);

                foreach ($breadcrumbs as $breadcrumb) {
                    $browser->assertSee($breadcrumb);
                }

                $browser->screenshot('system_breadcrumb_' . str_replace('/', '_', $page));
            });
        }
    }

    /**
     * Test external links functionality
     */
    public function test_external_links_functionality()
    {
        $this->browse(function (Browser $browser) {
            // Test external links in Portal OPD
            $browser->visit('/portal-opd')
                ->waitForText('Portal OPD')
                ->whenAvailable('a[href*="http"]:not([href*="' . config('app.url') . '"])', function ($link) {
                    $link->assertAttribute('target', '_blank');
                })
                ->screenshot('system_external_links');
        });
    }

    /**
     * Test 404 error handling
     */
    public function test_404_error_handling()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/nonexistent-page')
                ->waitFor('body', 5)
                ->screenshot('system_404_error');
        });
    }

    /**
     * Test JavaScript functionality
     */
    public function test_javascript_functionality()
    {
        $this->browse(function (Browser $browser) {
            // Test accordion functionality in FAQ
            $browser->visit('/faq')
                ->waitForText('FAQ')
                ->whenAvailable('.faq-item:first-child .question', function ($question) {
                    $question->click();
                })
                ->waitFor('.answer', 3)
                ->screenshot('system_javascript_accordion');

            // Test modal functionality (if available)
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('.gallery-item:first-child img', function ($img) {
                    $img->click();
                })
                ->waitFor('.modal', 3)
                ->screenshot('system_javascript_modal');
        });
    }

    /**
     * Test meta tags and SEO elements
     */
    public function test_meta_tags_and_seo()
    {
        $pages = [
            '/' => 'Inspektorat',
            '/profil' => 'Profil',
            '/berita' => 'Berita',
            '/pelayanan' => 'Pelayanan',
            '/dokumen' => 'Dokumen',
            '/galeri' => 'Galeri',
            '/faq' => 'FAQ',
            '/kontak' => 'Kontak',
            '/wbs' => 'WBS',
            '/portal-opd' => 'Portal OPD'
        ];

        foreach ($pages as $page => $expectedTitleContent) {
            $this->browse(function (Browser $browser) use ($page, $expectedTitleContent) {
                $browser->visit($page)
                    ->waitFor('body', 10)
                    ->assertTitleContains($expectedTitleContent)
                    ->screenshot('system_seo_' . str_replace('/', '_', $page));
            });
        }
    }

    /**
     * Test image loading and optimization
     */
    public function test_image_loading_optimization()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/galeri')
                ->waitForText('Galeri')
                ->whenAvailable('img', function ($img) {
                    $img->assertAttribute('alt')
                        ->assertAttribute('loading', 'lazy');
                })
                ->screenshot('system_image_optimization');
        });
    }

    /**
     * Test accessibility features
     */
    public function test_accessibility_features()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Inspektorat Provinsi Papua Tengah')
                ->assertPresent('main')
                ->assertPresent('nav')
                ->assertPresent('footer')
                ->whenAvailable('img', function ($img) {
                    $img->assertAttribute('alt');
                })
                ->screenshot('system_accessibility_features');
        });
    }

    /**
     * Test content security and validation
     */
    public function test_content_security_validation()
    {
        $this->browse(function (Browser $browser) {
            // Test XSS prevention in forms
            $browser->visit('/kontak')
                ->waitForText('Kontak')
                ->type('nama', '<script>alert("XSS")</script>')
                ->type('email', 'test@example.com')
                ->type('pesan', 'Test message')
                ->press('Kirim')
                ->waitFor('body', 5)
                ->screenshot('system_xss_prevention');
        });
    }

    /**
     * Test API endpoints availability
     */
    public function test_api_endpoints_availability()
    {
        $this->browse(function (Browser $browser) {
            // Test if API routes are not accessible without proper authentication
            $browser->visit('/api/users')
                ->waitFor('body', 5)
                ->screenshot('system_api_security');
        });
    }
}
