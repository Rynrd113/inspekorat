<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Update any remaining 'user' role to 'content_admin'
            DB::table('users')->where('role', 'user')->update(['role' => 'content_admin']);
            
            // Recreate the enum column with only the 3 roles
            $table->enum('role', [
                'content_admin',
                'admin', 
                'super_admin'
            ])->default('content_admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore original enum with 4 roles
            $table->enum('role', [
                'user',
                'content_admin',
                'admin',
                'super_admin'
            ])->default('user')->change();
        });
    }
};
