<?php

namespace App\Actions\User;

use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchUsersAction
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function execute(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $perPage = $request->get('per_page', 15);

            if (empty($query)) {
                return response()->json(
                    ApiResponse::error('Search query is required', 400),
                    400
                );
            }

            $users = $this->userService->searchUsers($query, $perPage);

            return response()->json(
                ApiResponse::paginated($users, 'Users found successfully')
            );
        } catch (\Exception $e) {
            Log::error('Failed to search users: ' . $e->getMessage(), [
                'exception' => $e,
                'query' => $request->get('q')
            ]);

            return response()->json(
                ApiResponse::error('Failed to search users', 500),
                500
            );
        }
    }
}
