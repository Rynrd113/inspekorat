<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\PortalPapuaTengah;
use App\Models\Wbs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuditLogTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'superadmin@inspektorat.id'
        ]);
        
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@inspektorat.id'
        ]);
        
        $this->createTestAuditLogData();
    }

    private function createTestAuditLogData()
    {
        // Create some test models to generate audit logs
        $berita = PortalPapuaTengah::create([
            'judul' => 'Test Berita untuk Audit',
            'slug' => 'test-berita-audit',
            'konten' => 'Konten test untuk audit log',
            'penulis' => $this->admin->name,
            'kategori' => 'berita',
            'is_published' => true,
            'created_by' => $this->admin->id
        ]);

        $wbs = Wbs::create([
            'nama_pelapor' => 'Test Reporter',
            'email' => 'test@example.com',
            'subjek' => 'Test WBS untuk Audit',
            'deskripsi' => 'Deskripsi test WBS untuk audit log',
            'status' => 'pending'
        ]);

        // Generate audit logs manually for testing
        $actions = ['created', 'updated', 'deleted', 'viewed', 'approved', 'rejected'];
        $models = [
            ['type' => PortalPapuaTengah::class, 'id' => $berita->id],
            ['type' => Wbs::class, 'id' => $wbs->id],
            ['type' => User::class, 'id' => $this->admin->id]
        ];

        for ($i = 1; $i <= 20; $i++) {
            $model = $models[($i - 1) % 3];
            $action = $actions[($i - 1) % 6];
            $user = ($i % 2 === 0) ? $this->admin : $this->superAdmin;

            AuditLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'model_type' => $model['type'],
                'model_id' => $model['id'],
                'old_values' => ($action === 'updated') ? ['status' => 'draft'] : null,
                'new_values' => ($action === 'updated') ? ['status' => 'published'] : null,
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 Test Browser',
                'created_at' => now()->subHours(rand(1, 168)) // Random time in last week
            ]);
        }
    }

    /**
     * Test Audit Log index page
     */
    public function testAuditLogIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('Log Audit')
                ->assertSee('User')
                ->assertSee('Aksi')
                ->assertSee('Model')
                ->assertSee('Waktu')
                ->screenshot('admin_audit_log_index');
        });
    }

    /**
     * Test Audit Log pagination
     */
    public function testAuditLogPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('created')
                ->assertSee('updated')
                ->assertVisible('.pagination')
                ->screenshot('admin_audit_log_pagination');
        });
    }

    /**
     * Test Audit Log filter by user
     */
    public function testAuditLogUserFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->select('user_id', $this->admin->id)
                ->press('Filter')
                ->pause(1000)
                ->assertSee($this->admin->email)
                ->screenshot('admin_audit_log_user_filter');
        });
    }

    /**
     * Test Audit Log filter by action
     */
    public function testAuditLogActionFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->select('action', 'created')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Dibuat')
                ->screenshot('admin_audit_log_action_filter');
        });
    }

    /**
     * Test Audit Log filter by model type
     */
    public function testAuditLogModelFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->select('model_type', 'App\Models\PortalPapuaTengah')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('PortalPapuaTengah')
                ->screenshot('admin_audit_log_model_filter');
        });
    }

    /**
     * Test Audit Log date range filter
     */
    public function testAuditLogDateFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->type('date_from', now()->subDays(7)->format('Y-m-d'))
                ->type('date_to', now()->format('Y-m-d'))
                ->press('Filter')
                ->pause(1000)
                ->screenshot('admin_audit_log_date_filter');
        });
    }

    /**
     * Test Audit Log show detail
     */
    public function testAuditLogShowDetail()
    {
        $auditLog = AuditLog::where('action', 'updated')->first();
        
        $this->browse(function (Browser $browser) use ($auditLog) {
            $browser->loginAs($this->superAdmin)
                ->visit("/admin/audit-logs/{$auditLog->id}")
                ->assertSee('Detail Log Audit')
                ->assertSee('Perubahan Data')
                ->assertSee('IP Address')
                ->assertSee('User Agent')
                ->screenshot('admin_audit_log_detail');
        });
    }

    /**
     * Test Audit Log export functionality
     */
    public function testAuditLogExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->press('Export Log')
                ->pause(2000)
                ->screenshot('admin_audit_log_export');
        });
    }

    /**
     * Test Audit Log statistics
     */
    public function testAuditLogStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs/stats')
                ->assertSee('Statistik Log Audit')
                ->assertSee('Total Aktivitas')
                ->assertSee('Aktivitas Hari Ini')
                ->assertSee('Top Users')
                ->assertSee('Top Actions')
                ->screenshot('admin_audit_log_statistics');
        });
    }

    /**
     * Test Audit Log search functionality
     */
    public function testAuditLogSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->type('search', 'Test Berita')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Test Berita')
                ->screenshot('admin_audit_log_search');
        });
    }

    /**
     * Test Audit Log automatic generation
     */
    public function testAuditLogAutomaticGeneration()
    {
        $this->browse(function (Browser $browser) {
            // Perform an action that should generate audit log
            $browser->loginAs($this->admin)
                ->visit('/admin/portal-papua-tengah/create')
                ->type('judul', 'Berita untuk Test Audit Log')
                ->type('slug', 'berita-test-audit-log')
                ->type('konten', 'Konten berita untuk testing audit log')
                ->select('kategori', 'berita')
                ->type('penulis', $this->admin->name)
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Berita berhasil ditambahkan')
                ->screenshot('audit_log_generation_create');

            // Check if audit log was created
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('Berita untuk Test Audit Log')
                ->assertSee('created')
                ->screenshot('audit_log_generation_verify');
        });
    }

    /**
     * Test Audit Log access control (only super admin)
     */
    public function testAuditLogAccessControl()
    {
        $this->browse(function (Browser $browser) {
            // Regular admin should not access audit logs
            $browser->loginAs($this->admin)
                ->visit('/admin/audit-logs')
                ->assertSee('403')
                ->screenshot('audit_log_access_denied');

            // Super admin should access audit logs
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('Log Audit')
                ->assertDontSee('403')
                ->screenshot('audit_log_access_granted');
        });
    }

    /**
     * Test Audit Log bulk delete (admin function)
     */
    public function testAuditLogBulkDelete()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->check('select_all')
                ->press('Hapus Terpilih')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Ya, Hapus');
                })
                ->pause(2000)
                ->assertSee('Log audit berhasil dihapus')
                ->screenshot('audit_log_bulk_delete');
        });
    }

    /**
     * Test Audit Log performance monitoring
     */
    public function testAuditLogPerformanceMonitoring()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/audit-logs')
                ->assertSee('Log Audit')
                ->assertPresent('table')
                ->screenshot('audit_log_performance');
        });
    }
}
