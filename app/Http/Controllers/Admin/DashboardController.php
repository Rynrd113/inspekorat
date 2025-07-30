<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfoKantor;
use App\Models\Wbs;
use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\User;
use App\Models\Pengaduan;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Faq;
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
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as in_progress'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as resolved'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejected')
                )
                ->addBinding(['pending', 'in_progress', 'resolved', 'rejected'])
                ->first();

            // Using Eloquent for simple counts
            $totalInfoKantor = InfoKantor::count();
            $totalPortalPapuaTengah = PortalPapuaTengah::count();
            $activePortalPapuaTengah = PortalPapuaTengah::published()->count();
            $activeInfoKantor = InfoKantor::active()->count();
            
            // Portal OPD stats
            $totalPortalOpd = PortalOpd::count();
            $activePortalOpd = PortalOpd::active()->count();
            
            // User stats
            $totalUsers = User::count();
            $adminUsers = User::where('role', 'LIKE', '%admin%')->count();

            // Pengaduan stats
            $pengaduanStats = DB::table('pengaduans')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as proses'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as selesai')
                )
                ->addBinding(['pending', 'proses', 'selesai'])
                ->first();

            // Content stats  
            $totalPelayanan = Pelayanan::count();
            $activePelayanan = Pelayanan::where('status', true)->count();
            $totalDokumen = Dokumen::count();
            $activeDokumen = Dokumen::where('status', true)->count();
            $totalGaleri = Galeri::count();
            $activeGaleri = Galeri::where('status', true)->count();
            $totalFaq = Faq::count();
            $activeFaq = Faq::where('status', true)->count();

            return [
                'wbs' => [
                    'total' => $wbsStats->total,
                    'pending' => $wbsStats->pending,
                    'in_progress' => $wbsStats->in_progress,
                    'resolved' => $wbsStats->resolved,
                    'rejected' => $wbsStats->rejected,
                ],
                'pengaduan' => [
                    'total' => $pengaduanStats->total,
                    'pending' => $pengaduanStats->pending,
                    'proses' => $pengaduanStats->proses,
                    'selesai' => $pengaduanStats->selesai,
                ],
                'info_kantor' => [
                    'total' => $totalInfoKantor,
                    'active' => $activeInfoKantor,
                ],
                'portal_papua_tengah' => [
                    'total' => $totalPortalPapuaTengah,
                    'active' => $activePortalPapuaTengah,
                ],
                'portal_opd' => [
                    'total' => $totalPortalOpd,
                    'active' => $activePortalOpd,
                ],
                'users' => [
                    'total' => $totalUsers,
                    'admin' => $adminUsers,
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
