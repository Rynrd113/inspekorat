<?php

namespace App\Services\Contracts;

use App\Models\Pelayanan;
use App\Http\Requests\StorePelayananRequest;
use App\Http\Requests\UpdatePelayananRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PelayananServiceInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function createPelayanan(StorePelayananRequest $request): Pelayanan;
    public function updatePelayanan(int $id, UpdatePelayananRequest $request): bool;
    public function deletePelayanan(int $id): bool;
    public function getPublicPelayanan(): Collection;
    public function searchPelayanan(string $search): Collection;
}
