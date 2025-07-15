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

    private function logActivity(Pelayanan $pelayanan, string $action, array $oldValues = null)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => 'App\Models\Pelayanan',
            'model_id' => $pelayanan->id,
            'old_values' => $oldValues,
            'new_values' => $pelayanan->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    private function clearCache(Pelayanan $pelayanan)
    {
        Cache::forget('pelayanans.all');
        Cache::forget('pelayanans.active');
        Cache::forget('pelayanans.kategori.' . $pelayanan->kategori);
        
        // Clear paginated cache
        Cache::flush();
    }
}
