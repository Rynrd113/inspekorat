<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users for different roles
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@inspektorat.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Profil',
            'email' => 'admin.profil@inspektorat.id',
            'password' => bcrypt('adminprofil123'),
            'role' => 'admin_profil',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Pelayanan',
            'email' => 'admin.pelayanan@inspektorat.id',
            'password' => bcrypt('adminpelayanan123'),
            'role' => 'admin_pelayanan',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Dokumen',
            'email' => 'admin.dokumen@inspektorat.id',
            'password' => bcrypt('admindokumen123'),
            'role' => 'admin_dokumen',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Galeri',
            'email' => 'admin.galeri@inspektorat.id',
            'password' => bcrypt('admingaleri123'),
            'role' => 'admin_galeri',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin FAQ',
            'email' => 'admin.faq@inspektorat.id',
            'password' => bcrypt('adminfaq123'),
            'role' => 'admin_faq',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Berita',
            'email' => 'admin.berita@inspektorat.id',
            'password' => bcrypt('adminberita123'),
            'role' => 'admin_berita',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin WBS',
            'email' => 'admin.wbs@inspektorat.id',
            'password' => bcrypt('adminwbs123'),
            'role' => 'admin_wbs',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Portal OPD',
            'email' => 'admin.opd@inspektorat.id',
            'password' => bcrypt('adminopd123'),
            'role' => 'admin_opd',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'User Public',
            'email' => 'user.public@inspektorat.id',
            'password' => bcrypt('userpublic123'),
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    /**
     * Test admin login with valid credentials
     */
    public function testLoginWithValidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->assertSee('Login')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->type('email', 'admin@inspektorat.id')
                ->type('password', 'admin123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');
        });
    }

    /**
     * Test superadmin login
     */
    public function testSuperAdminLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'superadmin@inspektorat.id')
                ->type('password', 'superadmin123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard')
                ->assertSee('Super Admin');
        });
    }

    /**
     * Test login with invalid credentials
     */
    public function testLoginWithInvalidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin@inspektorat.id')
                ->type('password', 'wrongpassword')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/login')
                ->assertSee('These credentials do not match our records');
        });
    }

    /**
     * Test logout functionality
     */
    public function testLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin@inspektorat.id')
                ->type('password', 'admin123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->click('button[onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();"]')
                ->pause(1000)
                ->assertPathIs('/admin/login')
                ->assertSee('Login');
        });
    }

    /**
     * Test login form validation
     */
    public function testLoginFormValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->press('Login')
                ->pause(1000)
                ->assertSee('The email field is required')
                ->assertSee('The password field is required');
        });
    }

    /**
     * Test login with inactive user
     */
    public function testLoginWithInactiveUser()
    {
        // Create inactive user
        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@inspektorat.id',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'is_active' => false,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'inactive@inspektorat.id')
                ->type('password', 'password123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/login')
                ->assertSee('Your account is not active');
        });
    }

    /**
     * Test remember me functionality
     */
    public function testRememberMeFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin@inspektorat.id')
                ->type('password', 'admin123')
                ->check('remember')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');
        });
    }

    /**
     * Test password reset link
     */
    public function testPasswordResetLink()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->clickLink('Forgot Password?')
                ->pause(1000)
                ->assertPathIs('/admin/password/reset')
                ->assertSee('Reset Password');
        });
    }

    /**
     * Test responsive design on mobile
     */
    public function testLoginPageResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->visit('/admin/login')
                ->assertSee('Login')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->resize(1280, 720); // Reset to desktop size
        });
    }

    /**
     * Test login redirect after successful authentication
     */
    public function testLoginRedirectAfterAuthentication()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/users')
                ->assertPathIs('/admin/login')
                ->type('email', 'admin@inspektorat.id')
                ->type('password', 'admin123')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/users');
        });
    }
}
