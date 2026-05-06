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

        // 2. Get first active photo from album
        $firstPhoto = $this->activePhotos()->first();
        if ($firstPhoto && $firstPhoto->file_path && $this->isFileValid($firstPhoto->file_path)) {
            return asset('storage/' . $firstPhoto->file_path);
        }

        // 3. Get first active photo from child albums recursively
        foreach ($this->children()->where('status', true)->get() as $child) {
            $url = $child->cover_image_url;
            if ($url && $url !== asset('images/logo.png')) {
                return $url;
            }
        }

        // 4. Return placeholder (use logo as fallback)
        return asset('images/logo.png');
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
