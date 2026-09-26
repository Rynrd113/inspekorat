<?php

namespace App\Listeners;

use App\Events\PengaduanUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyPengaduanUpdated implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(PengaduanUpdated $event): void
    {
        try {
            $pengaduan = $event->pengaduan;
            $oldStatus = $event->oldStatus;
            $newStatus = $event->newStatus;

            // Only send notification if status actually changed
            if ($oldStatus === $newStatus) {
                Log::info('Pengaduan status unchanged, skipping notification', [
                    'pengaduan_id' => $pengaduan->id,
                    'status' => $newStatus
                ]);
                return;
            }

            // Skip notification for anonymous reports (no email to send to)
            if ($pengaduan->is_anonymous || $pengaduan->email === 'anonim@system.local') {
                Log::info('Anonymous pengaduan, skipping reporter notification', [
                    'pengaduan_id' => $pengaduan->id
                ]);
                return;
            }

            // Send email notification to reporter
            try {
                Mail::send('emails.pengaduan-status-updated', [
                    'pengaduan' => $pengaduan,
                    'oldStatus' => $oldStatus,
                    'newStatus' => $newStatus,
                ], function ($message) use ($pengaduan, $oldStatus, $newStatus) {
                    $message->to($pengaduan->email, $pengaduan->nama_pengadu)
                        ->subject('[INFO] Update Status Pengaduan #' . $pengaduan->id . ' - ' . ucfirst($newStatus));
                });

                Log::info('Status update notification sent to reporter', [
                    'pengaduan_id' => $pengaduan->id,
                    'reporter_email' => $pengaduan->email,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to send status update email to reporter', [
                    'pengaduan_id' => $pengaduan->id,
                    'reporter_email' => $pengaduan->email,
                    'error' => $e->getMessage()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in NotifyPengaduanUpdated listener', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}