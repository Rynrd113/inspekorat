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
        'deskripsi',
        'alamat',
        'telepon',
        'email',
        'website',
        'kepala_opd',
        'logo',
        'visi',
        'misi',
        'tugas_fungsi',
        'status',
        'urutan'
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer',
        'misi' => 'array'
    ];

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
            // Check if file exists in storage, otherwise try uploads
            $storagePath = public_path('storage/' . $this->logo);
            $uploadsPath = public_path('uploads/' . $this->logo);
            
            if (file_exists($storagePath)) {
                return asset('storage/' . $this->logo);
            } elseif (file_exists($uploadsPath)) {
                return asset('uploads/' . $this->logo);
            } else {
                // Try direct path
                return asset($this->logo);
            }
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
