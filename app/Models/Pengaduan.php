<?php

namespace App\Models;

use App\Observers\PengaduanObserver;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory, HasAuditLog;

    protected $fillable = [
        'nama_pengadu',
        'email',
        'telepon',
        'subjek',
        'isi_pengaduan',
        'kategori',
        'status',
        'tanggapan',
        'attachment',
        'tanggal_pengaduan',
        'is_anonymous',
        'bukti_files'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tanggal_pengaduan' => 'datetime',
        'is_anonymous' => 'boolean',
        'bukti_files' => 'array',
    ];

    /**
     * Boot the model and register observer
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(PengaduanObserver::class);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        if ($status) {
            return $query->where('status', $status);
        }
        
        return $query;
    }

    /**
     * Scope untuk pengaduan diterima
     */
    public function scopeDiterima(Builder $query): Builder
    {
        return $query->where('status', 'diterima');
    }

    /**
     * Scope untuk pengaduan proses
     */
    public function scopeProses(Builder $query): Builder
    {
        return $query->where('status', 'proses');
    }

    /**
     * Scope untuk pengaduan selesai
     */
    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk pengaduan ditolak
     */
    public function scopeDitolak(Builder $query): Builder
    {
        return $query->where('status', 'ditolak');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'diterima' => 'bg-yellow-100 text-yellow-800',
            'proses' => 'bg-blue-100 text-blue-800', 
            'selesai' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
        ];
        
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
    
    /**
     * Get status label in Indonesian
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'diterima' => 'Diterima',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get ticket number for display
     */
    public function getTicketNumberAttribute(): string
    {
        return '#' . $this->id;
    }
}
