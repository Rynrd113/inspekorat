<?php

namespace App\Actions\User;

use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GetUserStatsAction
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function execute(): JsonResponse
    {
        try {
            $stats = $this->userService->getUserStats();

            return response()->json(
                ApiResponse::success($stats, 'User statistics retrieved successfully')
            );
        } catch (\Exception $e) {
            Log::error('Failed to get user statistics: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json(
                ApiResponse::error('Failed to get user statistics', 500),
                500
            );
        }
    }
}
