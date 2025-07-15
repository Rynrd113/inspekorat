<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\PerformanceOptimizerService;

class PerformanceCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:cleanup 
                            {--days=30 : Number of days to keep performance logs}
                            {--assets : Cleanup old optimized assets}
                            {--logs : Cleanup old performance logs}
                            {--all : Cleanup everything}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old performance logs and optimized assets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');
        $cleanupAssets = $this->option('assets');
        $cleanupLogs = $this->option('logs');
        $cleanupAll = $this->option('all');

        if ($cleanupAll) {
            $cleanupAssets = true;
            $cleanupLogs = true;
        }

        if (!$cleanupAssets && !$cleanupLogs && !$cleanupAll) {
            $this->error('Please specify what to cleanup: --assets, --logs, or --all');
            return 1;
        }

        $this->info('Starting performance cleanup...');

        if ($cleanupLogs) {
            $this->cleanupPerformanceLogs($days);
        }

        if ($cleanupAssets) {
            $this->cleanupOptimizedAssets();
        }

        $this->info('Performance cleanup completed!');
        
        return 0;
    }

    /**
     * Cleanup old performance logs
     */
    private function cleanupPerformanceLogs(int $days): void
    {
        $this->info("Cleaning up performance logs older than {$days} days...");

        try {
            $deleted = DB::table('performance_logs')
                ->where('created_at', '<', now()->subDays($days))
                ->delete();

            $this->info("Deleted {$deleted} old performance log records.");
        } catch (\Exception $e) {
            $this->error("Error cleaning up performance logs: {$e->getMessage()}");
        }
    }

    /**
     * Cleanup old optimized assets
     */
    private function cleanupOptimizedAssets(): void
    {
        $this->info('Cleaning up old optimized assets...');

        try {
            $optimizer = app(PerformanceOptimizerService::class);
            $optimizer->cleanupOptimizedAssets();
            
            $this->info('Cleaned up old optimized assets.');
        } catch (\Exception $e) {
            $this->error("Error cleaning up optimized assets: {$e->getMessage()}");
        }
    }
}
