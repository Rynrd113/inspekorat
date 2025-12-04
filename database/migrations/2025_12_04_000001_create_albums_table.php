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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('albums')->onDelete('cascade');
            $table->string('nama_album');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('cover_image')->nullable();
            $table->date('tanggal_kegiatan')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            
            $table->index('parent_id');
            $table->index('slug');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
