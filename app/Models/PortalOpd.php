<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasSearch;
use App\Traits\HasPagination;

class PortalOpd extends Model
{
    use HasFactory, SoftDeletes, HasSearch, HasPagination;

    protected $table = 'portal_opds';

    protected $fillable = [
        'nama_opd',
        'singkatan',
        'alamat',
        'telepon',
        'email',
        'website',
        'kepala_opd',
        'nip_kepala',
        'deskripsi',
        'visi',
        'misi',
        'logo',
        'banner',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'misi' => 'array',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the user who created this OPD
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this OPD
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for active OPDs
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Debug method to check data consistency
     */
    public static function debugData()
    {
        \Log::info('Portal OPD Debug', [
            'total_count' => self::count(),
            'active_count' => self::active()->count(),
            'first_10' => self::take(10)->get(['id', 'nama_opd', 'status'])->toArray()
        ]);
    }

    /**
     * Get formatted address
     */
    public function getFormattedAlamatAttribute()
    {
        return $this->alamat ? strip_tags($this->alamat) : '';
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-opd-logo.png');
    }

    /**
     * Get banner URL
     */
    public function getBannerUrlAttribute()
    {
        if ($this->banner) {
            return asset('storage/' . $this->banner);
        }
        return asset('images/default-opd-banner.jpg');
    }

    /**
     * Get searchable fields for PortalOpd
     */
    protected function getSearchableFields(): array
    {
        return [
            'nama_opd', 'singkatan', 'alamat', 'email', 'kepala_opd', 'deskripsi', 'visi'
        ];
    }

    /**
     * Get filterable fields for PortalOpd
     */
    protected function getFilterableFields(): array
    {
        return [
            'status' => 'boolean',
            'created_at' => 'date_range',
            'updated_at' => 'date_range',
        ];
    }

    /**
     * Get sortable fields for PortalOpd
     */
    protected function getSortableFields(): array
    {
        return [
            'id', 'nama_opd', 'singkatan', 'kepala_opd', 'email', 'status', 'created_at', 'updated_at'
        ];
    }
}
