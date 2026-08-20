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
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
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

            $pengaduanStats = DB::table('pengaduans')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as proses'),
                    DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as selesai')
                )
                ->addBinding(['pending', 'proses', 'selesai'])
                ->first();

            $contentStats = DB::table(function ($q) {
                $q->selectRaw("'pelayanan' as type, COUNT(*) as total, SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active")->from('pelayanans')
                    ->unionAll(function ($q2) {
                        $q2->selectRaw("'dokumen' as type, COUNT(*), SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)")->from('dokumens');
                    })
                    ->unionAll(function ($q3) {
                        $q3->selectRaw("'galeri' as type, COUNT(*), SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)")->from('galeris');
                    })
                    ->unionAll(function ($q4) {
                        $q4->selectRaw("'faq' as type, COUNT(*), SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)")->from('faqs');
                    });
            })->get();

            $contentMap = collect($contentStats)->pluck('total', 'type');
            $activeMap = collect($contentStats)->pluck('active', 'type');

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
                    'total' => InfoKantor::count(),
                    'active' => InfoKantor::active()->count(),
                ],
                'portal_papua_tengah' => [
                    'total' => PortalPapuaTengah::count(),
                    'active' => PortalPapuaTengah::published()->count(),
                ],
                'portal_opd' => [
                    'total' => PortalOpd::count(),
                    'active' => PortalOpd::active()->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'admin' => User::whereIn('role', ['super_admin', 'admin'])->count(),
                ],
                'content' => [
                    'pelayanan' => ['total' => $contentMap['pelayanan'] ?? 0, 'active' => $activeMap['pelayanan'] ?? 0],
                    'dokumen' => ['total' => $contentMap['dokumen'] ?? 0, 'active' => $activeMap['dokumen'] ?? 0],
                    'galeri' => ['total' => $contentMap['galeri'] ?? 0, 'active' => $activeMap['galeri'] ?? 0],
                    'faq' => ['total' => $contentMap['faq'] ?? 0, 'active' => $activeMap['faq'] ?? 0],
                ],
            ];
        });

        $recentWbs = Wbs::with([])->latest()
            ->take(5)
            ->get(['id', 'nama_pelapor', 'is_anonymous', 'subjek', 'status', 'created_at']);

        $recentNews = PortalPapuaTengah::latest()
            ->take(3)
            ->get(['id', 'judul', 'is_published', 'created_at']);

        return view('admin.dashboard', compact('stats', 'recentWbs', 'recentNews'));
    }
}
