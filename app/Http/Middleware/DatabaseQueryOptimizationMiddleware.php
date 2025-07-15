<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DatabaseQueryOptimizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.env') !== 'production') {
            $startTime = microtime(true);
            $startQueries = count(DB::getQueryLog());
            
            // Enable query logging
            DB::enableQueryLog();
        }
        
        $response = $next($request);
        
        if (config('app.env') !== 'production') {
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
            
            $queries = DB::getQueryLog();
            $queryCount = count($queries) - $startQueries;
            
            // Log slow queries and performance metrics
            if ($executionTime > 500 || $queryCount > 50) {
                Log::warning('Slow request detected', [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'execution_time' => $executionTime . 'ms',
                    'query_count' => $queryCount,
                    'memory_usage' => memory_get_peak_usage(true) / 1024 / 1024 . 'MB'
                ]);
            }
            
            // Add performance headers for debugging
            $response->headers->add([
                'X-Query-Count' => $queryCount,
                'X-Execution-Time' => $executionTime . 'ms',
                'X-Memory-Usage' => memory_get_peak_usage(true) / 1024 / 1024 . 'MB'
            ]);
        }
        
        return $response;
    }
}
