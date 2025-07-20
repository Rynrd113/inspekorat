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
        // Check if the wbs table exists before trying to modify it
        if (Schema::hasTable('wbs')) {
            Schema::table('wbs', function (Blueprint $table) {
                if (!Schema::hasColumn('wbs', 'bukti_files')) {
                    $table->json('bukti_files')->nullable()->after('bukti_file');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('wbs') && Schema::hasColumn('wbs', 'bukti_files')) {
            Schema::table('wbs', function (Blueprint $table) {
                $table->dropColumn('bukti_files');
            });
        }
    }
};
