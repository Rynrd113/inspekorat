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
        Schema::create('pelayanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->text('prosedur')->nullable();
            $table->text('persyaratan')->nullable();
            $table->string('waktu_penyelesaian')->nullable();
            $table->string('biaya')->nullable();
            $table->string('kategori')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('urutan')->default(0);
            $table->string('kontak_pic')->nullable();
            $table->string('email_pic')->nullable();
            $table->string('telepon_pic')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanans');
    }
};
