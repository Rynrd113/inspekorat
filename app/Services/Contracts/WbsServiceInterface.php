<?php

namespace App\Services\Contracts;

use App\Models\Wbs;
use App\Http\Requests\StoreWbsRequest;
use App\Http\Requests\UpdateWbsRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface WbsServiceInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function createWbsReport(StoreWbsRequest $request): Wbs;
    public function updateWbsReport(int $id, UpdateWbsRequest $request): bool;
    public function deleteWbsReport(int $id): bool;
    public function getWbsById(int $id): ?Wbs;
    public function respondToReport(int $id, string $response): bool;
    public function changeStatus(int $id, string $status): bool;
    public function getPendingReports(): Collection;
    public function getStatistics(): array;
}
