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
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('kategori');
            $table->enum('type', ['foto', 'video']);
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->date('tanggal_event')->nullable();
            $table->string('lokasi_event')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('urutan')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
