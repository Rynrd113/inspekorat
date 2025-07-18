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
        Schema::table('wbs', function (Blueprint $table) {
            // Add missing fields that the model expects
            if (!Schema::hasColumn('wbs', 'bukti_files')) {
                $table->json('bukti_files')->nullable()->after('bukti_file');
            }
            
            // Note: created_by and updated_by are already added by migration 2025_07_16_032447
            // We only need to add them if they don't exist (in case that migration didn't run)
            if (!Schema::hasColumn('wbs', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('is_anonymous');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('wbs', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });
        
        // Update the status enum to include 'proses' which is used in the application
        $platform = config('database.default');
        if ($platform === 'mysql') {
            DB::statement("ALTER TABLE wbs MODIFY COLUMN status ENUM('pending', 'proses', 'in_progress', 'resolved', 'rejected', 'selesai') DEFAULT 'pending'");
        } else {
            // For PostgreSQL or other databases, we would need a different approach
            // For now, we'll handle this later if needed
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wbs', function (Blueprint $table) {
            // Only drop bukti_files as created_by/updated_by are handled by migration 2025_07_16_032447
            if (Schema::hasColumn('wbs', 'bukti_files')) {
                $table->dropColumn('bukti_files');
            }
        });
        
        // Restore original status enum
        $platform = config('database.default');
        if ($platform === 'mysql') {
            DB::statement("ALTER TABLE wbs MODIFY COLUMN status ENUM('pending', 'in_progress', 'resolved', 'rejected') DEFAULT 'pending'");
        }
    }
};