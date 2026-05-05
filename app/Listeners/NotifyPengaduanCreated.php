<?php

namespace App\Listeners;

use App\Events\PengaduanCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyPengaduanCreated
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

            // Get all admins and super admins
            $admins = User::whereIn('role', ['admin', 'super_admin'])
                ->where('email', '!=', 'anonim@system.local')
                ->get();

            Log::info('Notifying ' . $admins->count() . ' admins about new pengaduan', [
                'pengaduan_id' => $pengaduan->id,
                'subjek' => $pengaduan->subjek
            ]);

            // Send email notification to each admin
            foreach ($admins as $admin) {
                try {
                    Mail::send('emails.pengaduan-notification', [
                        'pengaduan' => $pengaduan,
                        'admin' => $admin,
                    ], function ($message) use ($admin, $pengaduan) {
                        $message->to($admin->email)
                            ->subject('[LAPORAN] Pengaduan Masyarakat Masuk - ' . $pengaduan->subjek);
                    });

                    Log::info('Email notification sent to admin', [
                        'admin_email' => $admin->email,
                        'pengaduan_id' => $pengaduan->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send email to admin', [
                        'admin_email' => $admin->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Pengaduan notification completed', [
                'pengaduan_id' => $pengaduan->id,
                'admins_notified' => $admins->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in NotifyPengaduanCreated listener', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

