<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pelayanan;
use App\Observers\PelayananObserver;

// Repository Contracts
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WbsRepositoryInterface;
use App\Repositories\Contracts\PortalOpdRepositoryInterface;
use App\Repositories\Contracts\PelayananRepositoryInterface;

// Repository Implementations
use App\Repositories\Implementation\UserRepository;
use App\Repositories\Implementation\WbsRepository;
use App\Repositories\Implementation\PortalOpdRepository;
use App\Repositories\Implementation\PelayananRepository;

// Service Contracts
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\WbsServiceInterface;
use App\Services\Contracts\PelayananServiceInterface;

// Service Implementations
use App\Services\Implementation\UserService;
use App\Services\Implementation\WbsService;
use App\Services\Implementation\PelayananService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Repository bindings
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WbsRepositoryInterface::class, WbsRepository::class);
        $this->app->bind(PortalOpdRepositoryInterface::class, PortalOpdRepository::class);
        $this->app->bind(PelayananRepositoryInterface::class, PelayananRepository::class);

        // Register Service bindings
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(WbsServiceInterface::class, WbsService::class);
        $this->app->bind(PelayananServiceInterface::class, PelayananService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Pelayanan::observe(PelayananObserver::class);

        // Handle forwarded URLs for port forwarding (GitHub Codespaces, VS Code, etc.)
        if (request()->hasHeader('X-Forwarded-Proto') || request()->hasHeader('X-Forwarded-Host')) {
            \URL::forceScheme('https');
            
            // Set secure proxy headers with proper Laravel 12 constants
            request()->setTrustedProxies(['*'], 
                \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
            );
        }
    }
}
