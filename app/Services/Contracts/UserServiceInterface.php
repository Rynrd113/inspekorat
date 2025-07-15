<?php

namespace App\Services\Contracts;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function createUser(StoreUserRequest $request): User;
    public function updateUser(int $id, UpdateUserRequest $request): bool;
    public function deleteUser(int $id): bool;
    public function getUserById(int $id): ?User;
    public function getUserByEmail(string $email): ?User;
    public function getAdminUsers(): Collection;
    public function changeUserRole(int $userId, string $role): bool;
    public function activateUser(int $userId): bool;
    public function deactivateUser(int $userId): bool;
    
    /**
     * Get users by role
     */
    public function getUsersByRole(string $role, int $perPage = 15): LengthAwarePaginator;

    /**
     * Search users
     */
    public function searchUsers(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Update user last login
     */
    public function updateLastLogin(int $id): bool;

    /**
     * Change user password
     */
    public function changePassword(int $id, string $newPassword): bool;

    /**
     * Get user statistics
     */
    public function getUserStats(): array;
}
