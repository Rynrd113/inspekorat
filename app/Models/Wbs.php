<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Wbs extends Model
{
    use HasFactory;

    protected $table = 'wbs';

    protected $fillable = [
        'nama_pelapor',
        'email',
        'no_telepon',
        'subjek',
        'deskripsi',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'pihak_terlibat',
        'kronologi',
        'bukti_file',
        'status',
        'response',
        'responded_at',
        'admin_note',
        'attachment',
        'is_anonymous'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'responded_at' => 'datetime',
        'tanggal_kejadian' => 'date',
    ];

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
     * Scope untuk pengaduan pending
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk pengaduan selesai
     */
    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'proses' => 'bg-blue-100 text-blue-800', 
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}
