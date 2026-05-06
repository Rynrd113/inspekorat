<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PortalPapuaTengah extends Model
{
    use HasFactory;

    protected $table = 'portal_papua_tengahs';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'kategori',
        'author',
        'penulis',
        'tanggal_publikasi',
        'gambar',
        'thumbnail',
        'status',
        'is_published',
        'is_featured',
        'published_at',
        'views',
        'tags',
        'meta_description',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'status' => 'boolean',
        'tanggal_publikasi' => 'date',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'views' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        // Temporarily disabled slug generation until migration is added
        // static::creating(function ($model) {
        //     if (empty($model->slug)) {
        //         $model->slug = Str::slug($model->judul);
        //     }
        // });

        // static::updating(function ($model) {
        //     if ($model->isDirty('judul')) {
        //         $model->slug = Str::slug($model->judul);
        //     }
        // });
    }

    /**
     * Scope a query to only include published portal content.
     */
    public function scopePublished($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include active portal content.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include featured portal content.
     */
    public function scopeFeatured($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to order by published date (newest first).
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('tanggal_publikasi', 'desc');
    }
}
