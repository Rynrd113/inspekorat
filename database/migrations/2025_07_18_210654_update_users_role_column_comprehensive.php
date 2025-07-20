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
        // Change role column to string to support all roles
        $platform = config('database.default');
        if ($platform === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) DEFAULT 'admin'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 50)->default('admin')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't revert to ENUM in testing environment to avoid truncation errors
        if (!app()->environment(['testing', ])) {
            // Restore to a simple enum only in non-testing environments
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
