<?php

namespace App\Actions\User;

use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetUsersByRoleAction
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function execute(Request $request): JsonResponse
    {
        try {
            $role = $request->get('role');
            $perPage = $request->get('per_page', 15);

            if (empty($role)) {
                return response()->json(
                    ApiResponse::error('Role is required', 400),
                    400
                );
            }

            $users = $this->userService->getUsersByRole($role, $perPage);

            return response()->json(
                ApiResponse::paginated($users, 'Users retrieved successfully')
            );
        } catch (\Exception $e) {
            Log::error('Failed to get users by role: ' . $e->getMessage(), [
                'exception' => $e,
                'role' => $request->get('role')
            ]);

            return response()->json(
                ApiResponse::error('Failed to get users by role', 500),
                500
            );
        }
    }
}
