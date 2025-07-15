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
        // Add critical indexes for pelayanans table
        Schema::table('pelayanans', function (Blueprint $table) {
            $table->index('status');
            $table->index('kategori');
            $table->index(['status', 'kategori']);
            $table->index(['status', 'urutan']);
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
            
            // Full-text search for better performance
            $table->fullText(['nama', 'deskripsi']);
        });

        // Add missing indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('email_verified_at');
            $table->index(['role', 'email_verified_at']);
            $table->index('created_at');
        });

        // Add missing indexes for wbs table
        Schema::table('wbs', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['status', 'responded_at']);
            $table->index('tanggal_kejadian');
            $table->index('is_anonymous');
            
            // Full-text search for reports
            $table->fullText(['subjek', 'deskripsi', 'kronologi']);
        });

        // Add missing indexes for portal_papua_tengahs table
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            $table->index(['is_published', 'published_at']);
            $table->index(['kategori', 'is_published', 'published_at']);
            $table->index(['is_featured', 'is_published', 'published_at']);
            $table->index('views');
            $table->index('penulis');
            
            // Full-text search for content
            $table->fullText(['judul', 'konten', 'tags']);
        });

        // Create audit_logs table with proper indexes
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 50);
            $table->string('model_type', 100);
            $table->unsignedBigInteger('model_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Critical indexes for audit logs
            $table->index(['model_type', 'model_id']);
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index('created_at');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Create system_logs table for application logging
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level', 20);
            $table->text('message');
            $table->json('context')->nullable();
            $table->string('channel', 50)->nullable();
            $table->timestamp('created_at');
            
            $table->index(['level', 'created_at']);
            $table->index(['channel', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelayanans', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['kategori']);
            $table->dropIndex(['status', 'kategori']);
            $table->dropIndex(['status', 'urutan']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['deleted_at']);
            $table->dropFullText(['nama', 'deskripsi']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['email_verified_at']);
            $table->dropIndex(['role', 'email_verified_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('wbs', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['status', 'responded_at']);
            $table->dropIndex(['tanggal_kejadian']);
            $table->dropIndex(['is_anonymous']);
            $table->dropFullText(['subjek', 'deskripsi', 'kronologi']);
        });

        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'published_at']);
            $table->dropIndex(['kategori', 'is_published', 'published_at']);
            $table->dropIndex(['is_featured', 'is_published', 'published_at']);
            $table->dropIndex(['views']);
            $table->dropIndex(['penulis']);
            $table->dropFullText(['judul', 'konten', 'tags']);
        });

        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('system_logs');
    }
};
