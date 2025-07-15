<?php

namespace App\Services\Implementation;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getPaginated($perPage, $filters);
    }

    public function createUser(StoreUserRequest $request): User
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            
            $user = $this->userRepository->create($data);

            // Log activity
            AuditLog::log('created', $user, null, $user->toArray());

            return $user;
        });
    }

    public function updateUser(int $id, UpdateUserRequest $request): bool
    {
        return DB::transaction(function () use ($id, $request) {
            $user = $this->userRepository->findById($id);
            
            if (!$user) {
                return false;
            }

            $oldData = $user->toArray();
            $data = $request->validated();
            
            // Hash password if provided
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $result = $this->userRepository->update($id, $data);

            // Log activity
            if ($result) {
                AuditLog::log('updated', $user->fresh(), $oldData, $data);
            }

            return $result;
        });
    }

    public function deleteUser(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $user = $this->userRepository->findById($id);
            
            if (!$user) {
                return false;
            }

            $result = $this->userRepository->delete($id);

            // Log activity
            if ($result) {
                AuditLog::log('deleted', $user, $user->toArray(), null);
            }

            return $result;
        });
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function getAdminUsers(): Collection
    {
        return $this->userRepository->getAdmins();
    }

    public function changeUserRole(int $userId, string $role): bool
    {
        return DB::transaction(function () use ($userId, $role) {
            $user = $this->userRepository->findById($userId);
            
            if (!$user) {
                return false;
            }

            $oldRole = $user->role;
            $result = $this->userRepository->update($userId, ['role' => $role]);

            // Log activity
            if ($result) {
                AuditLog::log('role_changed', $user->fresh(), 
                    ['role' => $oldRole], 
                    ['role' => $role]
                );
            }

            return $result;
        });
    }

    public function activateUser(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->userRepository->findById($userId);
            
            if (!$user) {
                return false;
            }

            $result = $this->userRepository->update($userId, ['is_active' => true]);

            // Log activity
            if ($result) {
                AuditLog::log('activated', $user->fresh(), 
                    ['is_active' => false], 
                    ['is_active' => true]
                );
            }

            return $result;
        });
    }

    public function deactivateUser(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->userRepository->findById($userId);
            
            if (!$user) {
                return false;
            }

            $result = $this->userRepository->update($userId, ['is_active' => false]);

            // Log activity
            if ($result) {
                AuditLog::log('deactivated', $user->fresh(), 
                    ['is_active' => true], 
                    ['is_active' => false]
                );
            }

            return $result;
        });
    }

    public function getUsersByRole(string $role, int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getUsersByRole($role, $perPage);
    }

    public function searchUsers(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->searchUsersPaginated($query, $perPage);
    }

    public function updateLastLogin(int $id): bool
    {
        return $this->userRepository->updateLastLogin($id);
    }

    public function changePassword(int $id, string $newPassword): bool
    {
        return DB::transaction(function () use ($id, $newPassword) {
            $user = $this->userRepository->findById($id);
            
            if (!$user) {
                return false;
            }

            $hashedPassword = Hash::make($newPassword);
            $result = $this->userRepository->update($id, ['password' => $hashedPassword]);

            // Log activity
            if ($result) {
                AuditLog::log('password_changed', $user->fresh(), null, null);
            }

            return $result;
        });
    }

    public function getUserStats(): array
    {
        return $this->userRepository->getUserStats();
    }
}
