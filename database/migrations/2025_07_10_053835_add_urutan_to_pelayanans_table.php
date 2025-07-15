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
        // Check if the pelayanans table exists before trying to modify it
        if (Schema::hasTable('pelayanans')) {
            Schema::table('pelayanans', function (Blueprint $table) {
                if (!Schema::hasColumn('pelayanans', 'urutan')) {
                    $table->integer('urutan')->default(0)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pelayanans') && Schema::hasColumn('pelayanans', 'urutan')) {
            Schema::table('pelayanans', function (Blueprint $table) {
                $table->dropColumn('urutan');
            });
        }
    }
};
