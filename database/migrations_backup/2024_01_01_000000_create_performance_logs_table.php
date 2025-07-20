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
        Schema::create('performance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            $table->string('method', 10);
            $table->decimal('execution_time', 10, 2); // in milliseconds
            $table->decimal('memory_usage', 10, 2); // in MB
            $table->decimal('peak_memory', 10, 2); // in MB
            $table->integer('query_count');
            $table->integer('http_status');
            $table->integer('content_length');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['url', 'created_at']);
            $table->index(['execution_time', 'created_at']);
            $table->index(['memory_usage', 'created_at']);
            $table->index(['query_count', 'created_at']);
            $table->index(['http_status', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_logs');
    }
};
