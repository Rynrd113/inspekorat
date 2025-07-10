<?php

namespace App\Http\Controllers;

use App\Models\InfoKantor;
use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\Wbs;
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
        // Mock data - replace with actual Pelayanan model when implemented
        $pelayanans = collect([
            (object)[
                'id' => 1,
                'nama_layanan' => 'Konsultasi Pengawasan',
                'deskripsi' => 'Layanan konsultasi terkait pengawasan internal',
                'kategori' => 'konsultasi',
                'waktu_pelayanan' => '3 hari kerja'
            ],
            (object)[
                'id' => 2,
                'nama_layanan' => 'Audit Internal',
                'deskripsi' => 'Layanan audit internal untuk OPD',
                'kategori' => 'audit',
                'waktu_pelayanan' => '14 hari kerja'
            ]
        ]);

        return view('public.pelayanan.index', compact('pelayanans'));
    }

    /**
     * Show specific service detail
     */
    public function pelayananShow($id): View
    {
        // Mock data - replace with actual Pelayanan model
        $pelayanan = (object)[
            'id' => $id,
            'nama_layanan' => 'Konsultasi Pengawasan',
            'deskripsi' => 'Layanan konsultasi terkait pengawasan internal',
            'prosedur' => [
                'Mengajukan permohonan',
                'Verifikasi berkas',
                'Proses konsultasi',
                'Hasil konsultasi'
            ],
            'persyaratan' => [
                'Surat permohonan',
                'Identitas pemohon',
                'Dokumen pendukung'
            ]
        ];

        return view('public.pelayanan.show', compact('pelayanan'));
    }

    /**
     * Show documents list
     */
    public function dokumen(Request $request): View
    {
        // Mock data - replace with actual Dokumen model
        $dokumens = collect([
            (object)[
                'id' => 1,
                'judul' => 'Peraturan Inspektorat No. 1/2024',
                'kategori' => 'peraturan',
                'tahun' => 2024,
                'tanggal_terbit' => '2024-01-15'
            ]
        ]);

        return view('public.dokumen.index', compact('dokumens'));
    }

    /**
     * Download document
     */
    public function dokumenDownload($id)
    {
        // Implementation for document download
        return response()->download(storage_path('app/public/dokumen/sample.pdf'));
    }

    /**
     * Show gallery
     */
    public function galeri(Request $request): View
    {
        // Mock data - replace with actual Galeri model
        $galeris = collect([
            (object)[
                'id' => 1,
                'judul' => 'Kegiatan Pengawasan 2024',
                'kategori' => 'foto',
                'album' => 'Pengawasan',
                'thumbnail' => 'galeri/sample1.jpg'
            ]
        ]);

        return view('public.galeri.index', compact('galeris'));
    }

    /**
     * Show specific gallery item
     */
    public function galeriShow($id): View
    {
        // Mock data - replace with actual Galeri model
        $galeri = (object)[
            'id' => $id,
            'judul' => 'Kegiatan Pengawasan 2024',
            'kategori' => 'foto',
            'file_media' => 'galeri/sample1.jpg'
        ];

        return view('public.galeri.show', compact('galeri'));
    }

    /**
     * Show FAQ page
     */
    public function faq(): View
    {
        // Mock data - replace with actual Faq model
        $faqs = collect([
            (object)[
                'id' => 1,
                'pertanyaan' => 'Apa itu WBS?',
                'jawaban' => 'Whistleblowing System adalah sistem pelaporan...',
                'kategori' => 'wbs'
            ],
            (object)[
                'id' => 2,
                'pertanyaan' => 'Bagaimana cara mengakses Portal OPD?',
                'jawaban' => 'Portal OPD dapat diakses melalui menu...',
                'kategori' => 'portal_opd'
            ]
        ]);

        return view('public.faq', compact('faqs'));
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
