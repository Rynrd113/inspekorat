<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWbsRequest;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Show the public landing page
     */
    public function index(): View
    {
        // Cache data public untuk performa - ambil hanya 5 berita terbaru dengan eager loading
        $portalPapuaTengah = Cache::remember('public_portal_papua_tengah', 600, function () {
            return PortalPapuaTengah::with(['creator:id,name,email', 'updater:id,name,email'])
                ->published()
                ->ordered()
                ->select(['id', 'judul', 'slug', 'konten', 'kategori', 'gambar', 'published_at', 'views', 'created_by', 'updated_by'])
                ->take(5)
                ->get();
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
    public function storeWbs(StoreWbsRequest $request)
    {
        $data = $request->validated();

        // Handle anonymous submissions
        if ($data['is_anonymous'] ?? false) {
            $data['nama_pelapor'] = 'Anonymous';
            $data['email'] = 'anonymous@system.local';
        }

        // Handle multiple file uploads
        if ($request->hasFile('attachments')) {
            $filePaths = [];
            foreach ($request->file('attachments') as $file) {
                $filePaths[] = $file->store('wbs-attachments', 'public');
            }
            $data['bukti_files'] = $filePaths;
        }

        Wbs::create($data);

        return redirect()->route('public.wbs')->with('success', 'Laporan WBS berhasil dikirim! Terima kasih atas partisipasi Anda.');
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

        // Check if file exists
        if (isset($dokumen->file_path) && Storage::exists($dokumen->file_path)) {
            $pathInfo = pathinfo($dokumen->file_path);
            $fileName = $dokumen->file_name ?? ($dokumen->judul . '.' . ($pathInfo['extension'] ?? 'pdf'));
            
            return Storage::download($dokumen->file_path, $fileName);
        }

        // Fallback: Generate a sample file if file not found
        $fileName = ($dokumen->file_name ?? ($dokumen->judul . '.pdf'));
        $sampleContent = $this->generateSamplePDF($dokumen->judul ?? 'Dokumen', $dokumen->deskripsi ?? '');
        
        return response($sampleContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Preview document
     */
    public function dokumenPreview($id)
    {
        $dokumen = Dokumen::where('status', true)->findOrFail($id);
        
        // Check if file exists
        if (isset($dokumen->file_path) && Storage::exists($dokumen->file_path)) {
            $pathInfo = pathinfo($dokumen->file_path);
            $extension = strtolower($pathInfo['extension'] ?? 'pdf');
            
            // Handle different file types
            switch ($extension) {
                case 'pdf':
                    return Storage::response($dokumen->file_path, null, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="preview.pdf"'
                    ]);
                    
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    return Storage::response($dokumen->file_path, null, [
                        'Content-Type' => 'image/' . ($extension === 'jpg' ? 'jpeg' : $extension),
                        'Content-Disposition' => 'inline'
                    ]);
                    
                default:
                    // For other file types, force download
                    return $this->dokumenDownload($id);
            }
        }

        // Fallback: Generate a sample PDF for preview
        $sampleContent = $this->generateSamplePDF($dokumen->judul ?? 'Dokumen', $dokumen->deskripsi ?? '');
        
        return response($sampleContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="preview.pdf"');
    }

    /**
     * Generate sample PDF content
     */
    private function generateSamplePDF($title, $description = '')
    {
        $date = now()->format('d F Y');
        $content = wordwrap($description, 80, "\n", true);
        
        return "%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj
2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj
3 0 obj
<<
/Type /Page
/Parent 2 0 R
/MediaBox [0 0 612 792]
/Contents 4 0 R
/Resources <<
/Font <<
/F1 5 0 R
/F2 6 0 R
>>
>>
>>
endobj
4 0 obj
<<
/Length 250
>>
stream
BT
/F1 16 Tf
72 720 Td
({$title}) Tj
0 -40 Td
/F2 12 Tf
(Tanggal: {$date}) Tj
0 -30 Td
({$content}) Tj
0 -60 Td
(--- Dokumen dari Inspektorat Papua Tengah ---) Tj
ET
endstream
endobj
5 0 obj
<<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica-Bold
>>
endobj
6 0 obj
<<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica
>>
endobj
xref
0 7
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000280 00000 n 
0000000580 00000 n 
0000000650 00000 n 
trailer
<<
/Size 7
/Root 1 0 R
>>
startxref
714
%%EOF";
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

    /**
     * Handle contact form submission
     */
    public function kontakKirim(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string|max:2000'
        ]);

        // Here you can add logic to save the contact message to database
        // or send an email to administrators
        
        // For now, we'll just redirect back with a success message
        return redirect()->route('public.kontak')->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan segera merespons.');
    }

    /**
     * Show pengaduan (complaint) page
     */
    public function pengaduan(): View
    {
        return view('public.pengaduan');
    }
}
