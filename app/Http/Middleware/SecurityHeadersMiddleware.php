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
        $isDevelopment = config('app.env') === 'local';
        
        if ($isDevelopment) {
            // Get potential Vite dev server ports
            $vitePorts = $this->getViteDevServerPorts();
            
            // Development CSP - more permissive for Vite
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' " . $vitePorts['script'] . "; " .
                   "style-src 'self' 'unsafe-inline' " . $vitePorts['style'] . " https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data: https:; " .
                   "font-src 'self' https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "connect-src 'self' " . $vitePorts['connect'] . "; " .
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
    
    /**
     * Get Vite dev server ports for CSP
     */
    private function getViteDevServerPorts(): array
    {
        // Common Vite ports - 5173 is default, but it can use 5174, 5175, etc.
        $ports = [5173, 5174, 5175, 5176, 5177];
        
        $httpPorts = [];
        $wsPorts = [];
        
        foreach ($ports as $port) {
            $httpPorts[] = "http://localhost:$port";
            $wsPorts[] = "ws://localhost:$port";
        }
        
        return [
            'script' => implode(' ', array_merge($httpPorts, $wsPorts)),
            'style' => implode(' ', $httpPorts),
            'connect' => implode(' ', array_merge($httpPorts, $wsPorts))
        ];
    }
}
