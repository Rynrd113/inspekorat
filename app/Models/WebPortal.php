<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebPortal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'url',
        'deskripsi',
        'kategori',
        'status',
        'urutan'
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer'
    ];

    /**
     * Scope untuk portal yang aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
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
        return $query->orderBy('urutan', 'asc')->orderBy('nama', 'asc');
    }
}
