<?php

namespace App\Observers;

use App\Events\PengaduanCreated;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Log;

class PengaduanObserver
{
    /**
     * Handle the Pengaduan "created" event.
     */
    public function created(Pengaduan $pengaduan): void
    {
        try {
            Log::info('PengaduanObserver - created event triggered', [
                'pengaduan_id' => $pengaduan->id,
                'subjek' => $pengaduan->subjek
            ]);

            // Dispatch the event
            event(new PengaduanCreated($pengaduan));
        } catch (\Exception $e) {
            Log::error('Error in PengaduanObserver created method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the Pengaduan "updated" event.
     */
    public function updated(Pengaduan $pengaduan): void
    {
        Log::info('Pengaduan updated', [
            'pengaduan_id' => $pengaduan->id,
            'status' => $pengaduan->status
        ]);
    }

    /**
     * Handle the Pengaduan "deleted" event.
     */
    public function deleted(Pengaduan $pengaduan): void
    {
        Log::info('Pengaduan deleted', [
            'pengaduan_id' => $pengaduan->id
        ]);
    }
}

