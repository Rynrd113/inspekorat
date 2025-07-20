<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class InfoKantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email',
        'website',
        'deskripsi',
        'jam_operasional',
        'latitude',
        'longitude',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Scope untuk info yang aktif
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
        return $query->orderBy('urutan', 'asc')->orderBy('judul', 'asc');
    }
}
