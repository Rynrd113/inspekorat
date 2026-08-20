<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthCheckController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
        ];

        try {
            DB::select('SELECT 1');
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $checks['database'] = 'error: ' . $e->getMessage();
            $checks['status'] = 'degraded';
        }

        try {
            Cache::put('_health_check', true, 10);
            Cache::get('_health_check');
            $checks['cache'] = 'ok';
        } catch (\Exception $e) {
            $checks['cache'] = 'error: ' . $e->getMessage();
            $checks['status'] = 'degraded';
        }

        $statusCode = $checks['status'] === 'ok' ? 200 : 503;

        return response()->json($checks, $statusCode);
    }
}
