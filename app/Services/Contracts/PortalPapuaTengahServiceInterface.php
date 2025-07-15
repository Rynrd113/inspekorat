<?php

namespace App\Services\Contracts;

use App\Models\PortalPapuaTengah;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PortalPapuaTengahServiceInterface
{
    /**
     * Get all portal content with optional filtering
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get portal content by ID
     */
    public function getById(int $id): ?PortalPapuaTengah;

    /**
     * Get portal content by slug
     */
    public function getBySlug(string $slug): ?PortalPapuaTengah;

    /**
     * Create new portal content
     */
    public function create(array $data): PortalPapuaTengah;

    /**
     * Update portal content
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete portal content
     */
    public function delete(int $id): bool;

    /**
     * Get published content
     */
    public function getPublished(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get featured content
     */
    public function getFeatured(int $limit = 5): Collection;

    /**
     * Get popular content
     */
    public function getPopular(int $limit = 10): Collection;

    /**
     * Increment view count
     */
    public function incrementViews(int $id): bool;

    /**
     * Search content
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get content by category
     */
    public function getByCategory(string $category, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get content statistics
     */
    public function getStats(): array;
}
