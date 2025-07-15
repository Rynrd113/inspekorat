<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log activity for authenticated users
        if (Auth::check() && $this->shouldLog($request)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Determine if the request should be logged.
     */
    protected function shouldLog(Request $request): bool
    {
        // Don't log certain routes
        $skipRoutes = [
            'api/health',
            'api/status',
            'telescope',
            'horizon',
        ];

        $path = $request->path();

        foreach ($skipRoutes as $route) {
            if (str_starts_with($path, $route)) {
                return false;
            }
        }

        // Only log specific HTTP methods
        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    /**
     * Log the activity.
     */
    protected function logActivity(Request $request, Response $response): void
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $request->method() . ' ' . $request->path(),
                'table_name' => $this->extractTableName($request),
                'record_id' => $this->extractRecordId($request),
                'old_values' => json_encode($this->getOldValues($request)),
                'new_values' => json_encode($this->getNewValues($request)),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the request
            logger()->error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Extract table name from request.
     */
    protected function extractTableName(Request $request): ?string
    {
        $path = $request->path();
        
        // Extract from admin routes
        if (preg_match('/admin\/([^\/]+)/', $path, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Extract record ID from request.
     */
    protected function extractRecordId(Request $request): ?int
    {
        $path = $request->path();
        
        // Extract ID from URL patterns like /admin/pelayanan/123
        if (preg_match('/\/(\d+)(?:\/|$)/', $path, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Get old values from request.
     */
    protected function getOldValues(Request $request): array
    {
        // For update operations, we would need to fetch the old values
        // This is a simplified implementation
        return [];
    }

    /**
     * Get new values from request.
     */
    protected function getNewValues(Request $request): array
    {
        // Filter sensitive data
        $data = $request->all();
        $sensitiveFields = ['password', 'password_confirmation', 'token', '_token'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[FILTERED]';
            }
        }

        return $data;
    }
}
