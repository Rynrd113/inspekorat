<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('review_opd', function (Blueprint $table) {
            $table->smallInteger('tahun_anggaran')->default(date('Y'))->after('nama_opd');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_review');
            $table->string('dokumen_path')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('review_opd', function (Blueprint $table) {
            $table->dropColumn(['tahun_anggaran', 'tanggal_selesai', 'dokumen_path']);
        });
    }
};
