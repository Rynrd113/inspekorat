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
        // Info Kantors Table
        Schema::create('info_kantors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('status');
        });

        // Web Portals Table
        Schema::create('web_portals', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('url');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('kategori');
            $table->index('status');
            $table->index('urutan');
        });

        // Portal Papua Tengahs Table
        Schema::create('portal_papua_tengahs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konten');
            $table->string('kategori');
            $table->string('author')->nullable();
            $table->date('tanggal_publikasi');
            $table->string('gambar')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('views')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('kategori');
            $table->index('status');
            $table->index('tanggal_publikasi');
            $table->index('views');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Portal OPDs Table
        Schema::create('portal_opds', function (Blueprint $table) {
            $table->id();
            $table->string('nama_opd');
            $table->string('singkatan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('kepala_opd')->nullable();
            $table->string('logo')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('tugas_fungsi')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_opds');
        Schema::dropIfExists('portal_papua_tengahs');
        Schema::dropIfExists('web_portals');
        Schema::dropIfExists('info_kantors');
    }
};