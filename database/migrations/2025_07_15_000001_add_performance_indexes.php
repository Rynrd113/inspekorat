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
            // Check and add indexes only if they don't exist
            $existingIndexes = DB::select("SHOW INDEX FROM pelayanans");
            $indexNames = collect($existingIndexes)->pluck('Key_name')->toArray();
            
            if (!in_array('pelayanans_status_index', $indexNames)) {
                $table->index('status');
            }
            if (!in_array('pelayanans_kategori_index', $indexNames)) {
                $table->index('kategori');
            }
            if (!in_array('pelayanans_status_kategori_index', $indexNames)) {
                $table->index(['status', 'kategori']);
            }
            if (!in_array('pelayanans_status_urutan_index', $indexNames)) {
                $table->index(['status', 'urutan']);
            }
            if (!in_array('pelayanans_created_at_index', $indexNames)) {
                $table->index('created_at');
            }
            if (!in_array('pelayanans_updated_at_index', $indexNames)) {
                $table->index('updated_at');
            }
            if (!in_array('pelayanans_deleted_at_index', $indexNames)) {
                $table->index('deleted_at');
            }
            
            // Full-text search for better performance
            if (!in_array('pelayanans_nama_deskripsi_fulltext', $indexNames)) {
                $table->fullText(['nama', 'deskripsi']);
            }
        });

        // Add missing indexes for users table
        Schema::table('users', function (Blueprint $table) {
            // Only add indexes if they don't already exist
            $existingIndexes = DB::select("SHOW INDEX FROM users");
            $indexNames = collect($existingIndexes)->pluck('Key_name')->toArray();
            
            if (!in_array('users_role_index', $indexNames)) {
                $table->index('role');
            }
            if (!in_array('users_email_verified_at_index', $indexNames)) {
                $table->index('email_verified_at');
            }
            if (!in_array('users_role_email_verified_at_index', $indexNames)) {
                $table->index(['role', 'email_verified_at']);
            }
            if (!in_array('users_created_at_index', $indexNames)) {
                $table->index('created_at');
            }
        });

        // Add missing indexes for wbs table
        Schema::table('wbs', function (Blueprint $table) {
            $existingIndexes = DB::select("SHOW INDEX FROM wbs");
            $indexNames = collect($existingIndexes)->pluck('Key_name')->toArray();
            
            if (!in_array('wbs_status_created_at_index', $indexNames)) {
                $table->index(['status', 'created_at']);
            }
            if (!in_array('wbs_status_responded_at_index', $indexNames)) {
                $table->index(['status', 'responded_at']);
            }
            if (!in_array('wbs_tanggal_kejadian_index', $indexNames)) {
                $table->index('tanggal_kejadian');
            }
            if (!in_array('wbs_is_anonymous_index', $indexNames)) {
                $table->index('is_anonymous');
            }
            
            // Full-text search for reports
            if (!in_array('wbs_subjek_deskripsi_kronologi_fulltext', $indexNames)) {
                $table->fullText(['subjek', 'deskripsi', 'kronologi']);
            }
        });

        // Add missing indexes for portal_papua_tengahs table
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            $existingIndexes = DB::select("SHOW INDEX FROM portal_papua_tengahs");
            $indexNames = collect($existingIndexes)->pluck('Key_name')->toArray();
            
            if (!in_array('portal_papua_tengahs_is_published_published_at_index', $indexNames)) {
                $table->index(['is_published', 'published_at']);
            }
            if (!in_array('portal_papua_tengahs_kategori_is_published_published_at_index', $indexNames)) {
                $table->index(['kategori', 'is_published', 'published_at']);
            }
            if (!in_array('portal_papua_tengahs_is_featured_is_published_published_at_index', $indexNames)) {
                $table->index(['is_featured', 'is_published', 'published_at']);
            }
            if (!in_array('portal_papua_tengahs_views_index', $indexNames)) {
                $table->index('views');
            }
            if (!in_array('portal_papua_tengahs_penulis_index', $indexNames)) {
                $table->index('penulis');
            }
            
            // Full-text search for content
            if (!in_array('portal_papua_tengahs_judul_konten_tags_fulltext', $indexNames)) {
                $table->fullText(['judul', 'konten', 'tags']);
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
