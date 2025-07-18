<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;
use App\Traits\HasPagination;

class Pelayanan extends Model
{
    use HasFactory, HasSearch, HasPagination;

    protected $table = 'pelayanans';

    protected $fillable = [
        'nama',
        'deskripsi',
        'prosedur',
        'persyaratan',
        'waktu_penyelesaian',
        'biaya',
        'kategori',
        'status',
        'urutan',
        'kontak_pic',
        'email_pic',
        'telepon_pic',
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

    /**
     * Get searchable fields for Pelayanan
     */
    protected function getSearchableFields(): array
    {
        return [
            'nama', 'deskripsi', 'kontak_pic', 'email_pic'
        ];
    }

    /**
     * Get filterable fields for Pelayanan
     */
    protected function getFilterableFields(): array
    {
        return [
            'kategori' => 'exact',
            'status' => 'boolean',
            'created_at' => 'date_range',
            'updated_at' => 'date_range',
        ];
    }

    /**
     * Get sortable fields for Pelayanan
     */
    protected function getSortableFields(): array
    {
        return [
            'id', 'nama', 'kategori', 'status', 'urutan', 'biaya', 'created_at', 'updated_at'
        ];
    }
}
