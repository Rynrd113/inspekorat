<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfoKantor;
use App\Models\Wbs;
use App\Models\PortalPapuaTengah;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index(): View
    {
        // Cache dashboard stats for 5 minutes
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            // Using Query Builder for heavy aggregation as per requirements
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
            ];
        });

        // Recent WBS dengan Eager Loading
        $recentWbs = Wbs::latest()
            ->take(5)
            ->get(['id', 'nama_pelapor', 'subjek', 'status', 'created_at']);

        return view('admin.dashboard', compact('stats', 'recentWbs'));
    }
}
