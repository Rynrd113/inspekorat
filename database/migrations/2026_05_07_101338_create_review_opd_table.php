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
        Schema::create('review_opd', function (Blueprint $table) {
            $table->id();
            $table->string('nama_opd');
            $table->date('tanggal_review');
            $table->enum('status_review', ['dijadwalkan', 'sedang_berjalan', 'selesai'])->default('dijadwalkan');
            $table->string('hasil_review')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_opd');
    }
};
