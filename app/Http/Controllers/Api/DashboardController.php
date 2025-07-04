<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfoKantor;
use App\Models\Wbs;
use App\Models\PortalPapuaTengah;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(): JsonResponse
    {
        $stats = Cache::remember('dashboard_stats', 300, function () {
            // Using Query Builder for heavy aggregation queries as per requirements
            $wbsStats = DB::table('wbs')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END) as in_progress'),
                    DB::raw('SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved'),
                    DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
                )
                ->first();

            // Using Eloquent for simple counts
            $totalInfoKantor = InfoKantor::count();
            $totalPortalPapuaTengah = PortalPapuaTengah::count();
            $activePortalPapuaTengah = PortalPapuaTengah::published()->count();
            $activeInfoKantor = InfoKantor::active()->count();

            // Recent WBS dengan Eager Loading
            $recentWbs = Wbs::latest()
                ->take(5)
                ->get(['id', 'nama_pelapor', 'subjek', 'status', 'created_at']);

            return [
                'wbs' => [
                    'total' => $wbsStats->total,
                    'pending' => $wbsStats->pending,
                    'in_progress' => $wbsStats->in_progress,
                    'resolved' => $wbsStats->resolved,
                    'rejected' => $wbsStats->rejected,
                ],
                'info_kantor' => [
                    'total' => $totalInfoKantor,
                    'active' => $activeInfoKantor,
                ],
                'portal_papua_tengah' => [
                    'total' => $totalPortalPapuaTengah,
                    'active' => $activePortalPapuaTengah,
                ],
                'recent_wbs' => $recentWbs->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_pelapor' => $item->nama_pelapor,
                        'subjek' => $item->subjek,
                        'status' => $item->status,
                        'created_at' => $item->created_at->diffForHumans(),
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get WBS statistics by month (for charts)
     */
    public function wbsChart(): JsonResponse
    {
        $data = Cache::remember('wbs_chart', 600, function () {
            return DB::table('wbs')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year)),
                        'value' => $item->total
                    ];
                });
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
