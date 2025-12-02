<?php

namespace App\Observers;

use App\Models\Pelayanan;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Cache;

class PelayananObserver
{
    public function created(Pelayanan $pelayanan)
    {
        $this->logActivity($pelayanan, 'created');
        $this->clearCache($pelayanan);
    }

    public function updated(Pelayanan $pelayanan)
    {
        $this->logActivity($pelayanan, 'updated', $pelayanan->getOriginal());
        $this->clearCache($pelayanan);
    }

    public function deleted(Pelayanan $pelayanan)
    {
        $this->logActivity($pelayanan, 'deleted');
        $this->clearCache($pelayanan);
    }

    private function logActivity(Pelayanan $pelayanan, string $action, ?array $oldValues = null)
    {
        // Run in background to avoid slowing down the response
        try {
            AuditLog::create([
                'user_id' => auth()->id() ?? 1,
                'event' => $action,
                'auditable_type' => 'App\Models\Pelayanan',
                'auditable_id' => $pelayanan->id,
                'old_values' => $oldValues,
                'new_values' => $pelayanan->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            \Log::error('Failed to create audit log: ' . $e->getMessage());
        }
    }

    private function clearCache(Pelayanan $pelayanan)
    {
        // Only clear specific cache keys, not all cache
        Cache::forget('pelayanans.all');
        Cache::forget('pelayanans.active');
        Cache::forget('pelayanans.kategori.' . $pelayanan->kategori);
        Cache::forget('public_latest_pelayanan');
    }
}
