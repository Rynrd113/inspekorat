<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Events\QueryExecuted;
use App\Http\Middleware\PerformanceMonitoring;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register performance monitoring middleware
        $this->app->singleton(PerformanceMonitoring::class);
        
        // Register performance-related services
        $this->app->bind('performance.monitor', function ($app) {
            return new \App\Services\PerformanceMonitorService();
        });
        
        $this->app->bind('performance.optimizer', function ($app) {
            return new \App\Services\PerformanceOptimizerService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enable query logging if configured
        if (config('performance.database.query_log')) {
            DB::enableQueryLog();
        }
        
        // Listen for slow queries
        if (config('performance.database.slow_query_threshold')) {
            DB::listen(function (QueryExecuted $query) {
                $threshold = config('performance.database.slow_query_threshold');
                
                if ($query->time > $threshold) {
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                        'connection' => $query->connectionName,
                    ]);
                }
            });
        }
        
        // Share performance data with views
        View::composer('*', function ($view) {
            if (config('app.debug')) {
                $view->with('performance_data', [
                    'memory_usage' => $this->formatBytes(memory_get_usage()),
                    'peak_memory' => $this->formatBytes(memory_get_peak_usage()),
                    'execution_time' => $this->getExecutionTime(),
                    'query_count' => count(DB::getQueryLog()),
                ]);
            }
        });
        
        // Register performance monitoring for specific routes
        $this->registerPerformanceMonitoring();
        
        // Setup asset optimization
        $this->setupAssetOptimization();
        
        // Setup caching optimization
        $this->setupCachingOptimization();
    }
    
    /**
     * Register performance monitoring middleware
     */
    private function registerPerformanceMonitoring(): void
    {
        $router = $this->app['router'];
        
        // Apply performance monitoring to web routes
        $router->pushMiddlewareToGroup('web', PerformanceMonitoring::class);
        
        // Apply performance monitoring to API routes
        $router->pushMiddlewareToGroup('api', PerformanceMonitoring::class);
    }
    
    /**
     * Setup asset optimization
     */
    private function setupAssetOptimization(): void
    {
        if (config('performance.assets.lazy_loading.enabled')) {
            // Register lazy loading view directive
            \Blade::directive('lazyImage', function ($expression) {
                return "<?php echo view('components.lazy-image', $expression); ?>";
            });
        }
        
        if (config('performance.assets.image_optimization.enabled')) {
            // Register image optimization helper
            \Blade::directive('optimizedImage', function ($expression) {
                return "<?php echo app('performance.optimizer')->optimizeImage($expression); ?>";
            });
        }
    }
    
    /**
     * Setup caching optimization
     */
    private function setupCachingOptimization(): void
    {
        if (config('performance.caching.enabled')) {
            // Configure cache serialization
            $serialization = config('performance.caching.serialization');
            
            if ($serialization === 'igbinary' && extension_loaded('igbinary')) {
                config(['cache.serializer' => 'igbinary']);
            }
            
            // Configure cache compression
            if (config('performance.caching.compression')) {
                config(['cache.compress' => true]);
            }
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Get execution time since application start
     */
    private function getExecutionTime(): string
    {
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : $_SERVER['REQUEST_TIME_FLOAT'];
        $executionTime = (microtime(true) - $startTime) * 1000;
        
        return round($executionTime, 2) . 'ms';
    }
}
