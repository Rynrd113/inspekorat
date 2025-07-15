<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function execute(User $user, array $data): bool
    {
        return DB::transaction(function () use ($user, $data) {
            $oldData = $user->toArray();
            
            // Hash password if provided
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Update user
            $result = $user->update($data);

            // Log activity
            if ($result) {
                AuditLog::log('updated', $user->fresh(), $oldData, $data);
            }

            return $result;
        });
    }
}
