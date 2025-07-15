<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoring
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // Process the request
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        // Calculate performance metrics
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = ($endMemory - $startMemory) / 1024 / 1024; // Convert to MB
        $peakMemory = memory_get_peak_usage() / 1024 / 1024; // Convert to MB
        
        // Get additional metrics
        $queryCount = $this->getQueryCount();
        $httpStatus = $response->getStatusCode();
        $contentLength = strlen($response->getContent());
        
        // Create performance data array
        $performanceData = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'execution_time' => round($executionTime, 2),
            'memory_usage' => round($memoryUsage, 2),
            'peak_memory' => round($peakMemory, 2),
            'query_count' => $queryCount,
            'http_status' => $httpStatus,
            'content_length' => $contentLength,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ];
        
        // Log performance data
        $this->logPerformanceData($performanceData);
        
        // Add performance headers to response
        $response->headers->set('X-Execution-Time', $executionTime . 'ms');
        $response->headers->set('X-Memory-Usage', $memoryUsage . 'MB');
        $response->headers->set('X-Query-Count', $queryCount);
        
        // Add performance data to response for debugging (only in debug mode)
        if (config('app.debug')) {
            $response->headers->set('X-Performance-Data', json_encode($performanceData));
        }
        
        // Check for performance issues and alert if necessary
        $this->checkPerformanceThresholds($performanceData);
        
        return $response;
    }
    
    /**
     * Get the number of database queries executed
     */
    private function getQueryCount(): int
    {
        return count(\DB::getQueryLog());
    }
    
    /**
     * Log performance data
     */
    private function logPerformanceData(array $data): void
    {
        // Log to performance channel
        Log::channel('performance')->info('Request Performance', $data);
        
        // Store in database for analysis (optional)
        if (config('app.store_performance_data')) {
            $this->storePerformanceData($data);
        }
    }
    
    /**
     * Store performance data in database
     */
    private function storePerformanceData(array $data): void
    {
        try {
            \DB::table('performance_logs')->insert([
                'url' => $data['url'],
                'method' => $data['method'],
                'execution_time' => $data['execution_time'],
                'memory_usage' => $data['memory_usage'],
                'peak_memory' => $data['peak_memory'],
                'query_count' => $data['query_count'],
                'http_status' => $data['http_status'],
                'content_length' => $data['content_length'],
                'user_id' => $data['user_id'],
                'ip_address' => $data['ip_address'],
                'user_agent' => $data['user_agent'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to store performance data: ' . $e->getMessage());
        }
    }
    
    /**
     * Check performance thresholds and alert if necessary
     */
    private function checkPerformanceThresholds(array $data): void
    {
        $thresholds = config('performance.thresholds', [
            'execution_time' => 1000, // 1 second
            'memory_usage' => 50, // 50MB
            'query_count' => 20
        ]);
        
        $alerts = [];
        
        if ($data['execution_time'] > $thresholds['execution_time']) {
            $alerts[] = "High execution time: {$data['execution_time']}ms";
        }
        
        if ($data['memory_usage'] > $thresholds['memory_usage']) {
            $alerts[] = "High memory usage: {$data['memory_usage']}MB";
        }
        
        if ($data['query_count'] > $thresholds['query_count']) {
            $alerts[] = "High query count: {$data['query_count']}";
        }
        
        if (!empty($alerts)) {
            Log::warning('Performance Alert', [
                'url' => $data['url'],
                'alerts' => $alerts,
                'data' => $data
            ]);
            
            // Send notification to monitoring service
            $this->sendPerformanceAlert($data, $alerts);
        }
    }
    
    /**
     * Send performance alert to monitoring service
     */
    private function sendPerformanceAlert(array $data, array $alerts): void
    {
        // This could integrate with services like Slack, Discord, or email
        // For now, we'll just log it
        Log::alert('Performance threshold exceeded', [
            'url' => $data['url'],
            'alerts' => $alerts,
            'execution_time' => $data['execution_time'],
            'memory_usage' => $data['memory_usage'],
            'query_count' => $data['query_count']
        ]);
    }
}
