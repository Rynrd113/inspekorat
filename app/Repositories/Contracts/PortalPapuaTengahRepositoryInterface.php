<?php

namespace App\Repositories\Contracts;

use App\Models\PortalPapuaTengah;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PortalPapuaTengahRepositoryInterface
{
    public function findById(int $id): ?PortalPapuaTengah;
    public function findBySlug(string $slug): ?PortalPapuaTengah;
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function create(array $data): PortalPapuaTengah;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getPublished(): Collection;
    public function getFeatured(): Collection;
    public function getByKategori(string $kategori): Collection;
    public function getRecent(int $limit = 10): Collection;
    public function search(string $search): Collection;
    public function incrementViews(int $id): bool;
    public function getPopular(int $limit = 10): Collection;
    public function getStatistics(): array;
}
