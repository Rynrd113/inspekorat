<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use App\Http\Resources\ApiResponse;

class FormatApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $response = $next($request);

        // Only format API responses
        if ($request->is('api/*')) {
            // Handle different response types
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                
                // Check if response is already formatted
                if (!isset($data['success']) || !isset($data['message'])) {
                    // Format unformatted responses
                    $formattedData = ApiResponse::success($data, 'Success');
                    $response->setData($formattedData);
                }
            }
        }

        return $response;
    }
}
