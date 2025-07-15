<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Hash password
            $data['password'] = Hash::make($data['password']);
            
            // Set default values
            $data['is_active'] = $data['is_active'] ?? true;
            $data['email_verified_at'] = now();

            // Create user
            $user = User::create($data);

            // Log activity
            AuditLog::log('created', $user, null, $user->toArray());

            return $user;
        });
    }
}
