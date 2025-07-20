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
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wbs');
    }
};
