<?php

namespace App\Http\Controllers;

use App\Models\InfoKantor;
use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\Wbs;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

        // Cache statistik public untuk performa
        $stats = Cache::remember('public_stats', 300, function () {
            return [
                'portal_opd' => PortalOpd::active()->count(),
                'berita' => PortalPapuaTengah::published()->count(),
                'wbs' => Wbs::count(),
                'total_views' => DB::table('portal_papua_tengahs')->sum('views') + 1250, // Base views
            ];
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

        return view('public.index', compact('portalPapuaTengah', 'infoKantor', 'stats'));
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

    /**
     * Show organization profile
     */
    public function profil(): View
    {
        // Get organization profile data
        $profil = [
            'nama_organisasi' => 'Inspektorat Provinsi Papua Tengah',
            'visi' => 'Terwujudnya Pengawasan Internal yang Profesional dan Akuntabel untuk Mewujudkan Good Governance di Papua Tengah',
            'misi' => [
                'Melaksanakan pengawasan internal yang berkualitas',
                'Memberikan assurance dan consulting yang optimal',
                'Meningkatkan kapasitas pengawasan internal',
                'Memperkuat sistem pengendalian internal pemerintah'
            ],
            'sejarah' => 'Inspektorat Provinsi Papua Tengah dibentuk seiring dengan pembentukan provinsi Papua Tengah...',
        ];

        return view('public.profil', compact('profil'));
    }

    /**
     * Show services list
     */
    public function pelayanan(Request $request): View
    {
        $pelayanans = Cache::remember('public_pelayanans', 600, function () {
            return Pelayanan::where('status', true)
                ->orderBy('urutan', 'asc')
                ->orderBy('nama', 'asc')
                ->get();
        });

        return view('public.pelayanan.index', compact('pelayanans'));
    }

    /**
     * Show specific service detail
     */
    public function pelayananShow($id): View
    {
        $pelayanan = Pelayanan::where('status', true)->findOrFail($id);
        
        // Get related services (same category, different id, limit 3)
        $relatedServices = Pelayanan::where('status', true)
            ->where('kategori', $pelayanan->kategori)
            ->where('id', '!=', $id)
            ->orderBy('urutan')
            ->limit(3)
            ->get();
            
        return view('public.pelayanan.show', compact('pelayanan', 'relatedServices'));
    }

    /**
     * Show documents list
     */
    public function dokumen(Request $request): View
    {
        $query = Dokumen::where('status', true);

        // Filter by category if specified
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $dokumens = Cache::remember(
            'public_dokumens_' . md5($request->fullUrl()), 
            600, 
            function () use ($query) {
                return $query->orderBy('tanggal_publikasi', 'desc')->get();
            }
        );

        return view('public.dokumen.index', compact('dokumens'));
    }

    /**
     * Download document
     */
    public function dokumenDownload($id)
    {
        $dokumen = Dokumen::where('status', true)->findOrFail($id);
        
        // Increment download count
        $dokumen->increment('download_count');

        // Return file download
        $filePath = storage_path('app/' . $dokumen->file_path);
        
        if (file_exists($filePath)) {
            return response()->download($filePath, $dokumen->file_name);
        }

        // If file doesn't exist, return mock response for demo
        return response()->json([
            'message' => 'File download akan segera tersedia',
            'file' => $dokumen->file_name
        ]);
    }

    /**
     * Show gallery
     */
    public function galeri(Request $request): View
    {
        $query = Galeri::where('status', true);

        // Filter by type if specified
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category if specified
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $galeris = Cache::remember(
            'public_galeri_' . md5($request->fullUrl()), 
            600, 
            function () use ($query) {
                return $query->orderBy('urutan', 'asc')
                           ->orderBy('tanggal_event', 'desc')
                           ->get();
            }
        );

        return view('public.galeri.index', compact('galeris'));
    }

    /**
     * Show specific gallery item
     */
    public function galeriShow($id): View
    {
        $galeri = Galeri::where('status', true)->findOrFail($id);
        
        // Get related items from same category
        $related = Galeri::where('status', true)
            ->where('kategori', $galeri->kategori)
            ->where('id', '!=', $galeri->id)
            ->orderBy('urutan', 'asc')
            ->take(6)
            ->get();

        return view('public.galeri.show', compact('galeri', 'related'));
    }

    /**
     * Show FAQ page
     */
    public function faq(Request $request): View
    {
        $query = Faq::where('status', true);

        // Filter by category if specified
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pertanyaan', 'like', "%{$search}%")
                  ->orWhere('jawaban', 'like', "%{$search}%");
            });
        }

        $faqs = Cache::remember(
            'public_faqs_' . md5($request->fullUrl()), 
            600, 
            function () use ($query) {
                return $query->orderBy('is_popular', 'desc')
                           ->orderBy('urutan', 'asc')
                           ->get();
            }
        );

        // Get popular FAQs for sidebar
        $popularFaqs = Cache::remember('popular_faqs', 600, function () {
            return Faq::where('status', true)
                ->where('is_popular', true)
                ->orderBy('view_count', 'desc')
                ->take(5)
                ->get();
        });

        // Get FAQ categories
        $categories = Cache::remember('faq_categories', 600, function () {
            return Faq::where('status', true)
                ->select('kategori')
                ->distinct()
                ->whereNotNull('kategori')
                ->orderBy('kategori')
                ->pluck('kategori');
        });

        return view('public.faq', compact('faqs', 'popularFaqs', 'categories'));
    }

    /**
     * Show contact page
     */
    public function kontak(): View
    {
        $kontak = (object)[
            'nama' => 'Inspektorat Provinsi Papua Tengah',
            'alamat' => 'Jl. Raya Nabire No. 123, Nabire, Papua Tengah',
            'telepon' => '(0984) 21234',
            'email' => 'inspektorat@paputengah.go.id',
            'jam_operasional' => 'Senin - Jumat: 08:00 - 16:00 WIT'
        ];

        return view('public.kontak', compact('kontak'));
    }
}
