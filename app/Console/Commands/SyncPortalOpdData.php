<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PortalOpd;
use Illuminate\Support\Facades\Cache;

class SyncPortalOpdData extends Command
{
    protected $signature = 'portal-opd:sync {--clear-cache}';
    protected $description = 'Sync Portal OPD data and clear cache if needed';

    public function handle()
    {
        $this->info('Syncing Portal OPD data...');
        
        // Clear cache if option is provided
        if ($this->option('clear-cache')) {
            $this->info('Clearing cache...');
            Cache::forget('portal_opds.all');
            Cache::forget('portal_opds.active');
            
            // Clear all portal_opd specific cache
            $cacheKeys = ['portal_opds.all', 'portal_opds.active'];
            foreach (PortalOpd::all() as $opd) {
                $cacheKeys[] = "portal_opds.{$opd->id}";
            }
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
            $this->info('Cache cleared successfully.');
        }
        
        // Get and display data stats
        $totalCount = PortalOpd::count();
        $activeCount = PortalOpd::active()->count();
        
        $this->info("Total Portal OPDs: {$totalCount}");
        $this->info("Active Portal OPDs: {$activeCount}");
        
        // Display recent OPDs
        $recentOpds = PortalOpd::latest()->take(5)->get(['id', 'nama_opd', 'status', 'created_at']);
        
        if ($recentOpds->count() > 0) {
            $this->info('Recent Portal OPDs:');
            $this->table(
                ['ID', 'Nama OPD', 'Status', 'Created At'],
                $recentOpds->map(function ($opd) {
                    return [
                        $opd->id,
                        $opd->nama_opd,
                        $opd->status ? 'Active' : 'Inactive',
                        $opd->created_at->format('Y-m-d H:i:s')
                    ];
                })->toArray()
            );
        }
        
        $this->info('Sync completed successfully!');
        
        return 0;
    }
}
