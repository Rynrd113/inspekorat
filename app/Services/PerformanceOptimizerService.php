<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerformanceOptimizerService
{
    protected ImageManager $imageManager;
    
    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }
    
    /**
     * Optimize an image for web display
     */
    public function optimizeImage(string $path, array $options = []): string
    {
        $options = array_merge([
            'width' => config('performance.assets.image_optimization.max_width', 1920),
            'height' => config('performance.assets.image_optimization.max_height', 1080),
            'quality' => config('performance.assets.image_optimization.quality', 85),
            'format' => 'webp',
            'fallback' => 'jpg',
            'lazy' => true,
            'placeholder' => true,
        ], $options);
        
        $cacheKey = 'optimized_image_' . md5($path . serialize($options));
        
        return Cache::remember($cacheKey, config('performance.assets.caching.cache_duration'), function () use ($path, $options) {
            return $this->processImageOptimization($path, $options);
        });
    }
    
    /**
     * Process image optimization
     */
    private function processImageOptimization(string $path, array $options): string
    {
        if (!Storage::exists($path)) {
            return $this->generatePlaceholder($options);
        }
        
        $image = $this->imageManager->read(Storage::path($path));
        
        // Resize if needed
        if ($options['width'] || $options['height']) {
            $image->resize($options['width'], $options['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        // Generate WebP version if supported
        $webpPath = $this->generateWebPVersion($image, $path, $options);
        
        // Generate fallback version
        $fallbackPath = $this->generateFallbackVersion($image, $path, $options);
        
        // Return optimized image HTML
        return $this->generateOptimizedImageHtml($webpPath, $fallbackPath, $options);
    }
    
    /**
     * Generate WebP version of image
     */
    private function generateWebPVersion($image, string $originalPath, array $options): string
    {
        $webpPath = $this->getOptimizedPath($originalPath, 'webp');
        
        if (!Storage::exists($webpPath)) {
            $webpImage = clone $image;
            $webpContent = $webpImage->toWebp($options['quality']);
            Storage::put($webpPath, $webpContent);
        }
        
        return Storage::url($webpPath);
    }
    
    /**
     * Generate fallback version of image
     */
    private function generateFallbackVersion($image, string $originalPath, array $options): string
    {
        $fallbackPath = $this->getOptimizedPath($originalPath, $options['fallback']);
        
        if (!Storage::exists($fallbackPath)) {
            $fallbackImage = clone $image;
            
            if ($options['fallback'] === 'jpg') {
                $fallbackContent = $fallbackImage->toJpeg($options['quality']);
            } else {
                $fallbackContent = $fallbackImage->toPng();
            }
            
            Storage::put($fallbackPath, $fallbackContent);
        }
        
        return Storage::url($fallbackPath);
    }
    
    /**
     * Get optimized image path
     */
    private function getOptimizedPath(string $originalPath, string $extension): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        return $directory . '/optimized/' . $filename . '_optimized.' . $extension;
    }
    
    /**
     * Generate optimized image HTML
     */
    private function generateOptimizedImageHtml(string $webpPath, string $fallbackPath, array $options): string
    {
        $html = '<picture>';
        
        // WebP source
        $html .= '<source srcset="' . $webpPath . '" type="image/webp">';
        
        // Fallback source
        $html .= '<source srcset="' . $fallbackPath . '" type="image/' . $options['fallback'] . '">';
        
        // Img tag
        $imgAttributes = [
            'src' => $fallbackPath,
            'alt' => $options['alt'] ?? '',
            'loading' => $options['lazy'] ? 'lazy' : 'eager',
            'decoding' => 'async',
            'class' => 'responsive-image performance-optimized'
        ];
        
        if ($options['width']) {
            $imgAttributes['width'] = $options['width'];
        }
        
        if ($options['height']) {
            $imgAttributes['height'] = $options['height'];
        }
        
        $html .= '<img ' . $this->buildAttributes($imgAttributes) . '>';
        $html .= '</picture>';
        
        return $html;
    }
    
    /**
     * Generate placeholder image
     */
    private function generatePlaceholder(array $options): string
    {
        $width = $options['width'] ?? 300;
        $height = $options['height'] ?? 200;
        
        return '<div class="image-placeholder" style="width: ' . $width . 'px; height: ' . $height . 'px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #666;">
            <span>Image not found</span>
        </div>';
    }
    
    /**
     * Build HTML attributes string
     */
    private function buildAttributes(array $attributes): string
    {
        $html = [];
        
        foreach ($attributes as $key => $value) {
            $html[] = $key . '="' . htmlspecialchars($value) . '"';
        }
        
        return implode(' ', $html);
    }
    
    /**
     * Optimize CSS delivery
     */
    public function optimizeCSSDelivery(string $css): string
    {
        // Minify CSS
        $css = $this->minifyCSS($css);
        
        // Extract critical CSS
        $criticalCSS = $this->extractCriticalCSS($css);
        
        // Generate optimized CSS delivery
        return $this->generateOptimizedCSSDelivery($criticalCSS, $css);
    }
    
    /**
     * Minify CSS
     */
    private function minifyCSS(string $css): string
    {
        // Remove comments
        $css = preg_replace('/\/\*.*?\*\//s', '', $css);
        
        // Remove whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        $css = str_replace([' {', '{ ', ' }', '} ', ': ', ' :', '; ', ' ;'], ['{', '{', '}', '}', ':', ':', ';', ';'], $css);
        
        return trim($css);
    }
    
    /**
     * Extract critical CSS
     */
    private function extractCriticalCSS(string $css): string
    {
        // This is a simplified version - in production, you'd use tools like Puppeteer
        // to analyze the above-the-fold content
        
        $criticalSelectors = [
            'body', 'html', 'main', 'header', 'nav', 'h1', 'h2', 'h3', 'p', 'a',
            '.container', '.wrapper', '.content', '.header', '.nav', '.main',
            '.btn', '.button', '.form', '.input', '.critical'
        ];
        
        $criticalCSS = '';
        
        foreach ($criticalSelectors as $selector) {
            $pattern = '/' . preg_quote($selector, '/') . '\s*\{[^}]*\}/';
            preg_match_all($pattern, $css, $matches);
            
            foreach ($matches[0] as $match) {
                $criticalCSS .= $match . "\n";
            }
        }
        
        return $criticalCSS;
    }
    
    /**
     * Generate optimized CSS delivery
     */
    private function generateOptimizedCSSDelivery(string $criticalCSS, string $fullCSS): string
    {
        $html = '<style>' . $criticalCSS . '</style>';
        
        // Load non-critical CSS asynchronously
        $html .= '<link rel="preload" href="' . asset('css/app.css') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        $html .= '<noscript><link rel="stylesheet" href="' . asset('css/app.css') . '"></noscript>';
        
        return $html;
    }
    
    /**
     * Optimize JavaScript delivery
     */
    public function optimizeJavaScriptDelivery(array $scripts): string
    {
        $html = '';
        
        foreach ($scripts as $script) {
            if ($script['critical'] ?? false) {
                $html .= '<script src="' . $script['src'] . '"></script>';
            } else {
                $html .= '<script src="' . $script['src'] . '" defer></script>';
            }
        }
        
        return $html;
    }
    
    /**
     * Generate resource hints
     */
    public function generateResourceHints(array $resources): string
    {
        $html = '';
        
        foreach ($resources as $resource) {
            $rel = $resource['rel'] ?? 'preload';
            $href = $resource['href'];
            $as = $resource['as'] ?? '';
            $type = $resource['type'] ?? '';
            $crossorigin = $resource['crossorigin'] ?? false;
            
            $html .= '<link rel="' . $rel . '" href="' . $href . '"';
            
            if ($as) {
                $html .= ' as="' . $as . '"';
            }
            
            if ($type) {
                $html .= ' type="' . $type . '"';
            }
            
            if ($crossorigin) {
                $html .= ' crossorigin';
            }
            
            $html .= '>';
        }
        
        return $html;
    }
    
    /**
     * Cleanup old optimized assets
     */
    public function cleanupOptimizedAssets(): void
    {
        $optimizedDirectories = [
            'public/storage/optimized',
            'storage/app/public/optimized'
        ];
        
        foreach ($optimizedDirectories as $directory) {
            if (Storage::exists($directory)) {
                $files = Storage::allFiles($directory);
                
                foreach ($files as $file) {
                    $lastModified = Storage::lastModified($file);
                    $maxAge = config('performance.assets.caching.cache_duration', 86400);
                    
                    if (time() - $lastModified > $maxAge) {
                        Storage::delete($file);
                    }
                }
            }
        }
    }
}
