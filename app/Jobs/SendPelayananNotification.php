<?php

namespace App\Jobs;

use App\Models\Pelayanan;
use App\Models\User;
use App\Mail\PelayananCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPelayananNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pelayanan;
    protected $action;

    public function __construct(Pelayanan $pelayanan, string $action = 'created')
    {
        $this->pelayanan = $pelayanan;
        $this->action = $action;
    }

    public function handle()
    {
        // Get admins who should be notified
        $admins = User::whereIn('role', ['admin', 'superadmin'])
                     ->where('email_verified_at', '!=', null)
                     ->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(
                new PelayananCreatedNotification($this->pelayanan, $admin, $this->action)
            );
        }
    }

    public function failed(\Exception $exception)
    {
        // Log failed job
        \Log::error('Failed to send pelayanan notification', [
            'pelayanan_id' => $this->pelayanan->id,
            'action' => $this->action,
            'error' => $exception->getMessage(),
        ]);
    }
}
