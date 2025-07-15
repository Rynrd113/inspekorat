<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasAuditLog;

class PortalPapuaTengah extends Model
{
    use HasFactory, HasAuditLog;

    protected $table = 'portal_papua_tengahs';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'kategori',
        'gambar',
        'is_published',
        'published_at',
        'penulis',
        'tags',
        'ringkasan',
        'views'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array'
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

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
