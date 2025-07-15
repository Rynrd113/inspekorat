<?php

namespace App\Repositories\Implementation;

use App\Models\Wbs;
use App\Repositories\Contracts\WbsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class WbsRepository implements WbsRepositoryInterface
{
    protected $model;

    public function __construct(Wbs $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?Wbs
    {
        return $this->model->find($id);
    }

    public function getAll(): Collection
    {
        return Cache::remember('wbs.all', 600, function () {
            return $this->model->orderBy('created_at', 'desc')->get();
        });
    }

    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where('subjek', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('deskripsi', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('nama_pelapor', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): Wbs
    {
        $wbs = $this->model->create($data);
        
        // Clear cache
        Cache::forget('wbs.all');
        Cache::forget('wbs.pending');
        Cache::forget('wbs.statistics');
        
        return $wbs;
    }

    public function update(int $id, array $data): bool
    {
        $result = $this->model->find($id)->update($data);
        
        // Clear cache
        Cache::forget('wbs.all');
        Cache::forget('wbs.pending');
        Cache::forget('wbs.statistics');
        
        return $result;
    }

    public function delete(int $id): bool
    {
        $result = $this->model->find($id)->delete();
        
        // Clear cache
        Cache::forget('wbs.all');
        Cache::forget('wbs.pending');
        Cache::forget('wbs.statistics');
        
        return $result;
    }

    public function getByStatus(string $status): Collection
    {
        return Cache::remember("wbs.status.{$status}", 600, function () use ($status) {
            return $this->model->where('status', $status)->orderBy('created_at', 'desc')->get();
        });
    }

    public function getPendingReports(): Collection
    {
        return Cache::remember('wbs.pending', 600, function () {
            return $this->model->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        });
    }

    public function getRecentReports(int $days = 30): Collection
    {
        return $this->model->where('created_at', '>=', now()->subDays($days))
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function getStatistics(): array
    {
        return Cache::remember('wbs.statistics', 3600, function () {
            return [
                'total' => $this->model->count(),
                'pending' => $this->model->where('status', 'pending')->count(),
                'proses' => $this->model->where('status', 'proses')->count(),
                'selesai' => $this->model->where('status', 'selesai')->count(),
                'this_month' => $this->model->whereMonth('created_at', now()->month)->count(),
                'last_month' => $this->model->whereMonth('created_at', now()->subMonth()->month)->count(),
            ];
        });
    }
}
