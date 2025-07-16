<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasAuditLog;

class Wbs extends Model
{
    use HasFactory, HasAuditLog;

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
        'bukti_file', // Single file (legacy)
        'bukti_files', // Multiple files (JSON)
        'status',
        'response',
        'responded_at',
        'admin_note',
        'attachment',
        'is_anonymous',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'responded_at' => 'datetime',
        'tanggal_kejadian' => 'date',
        'bukti_files' => 'array',
    ];

    /**
     * Boot the model and set up event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
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
     * Get the user who created this WBS report
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this WBS report
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}
