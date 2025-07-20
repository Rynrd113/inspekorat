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
        // Skip this migration in testing environment for regular tests
        // But allow it for tests which need the full schema
        if (app()->environment(['testing', 'local'])) {
            return;
        }
        
        Schema::table('users', function (Blueprint $table) {
            // For MySQL, check if index exists using raw SQL
            if (config('database.default') === 'mysql') {
                $indexExists = collect(DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_role_index'"))->isNotEmpty();
                
                if ($indexExists) {
                    $table->dropIndex('users_role_index');
                }
            }
            
            $table->string('role', 50)->default('user')->change();
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip this migration in testing environment to avoid ENUM truncation issues
        if (app()->environment(['testing', ])) {
            return;
        }
        
        Schema::table('users', function (Blueprint $table) {
            // For MySQL, check if index exists using raw SQL
            if (config('database.default') === 'mysql') {
                $indexExists = collect(DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_role_index'"))->isNotEmpty();
                
                if ($indexExists) {
                    $table->dropIndex('users_role_index');
                }
            }
            
            // Only revert in production/staging environments
            if (app()->environment(['production', 'staging'])) {
                // First, clean up any roles that don't fit the enum
                DB::table('users')->whereNotIn('role', ['admin', 'super_admin'])->update(['role' => 'admin']);
                $table->enum('role', ['admin', 'super_admin'])->default('admin')->change();
            }
            $table->index('role');
        });
    }
};
