<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');
        
        // Content Security Policy
        $isDevelopment = in_array(config('app.env'), ['local', 'dusk.local', 'testing']);
        
        if ($isDevelopment) {
            // Development CSP - allow Vite dev server and local development
            $csp = "default-src 'self' http://localhost:* http://127.0.0.1:*; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:*; " .
                   "script-src-elem 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:*; " .
                   "style-src 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:* https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "style-src-elem 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:* https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data: https: http: http://localhost:* http://127.0.0.1:*; " .
                   "font-src 'self' https://fonts.bunny.net https://cdnjs.cloudflare.com http://localhost:* http://127.0.0.1:*; " .
                   "connect-src 'self' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*; " .
                   "frame-ancestors 'none';";
        } else {
            // Production CSP - more restrictive
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data: https:; " .
                   "font-src 'self' https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "connect-src 'self'; " .
                   "frame-ancestors 'none';";
        }
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
