<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only process JSON responses for API routes
        if ($request->is('api/*') && $response->headers->get('Content-Type') === 'application/json') {
            $content = $response->getContent();
            $data = json_decode($content, true);

            // If response is already formatted, return as is
            if (isset($data['success'])) {
                return $response;
            }

            // Format successful responses
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $formatted = [
                    'success' => true,
                    'message' => 'Success',
                    'data' => $data,
                    'timestamp' => now()->toISOString()
                ];

                $response->setContent(json_encode($formatted));
            }
        }

        return $response;
    }
}
