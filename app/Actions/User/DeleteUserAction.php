<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DeleteUserAction
{
    public function execute(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $userData = $user->toArray();
            
            // Delete user
            $result = $user->delete();

            // Log activity
            if ($result) {
                AuditLog::log('deleted', $user, $userData, null);
            }

            return $result;
        });
    }
}
