<?php

namespace App\Services\Contracts;

use App\Models\PortalOpd;
use App\Http\Requests\StorePortalOpdRequest;
use App\Http\Requests\UpdatePortalOpdRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PortalOpdServiceInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function createOpd(StorePortalOpdRequest $request): PortalOpd;
    public function updateOpd(int $id, UpdatePortalOpdRequest $request): bool;
    public function deleteOpd(int $id): bool;
    public function getOpdById(int $id): ?PortalOpd;
    public function getActiveOpds(): Collection;
    public function searchOpds(string $search): Collection;
    public function updateOpdStatus(int $id, bool $status): bool;
    public function getOpdStatistics(): array;
    public function getOpdsByStatus(bool $status): Collection;
    public function uploadLogo(int $id, $file): bool;
    public function uploadBanner(int $id, $file): bool;
}
