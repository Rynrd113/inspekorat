<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PerformanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:report 
                            {--days=7 : Number of days to include in report}
                            {--format=table : Output format (table, json, csv)}
                            {--output= : Output file path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate performance report';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');
        $format = $this->option('format');
        $output = $this->option('output');

        $this->info("Generating performance report for the last {$days} days...");

        $data = $this->getPerformanceData($days);

        if (empty($data)) {
            $this->warn('No performance data found for the specified period.');
            return 0;
        }

        switch ($format) {
            case 'json':
                $this->outputJson($data, $output);
                break;
            case 'csv':
                $this->outputCsv($data, $output);
                break;
            default:
                $this->outputTable($data);
                break;
        }

        return 0;
    }

    /**
     * Get performance data from database
     */
    private function getPerformanceData(int $days): array
    {
        return DB::table('performance_logs')
            ->select([
                'url',
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('AVG(execution_time) as avg_execution_time'),
                DB::raw('MAX(execution_time) as max_execution_time'),
                DB::raw('MIN(execution_time) as min_execution_time'),
                DB::raw('AVG(memory_usage) as avg_memory_usage'),
                DB::raw('MAX(memory_usage) as max_memory_usage'),
                DB::raw('AVG(query_count) as avg_query_count'),
                DB::raw('MAX(query_count) as max_query_count'),
                DB::raw('COUNT(CASE WHEN http_status >= 400 THEN 1 END) as error_count')
            ])
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('url')
            ->orderBy('avg_execution_time', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Output data as table
     */
    private function outputTable(array $data): void
    {
        $headers = [
            'URL',
            'Requests',
            'Avg Time (ms)',
            'Max Time (ms)',
            'Min Time (ms)',
            'Avg Memory (MB)',
            'Max Memory (MB)',
            'Avg Queries',
            'Max Queries',
            'Errors'
        ];

        $rows = [];
        foreach ($data as $row) {
            $rows[] = [
                $this->truncateUrl($row->url),
                $row->total_requests,
                round($row->avg_execution_time, 2),
                round($row->max_execution_time, 2),
                round($row->min_execution_time, 2),
                round($row->avg_memory_usage, 2),
                round($row->max_memory_usage, 2),
                round($row->avg_query_count, 0),
                $row->max_query_count,
                $row->error_count
            ];
        }

        $this->table($headers, $rows);

        // Summary statistics
        $this->info('Summary Statistics:');
        $this->line('Total unique URLs: ' . count($data));
        $this->line('Total requests: ' . array_sum(array_column($data, 'total_requests')));
        $this->line('Average execution time: ' . round(array_sum(array_column($data, 'avg_execution_time')) / count($data), 2) . 'ms');
        $this->line('Total errors: ' . array_sum(array_column($data, 'error_count')));
    }

    /**
     * Output data as JSON
     */
    private function outputJson(array $data, ?string $output): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);

        if ($output) {
            file_put_contents($output, $json);
            $this->info("Performance report saved to: {$output}");
        } else {
            $this->line($json);
        }
    }

    /**
     * Output data as CSV
     */
    private function outputCsv(array $data, ?string $output): void
    {
        $csv = "URL,Total Requests,Avg Execution Time,Max Execution Time,Min Execution Time,Avg Memory Usage,Max Memory Usage,Avg Query Count,Max Query Count,Error Count\n";

        foreach ($data as $row) {
            $csv .= implode(',', [
                '"' . $row->url . '"',
                $row->total_requests,
                round($row->avg_execution_time, 2),
                round($row->max_execution_time, 2),
                round($row->min_execution_time, 2),
                round($row->avg_memory_usage, 2),
                round($row->max_memory_usage, 2),
                round($row->avg_query_count, 0),
                $row->max_query_count,
                $row->error_count
            ]) . "\n";
        }

        if ($output) {
            file_put_contents($output, $csv);
            $this->info("Performance report saved to: {$output}");
        } else {
            $this->line($csv);
        }
    }

    /**
     * Truncate URL for display
     */
    private function truncateUrl(string $url, int $length = 50): string
    {
        if (strlen($url) <= $length) {
            return $url;
        }

        return substr($url, 0, $length - 3) . '...';
    }
}
