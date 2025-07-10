<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumens';

    protected $fillable = [
        'judul',
        'deskripsi',
        'kategori',
        'tahun',
        'nomor_dokumen',
        'tanggal_terbit',
        'file_dokumen',
        'file_cover',
        'status',
        'is_public',
        'tags',
        'download_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'status' => 'boolean',
        'is_public' => 'boolean',
        'download_count' => 'integer',
    ];

    /**
     * Scope for active documents
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for public documents
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for specific category
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope for specific year
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Get the user who created this document
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this document
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get available document categories
     */
    public static function getKategoriOptions()
    {
        return [
            'peraturan' => 'Peraturan',
            'kebijakan' => 'Kebijakan',
            'laporan' => 'Laporan',
            'panduan' => 'Panduan/Manual',
            'formulir' => 'Formulir',
            'sk' => 'Surat Keputusan',
            'st' => 'Surat Tugas',
            'lainnya' => 'Lainnya',
        ];
    }

    /**
     * Get kategori label
     */
    public function getKategoriLabelAttribute()
    {
        $options = self::getKategoriOptions();
        return $options[$this->kategori] ?? $this->kategori;
    }

    /**
     * Increment download count
     */
    public function incrementDownload()
    {
        $this->increment('download_count');
    }
}
