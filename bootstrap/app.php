<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'activity.log' => \App\Http\Middleware\ActivityLogMiddleware::class,
            'api.format' => \App\Http\Middleware\FormatApiResponse::class,
            'api.errors' => \App\Http\Middleware\HandleApiErrors::class,
            'api.rate.limit' => \App\Http\Middleware\ApiRateLimitMiddleware::class,
            'db.optimize' => \App\Http\Middleware\DatabaseQueryOptimizationMiddleware::class,
            'asset.optimize' => \App\Http\Middleware\AssetOptimizationMiddleware::class,
        ]);
        
        $middleware->append([
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\AssetOptimizationMiddleware::class,
        ]);

        // Configure authentication redirects
        $middleware->redirectGuestsTo(function () {
            // Only redirect to admin login if on admin routes
            if (request()->is('admin*')) {
                return route('admin.login');
            }
            return route('public.index');
        });
        
        // API middleware group with rate limiting and performance monitoring
        $middleware->group('api', [
            \App\Http\Middleware\HandleApiErrors::class,
            \App\Http\Middleware\FormatApiResponse::class,
            \App\Http\Middleware\ApiRateLimitMiddleware::class . ':120,1', // 120 requests per minute
            \App\Http\Middleware\DatabaseQueryOptimizationMiddleware::class,
        ]);
        
        // Web middleware group with database optimization and asset optimization
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\DatabaseQueryOptimizationMiddleware::class,
            \App\Http\Middleware\AssetOptimizationMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
