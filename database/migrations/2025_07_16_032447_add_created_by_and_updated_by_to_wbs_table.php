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
        Schema::table('wbs', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('is_anonymous')->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wbs', function (Blueprint $table) {
            try {
                $table->dropForeign(['created_by']);
            } catch (Exception $e) {
                // Foreign key might not exist
            }
            
            try {
                $table->dropForeign(['updated_by']);
            } catch (Exception $e) {
                // Foreign key might not exist
            }
            
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
