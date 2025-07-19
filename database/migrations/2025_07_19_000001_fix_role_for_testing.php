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
        // Only run in testing environment
        if (app()->environment(['testing', 'dusk.local', 'local'])) {
            $platform = config('database.default');
            if ($platform === 'mysql') {
                // Change role column to VARCHAR to support all roles
                DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) DEFAULT 'admin'");
            } else {
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
        // Revert back if needed
        if (app()->environment(['testing', 'dusk.local', 'local'])) {
            $platform = config('database.default');
            if ($platform === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin') DEFAULT 'admin'");
            } else {
                Schema::table('users', function (Blueprint $table) {
                    $table->enum('role', ['super_admin', 'admin'])->default('admin')->change();
                });
            }
        }
    }
};
