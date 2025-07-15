<?php

namespace App\Repositories\Implementation;

use App\Models\PortalOpd;
use App\Repositories\Contracts\PortalOpdRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PortalOpdRepository implements PortalOpdRepositoryInterface
{
    protected $model;

    public function __construct(PortalOpd $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?PortalOpd
    {
        return Cache::remember("portal_opds.{$id}", 600, function () use ($id) {
            return $this->model->with(['creator', 'updater'])->find($id);
        });
    }

    public function getAll(): Collection
    {
        return Cache::remember('portal_opds.all', 600, function () {
            return $this->model->with(['creator:id,name', 'updater:id,name'])->get();
        });
    }

    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where('nama_opd', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('singkatan', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('kepala_opd', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with(['creator:id,name', 'updater:id,name'])
                     ->latest()
                     ->paginate($perPage);
    }

    public function create(array $data): PortalOpd
    {
        $portalOpd = $this->model->create($data);
        
        // Clear cache
        Cache::forget('portal_opds.all');
        Cache::forget('portal_opds.active');
        
        return $portalOpd;
    }

    public function update(int $id, array $data): bool
    {
        $result = $this->model->find($id)->update($data);
        
        // Clear cache
        Cache::forget('portal_opds.all');
        Cache::forget('portal_opds.active');
        Cache::forget("portal_opds.{$id}");
        
        return $result;
    }

    public function delete(int $id): bool
    {
        $result = $this->model->find($id)->delete();
        
        // Clear cache
        Cache::forget('portal_opds.all');
        Cache::forget('portal_opds.active');
        Cache::forget("portal_opds.{$id}");
        
        return $result;
    }

    public function getActive(): Collection
    {
        return Cache::remember('portal_opds.active', 600, function () {
            return $this->model->where('status', true)->orderBy('nama_opd')->get();
        });
    }

    public function searchOpd(string $search): Collection
    {
        return $this->model->where('nama_opd', 'like', '%' . $search . '%')
                          ->orWhere('singkatan', 'like', '%' . $search . '%')
                          ->orWhere('kepala_opd', 'like', '%' . $search . '%')
                          ->orderBy('nama_opd')
                          ->get();
    }

    public function getByCreator(int $creatorId): Collection
    {
        return Cache::remember("portal_opds.creator.{$creatorId}", 600, function () use ($creatorId) {
            return $this->model->where('created_by', $creatorId)->orderBy('created_at', 'desc')->get();
        });
    }
}
