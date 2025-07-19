<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebPortal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_portal',
        'deskripsi',
        'url_portal',
        'url',
        'kategori',
        'icon',
        'is_active',
        'status',
        'urutan',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope untuk portal yang aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori(Builder $query, ?string $kategori): Builder
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        
        return $query;
    }

    /**
     * Scope untuk sorting berdasarkan urutan
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan', 'asc')->orderBy('nama_portal', 'asc');
    }
}
