<?php

namespace App\Services\Implementation;

use App\Models\PortalPapuaTengah;
use App\Services\Contracts\PortalPapuaTengahServiceInterface;
use App\Repositories\Contracts\PortalPapuaTengahRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PortalPapuaTengahService implements PortalPapuaTengahServiceInterface
{
    protected $repository;

    public function __construct(PortalPapuaTengahRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all portal content with optional filtering
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getAllPaginated($filters, $perPage);
    }

    /**
     * Get portal content by ID
     */
    public function getById(int $id): ?PortalPapuaTengah
    {
        return $this->repository->findById($id);
    }

    /**
     * Get portal content by slug
     */
    public function getBySlug(string $slug): ?PortalPapuaTengah
    {
        return $this->repository->findBySlug($slug);
    }

    /**
     * Create new portal content
     */
    public function create(array $data): PortalPapuaTengah
    {
        DB::beginTransaction();
        try {
            // Generate slug if not provided
            if (!isset($data['slug']) || empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Handle file uploads
            if (isset($data['image']) && $data['image']) {
                $data['image'] = $this->handleImageUpload($data['image']);
            }

            if (isset($data['document']) && $data['document']) {
                $data['document'] = $this->handleDocumentUpload($data['document']);
            }

            $content = $this->repository->create($data);

            DB::commit();
            return $content;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update portal content
     */
    public function update(int $id, array $data): bool
    {
        DB::beginTransaction();
        try {
            $content = $this->repository->findById($id);
            if (!$content) {
                return false;
            }

            // Generate slug if title changed
            if (isset($data['title']) && $data['title'] !== $content->title) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Handle file uploads
            if (isset($data['image']) && $data['image']) {
                // Delete old image
                if ($content->image) {
                    Storage::disk('public')->delete($content->image);
                }
                $data['image'] = $this->handleImageUpload($data['image']);
            }

            if (isset($data['document']) && $data['document']) {
                // Delete old document
                if ($content->document) {
                    Storage::disk('public')->delete($content->document);
                }
                $data['document'] = $this->handleDocumentUpload($data['document']);
            }

            $updated = $this->repository->update($id, $data);

            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete portal content
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $content = $this->repository->findById($id);
            if (!$content) {
                return false;
            }

            // Delete associated files
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
            }
            if ($content->document) {
                Storage::disk('public')->delete($content->document);
            }

            $deleted = $this->repository->delete($id);

            DB::commit();
            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get published content
     */
    public function getPublished(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPublished($perPage);
    }

    /**
     * Get featured content
     */
    public function getFeatured(int $limit = 5): Collection
    {
        return $this->repository->getFeatured($limit);
    }

    /**
     * Get popular content
     */
    public function getPopular(int $limit = 10): Collection
    {
        return $this->repository->getPopular($limit);
    }

    /**
     * Increment view count
     */
    public function incrementViews(int $id): bool
    {
        return $this->repository->incrementViews($id);
    }

    /**
     * Search content
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $perPage);
    }

    /**
     * Get content by category
     */
    public function getByCategory(string $category, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getByCategory($category, $perPage);
    }

    /**
     * Get content statistics
     */
    public function getStats(): array
    {
        return [
            'total' => $this->repository->count(),
            'published' => $this->repository->countPublished(),
            'draft' => $this->repository->countDraft(),
            'featured' => $this->repository->countFeatured(),
            'total_views' => $this->repository->getTotalViews(),
            'recent_posts' => $this->repository->getRecentPosts(5),
        ];
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload($image): string
    {
        $path = $image->store('portal-papua-tengah/images', 'public');
        return $path;
    }

    /**
     * Handle document upload
     */
    private function handleDocumentUpload($document): string
    {
        $path = $document->store('portal-papua-tengah/documents', 'public');
        return $path;
    }
}
