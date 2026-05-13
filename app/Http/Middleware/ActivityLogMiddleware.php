<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogMiddleware
{
    protected static array $tableMap = [
        'wbs'                  => 'wbs',
        'review-opd'           => 'review_opd',
        'pengaduan'            => 'pengaduans',
        'pelayanan'            => 'pelayanans',
        'system-configuration' => 'system_configurations',
        'configurations'       => 'system_configurations',
        'dokumen'              => 'dokumens',
        'galeri'               => 'galeris',
        'portal-papua-tengah'  => 'portal_papua_tengahs',
        'portal-opd'           => 'portal_opds',
        'users'                => 'users',
        'albums'               => 'albums',
        'hero-sliders'         => 'hero_sliders',
        'faq'                  => 'faqs',
        'profil'               => 'profils',
        'web-portal'           => 'web_portals',
        'info-kantor'          => 'info_kantors',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $oldValues = [];
        if (Auth::check() && $this->shouldLog($request) && in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {
            $oldValues = $this->fetchOldValues($request);
        }

        $response = $next($request);

        if (Auth::check() && $this->shouldLog($request)) {
            $this->logActivity($request, $response, $oldValues);
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

    protected function logActivity(Request $request, Response $response, array $oldValues = []): void
    {
        try {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => $request->method() . ' ' . $request->path(),
                'table_name' => $this->extractTableName($request),
                'record_id'  => $this->extractRecordId($request),
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($this->getNewValues($request)),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            logger()->error('Failed to log activity: ' . $e->getMessage());
        }
    }

    protected function fetchOldValues(Request $request): array
    {
        $segment  = $this->extractTableName($request);
        $recordId = $this->extractRecordId($request);

        if (!$segment || !$recordId || !isset(self::$tableMap[$segment])) {
            return [];
        }

        $record = DB::table(self::$tableMap[$segment])->find($recordId);
        return $record ? (array) $record : [];
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
