<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Repository Contracts
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WbsRepositoryInterface;
use App\Repositories\Contracts\PortalOpdRepositoryInterface;
use App\Repositories\Contracts\PortalPapuaTengahRepositoryInterface;
use App\Repositories\Contracts\PelayananRepositoryInterface;

// Repository Implementations
use App\Repositories\Implementation\UserRepository;
use App\Repositories\Implementation\WbsRepository;
use App\Repositories\Implementation\PortalOpdRepository;
use App\Repositories\Implementation\PortalPapuaTengahRepository;
use App\Repositories\Implementation\PelayananRepository;

// Service Contracts
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\WbsServiceInterface;
use App\Services\Contracts\PortalOpdServiceInterface;
use App\Services\Contracts\PortalPapuaTengahServiceInterface;
use App\Services\Contracts\PelayananServiceInterface;

// Service Implementations
use App\Services\Implementation\UserService;
use App\Services\Implementation\WbsService;
use App\Services\Implementation\PortalOpdService;
use App\Services\Implementation\PortalPapuaTengahService;
use App\Services\Implementation\PelayananService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WbsRepositoryInterface::class, WbsRepository::class);
        $this->app->bind(PortalOpdRepositoryInterface::class, PortalOpdRepository::class);
        $this->app->bind(PortalPapuaTengahRepositoryInterface::class, PortalPapuaTengahRepository::class);
        $this->app->bind(PelayananRepositoryInterface::class, PelayananRepository::class);

        // Register Services
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(WbsServiceInterface::class, WbsService::class);
        $this->app->bind(PortalOpdServiceInterface::class, PortalOpdService::class);
        $this->app->bind(PortalPapuaTengahServiceInterface::class, PortalPapuaTengahService::class);
        $this->app->bind(PelayananServiceInterface::class, PelayananService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
