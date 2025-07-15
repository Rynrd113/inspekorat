<?php

namespace App\Helpers;

class AssetHelper
{
    /**
     * Generate optimized asset URLs with versioning
     */
    public static function asset(string $path, array $options = []): string
    {
        $url = asset($path);
        
        // Add version parameter for cache busting
        if (config('app.env') === 'production') {
            $version = config('app.version', '1.0.0');
            $url .= '?v=' . $version;
        }
        
        return $url;
    }

    /**
     * Generate preload links for critical assets
     */
    public static function preload(string $path, string $type = 'script'): string
    {
        $url = self::asset($path);
        
        $as = match($type) {
            'script' => 'script',
            'style' => 'style',
            'font' => 'font',
            'image' => 'image',
            default => 'script'
        };
        
        $crossorigin = $type === 'font' ? ' crossorigin' : '';
        
        return '<link rel="preload" href="' . $url . '" as="' . $as . '"' . $crossorigin . '>';
    }

    /**
     * Generate critical CSS inline for above-the-fold content
     */
    public static function criticalCss(): string
    {
        $criticalCssPath = resource_path('css/critical.css');
        
        if (file_exists($criticalCssPath)) {
            return '<style>' . file_get_contents($criticalCssPath) . '</style>';
        }
        
        return '';
    }

    /**
     * Generate lazy loading image tag
     */
    public static function lazyImage(string $src, string $alt = '', array $attributes = []): string
    {
        $defaultAttributes = [
            'loading' => 'lazy',
            'decoding' => 'async',
            'src' => self::asset($src),
            'alt' => $alt,
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $attributeString = '';
        foreach ($attributes as $key => $value) {
            $attributeString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }
        
        return '<img' . $attributeString . '>';
    }

    /**
     * Generate WebP image with fallback
     */
    public static function webpImage(string $src, string $alt = '', array $attributes = []): string
    {
        $webpSrc = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $src);
        $fallbackSrc = $src;
        
        $attributeString = '';
        foreach ($attributes as $key => $value) {
            $attributeString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }
        
        return '<picture>
            <source srcset="' . self::asset($webpSrc) . '" type="image/webp">
            <img src="' . self::asset($fallbackSrc) . '" alt="' . htmlspecialchars($alt) . '"' . $attributeString . ' loading="lazy" decoding="async">
        </picture>';
    }

    /**
     * Generate resource hints for performance
     */
    public static function resourceHints(): string
    {
        $hints = [];
        
        // DNS prefetch for external domains
        $hints[] = '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
        $hints[] = '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
        
        // Preconnect for critical external resources
        $hints[] = '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
        $hints[] = '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        
        return implode("\n", $hints);
    }

    /**
     * Generate service worker registration
     */
    public static function serviceWorker(): string
    {
        if (config('app.env') === 'production') {
            return '<script>
                if ("serviceWorker" in navigator) {
                    window.addEventListener("load", function() {
                        navigator.serviceWorker.register("/sw.js")
                            .then(function(registration) {
                                console.log("SW registered: ", registration);
                            })
                            .catch(function(registrationError) {
                                console.log("SW registration failed: ", registrationError);
                            });
                    });
                }
            </script>';
        }
        
        return '';
    }

    /**
     * Generate critical resources preload
     */
    public static function criticalPreloads(): string
    {
        $preloads = [];
        
        // Preload critical CSS
        $preloads[] = self::preload('build/assets/app.css', 'style');
        
        // Preload critical JavaScript
        $preloads[] = self::preload('build/assets/app.js', 'script');
        
        // Preload critical fonts
        $preloads[] = '<link rel="preload" href="' . self::asset('fonts/inter.woff2') . '" as="font" type="font/woff2" crossorigin>';
        
        return implode("\n", $preloads);
    }
}
