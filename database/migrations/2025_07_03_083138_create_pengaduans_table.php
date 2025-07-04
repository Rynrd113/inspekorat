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
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengadu');
            $table->string('email');
            $table->string('telepon')->nullable();
            $table->string('subjek');
            $table->text('isi_pengaduan');
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->text('tanggapan')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index('status');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
