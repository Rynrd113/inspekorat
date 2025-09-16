<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean up any references to old Lambang_Papua_Tengah file
        $tablesToClean = [
            'portal_papua_tengah' => ['logo'],
            'portal_opd' => ['logo'],
            'system_configurations' => ['value'],
            'beritas' => ['image', 'thumbnail'],
        ];

        foreach ($tablesToClean as $table => $columns) {
            if (Schema::hasTable($table)) {
                foreach ($columns as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        DB::table($table)
                            ->where($column, 'like', '%Lambang_Papua_Tengah%')
                            ->update([$column => null]);
                            
                        echo "Cleaned {$table}.{$column}\n";
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse cleanup
    }
};