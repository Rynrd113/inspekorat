<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For testing environment, change role from enum to string to avoid truncation
        if (app()->environment() || app()->environment('testing')) {
            // For MySQL, we need to use raw SQL to modify the enum
            $platform = config('database.default');
            if ($platform === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) DEFAULT 'admin'");
            } else {
                // For PostgreSQL or other databases
                Schema::table('users', function (Blueprint $table) {
                    $table->string('role', 50)->default('admin')->change();
                });
            }
        } else {
            // For production, extend the enum to include all roles
            $platform = config('database.default');
            if ($platform === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
                    'super_admin', 
                    'admin', 
                    'content_manager',
                    'admin_profil',
                    'admin_portal_opd', 
                    'admin_pelayanan',
                    'admin_dokumen',
                    'admin_galeri',
                    'admin_faq',
                    'admin_berita',
                    'admin_wbs'
                ) DEFAULT 'admin'");
            } else {
                // For PostgreSQL or other databases, we'll use string
                Schema::table('users', function (Blueprint $table) {
                    $table->string('role', 50)->default('admin')->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't revert to ENUM in testing environment to avoid truncation errors
        if (!app()->environment(['testing', ])) {
            // Restore original enum only in non-testing environments
            $platform = config('database.default');
            if ($platform === 'mysql') {
                // First, clean up any roles that don't fit the enum
                DB::table('users')->whereNotIn('role', ['super_admin', 'admin', 'admin_wbs', 'admin_berita', 'admin_portal_opd'])
                  ->update(['role' => 'admin']);
                  
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'admin_wbs', 'admin_berita', 'admin_portal_opd') DEFAULT 'admin'");
            } else {
                Schema::table('users', function (Blueprint $table) {
                    $table->enum('role', ['super_admin', 'admin', 'admin_wbs', 'admin_berita', 'admin_portal_opd'])->default('admin')->change();
                });
            }
        }
    }
};