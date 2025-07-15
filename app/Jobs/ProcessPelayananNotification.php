<?php

namespace App\Jobs;

use App\Models\Pelayanan;
use App\Models\AuditLog;
use App\Mail\PelayananNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class ProcessPelayananNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pelayanan;
    protected $action;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(Pelayanan $pelayanan, string $action, int $userId)
    {
        $this->pelayanan = $pelayanan;
        $this->action = $action;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Log audit activity
            $this->logAuditActivity();

            // Send notification to admins
            $this->sendNotificationToAdmins();

            // Update cache
            $this->updateCache();

            // Generate reports if needed
            if ($this->action === 'created') {
                $this->generateReports();
            }

        } catch (\Exception $e) {
            // Log error
            \Log::error('ProcessPelayananNotification failed', [
                'pelayanan_id' => $this->pelayanan->id,
                'action' => $this->action,
                'error' => $e->getMessage(),
            ]);

            // Re-throw to mark job as failed
            throw $e;
        }
    }

    /**
     * Log audit activity
     */
    private function logAuditActivity(): void
    {
        AuditLog::create([
            'user_id' => $this->userId,
            'action' => $this->action,
            'model_type' => 'App\Models\Pelayanan',
            'model_id' => $this->pelayanan->id,
            'new_values' => $this->pelayanan->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Send notification to admins
     */
    private function sendNotificationToAdmins(): void
    {
        $admins = \App\Models\User::whereIn('role', ['admin', 'superadmin', 'service_manager'])
                                  ->where('email_verified_at', '!=', null)
                                  ->select(['id', 'name', 'email', 'role'])
                                  ->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(
                new PelayananNotification($this->pelayanan, $this->action, $admin)
            );
        }
    }

    /**
     * Update cache
     */
    private function updateCache(): void
    {
        Cache::forget('pelayanans.all');
        Cache::forget('pelayanans.active');
        Cache::forget('pelayanans.kategori.' . $this->pelayanan->kategori);
        Cache::forget('pelayanans.public');
    }

    /**
     * Generate reports
     */
    private function generateReports(): void
    {
        // Generate monthly service report
        $monthlyStats = [
            'total_services' => Pelayanan::count(),
            'active_services' => Pelayanan::where('status', true)->count(),
            'by_category' => Pelayanan::groupBy('kategori')
                                    ->selectRaw('kategori, count(*) as count')
                                    ->pluck('count', 'kategori')
                                    ->toArray(),
        ];

        Cache::put('pelayanan_monthly_stats', $monthlyStats, 86400); // 24 hours
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        \Log::error('ProcessPelayananNotification job failed', [
            'pelayanan_id' => $this->pelayanan->id,
            'action' => $this->action,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
