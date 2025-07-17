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
        // Add critical indexes for pelayanans table
        Schema::table('pelayanans', function (Blueprint $table) {
            $indexes = [
                'status',
                'kategori',
                ['status', 'kategori'],
                ['status', 'urutan'],
                'created_at',
                'updated_at',
                'deleted_at'
            ];
            
            foreach ($indexes as $index) {
                try {
                    $table->index($index);
                } catch (\Exception $e) {
                    // Index already exists, ignore
                }
            }
            
            // Full-text search for better performance (only for MySQL)
            if (DB::connection()->getDriverName() === 'mysql') {
                try {
                    $table->fullText(['nama', 'deskripsi']);
                } catch (\Exception $e) {
                    // Index already exists, ignore
                }
            }
        });

        // Add missing indexes for users table (skip role index as it's already added)
        Schema::table('users', function (Blueprint $table) {
            $indexes = [
                'email_verified_at',
                ['role', 'email_verified_at'],
                'created_at'
            ];
            
            foreach ($indexes as $index) {
                try {
                    $table->index($index);
                } catch (\Exception $e) {
                    // Index already exists, ignore
                }
            }
        });

        // Add missing indexes for wbs table
        Schema::table('wbs', function (Blueprint $table) {
            $indexes = [
                ['status', 'created_at'],
                ['status', 'responded_at'],
                'tanggal_kejadian',
                'is_anonymous'
            ];
            
            foreach ($indexes as $index) {
                try {
                    $table->index($index);
                } catch (\Exception $e) {
                    // Index already exists, ignore
                }
            }
            
            // Full-text search for reports (only for MySQL)
            if (DB::connection()->getDriverName() === 'mysql') {
                try {
                    $table->fullText(['subjek', 'deskripsi', 'kronologi']);
                } catch (\Exception $e) {
                    // Index already exists, ignore
                }
            }
        });

        // Add missing indexes for portal_papua_tengahs table
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            $indexes = [
                ['is_published', 'published_at'],
                ['kategori', 'is_published', 'published_at'],
                ['is_featured', 'is_published', 'published_at'],
                'views',
                'penulis'
            ];
            
            foreach ($indexes as $index) {
                try {
                    $table->index($index);
                } catch (\Exception $e) {
                    // Index already exists, ignore
                }
            }
            
            // Full-text search for content (only for MySQL)
            if (DB::connection()->getDriverName() === 'mysql') {
                try {
                    $table->fullText(['judul', 'konten', 'tags']);
                } catch (\Exception $e) {
                    // Index already exists, ignore
                }
            }
        });

        // Create audit_logs table with proper indexes (only if it doesn't exist)
        if (!Schema::hasTable('audit_logs')) {
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
        }

        // Create system_logs table for application logging (only if it doesn't exist)
        if (!Schema::hasTable('system_logs')) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelayanans', function (Blueprint $table) {
            // Use try-catch to handle non-existent indexes gracefully
            try {
                $table->dropIndex(['status']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['kategori']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['status', 'kategori']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['status', 'urutan']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['created_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['updated_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['deleted_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropFullText(['nama', 'deskripsi']);
            } catch (\Exception $e) {}
        });

        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropIndex(['role']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['email_verified_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['role', 'email_verified_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['created_at']);
            } catch (\Exception $e) {}
        });

        Schema::table('wbs', function (Blueprint $table) {
            try {
                $table->dropIndex(['status', 'created_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['status', 'responded_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['tanggal_kejadian']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['is_anonymous']);
            } catch (\Exception $e) {}
            try {
                $table->dropFullText(['subjek', 'deskripsi', 'kronologi']);
            } catch (\Exception $e) {}
        });

        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            try {
                $table->dropIndex(['is_published', 'published_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['kategori', 'is_published', 'published_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['is_featured', 'is_published', 'published_at']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['views']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['penulis']);
            } catch (\Exception $e) {}
            try {
                $table->dropFullText(['judul', 'konten', 'tags']);
            } catch (\Exception $e) {}
        });

        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('system_logs');
    }
};
