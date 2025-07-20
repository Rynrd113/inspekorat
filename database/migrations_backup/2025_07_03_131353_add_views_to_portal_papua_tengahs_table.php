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
            if (!Schema::hasColumn('portal_papua_tengahs', 'views')) {
                $table->integer('views')->default(0)->after('ringkasan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }
};
