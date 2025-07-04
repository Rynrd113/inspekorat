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
        Schema::create('web_portals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_portal');
            $table->text('deskripsi');
            $table->string('url_portal');
            $table->string('kategori'); // contoh: 'layanan', 'informasi', 'aplikasi'
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['kategori', 'is_active']);
            $table->index('url_portal');
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_portals');
    }
};
