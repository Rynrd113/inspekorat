<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetOptimizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Apply optimizations only for HTML responses
        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            $content = $response->getContent();
            
            // Apply optimizations
            $content = $this->optimizeHtml($content);
            $content = $this->addResourceHints($content);
            $content = $this->optimizeImages($content);
            $content = $this->addCriticalCss($content);
            
            $response->setContent($content);
        }

        // Add performance headers
        $response->headers->add([
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ]);

        // Add caching headers for static assets
        if ($this->isStaticAsset($request)) {
            $response->headers->add([
                'Cache-Control' => 'public, max-age=31536000, immutable',
                'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000),
            ]);
        }

        return $response;
    }

    /**
     * Optimize HTML content
     */
    private function optimizeHtml(string $content): string
    {
        if (config('app.env') === 'production') {
            // Remove unnecessary whitespace and comments
            $content = preg_replace('/<!--.*?-->/s', '', $content);
            $content = preg_replace('/\s+/', ' ', $content);
            $content = preg_replace('/>\s+</', '><', $content);
        }
        
        return $content;
    }

    /**
     * Add resource hints to HTML
     */
    private function addResourceHints(string $content): string
    {
        $hints = [
            '<link rel="dns-prefetch" href="//fonts.googleapis.com">',
            '<link rel="dns-prefetch" href="//fonts.gstatic.com">',
            '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>',
            '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>',
        ];
        
        $hintsString = implode("\n    ", $hints);
        
        return str_replace('<head>', "<head>\n    " . $hintsString, $content);
    }

    /**
     * Optimize images in HTML
     */
    private function optimizeImages(string $content): string
    {
        // Add lazy loading to images
        $content = preg_replace(
            '/<img([^>]*?)src=(["\'])([^"\']*?)\2([^>]*?)>/i',
            '<img$1src=$2$3$2$4 loading="lazy" decoding="async">',
            $content
        );
        
        // Add WebP support for modern browsers
        $content = preg_replace_callback(
            '/<img([^>]*?)src=(["\'])([^"\']*?)\.(jpg|jpeg|png)\2([^>]*?)>/i',
            function ($matches) {
                $fullSrc = $matches[3] . '.' . $matches[4];
                $webpSrc = str_replace('.' . $matches[4], '.webp', $fullSrc);
                
                // Extract the path from the URL for file existence check
                $webpPath = parse_url($webpSrc, PHP_URL_PATH);
                $publicWebpPath = public_path($webpPath);
                
                // Only add WebP source if the WebP file exists
                if (file_exists($publicWebpPath)) {
                    return '<picture>
                        <source srcset="' . $webpSrc . '" type="image/webp">
                        <img' . $matches[1] . 'src=' . $matches[2] . $fullSrc . $matches[2] . $matches[5] . ' loading="lazy" decoding="async">
                    </picture>';
                } else {
                    // Just add lazy loading if no WebP version exists
                    return '<img' . $matches[1] . 'src=' . $matches[2] . $fullSrc . $matches[2] . $matches[5] . ' loading="lazy" decoding="async">';
                }
            },
            $content
        );
        
        return $content;
    }

    /**
     * Add critical CSS inline
     */
    private function addCriticalCss(string $content): string
    {
        $criticalCssPath = resource_path('css/critical.css');
        
        if (file_exists($criticalCssPath)) {
            $criticalCss = file_get_contents($criticalCssPath);
            $criticalCssTag = '<style id="critical-css">' . $criticalCss . '</style>';
            
            $content = str_replace('</head>', $criticalCssTag . "\n</head>", $content);
        }
        
        return $content;
    }

    /**
     * Check if request is for static asset
     */
    private function isStaticAsset(Request $request): bool
    {
        $path = $request->path();
        
        return preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot|ico)$/', $path);
    }
}
