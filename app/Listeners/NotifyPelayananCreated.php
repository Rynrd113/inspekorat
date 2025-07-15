<?php

namespace App\Listeners;

use App\Events\PelayananCreated;
use App\Jobs\ProcessPelayananNotification;
use Illuminate\Support\Facades\Cache;

class NotifyPelayananCreated
{
    /**
     * Handle the event.
     */
    public function handle(PelayananCreated $event): void
    {
        // Dispatch background job for processing
        ProcessPelayananNotification::dispatch(
            $event->pelayanan,
            'created',
            auth()->id()
        );

        // Clear cache immediately
        Cache::forget('pelayanans.all');
        Cache::forget('pelayanans.active');
        Cache::forget('pelayanans.kategori.' . $event->pelayanan->kategori);
        Cache::forget('pelayanans.public');
    }
}
