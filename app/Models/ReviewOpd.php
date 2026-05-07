<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewOpd extends Model
{
    protected $table = 'review_opd';

    protected $fillable = [
        'nama_opd',
        'tanggal_review',
        'status_review',
        'hasil_review',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_review' => 'date',
    ];

    public static array $statusLabels = [
        'dijadwalkan'    => 'Dijadwalkan',
        'sedang_berjalan' => 'Sedang Berjalan',
        'selesai'        => 'Selesai',
    ];
}
