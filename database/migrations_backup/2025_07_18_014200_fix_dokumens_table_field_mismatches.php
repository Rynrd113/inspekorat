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
        Schema::table('dokumens', function (Blueprint $table) {
            // Add missing fields that the model expects
            if (!Schema::hasColumn('dokumens', 'tahun')) {
                $table->year('tahun')->nullable()->after('kategori');
            }
            
            if (!Schema::hasColumn('dokumens', 'nomor_dokumen')) {
                $table->string('nomor_dokumen')->nullable()->after('tahun');
            }
            
            if (!Schema::hasColumn('dokumens', 'tanggal_terbit')) {
                $table->date('tanggal_terbit')->nullable()->after('nomor_dokumen');
            }
            
            if (!Schema::hasColumn('dokumens', 'file_dokumen')) {
                $table->string('file_dokumen')->nullable()->after('tanggal_terbit');
            }
            
            if (!Schema::hasColumn('dokumens', 'file_cover')) {
                $table->string('file_cover')->nullable()->after('file_dokumen');
            }
            
            if (!Schema::hasColumn('dokumens', 'is_public')) {
                $table->boolean('is_public')->default(true)->after('status');
            }
            
            if (!Schema::hasColumn('dokumens', 'tags')) {
                $table->string('tags')->nullable()->after('is_public');
            }
            
            if (!Schema::hasColumn('dokumens', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Add indexes for better performance
            $table->index('tahun');
            $table->index('kategori');
            $table->index('status');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            // Drop indexes first using correct Laravel syntax
            $indexesToDrop = ['tahun', 'kategori', 'status', 'is_public'];
            foreach ($indexesToDrop as $index) {
                try {
                    $table->dropIndex([$index]);
                } catch (Exception $e) {
                    // Index might not exist, continue
                }
            }
            
            // Drop foreign key and columns
            if (Schema::hasColumn('dokumens', 'updated_by')) {
                try {
                    $table->dropForeign(['updated_by']);
                } catch (Exception $e) {
                    // Foreign key might not exist
                }
                $table->dropColumn('updated_by');
            }
            
            $columnsToRemove = ['tahun', 'nomor_dokumen', 'tanggal_terbit', 'file_dokumen', 'file_cover', 'is_public', 'tags'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('dokumens', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};