<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewOpd extends Model
{
    protected $table = 'review_opd';

    protected $fillable = [
        'nama_opd',
        'tahun_anggaran',
        'tanggal_review',
        'tanggal_selesai',
        'status_review',
        'hasil_review',
        'keterangan',
        'dokumen_path',
    ];

    protected $casts = [
        'tanggal_review'  => 'date',
        'tanggal_selesai' => 'date',
    ];

    public static array $statusLabels = [
        'dijadwalkan'    => 'Dijadwalkan',
        'sedang_berjalan' => 'Sedang Berjalan',
        'selesai'        => 'Selesai',
    ];
}
