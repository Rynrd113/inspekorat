<?php

namespace App\Listeners;

use App\Events\PengaduanCreated;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class LogPengaduanActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PengaduanCreated $event): void
    {
        try {
            $pengaduan = $event->pengaduan;

            // Create audit log for pengaduan creation
            AuditLog::create([
                'event' => 'create',
                'auditable_type' => 'App\Models\Pengaduan',
                'auditable_id' => $pengaduan->id,
                'user_id' => null, // Public submission
                'old_values' => null,
                'new_values' => $pengaduan->toArray(),
                'ip_address' => request()->ip(),
            ]);

            Log::info('Pengaduan activity logged', [
                'pengaduan_id' => $pengaduan->id,
                'action' => 'create'
            ]);

        } catch (\Exception $e) {
            Log::error('Error logging pengaduan activity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

