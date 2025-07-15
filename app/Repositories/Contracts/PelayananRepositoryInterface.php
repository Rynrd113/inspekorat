<?php

namespace App\Repositories\Contracts;

use App\Models\Pelayanan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PelayananRepositoryInterface
{
    public function findById(int $id): ?Pelayanan;
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function create(array $data): Pelayanan;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByKategori(string $kategori): Collection;
    public function getActive(): Collection;
}
