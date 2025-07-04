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
        Schema::create('portal_papua_tengahs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('konten');
            $table->string('penulis');
            $table->enum('kategori', ['berita', 'pengumuman', 'kegiatan', 'regulasi', 'layanan']);
            $table->string('thumbnail')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->string('tags')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['kategori', 'is_published']);
            $table->index(['is_featured', 'is_published']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_papua_tengahs');
    }
};
