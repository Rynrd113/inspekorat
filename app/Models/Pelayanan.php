<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    use HasFactory;

    protected $table = 'pelayanans';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'prosedur',
        'persyaratan',
        'waktu_pelayanan',
        'biaya',
        'dasar_hukum',
        'kategori',
        'status',
        'kontak_penanggung_jawab',
        'file_formulir',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'prosedur' => 'array',
        'persyaratan' => 'array',
        'status' => 'boolean',
    ];

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for specific category
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Get the user who created this service
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this service
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get available service categories
     */
    public static function getKategoriOptions()
    {
        return [
            'perizinan' => 'Perizinan',
            'administrasi' => 'Administrasi',
            'pengawasan' => 'Pengawasan',
            'konsultasi' => 'Konsultasi',
            'audit' => 'Audit',
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
}
