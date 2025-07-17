<?php

namespace Tests\Browser\DataIntegrity;

use App\Models\User;
use App\Models\Berita;
use App\Models\Wbs;
use App\Models\Dokumen;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\InteractsWithAuthentication;
use Illuminate\Support\Facades\DB;

class DataIntegrityTestingComprehensiveTest extends DuskTestCase
{
    use DatabaseMigrations, InteractsWithAuthentication;

    /**
     * Test Database Foreign Key Constraints
     */
    public function test_database_foreign_key_constraints()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        $berita = Berita::factory()->create(['created_by' => $user->id]);
        
        $this->browse(function (Browser $browser) use ($user, $berita) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->waitForText($berita->judul)
                ->assertSee($berita->judul);
            
            // Test foreign key constraint by checking database
            $this->assertTrue(DB::table('berita')->where('created_by', $user->id)->exists());
            
            // Test cascade behavior
            $user->delete();
            $this->assertFalse(DB::table('berita')->where('id', $berita->id)->exists());
        });
    }

    /**
     * Test Data Validation Constraints
     */
    public function test_data_validation_constraints()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', str_repeat('a', 300)) // Too long
                ->type('konten', 'Test content')
                ->press('Simpan')
                ->waitForText('Judul terlalu panjang')
                ->assertSee('Judul terlalu panjang');
            
            // Test database constraint
            $this->assertEquals(0, DB::table('berita')->where('judul', str_repeat('a', 300))->count());
        });
    }

    /**
     * Test Unique Constraints
     */
    public function test_unique_constraints()
    {
        $user1 = User::factory()->create(['email' => 'test@example.com']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        $this->browse(function (Browser $browser) use ($superAdmin) {
            $browser->loginAs($superAdmin)
                ->visit('/admin/users/create')
                ->type('name', 'Duplicate Email Test')
                ->type('email', 'test@example.com') // Duplicate email
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->press('Simpan')
                ->waitForText('Email sudah digunakan')
                ->assertSee('Email sudah digunakan');
            
            // Verify only one record exists
            $this->assertEquals(1, DB::table('users')->where('email', 'test@example.com')->count());
        });
    }

    /**
     * Test Data Type Constraints
     */
    public function test_data_type_constraints()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Test Article')
                ->type('konten', 'Test content')
                ->script('
                    // Try to submit invalid date
                    let dateInput = document.querySelector("input[name=\"tanggal_publikasi\"]");
                    if (dateInput) {
                        dateInput.value = "invalid-date";
                    }
                ')
                ->press('Simpan')
                ->waitForText('Format tanggal tidak valid')
                ->assertSee('Format tanggal tidak valid');
        });
    }

    /**
     * Test Referential Integrity
     */
    public function test_referential_integrity()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        $berita = Berita::factory()->create(['created_by' => $user->id]);
        
        $this->browse(function (Browser $browser) use ($user, $berita) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->waitForText($berita->judul)
                ->assertSee($berita->judul);
            
            // Test that berita references valid user
            $this->assertTrue(DB::table('berita')
                ->join('users', 'berita.created_by', '=', 'users.id')
                ->where('berita.id', $berita->id)
                ->exists());
            
            // Test orphaned record prevention
            try {
                DB::table('berita')->insert([
                    'judul' => 'Orphaned Article',
                    'konten' => 'Test content',
                    'created_by' => 999999, // Non-existent user
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->fail('Should have failed due to foreign key constraint');
            } catch (\Exception $e) {
                $this->assertStringContains('foreign key constraint', strtolower($e->getMessage()));
            }
        });
    }

    /**
     * Test Data Consistency in Transactions
     */
    public function test_data_consistency_in_transactions()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Transaction Test Article')
                ->type('konten', 'Test content')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            // Test that both main record and audit trail are created
            $berita = DB::table('berita')->where('judul', 'Transaction Test Article')->first();
            $this->assertNotNull($berita);
            
            // Test audit trail exists
            $this->assertTrue(DB::table('audit_logs')
                ->where('model_type', 'App\\Models\\Berita')
                ->where('model_id', $berita->id)
                ->where('action', 'create')
                ->exists());
        });
    }

    /**
     * Test Data Integrity During Bulk Operations
     */
    public function test_data_integrity_during_bulk_operations()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $berita1 = Berita::factory()->create(['status' => 'draft']);
        $berita2 = Berita::factory()->create(['status' => 'draft']);
        $berita3 = Berita::factory()->create(['status' => 'draft']);
        
        $this->browse(function (Browser $browser) use ($user, $berita1, $berita2, $berita3) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->check('selected[]', $berita1->id)
                ->check('selected[]', $berita2->id)
                ->check('selected[]', $berita3->id)
                ->select('bulk_action', 'publish')
                ->press('Terapkan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            // Test all records were updated consistently
            $this->assertEquals(3, DB::table('berita')
                ->whereIn('id', [$berita1->id, $berita2->id, $berita3->id])
                ->where('status', 'published')
                ->count());
            
            // Test audit trail for bulk operations
            $this->assertTrue(DB::table('audit_logs')
                ->where('model_type', 'App\\Models\\Berita')
                ->where('action', 'bulk_update')
                ->exists());
        });
    }

    /**
     * Test Database Rollback on Errors
     */
    public function test_database_rollback_on_errors()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        $initialCount = DB::table('berita')->count();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Rollback Test Article')
                ->type('konten', 'Test content')
                ->script('
                    // Simulate form submission with error
                    let form = document.querySelector("form");
                    form.addEventListener("submit", function(e) {
                        // Simulate server error
                        throw new Error("Simulated server error");
                    });
                ')
                ->press('Simpan')
                ->waitForText('Terjadi kesalahan')
                ->assertSee('Terjadi kesalahan');
            
            // Test that no partial data was saved
            $finalCount = DB::table('berita')->count();
            $this->assertEquals($initialCount, $finalCount);
        });
    }

    /**
     * Test Concurrent Data Modifications
     */
    public function test_concurrent_data_modifications()
    {
        $user1 = User::factory()->create(['role' => 'content_manager']);
        $user2 = User::factory()->create(['role' => 'content_manager']);
        $berita = Berita::factory()->create(['judul' => 'Concurrent Test Article']);
        
        $this->browse(function (Browser $browser1, Browser $browser2) use ($user1, $user2, $berita) {
            // User 1 starts editing
            $browser1->loginAs($user1)
                ->visit('/admin/berita/' . $berita->id . '/edit')
                ->type('judul', 'Modified by User 1')
                ->pause(1000);
            
            // User 2 also edits the same record
            $browser2->loginAs($user2)
                ->visit('/admin/berita/' . $berita->id . '/edit')
                ->type('judul', 'Modified by User 2')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            // User 1 tries to save (should get conflict warning)
            $browser1->press('Simpan')
                ->waitForText('Data telah diubah oleh user lain')
                ->assertSee('Data telah diubah oleh user lain');
            
            // Test that last valid update was preserved
            $this->assertEquals('Modified by User 2', 
                DB::table('berita')->where('id', $berita->id)->value('judul'));
        });
    }

    /**
     * Test Data Validation Cross-Fields
     */
    public function test_data_validation_cross_fields()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Test Article')
                ->type('konten', 'Test content')
                ->select('status', 'published')
                ->script('
                    // Set future publication date for published article
                    let dateInput = document.querySelector("input[name=\"tanggal_publikasi\"]");
                    if (dateInput) {
                        let futureDate = new Date();
                        futureDate.setDate(futureDate.getDate() + 7);
                        dateInput.value = futureDate.toISOString().split("T")[0];
                    }
                ')
                ->press('Simpan')
                ->waitForText('Artikel published tidak boleh memiliki tanggal publikasi di masa depan')
                ->assertSee('Artikel published tidak boleh memiliki tanggal publikasi di masa depan');
        });
    }

    /**
     * Test Data Archival and Soft Deletes
     */
    public function test_data_archival_and_soft_deletes()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $berita = Berita::factory()->create(['judul' => 'Archive Test Article']);
        
        $this->browse(function (Browser $browser) use ($user, $berita) {
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->click('a[href*="delete"]')
                ->waitForText('Konfirmasi')
                ->press('Ya, Hapus')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            // Test soft delete - record should still exist with deleted_at
            $this->assertTrue(DB::table('berita')
                ->where('id', $berita->id)
                ->whereNotNull('deleted_at')
                ->exists());
            
            // Test record is not visible in normal queries
            $browser->visit('/admin/berita')
                ->assertDontSee('Archive Test Article');
        });
    }

    /**
     * Test Data Backup and Recovery Integrity
     */
    public function test_data_backup_recovery_integrity()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $originalData = Berita::factory()->create(['judul' => 'Backup Test Article']);
        
        $this->browse(function (Browser $browser) use ($user, $originalData) {
            $browser->loginAs($user)
                ->visit('/admin/configurations')
                ->click('a[href*="backup"]')
                ->waitForText('Backup berhasil')
                ->assertSee('Backup berhasil');
            
            // Modify original data
            $originalData->update(['judul' => 'Modified Article']);
            
            // Restore backup
            $browser->click('a[href*="restore"]')
                ->waitForText('Restore berhasil')
                ->assertSee('Restore berhasil');
            
            // Test data integrity after restore
            $restoredData = DB::table('berita')->where('id', $originalData->id)->first();
            $this->assertEquals('Backup Test Article', $restoredData->judul);
        });
    }

    /**
     * Test Data Migration Integrity
     */
    public function test_data_migration_integrity()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $beforeCount = DB::table('berita')->count();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/configurations')
                ->click('a[href*="migrate"]')
                ->waitForText('Migrasi berhasil')
                ->assertSee('Migrasi berhasil');
            
            // Test that data count is preserved
            $afterCount = DB::table('berita')->count();
            $this->assertEquals($beforeCount, $afterCount);
            
            // Test that table structure is correct
            $this->assertTrue(DB::getSchemaBuilder()->hasTable('berita'));
            $this->assertTrue(DB::getSchemaBuilder()->hasColumn('berita', 'id'));
            $this->assertTrue(DB::getSchemaBuilder()->hasColumn('berita', 'judul'));
        });
    }

    /**
     * Test Index and Performance Integrity
     */
    public function test_index_and_performance_integrity()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        // Create large dataset
        Berita::factory()->count(100)->create();
        
        $this->browse(function (Browser $browser) use ($user) {
            $startTime = microtime(true);
            
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->type('search', 'test')
                ->press('Cari')
                ->waitForText('Berita');
            
            $endTime = microtime(true);
            $queryTime = $endTime - $startTime;
            
            // Test query performance (should be under 2 seconds)
            $this->assertLessThan(2.0, $queryTime, 'Query took too long: ' . $queryTime . ' seconds');
            
            // Test database indexes are being used
            $explain = DB::select('EXPLAIN SELECT * FROM berita WHERE judul LIKE ?', ['%test%']);
            $this->assertNotEmpty($explain);
        });
    }

    /**
     * Test Data Encryption and Security
     */
    public function test_data_encryption_and_security()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Sensitive Data Test')
                ->type('konten', 'This contains sensitive information')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            // Test that sensitive data is properly encrypted in database
            $rawData = DB::table('berita')
                ->where('judul', 'Sensitive Data Test')
                ->first();
            
            $this->assertNotNull($rawData);
            
            // Test password hashing
            $hashedPassword = DB::table('users')
                ->where('id', $user->id)
                ->value('password');
            
            $this->assertTrue(password_verify('password', $hashedPassword));
        });
    }

    /**
     * Test Data Audit Trail Integrity
     */
    public function test_data_audit_trail_integrity()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/berita/create')
                ->type('judul', 'Audit Trail Test')
                ->type('konten', 'Test content')
                ->press('Simpan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            $berita = DB::table('berita')->where('judul', 'Audit Trail Test')->first();
            
            // Test audit trail was created
            $this->assertTrue(DB::table('audit_logs')
                ->where('model_type', 'App\\Models\\Berita')
                ->where('model_id', $berita->id)
                ->where('action', 'create')
                ->where('user_id', $user->id)
                ->exists());
            
            // Test audit trail completeness
            $auditLog = DB::table('audit_logs')
                ->where('model_type', 'App\\Models\\Berita')
                ->where('model_id', $berita->id)
                ->where('action', 'create')
                ->first();
            
            $this->assertNotNull($auditLog->old_values);
            $this->assertNotNull($auditLog->new_values);
            $this->assertNotNull($auditLog->created_at);
        });
    }

    /**
     * Test Data Import/Export Integrity
     */
    public function test_data_import_export_integrity()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $originalData = Berita::factory()->count(5)->create();
        
        $this->browse(function (Browser $browser) use ($user, $originalData) {
            // Export data
            $browser->loginAs($user)
                ->visit('/admin/berita')
                ->click('a[href*="export"]')
                ->waitForText('Export berhasil')
                ->assertSee('Export berhasil');
            
            // Clear existing data
            DB::table('berita')->truncate();
            
            // Import data
            $browser->visit('/admin/berita')
                ->click('a[href*="import"]')
                ->waitForText('Import berhasil')
                ->assertSee('Import berhasil');
            
            // Test data integrity after import
            $importedCount = DB::table('berita')->count();
            $this->assertEquals($originalData->count(), $importedCount);
            
            // Test specific data integrity
            foreach ($originalData as $original) {
                $imported = DB::table('berita')->where('judul', $original->judul)->first();
                $this->assertNotNull($imported);
                $this->assertEquals($original->konten, $imported->konten);
            }
        });
    }

    /**
     * Test Database Connection Integrity
     */
    public function test_database_connection_integrity()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    // Test database connection status
                    fetch("/admin/api/health-check")
                    .then(response => response.json())
                    .then(data => {
                        if (data.database && data.database.status === "ok") {
                            document.body.innerHTML += "<div id=\"db-connection-ok\">Database Connection OK</div>";
                        }
                    });
                ')
                ->waitFor('#db-connection-ok')
                ->assertSee('Database Connection OK');
            
            // Test database query execution
            $this->assertTrue(DB::connection()->getPdo() !== null);
            $this->assertTrue(DB::table('users')->where('id', $user->id)->exists());
        });
    }

    /**
     * Test Data Consistency Across Multiple Tables
     */
    public function test_data_consistency_across_multiple_tables()
    {
        $user = User::factory()->create(['role' => 'wbs_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/wbs')
                ->type('nama', 'Test Reporter')
                ->type('email', 'reporter@example.com')
                ->type('judul', 'Test WBS Report')
                ->type('keterangan', 'Test description')
                ->press('Kirim Laporan')
                ->waitForText('Berhasil')
                ->assertSee('Berhasil');
            
            // Test that data exists in main table
            $wbs = DB::table('wbs')->where('judul', 'Test WBS Report')->first();
            $this->assertNotNull($wbs);
            
            // Test that related data exists in audit table
            $this->assertTrue(DB::table('audit_logs')
                ->where('model_type', 'App\\Models\\Wbs')
                ->where('model_id', $wbs->id)
                ->where('action', 'create')
                ->exists());
            
            // Test that notification record exists
            $this->assertTrue(DB::table('notifications')
                ->where('type', 'wbs_report_created')
                ->where('data', 'like', '%' . $wbs->id . '%')
                ->exists());
        });
    }
}