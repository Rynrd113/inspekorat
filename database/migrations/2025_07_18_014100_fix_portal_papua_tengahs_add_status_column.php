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
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            if (!Schema::hasColumn('portal_papua_tengahs', 'status')) {
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('is_published');
                $table->index('status');
            }
            
            // Add missing fields that are used in factories and models
            if (!Schema::hasColumn('portal_papua_tengahs', 'gambar')) {
                $table->string('gambar')->nullable()->after('thumbnail');
            }
            
            if (!Schema::hasColumn('portal_papua_tengahs', 'isi')) {
                $table->text('isi')->nullable()->after('konten');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            if (Schema::hasColumn('portal_papua_tengahs', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('portal_papua_tengahs', 'gambar')) {
                $table->dropColumn('gambar');
            }
            
            if (Schema::hasColumn('portal_papua_tengahs', 'isi')) {
                $table->dropColumn('isi');
            }
        });
    }
};