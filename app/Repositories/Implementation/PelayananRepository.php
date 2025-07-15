<?php

namespace App\Repositories\Implementation;

use App\Models\Pelayanan;
use App\Repositories\Contracts\PelayananRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PelayananRepository implements PelayananRepositoryInterface
{
    protected $model;

    public function __construct(Pelayanan $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?Pelayanan
    {
        return $this->model->find($id);
    }

    public function getAll(): Collection
    {
        return Cache::remember('pelayanans.all', 3600, function () {
            return $this->model->with(['creator:id,name', 'updater:id,name'])
                              ->select([
                                  'id', 'nama_layanan', 'deskripsi', 'kategori', 
                                  'status', 'waktu_penyelesaian', 'biaya', 'urutan',
                                  'created_at', 'updated_at', 'created_by', 'updated_by'
                              ])
                              ->orderBy('urutan')
                              ->get();
        });
    }

    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_layanan', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('deskripsi', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('persyaratan', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Critical: Always eager load relationships to prevent N+1 queries
        return $query->with([
                         'creator:id,name,email',
                         'updater:id,name,email'
                     ])
                     ->select([
                         'id', 'nama_layanan', 'deskripsi', 'kategori', 'status', 
                         'waktu_penyelesaian', 'biaya', 'urutan', 'created_at', 
                         'updated_at', 'created_by', 'updated_by'
                     ])
                     ->latest()
                     ->paginate($perPage);
    }

    public function create(array $data): Pelayanan
    {
        $pelayanan = $this->model->create($data);
        
        // Clear cache
        Cache::forget('pelayanans.all');
        
        return $pelayanan;
    }

    public function update(int $id, array $data): bool
    {
        $result = $this->model->find($id)->update($data);
        
        // Clear cache
        Cache::forget('pelayanans.all');
        
        return $result;
    }

    public function delete(int $id): bool
    {
        $result = $this->model->find($id)->delete();
        
        // Clear cache
        Cache::forget('pelayanans.all');
        
        return $result;
    }

    public function getByKategori(string $kategori): Collection
    {
        return Cache::remember("pelayanans.kategori.{$kategori}", 600, function () use ($kategori) {
            return $this->model->where('kategori', $kategori)
                              ->where('status', true)
                              ->orderBy('urutan')
                              ->get();
        });
    }

    public function getActive(): Collection
    {
        return Cache::remember('pelayanans.active', 600, function () {
            return $this->model->where('status', true)
                              ->orderBy('urutan')
                              ->get();
        });
    }
}
