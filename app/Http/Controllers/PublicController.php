<?php

namespace App\Http\Controllers;

use App\Models\InfoKantor;
use App\Models\PortalPapuaTengah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Show the public landing page
     */
    public function index(): View
    {
        // Cache data public untuk performa - ambil hanya 5 berita terbaru
        $portalPapuaTengah = Cache::remember('public_portal_papua_tengah', 600, function () {
            return PortalPapuaTengah::published()->ordered()->take(5)->get();
        });

        // Info kantor statis
        $infoKantor = new \stdClass();
        $infoKantor->nama = 'Inspektorat Provinsi Papua Tengah';
        $infoKantor->alamat = 'Jl. Raya Nabire No. 123, Nabire, Papua Tengah';
        $infoKantor->telepon = '(0984) 21234';
        $infoKantor->email = 'inspektorat@paputengah.go.id';
        $infoKantor->jam_operasional = 'Senin - Jumat: 08:00 - 16:00 WIT';
        $infoKantor->koordinat = '-3.3683, 135.4956'; // Koordinat Nabire
        $infoKantor->website = 'https://inspektorat.paputengah.go.id';
        $infoKantor->fax = '(0984) 21235';

        return view('public.index', compact('portalPapuaTengah', 'infoKantor'));
    }

    /**
     * Show WBS form
     */
    public function wbs(): View
    {
        return view('public.wbs');
    }

    /**
     * Store WBS (form submission)
     */
    public function storeWbs(Request $request)
    {
        // This will be handled by API, redirect to form with success message
        return redirect()->route('public.wbs')->with('success', 'Laporan WBS berhasil dikirim!');
    }

    /**
     * Show single news article
     */
    public function show($id): View
    {
        $berita = PortalPapuaTengah::published()->findOrFail($id);

        // Increment views count
        $berita->increment('views');

        // Get related news (same category, exclude current)
        $relatedBerita = PortalPapuaTengah::published()
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('public.berita-detail', compact('berita', 'relatedBerita'));
    }

    /**
     * Show all news articles with pagination and filters
     */
    public function berita(Request $request): View
    {
        $query = PortalPapuaTengah::published();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        // Sort filter
        $sort = $request->get('sort', 'terbaru');
        if ($sort === 'terpopuler') {
            $query->orderBy('views', 'desc');
        } else {
            $query->orderBy('published_at', 'desc');
        }
        
        // Pagination
        $beritaList = $query->paginate(12)->withQueryString();
        
        // Get available categories
        $categories = PortalPapuaTengah::published()
            ->select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->sort();
        
        return view('public.berita-list', compact('beritaList', 'categories'));
    }
}
