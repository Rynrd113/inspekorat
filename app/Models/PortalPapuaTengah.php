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
        'isi',
        'kategori',
        'thumbnail',
        'gambar',
        'is_published',
        'published_at',
        'penulis',
        'tags',
        'views',
        'is_featured',
        'meta_description',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('judul')) {
                $model->slug = Str::slug($model->judul);
            }
        });
    }

    /**
     * Scope a query to only include published portal content.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include active portal content.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where('is_published', true);
    }

    /**
     * Scope a query to only include featured portal content.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
