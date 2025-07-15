<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasAuditLog;
use App\Traits\EagerLoadingOptimized;

class PortalPapuaTengah extends Model
{
    use HasFactory, HasAuditLog, EagerLoadingOptimized;

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

    /**
     * Default eager loading relationships
     */
    protected $defaultEagerLoad = [
        'creator:id,name,email',
        'updater:id,name,email'
    ];

    /**
     * Contextual eager loading untuk berbagai use case
     */
    protected $contextualEagerLoad = [
        'api' => [
            'creator:id,name,email',
        ],
        'web' => [
            'creator:id,name,email',
            'updater:id,name,email'
        ],
        'admin' => [
            'creator:id,name,email,role',
            'updater:id,name,email,role',
            'files'
        ]
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByAuthor($query, $author)
    {
        return $query->where('penulis', $author);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('published_at', '>=', now()->subDays($days));
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('views', 'desc')->limit($limit);
    }

    /**
     * Scope untuk API dengan optimized eager loading
     */
    public function scopeForApi($query)
    {
        return $query->withContext('api')
                    ->select([
                        'id', 'judul', 'slug', 'ringkasan', 'kategori', 
                        'is_published', 'published_at', 'views', 'penulis',
                        'gambar', 'created_at'
                    ]);
    }

    /**
     * Scope untuk Admin dengan full eager loading
     */
    public function scopeForAdmin($query)
    {
        return $query->withContext('admin')
                    ->withCounts(['files', 'comments'])
                    ->withExists(['attachments']);
    }

    /**
     * Scope untuk Web dengan balanced eager loading
     */
    public function scopeForWeb($query)
    {
        return $query->withContext('web')
                    ->select([
                        'id', 'judul', 'slug', 'konten', 'kategori', 
                        'is_published', 'published_at', 'views', 'penulis',
                        'gambar', 'ringkasan', 'tags', 'created_at'
                    ]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
