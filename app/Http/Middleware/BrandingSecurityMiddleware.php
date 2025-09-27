<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandingSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only allow super admin and specific roles
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has permission to access branding settings
        // Allow super admin, admin, and any authenticated user for now (can be restricted later)
        if (!$user->isSuperAdmin() && !$user->hasRole(['admin', 'branding_manager']) && !$user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengubah branding.');
        }

        // Additional security checks for file uploads
        if ($request->hasFile('image')) {
            $this->validateUploadSecurity($request);
        }

        // Rate limiting for branding changes
        $this->checkRateLimit($request);

        return $next($request);
    }

    /**
     * Validate upload security
     */
    private function validateUploadSecurity(Request $request): void
    {
        $file = $request->file('image');
        
        if (!$file) {
            return;
        }

        // Check file size (additional to form validation)
        if ($file->getSize() > 2 * 1024 * 1024) { // 2MB
            abort(413, 'File terlalu besar');
        }

        // Check MIME type
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png', 
            'image/svg+xml',
            'image/webp',
            'image/x-icon'
        ];

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            abort(415, 'Tipe file tidak didukung');
        }

        // Additional security: check for embedded scripts in SVG
        if ($file->getMimeType() === 'image/svg+xml') {
            $content = file_get_contents($file->getPathname());
            $dangerousPatterns = [
                '<script',
                'javascript:',
                'onload=',
                'onclick=',
                'onerror=',
                'onmouseover=',
                '<iframe',
                '<object',
                '<embed'
            ];

            foreach ($dangerousPatterns as $pattern) {
                if (stripos($content, $pattern) !== false) {
                    abort(422, 'File SVG mengandung konten yang tidak diizinkan');
                }
            }
        }
    }

    /**
     * Check rate limiting for branding changes
     */
    private function checkRateLimit(Request $request): void
    {
        $key = 'branding_changes_' . auth()->id();
        $maxAttempts = 10; // Max 10 changes per hour
        $decayMinutes = 60;

        if (cache()->has($key)) {
            $attempts = cache()->get($key, 0);
            
            if ($attempts >= $maxAttempts) {
                abort(429, 'Terlalu banyak perubahan branding. Coba lagi dalam 1 jam.');
            }
            
            cache()->put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        } else {
            cache()->put($key, 1, now()->addMinutes($decayMinutes));
        }
    }
}