<?php

namespace App\Providers;

use App\Events\PelayananCreated;
use App\Events\PelayananUpdated;
use App\Events\PelayananDeleted;
use App\Listeners\LogPelayananActivity;
use App\Listeners\NotifyPelayananCreated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        PelayananCreated::class => [
            LogPelayananActivity::class,
            NotifyPelayananCreated::class,
        ],
        PelayananUpdated::class => [
            LogPelayananActivity::class,
        ],
        PelayananDeleted::class => [
            LogPelayananActivity::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
