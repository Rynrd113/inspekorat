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
        'thumbnail',
        'is_published',
        'published_at',
        'penulis',
        'tags',
        'views',
        'is_featured',
        'meta_description'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime'
    ];

    /**
     * Constructor to set up contextual eager loading
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        // Set up contextual eager loading
        $this->contextualEagerLoad = [
            'api' => [
                // No creator/updater relationships since they don't exist
            ],
            'web' => [
                // No creator/updater relationships since they don't exist
            ],
            'admin' => [
                // No creator/updater relationships since they don't exist
            ]
        ];
    }

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
     * Get default eager loading relationships
     */
    public function getDefaultEagerLoad(): array
    {
        return [
            // No creator/updater relationships since they don't exist
        ];
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
