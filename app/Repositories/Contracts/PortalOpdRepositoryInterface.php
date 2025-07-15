<?php

namespace App\Repositories\Contracts;

use App\Models\PortalOpd;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PortalOpdRepositoryInterface
{
    public function findById(int $id): ?PortalOpd;
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function create(array $data): PortalOpd;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getActive(): Collection;
    public function searchOpd(string $search): Collection;
    public function getByCreator(int $creatorId): Collection;
}
