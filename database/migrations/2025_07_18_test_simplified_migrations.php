<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for testing environment only.
     */
    public function up(): void
    {
        // Skip complex migrations in testing environment
        if (app()->environment(['testing', 'dusk.local'])) {
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip in testing environment
        if (app()->environment(['testing', 'dusk.local'])) {
            return;
        }
    }
};