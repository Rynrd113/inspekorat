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
        Schema::create('info_kantors', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konten');
            $table->string('kategori'); // contoh: 'alamat', 'kontak', 'jam_operasional'
            $table->boolean('is_active')->default(true);
            $table->string('icon')->nullable(); // untuk icon di frontend
            $table->integer('urutan')->default(0); // untuk sorting
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['kategori', 'is_active']);
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_kantors');
    }
};
