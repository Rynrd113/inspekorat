<?php

namespace App\Repositories\Implementation;

use App\Models\PortalPapuaTengah;
use App\Repositories\Contracts\PortalPapuaTengahRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortalPapuaTengahRepository implements PortalPapuaTengahRepositoryInterface
{
    protected $model;

    public function __construct(PortalPapuaTengah $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?PortalPapuaTengah
    {
        return Cache::remember("portal_papua_tengah.{$id}", 600, function () use ($id) {
            return $this->model->find($id);
        });
    }

    public function findBySlug(string $slug): ?PortalPapuaTengah
    {
        return Cache::remember("portal_papua_tengah.slug.{$slug}", 600, function () use ($slug) {
            return $this->model->where('slug', $slug)->first();
        });
    }

    public function getAll(): Collection
    {
        return Cache::remember('portal_papua_tengah.all', 600, function () {
            return $this->model->orderBy('published_at', 'desc')->get();
        });
    }

    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where('judul', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('konten', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (isset($filters['is_published'])) {
            $query->where('is_published', $filters['is_published']);
        }

        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        return $query->withContext($filters['context'] ?? 'web')
                    ->orderBy('published_at', 'desc')
                    ->paginate($perPage);
    }

    public function create(array $data): PortalPapuaTengah
    {
        // Generate slug if not provided
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = Str::slug($data['judul']);
        }

        $data['published_at'] = $data['published_at'] ?? now();

        $portalPapuaTengah = $this->model->create($data);
        
        // Clear cache
        $this->clearCache();
        
        return $portalPapuaTengah;
    }

    public function update(int $id, array $data): bool
    {
        $portalPapuaTengah = $this->model->find($id);
        
        if (!$portalPapuaTengah) {
            return false;
        }

        // Update slug if title changed
        if (isset($data['judul']) && $data['judul'] !== $portalPapuaTengah->judul) {
            $data['slug'] = Str::slug($data['judul']);
        }

        $result = $portalPapuaTengah->update($data);
        
        // Clear cache
        $this->clearCache();
        Cache::forget("portal_papua_tengah.{$id}");
        Cache::forget("portal_papua_tengah.slug.{$portalPapuaTengah->slug}");
        
        return $result;
    }

    public function delete(int $id): bool
    {
        $portalPapuaTengah = $this->model->find($id);
        
        if (!$portalPapuaTengah) {
            return false;
        }

        // Delete associated files
        if ($portalPapuaTengah->gambar) {
            Storage::disk('public')->delete($portalPapuaTengah->gambar);
        }

        $result = $portalPapuaTengah->delete();
        
        // Clear cache
        $this->clearCache();
        Cache::forget("portal_papua_tengah.{$id}");
        Cache::forget("portal_papua_tengah.slug.{$portalPapuaTengah->slug}");
        
        return $result;
    }

    public function getPublished(): Collection
    {
        return Cache::remember('portal_papua_tengah.published', 600, function () {
            return $this->model->where('is_published', true)
                              ->orderBy('published_at', 'desc')
                              ->get();
        });
    }

    public function getFeatured(): Collection
    {
        return Cache::remember('portal_papua_tengah.featured', 600, function () {
            return $this->model->where('is_published', true)
                              ->where('is_featured', true)
                              ->orderBy('published_at', 'desc')
                              ->get();
        });
    }

    public function getByKategori(string $kategori): Collection
    {
        return Cache::remember("portal_papua_tengah.kategori.{$kategori}", 600, function () use ($kategori) {
            return $this->model->where('kategori', $kategori)
                              ->where('is_published', true)
                              ->orderBy('published_at', 'desc')
                              ->get();
        });
    }

    public function getRecent(int $limit = 10): Collection
    {
        return Cache::remember("portal_papua_tengah.recent.{$limit}", 600, function () use ($limit) {
            return $this->model->where('is_published', true)
                              ->orderBy('published_at', 'desc')
                              ->limit($limit)
                              ->get();
        });
    }

    public function search(string $search): Collection
    {
        return $this->model->where('judul', 'like', "%{$search}%")
                          ->orWhere('konten', 'like', "%{$search}%")
                          ->orWhere('ringkasan', 'like', "%{$search}%")
                          ->where('is_published', true)
                          ->orderBy('published_at', 'desc')
                          ->limit(20)
                          ->get();
    }

    public function incrementViews(int $id): bool
    {
        $result = $this->model->find($id)->increment('views');
        
        Cache::forget("portal_papua_tengah.{$id}");
        Cache::forget('portal_papua_tengah.popular.10');
        
        return $result;
    }

    public function getPopular(int $limit = 10): Collection
    {
        return Cache::remember("portal_papua_tengah.popular.{$limit}", 600, function () use ($limit) {
            return $this->model->where('is_published', true)
                              ->orderBy('views', 'desc')
                              ->limit($limit)
                              ->get();
        });
    }

    public function getStatistics(): array
    {
        return Cache::remember('portal_papua_tengah.statistics', 600, function () {
            return [
                'total' => $this->model->count(),
                'published' => $this->model->where('is_published', true)->count(),
                'draft' => $this->model->where('is_published', false)->count(),
                'featured' => $this->model->where('is_featured', true)->count(),
                'total_views' => $this->model->sum('views'),
                'recent' => $this->model->where('created_at', '>=', now()->subDays(30))->count(),
            ];
        });
    }

    private function clearCache(): void
    {
        Cache::forget('portal_papua_tengah.all');
        Cache::forget('portal_papua_tengah.published');
        Cache::forget('portal_papua_tengah.featured');
        Cache::forget('portal_papua_tengah.statistics');
        
        // Clear categorized caches
        $categories = ['berita', 'pengumuman', 'kegiatan'];
        foreach ($categories as $category) {
            Cache::forget("portal_papua_tengah.kategori.{$category}");
        }
        
        // Clear recent and popular caches
        Cache::forget('portal_papua_tengah.recent.10');
        Cache::forget('portal_papua_tengah.popular.10');
    }
}
