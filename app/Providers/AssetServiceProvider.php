<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\AssetHelper;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AssetHelper::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Blade directives untuk asset optimization
        $this->registerBladeDirectives();
        
        // Register view composers untuk asset optimization
        $this->registerViewComposers();
    }

    /**
     * Register custom Blade directives
     */
    private function registerBladeDirectives(): void
    {
        // Directive untuk preload assets
        Blade::directive('preload', function ($expression) {
            return "<?php echo App\\Helpers\\AssetHelper::preload($expression); ?>";
        });

        // Directive untuk critical CSS
        Blade::directive('criticalCss', function () {
            return "<?php echo App\\Helpers\\AssetHelper::criticalCss(); ?>";
        });

        // Directive untuk lazy loading images
        Blade::directive('lazyImage', function ($expression) {
            return "<?php echo App\\Helpers\\AssetHelper::lazyImage($expression); ?>";
        });

        // Directive untuk WebP images
        Blade::directive('webpImage', function ($expression) {
            return "<?php echo App\\Helpers\\AssetHelper::webpImage($expression); ?>";
        });

        // Directive untuk resource hints
        Blade::directive('resourceHints', function () {
            return "<?php echo App\\Helpers\\AssetHelper::resourceHints(); ?>";
        });

        // Directive untuk service worker
        Blade::directive('serviceWorker', function () {
            return "<?php echo App\\Helpers\\AssetHelper::serviceWorker(); ?>";
        });

        // Directive untuk critical preloads
        Blade::directive('criticalPreloads', function () {
            return "<?php echo App\\Helpers\\AssetHelper::criticalPreloads(); ?>";
        });

        // Directive untuk optimized asset URL
        Blade::directive('assetOptimized', function ($expression) {
            return "<?php echo App\\Helpers\\AssetHelper::asset($expression); ?>";
        });
    }

    /**
     * Register view composers
     */
    private function registerViewComposers(): void
    {
        view()->composer('*', function ($view) {
            $view->with('assetHelper', app(AssetHelper::class));
        });
    }
}
