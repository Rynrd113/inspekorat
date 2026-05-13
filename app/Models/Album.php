<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'nama_album',
        'slug',
        'deskripsi',
        'cover_image',
        'tanggal_kegiatan',
        'status',
        'urutan',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->nama_album);
            }
        });

        static::updating(function ($album) {
            if ($album->isDirty('nama_album') && empty($album->slug)) {
                $album->slug = Str::slug($album->nama_album);
            }
        });
    }

    /**
     * Relationship: Parent album
     */
    public function parent()
    {
        return $this->belongsTo(Album::class, 'parent_id');
    }

    /**
     * Relationship: Child albums (sub-albums)
     */
    public function children()
    {
        return $this->hasMany(Album::class, 'parent_id')->orderBy('urutan')->orderBy('nama_album');
    }

    /**
     * Relationship: Photos in this album
     */
    public function photos()
    {
        return $this->hasMany(Galeri::class, 'album_id')->orderBy('tanggal_publikasi', 'desc');
    }

    /**
     * Get active photos relation
     */
    public function activePhotos()
    {
        return $this->hasMany(Galeri::class, 'album_id')
            ->where('status', true)
            ->orderBy('tanggal_publikasi', 'desc');
    }

    /**
     * Scope: Active albums only
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: Root albums (no parent)
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get cover image URL with fallback
     */
    public function getCoverImageUrlAttribute()
    {
        // 1. Check if cover_image is set and exists
        if ($this->cover_image && $this->isFileValid($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }

        // 2. Get first active photo — use eager-loaded relation to avoid N+1
        $firstPhoto = $this->relationLoaded('photos')
            ? $this->photos->first()
            : $this->activePhotos()->first();
        if ($firstPhoto && $firstPhoto->file_path && $this->isFileValid($firstPhoto->file_path)) {
            return asset('storage/' . $firstPhoto->file_path);
        }

        // 3. Get first active photo from child albums recursively
        foreach ($this->children()->where('status', true)->get() as $child) {
            $url = $child->cover_image_url;
            if ($url && strpos($url, 'data:image') === 0) {
                return $url;
            }
        }

        // 4. Return a data URI gradient image as fallback
        return 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22%3E%3Cdefs%3E%3ClinearGradient id=%22grad%22 x1=%220%25%22 y1=%220%25%22 x2=%22100%25%22 y2=%22100%25%22%3E%3Cstop offset=%220%25%22 style=%22stop-color:%23dbeafe;stop-opacity:1%22 /%3E%3Cstop offset=%22100%25%22 style=%22stop-color:%23bfdbfe;stop-opacity:1%22 /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width=%22400%22 height=%22300%22 fill=%22url(%23grad)%22 /%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 font-size=%2224%22 fill=%22%234b5563%22 font-family=%22Arial%22%3E📷%3C/text%3E%3C/svg%3E';
    }

    /**
     * Check if file path is valid
     */
    protected function isFileValid($path)
    {
        return file_exists(public_path('storage/' . $path)) ||
               \Illuminate\Support\Facades\Storage::disk('public')->exists($path);
    }

    /**
     * Get total photo count (including from sub-albums)
     */
    public function getPhotoCount()
    {
        $count = $this->photos()->where('status', true)->count();

        // Add photos from child albums
        foreach ($this->children()->where('status', true)->get() as $child) {
            $count += $child->getPhotoCount();
        }

        return $count;
    }

    /**
     * Get breadcrumb path
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = collect([$this]);

        $parent = $this->parent;
        while ($parent) {
            $breadcrumbs->prepend($parent);
            $parent = $parent->parent;
        }

        return $breadcrumbs;
    }

    /**
     * Check if album has photos
     */
    public function hasPhotos()
    {
        return $this->activePhotos()->exists();
    }
}
