<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?User
    {
        return Cache::remember("users.{$id}", 600, function () use ($id) {
            return $this->model->find($id);
        });
    }

    public function findByEmail(string $email): ?User
    {
        return Cache::remember("users.email.{$email}", 600, function () use ($email) {
            return $this->model->where('email', $email)->first();
        });
    }

    public function getAll(): Collection
    {
        return Cache::remember('users.all', 600, function () {
            return $this->model->orderBy('name')->get();
        });
    }

    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): User
    {
        $user = $this->model->create($data);
        
        // Clear cache
        Cache::forget('users.all');
        
        return $user;
    }

    public function update(int $id, array $data): bool
    {
        $result = $this->model->find($id)->update($data);
        
        // Clear cache
        Cache::forget('users.all');
        Cache::forget("users.{$id}");
        
        return $result;
    }

    public function delete(int $id): bool
    {
        $result = $this->model->find($id)->delete();
        
        // Clear cache
        Cache::forget('users.all');
        Cache::forget("users.{$id}");
        
        return $result;
    }

    public function getByRole(string $role): Collection
    {
        return Cache::remember("users.role.{$role}", 600, function () use ($role) {
            return $this->model->where('role', $role)->orderBy('name')->get();
        });
    }

    public function getAdmins(): Collection
    {
        return Cache::remember('users.admins', 600, function () {
            return $this->model->whereIn('role', [
                'admin', 'super_admin', 'content_manager', 'service_manager', 
                'opd_manager', 'wbs_manager', 'admin_wbs', 'admin_berita', 
                'admin_portal_opd', 'admin_pelayanan', 'admin_dokumen', 
                'admin_galeri', 'admin_faq'
            ])->orderBy('name')->get();
        });
    }

    public function searchUsers(string $search): Collection
    {
        return $this->model->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%')
                          ->orderBy('name')
                          ->get();
    }

    public function getUsersByRole(string $role, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('role', $role)
                          ->orderBy('name')
                          ->paginate($perPage);
    }

    public function updateLastLogin(int $id): bool
    {
        $result = $this->model->find($id)->update(['last_login' => now()]);
        
        // Clear cache
        Cache::forget("users.{$id}");
        
        return $result;
    }

    public function searchUsersPaginated(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('name', 'like', '%' . $query . '%')
                          ->orWhere('email', 'like', '%' . $query . '%')
                          ->orderBy('name')
                          ->paginate($perPage);
    }

    public function getUserStats(): array
    {
        return Cache::remember('users.stats', 3600, function () {
            return [
                'total' => $this->model->count(),
                'active' => $this->model->where('status', 'active')->count(),
                'inactive' => $this->model->where('status', 'inactive')->count(),
                'admins' => $this->model->whereIn('role', [
                    'admin', 'super_admin', 'content_manager', 'service_manager',
                    'opd_manager', 'wbs_manager', 'admin_wbs', 'admin_berita',
                    'admin_portal_opd', 'admin_pelayanan', 'admin_dokumen',
                    'admin_galeri', 'admin_faq'
                ])->count(),
                'users' => $this->model->where('role', 'user')->count(),
                'recent_logins' => $this->model->whereNotNull('last_login')
                                                ->where('last_login', '>=', now()->subDays(30))
                                                ->count(),
            ];
        });
    }
}
