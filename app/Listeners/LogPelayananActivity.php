<?php

namespace App\Listeners;

use App\Events\PelayananCreated;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Cache;

class LogPelayananActivity
{
    public function handle(PelayananCreated $event)
    {
        // Log activity
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model_type' => 'App\Models\Pelayanan',
            'model_id' => $event->pelayanan->id,
            'new_values' => $event->pelayanan->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        // Clear related cache
        Cache::forget('pelayanans.all');
        Cache::forget('pelayanans.active');
        Cache::forget('pelayanans.kategori.' . $event->pelayanan->kategori);
    }
}
