<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PortalOpd;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DebugPortalOpdData extends Command
{
    protected $signature = 'portal-opd:debug';
    protected $description = 'Debug Portal OPD data and database connectivity';

    public function handle()
    {
        $this->info('=== Portal OPD Debug Information ===');
        
        try {
            // Test database connection
            $this->info('Testing database connection...');
            $connectionName = config('database.default');
            $this->info("Using connection: {$connectionName}");
            
            // Test basic query
            $dbTime = DB::selectOne('SELECT NOW() as db_time');
            $this->info("Database time: {$dbTime->db_time}");
            
            // Check tables
            $this->info('Checking portal_opds table...');
            $tableExists = DB::select("SHOW TABLES LIKE 'portal_opds'");
            if (empty($tableExists)) {
                $this->error('Table portal_opds does not exist!');
                return 1;
            }
            
            $this->info('✓ Table portal_opds exists');
            
            // Count records
            $totalCount = PortalOpd::count();
            $this->info("Total Portal OPD records: {$totalCount}");
            
            if ($totalCount === 0) {
                $this->warn('No Portal OPD records found. Running seeder...');
                $this->call('db:seed', ['--class' => 'PortalOpdSeeder']);
                $totalCount = PortalOpd::count();
                $this->info("After seeding - Total records: {$totalCount}");
            }
            
            // Check active records
            $activeCount = PortalOpd::where('status', true)->count();
            $this->info("Active Portal OPD records: {$activeCount}");
            
            // Show recent records
            $this->info('Recent Portal OPD records:');
            $records = PortalOpd::latest()->take(5)->get(['id', 'nama_opd', 'status', 'created_at']);
            
            if ($records->count() > 0) {
                $this->table(
                    ['ID', 'Nama OPD', 'Status', 'Created'],
                    $records->map(function ($record) {
                        return [
                            $record->id,
                            substr($record->nama_opd, 0, 30) . '...',
                            $record->status ? 'Active' : 'Inactive',
                            $record->created_at->format('Y-m-d H:i')
                        ];
                    })->toArray()
                );
            }
            
            // Check cache
            $this->info('Checking cache...');
            $cacheDriver = config('cache.default');
            $this->info("Cache driver: {$cacheDriver}");
            
            // Test cache operations
            $cacheKey = 'portal_opds.debug_test';
            Cache::put($cacheKey, 'test_value', 60);
            $cacheValue = Cache::get($cacheKey);
            
            if ($cacheValue === 'test_value') {
                $this->info('✓ Cache is working');
                Cache::forget($cacheKey);
            } else {
                $this->warn('⚠ Cache may have issues');
            }
            
            // Check for cached data
            $cachedActive = Cache::get('portal_opds.active');
            $this->info('Cached active OPDs: ' . ($cachedActive ? $cachedActive->count() : 'Not cached'));
            
            // Environment info
            $this->info('Environment Information:');
            $this->info('App Environment: ' . app()->environment());
            $this->info('Debug Mode: ' . (config('app.debug') ? 'Enabled' : 'Disabled'));
            
            $this->info('=== Debug Complete ===');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Debug failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
