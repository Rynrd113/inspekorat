<?php

namespace App\Repositories\Contracts;

use App\Models\Wbs;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface WbsRepositoryInterface
{
    public function findById(int $id): ?Wbs;
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function create(array $data): Wbs;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByStatus(string $status): Collection;
    public function getPendingReports(): Collection;
    public function getRecentReports(int $days = 30): Collection;
    public function getStatistics(): array;
}
