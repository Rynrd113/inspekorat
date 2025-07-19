<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\SystemConfiguration;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SystemConfigurationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'superadmin@inspektorat.id'
        ]);
        
        $this->createTestSystemConfigurationData();
    }

    private function createTestSystemConfigurationData()
    {
        $configurations = [
            [
                'key' => 'app_name',
                'value' => 'Portal Inspektorat Papua Tengah',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama aplikasi yang ditampilkan di header',
                'is_public' => true
            ],
            [
                'key' => 'app_logo',
                'value' => 'logo.png',
                'type' => 'image',
                'group' => 'general',
                'description' => 'Logo aplikasi',
                'is_public' => true
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@inspektorat.paputengah.go.id',
                'type' => 'email',
                'group' => 'contact',
                'description' => 'Email kontak utama',
                'is_public' => true
            ],
            [
                'key' => 'contact_phone',
                'value' => '(0984) 21234',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Nomor telepon kontak',
                'is_public' => true
            ],
            [
                'key' => 'office_address',
                'value' => 'Jl. Raya Nabire No. 123, Nabire, Papua Tengah',
                'type' => 'textarea',
                'group' => 'contact',
                'description' => 'Alamat kantor',
                'is_public' => true
            ],
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'type' => 'text',
                'group' => 'email',
                'description' => 'SMTP server host',
                'is_public' => false
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'type' => 'number',
                'group' => 'email',
                'description' => 'SMTP server port',
                'is_public' => false
            ],
            [
                'key' => 'enable_maintenance',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Mode maintenance',
                'is_public' => false
            ],
            [
                'key' => 'max_file_upload',
                'value' => '10240',
                'type' => 'number',
                'group' => 'system',
                'description' => 'Maksimal ukuran file upload (KB)',
                'is_public' => false
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/inspektorat.paputengah',
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL Facebook',
                'is_public' => true
            ]
        ];

        foreach ($configurations as $config) {
            SystemConfiguration::create($config);
        }
    }

    /**
     * Test System Configuration index page
     */
    public function testSystemConfigurationIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->assertSee('Konfigurasi Sistem')
                ->assertSee('app_name')
                ->assertSee('Portal Inspektorat Papua Tengah')
                ->screenshot('admin_system_config_index');
        });
    }

    /**
     * Test System Configuration group filtering
     */
    public function testSystemConfigurationGroupFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->select('group', 'general')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('app_name')
                ->assertSee('app_logo')
                ->screenshot('admin_system_config_group_filter');
        });
    }

    /**
     * Test System Configuration update text value
     */
    public function testSystemConfigurationUpdateText()
    {
        $config = SystemConfiguration::where('key', 'app_name')->first();
        
        $this->browse(function (Browser $browser) use ($config) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->click("button[data-id='{$config->id}']")
                ->whenAvailable('.modal', function ($modal) {
                    $modal->clear('value')
                        ->type('value', 'Portal Inspektorat Papua Tengah - Updated')
                        ->press('Update');
                })
                ->pause(2000)
                ->assertSee('Konfigurasi berhasil diperbarui')
                ->assertSee('Portal Inspektorat Papua Tengah - Updated')
                ->screenshot('admin_system_config_update_text');
        });
    }

    /**
     * Test System Configuration update boolean value
     */
    public function testSystemConfigurationUpdateBoolean()
    {
        $config = SystemConfiguration::where('key', 'enable_maintenance')->first();
        
        $this->browse(function (Browser $browser) use ($config) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->click("button[data-id='{$config->id}']")
                ->whenAvailable('.modal', function ($modal) {
                    $modal->check('value')
                        ->press('Update');
                })
                ->pause(2000)
                ->assertSee('Konfigurasi berhasil diperbarui')
                ->screenshot('admin_system_config_update_boolean');
        });
    }

    /**
     * Test System Configuration add new configuration
     */
    public function testSystemConfigurationStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->press('Tambah Konfigurasi')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->type('key', 'new_config_key')
                        ->type('value', 'New Configuration Value')
                        ->select('type', 'text')
                        ->select('group', 'general')
                        ->type('description', 'Konfigurasi baru untuk testing')
                        ->check('is_public')
                        ->press('Simpan');
                })
                ->pause(2000)
                ->assertSee('Konfigurasi berhasil ditambahkan')
                ->assertSee('new_config_key')
                ->screenshot('admin_system_config_store');
        });
    }

    /**
     * Test System Configuration delete
     */
    public function testSystemConfigurationDelete()
    {
        $config = SystemConfiguration::where('key', 'social_facebook')->first();
        
        $this->browse(function (Browser $browser) use ($config) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->click("button[onclick=\"deleteConfig({$config->id})\"]")
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Ya, Hapus');
                })
                ->pause(2000)
                ->assertSee('Konfigurasi berhasil dihapus')
                ->assertDontSee('social_facebook')
                ->screenshot('admin_system_config_delete');
        });
    }

    /**
     * Test System Configuration export
     */
    public function testSystemConfigurationExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->press('Export Konfigurasi')
                ->pause(2000)
                ->screenshot('admin_system_config_export');
        });
    }

    /**
     * Test System Configuration import
     */
    public function testSystemConfigurationImport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->press('Import Konfigurasi')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->assertSee('Upload File JSON');
                })
                ->screenshot('admin_system_config_import');
        });
    }

    /**
     * Test System Configuration initialize defaults
     */
    public function testSystemConfigurationInitialize()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->press('Initialize Default')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Ya, Initialize');
                })
                ->pause(3000)
                ->assertSee('Konfigurasi default berhasil diinisialisasi')
                ->screenshot('admin_system_config_initialize');
        });
    }

    /**
     * Test System Configuration search
     */
    public function testSystemConfigurationSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->type('search', 'app_name')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('app_name')
                ->assertDontSee('smtp_host')
                ->screenshot('admin_system_config_search');
        });
    }

    /**
     * Test System Configuration validation
     */
    public function testSystemConfigurationValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->press('Tambah Konfigurasi')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Simpan');
                })
                ->pause(1000)
                ->assertSee('Key harus diisi')
                ->assertSee('Type harus diisi')
                ->assertSee('Group harus diisi')
                ->screenshot('admin_system_config_validation');
        });
    }

    /**
     * Test System Configuration type-specific inputs
     */
    public function testSystemConfigurationTypeSpecificInputs()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/configurations')
                ->press('Tambah Konfigurasi')
                ->whenAvailable('.modal', function ($modal) {
                    // Test number type
                    $modal->select('type', 'number')
                        ->assertSee('type="number"');
                    
                    // Test email type
                    $modal->select('type', 'email')
                        ->assertSee('type="email"');
                    
                    // Test url type
                    $modal->select('type', 'url')
                        ->assertSee('type="url"');
                    
                    // Test boolean type
                    $modal->select('type', 'boolean')
                        ->assertSee('type="checkbox"');
                })
                ->screenshot('admin_system_config_type_inputs');
        });
    }
}
