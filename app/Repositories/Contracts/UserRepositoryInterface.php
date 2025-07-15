<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function create(array $data): User;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByRole(string $role): Collection;
    public function getAdmins(): Collection;
    public function getUsersWithRole(array $roles): Collection;
    public function updateLastLogin(int $id): bool;
    public function changePassword(int $id, string $password): bool;
    public function getActiveUsers(): Collection;
    public function searchUsers(string $search): Collection;
}
