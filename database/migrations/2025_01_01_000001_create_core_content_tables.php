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
        // WBS (Whistleblowing System) Table
        Schema::create('wbs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('email');
            $table->string('no_telepon')->nullable();
            $table->string('subjek');
            $table->text('deskripsi');
            $table->date('tanggal_kejadian')->nullable();
            $table->text('lokasi_kejadian')->nullable();
            $table->text('pihak_terlibat')->nullable();
            $table->text('kronologi')->nullable();
            $table->string('bukti_file')->nullable();
            $table->json('bukti_files')->nullable();
            $table->enum('status', ['pending', 'proses', 'in_progress', 'resolved', 'rejected', 'selesai'])->default('pending');
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
            $table->index('is_anonymous');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Pelayanans (Services) Table
        Schema::create('pelayanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->json('prosedur')->nullable();
            $table->json('persyaratan')->nullable();
            $table->string('waktu_penyelesaian')->nullable();
            $table->string('biaya')->nullable();
            $table->string('kategori')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('urutan')->default(0);
            $table->string('kontak_pic')->nullable();
            $table->string('email_pic')->nullable();
            $table->string('telepon_pic')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('kategori');
            $table->index('status');
            $table->index('urutan');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Dokumens Table
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('kategori');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->date('tanggal_publikasi')->nullable();
            $table->string('tahun')->nullable();
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->string('file_cover')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_public')->default(true);
            $table->string('tags')->nullable();
            $table->integer('download_count')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('kategori');
            $table->index('status');
            $table->index('is_public');
            $table->index('tahun');
            $table->index('tanggal_terbit');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Galeris Table
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('kategori');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->string('file_size')->nullable();
            $table->boolean('status')->default(true);
            $table->date('tanggal_publikasi');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('kategori');
            $table->index('status');
            $table->index('tanggal_publikasi');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // FAQs Table
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan');
            $table->text('jawaban');
            $table->string('kategori')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('kategori');
            $table->index('status');
            $table->index('urutan');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('galeris');
        Schema::dropIfExists('dokumens');
        Schema::dropIfExists('pelayanans');
        Schema::dropIfExists('wbs');
    }
};