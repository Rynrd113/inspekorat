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
            // Development CSP - allow Vite dev server (both HTTP and HTTPS, multiple ports)
            $viteHosts = "http://localhost:5173 https://localhost:5173 https://inspekorat.test:5173 https://vite.inspekorat.test:5173 http://localhost:5174 https://localhost:5174";
            $wsHosts = "ws://localhost:5173 wss://localhost:5173 wss://inspekorat.test:5173 ws://localhost:5174 wss://localhost:5174";
            
            $csp = "default-src 'self' {$viteHosts}; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$viteHosts}; " .
                   "script-src-elem 'self' 'unsafe-inline' {$viteHosts}; " .
                   "style-src 'self' 'unsafe-inline' {$viteHosts} https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "style-src-elem 'self' 'unsafe-inline' {$viteHosts} https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data: https: {$viteHosts}; " .
                   "font-src 'self' https://fonts.bunny.net https://cdnjs.cloudflare.com {$viteHosts}; " .
                   "connect-src 'self' {$viteHosts} {$wsHosts}; " .
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
