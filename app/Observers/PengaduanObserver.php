<?php

namespace App\Observers;

use App\Events\PengaduanCreated;
use App\Events\PengaduanUpdated;
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
        // Check if status or other important fields changed
        $changedFields = $pengaduan->getChanges();
        
        Log::info('Pengaduan updated', [
            'pengaduan_id' => $pengaduan->id,
            'status' => $pengaduan->status,
            'changed_fields' => array_keys($changedFields)
        ]);

        // Dispatch PengaduanUpdated event if status or tanggapan changed
        if (isset($changedFields['status']) || isset($changedFields['tanggapan'])) {
            $oldStatus = $pengaduan->getOriginal('status');
            
            try {
                event(new PengaduanUpdated($pengaduan, $oldStatus, array_keys($changedFields)));
            } catch (\Exception $e) {
                Log::error('Error dispatching PengaduanUpdated event', [
                    'pengaduan_id' => $pengaduan->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
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

