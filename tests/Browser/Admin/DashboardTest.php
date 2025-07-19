<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Wbs;
use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\InfoKantor;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;
    protected $admin;
    protected $adminWbs;
    protected $adminBerita;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users with different roles
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@inspektorat.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->adminWbs = User::create([
            'name' => 'Admin WBS',
            'email' => 'admin.wbs@inspektorat.id',
            'password' => bcrypt('adminwbs123'),
            'role' => 'admin_wbs',
            'is_active' => true,
        ]);

        $this->adminBerita = User::create([
            'name' => 'Admin Berita',
            'email' => 'admin.berita@inspektorat.id',
            'password' => bcrypt('adminberita123'),
            'role' => 'admin_berita',
            'is_active' => true,
        ]);

        // Create test data
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create WBS data
        Wbs::create([
            'nama_pelapor' => 'Test Reporter 1',
            'email' => 'reporter1@test.com',
            'telepon' => '081234567890',
            'subjek' => 'Test WBS Subject 1',
            'isi_laporan' => 'Test WBS Content 1',
            'status' => 'pending',
            'created_by' => $this->adminWbs->id,
        ]);

        Wbs::create([
            'nama_pelapor' => 'Test Reporter 2',
            'email' => 'reporter2@test.com',
            'telepon' => '081234567891',
            'subjek' => 'Test WBS Subject 2',
            'isi_laporan' => 'Test WBS Content 2',
            'status' => 'resolved',
            'created_by' => $this->adminWbs->id,
        ]);

        // Create Portal Papua Tengah data
        PortalPapuaTengah::create([
            'judul' => 'Test News 1',
            'slug' => 'test-news-1',
            'konten' => 'Test news content 1',
            'kategori' => 'berita',
            'is_published' => true,
            'published_at' => now(),
            'penulis' => 'Admin Test',
            'created_by' => $this->adminBerita->id,
        ]);

        PortalPapuaTengah::create([
            'judul' => 'Test News 2',
            'slug' => 'test-news-2',
            'konten' => 'Test news content 2',
            'kategori' => 'pengumuman',
            'is_published' => false,
            'penulis' => 'Admin Test',
            'created_by' => $this->adminBerita->id,
        ]);

        // Create Portal OPD data
        PortalOpd::create([
            'nama_opd' => 'Test OPD 1',
            'singkatan' => 'TOP1',
            'deskripsi' => 'Test OPD Description 1',
            'alamat' => 'Test Address 1',
            'telepon' => '021123456789',
            'email' => 'testopd1@test.com',
            'website' => 'https://testopd1.com',
            'status' => true,
            'created_by' => $this->admin->id,
        ]);

        // Create Info Kantor data
        InfoKantor::create([
            'judul' => 'Test Info Kantor 1',
            'konten' => 'Test info kantor content 1',
            'kategori' => 'informasi',
            'status' => true,
            'created_by' => $this->admin->id,
        ]);
    }

    /**
     * Test dashboard access with different roles
     */
    public function testDashboardAccessWithDifferentRoles()
    {
        // Test super admin access
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Selamat Datang, Super Admin')
                ->assertSee('Dashboard')
                ->assertSee('Kelola konten dan sistem Portal Inspektorat Papua Tengah');
        });

        // Test admin access
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/dashboard')
                ->assertSee('Selamat Datang, Admin')
                ->assertSee('Dashboard');
        });

        // Test role-specific admin access
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminWbs)
                ->visit('/admin/dashboard')
                ->assertSee('Selamat Datang, Admin WBS')
                ->assertSee('Dashboard');
        });
    }

    /**
     * Test dashboard statistics display
     */
    public function testDashboardStatisticsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Laporan WBS')
                ->assertSee('Berita')
                ->assertSee('Portal OPD')
                ->assertSee('Total Users')
                ->within('.grid', function ($browser) {
                    $browser->assertSee('2') // WBS count
                        ->assertSee('2') // News count
                        ->assertSee('1') // Portal OPD count
                        ->assertSee('4'); // Users count
                });
        });
    }

    /**
     * Test WBS statistics for WBS admin
     */
    public function testWbsStatisticsForWbsAdmin()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminWbs)
                ->visit('/admin/dashboard')
                ->assertSee('Laporan WBS')
                ->assertSee('2') // Total WBS
                ->assertSee('1 pending') // Pending count
                ->assertDontSee('Total Users'); // Should not see user management
        });
    }

    /**
     * Test news statistics for news admin
     */
    public function testNewsStatisticsForNewsAdmin()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminBerita)
                ->visit('/admin/dashboard')
                ->assertSee('Berita')
                ->assertSee('2') // Total news
                ->assertSee('1 published') // Published count
                ->assertDontSee('Total Users'); // Should not see user management
        });
    }

    /**
     * Test quick actions section
     */
    public function testQuickActionsSection()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Aksi Cepat')
                ->assertSee('Kelola WBS')
                ->assertSee('Portal Papua Tengah')
                ->assertSee('Portal OPD')
                ->assertSee('FAQ')
                ->assertSee('Pelayanan')
                ->assertSee('Dokumen')
                ->assertSee('Galeri');
        });
    }

    /**
     * Test navigation to different modules from dashboard
     */
    public function testNavigationToModulesFromDashboard()
    {
        $this->browse(function (Browser $browser) {
            // Test WBS navigation
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->click('a[href*="admin/wbs"]')
                ->assertUrlContains('/admin/wbs')
                ->back();

            // Test news navigation
            $browser->click('a[href*="admin/portal-papua-tengah"]')
                ->assertUrlContains('/admin/portal-papua-tengah')
                ->back();

            // Test Portal OPD navigation
            $browser->click('a[href*="admin/portal-opd"]')
                ->assertUrlContains('/admin/portal-opd')
                ->back();
        });
    }

    /**
     * Test recent activity section
     */
    public function testRecentActivitySection()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Aktivitas Terkini')
                ->assertSee('Laporan WBS Terbaru')
                ->assertSee('Test WBS Subject 1')
                ->assertSee('Test WBS Subject 2')
                ->assertSee('Berita Terbaru')
                ->assertSee('Test News 1')
                ->assertSee('Test News 2');
        });
    }

    /**
     * Test management section for super admin
     */
    public function testManagementSectionForSuperAdmin()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Manajemen Sistem')
                ->assertSee('Manajemen User')
                ->assertSee('Konfigurasi Sistem')
                ->assertSee('Audit Log');
        });
    }

    /**
     * Test management section is hidden for non-super admin
     */
    public function testManagementSectionHiddenForNonSuperAdmin()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminWbs)
                ->visit('/admin/dashboard')
                ->assertDontSee('Manajemen Sistem')
                ->assertDontSee('Manajemen User')
                ->assertDontSee('Konfigurasi Sistem');
        });
    }

    /**
     * Test dashboard responsive design
     */
    public function testDashboardResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->resize(1200, 800) // Desktop
                ->assertVisible('.grid')
                ->resize(768, 1024) // Tablet
                ->assertVisible('.grid')
                ->resize(375, 667); // Mobile
        });
    }

    /**
     * Test dashboard data refresh
     */
    public function testDashboardDataRefresh()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('2'); // Initial WBS count

            // Create new WBS
            Wbs::create([
                'nama_pelapor' => 'New Reporter',
                'email' => 'newreporter@test.com',
                'telepon' => '081234567892',
                'subjek' => 'New WBS Subject',
                'isi_laporan' => 'New WBS Content',
                'status' => 'pending',
                'created_by' => $this->adminWbs->id,
            ]);

            $browser->refresh()
                ->assertSee('3'); // Updated WBS count
        });
    }

    /**
     * Test dashboard error handling
     */
    public function testDashboardErrorHandling()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Dashboard')
                ->assertMissing('.error-message'); // No errors should be visible
        });
    }

    /**
     * Test dashboard performance
     */
    public function testDashboardPerformance()
    {
        $this->browse(function (Browser $browser) {
            $start = microtime(true);
            
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Dashboard');
            
            $end = microtime(true);
            $loadTime = $end - $start;
            
            // Dashboard should load within 3 seconds
            $this->assertLessThan(3.0, $loadTime, 'Dashboard took too long to load');
        });
    }

    /**
     * Test dashboard role-based content visibility
     */
    public function testDashboardRoleBasedContentVisibility()
    {
        // Test admin_wbs role - should only see WBS related content
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminWbs)
                ->visit('/admin/dashboard')
                ->assertSee('Laporan WBS')
                ->assertSee('Kelola WBS')
                ->assertDontSee('Portal Papua Tengah')
                ->assertDontSee('Portal OPD')
                ->assertDontSee('Total Users');
        });

        // Test admin_berita role - should only see news related content
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminBerita)
                ->visit('/admin/dashboard')
                ->assertSee('Berita')
                ->assertSee('Portal Papua Tengah')
                ->assertDontSee('Laporan WBS')
                ->assertDontSee('Portal OPD')
                ->assertDontSee('Total Users');
        });
    }

    /**
     * Test dashboard links functionality
     */
    public function testDashboardLinksFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard');

            // Test all quick action links
            $links = [
                'admin/wbs' => 'WBS',
                'admin/portal-papua-tengah' => 'Portal Papua Tengah',
                'admin/portal-opd' => 'Portal OPD',
                'admin/users' => 'Users',
                'admin/faq' => 'FAQ',
                'admin/pelayanan' => 'Pelayanan',
                'admin/dokumen' => 'Dokumen',
                'admin/galeri' => 'Galeri'
            ];

            foreach ($links as $url => $name) {
                $browser->visit('/admin/dashboard')
                    ->clickLink($name)
                    ->assertUrlContains($url)
                    ->assertDontSee('404')
                    ->assertDontSee('Error');
            }
        });
    }

    /**
     * Test dashboard with empty data
     */
    public function testDashboardWithEmptyData()
    {
        // Clear all test data
        Wbs::truncate();
        PortalPapuaTengah::truncate();
        PortalOpd::truncate();
        InfoKantor::truncate();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('0') // Should show zero counts
                ->assertSee('Dashboard')
                ->assertDontSee('Error');
        });
    }

    /**
     * Test dashboard search functionality if available
     */
    public function testDashboardSearchFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard');

            // Check if search is available on dashboard
            if ($browser->element('input[type="search"]')) {
                $browser->type('input[type="search"]', 'Test WBS')
                    ->pause(1000)
                    ->assertSee('Test WBS Subject 1');
            }
        });
    }

    /**
     * Test dashboard logout functionality
     */
    public function testDashboardLogoutFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/dashboard')
                ->assertSee('Dashboard')
                ->click('button[onclick*="logout"], a[href*="logout"], form[action*="logout"] button')
                ->assertUrlContains('/admin/login')
                ->assertSee('Login');
        });
    }
}
