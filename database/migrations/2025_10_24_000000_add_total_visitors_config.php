<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add total_visitors configuration if not exists
        DB::table('system_configurations')->updateOrInsert(
            ['key' => 'total_visitors'],
            [
                'key' => 'total_visitors',
                'value' => '0',
                'description' => 'Total unique visitors to the website',
                'group' => 'statistics',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_configurations')
            ->where('key', 'total_visitors')
            ->delete();
    }
};
