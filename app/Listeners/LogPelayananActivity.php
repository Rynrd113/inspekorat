<?php

namespace App\Listeners;

use App\Events\PelayananCreated;
use App\Events\PelayananUpdated;
use App\Events\PelayananDeleted;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Cache;

class LogPelayananActivity
{
    public function handle(object $event): void
    {
        $pelayanan = $event->pelayanan;

        $eventName = match (true) {
            $event instanceof PelayananCreated => 'created',
            $event instanceof PelayananUpdated => 'updated',
            $event instanceof PelayananDeleted => 'deleted',
            default => 'unknown',
        };

        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $eventName,
            'auditable_type' => 'App\Models\Pelayanan',
            'auditable_id' => $pelayanan->id,
            'new_values' => $pelayanan->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        Cache::forget('pelayanans.all');
        Cache::forget('pelayanans.active');
        if ($pelayanan->kategori) {
            Cache::forget('pelayanans.kategori.' . $pelayanan->kategori);
        }
    }
}
