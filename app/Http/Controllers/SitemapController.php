<?php

namespace App\Http\Controllers;

use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Album;
use App\Models\Faq;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML Sitemap
     */
    public function index(): Response
    {
        $urls = [];
        
        // Static pages
        $staticPages = [
            ['loc' => route('public.index'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('public.profil'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('public.berita.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('public.pelayanan.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('public.dokumen.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('public.galeri.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('public.faq'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('public.kontak'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('public.wbs'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('public.pengaduan'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('public.portal-opd.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
        ];
        
        foreach ($staticPages as $page) {
            $urls[] = $page;
        }
        
        // Berita/Portal Papua Tengah
        $berita = PortalPapuaTengah::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        foreach ($berita as $item) {
            $urls[] = [
                'loc' => route('public.berita.show', $item->id),
                'lastmod' => $item->updated_at->toW3cString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ];
        }
        
        // Portal OPD
        $portalOpd = PortalOpd::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        foreach ($portalOpd as $item) {
            $urls[] = [
                'loc' => route('public.portal-opd.show', $item),
                'lastmod' => $item->updated_at->toW3cString(),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ];
        }
        
        // Pelayanan
        $pelayanan = Pelayanan::where('status', true)
            ->orderBy('updated_at', 'desc')
            ->get();
        
        foreach ($pelayanan as $item) {
            $urls[] = [
                'loc' => route('public.pelayanan.show', $item->id),
                'lastmod' => $item->updated_at->toW3cString(),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ];
        }
        
        // Albums
        $albums = Album::where('status', true)
            ->orderBy('updated_at', 'desc')
            ->get();
        
        foreach ($albums as $album) {
            $urls[] = [
                'loc' => route('public.album', $album->slug),
                'lastmod' => $album->updated_at->toW3cString(),
                'priority' => '0.6',
                'changefreq' => 'weekly',
            ];
        }
        
        // Galeri items
        $galeriItems = Galeri::where('status', true)
            ->orderBy('updated_at', 'desc')
            ->take(100) // Limit to 100 most recent
            ->get();
        
        foreach ($galeriItems as $item) {
            $urls[] = [
                'loc' => route('public.galeri.show', $item->id),
                'lastmod' => $item->updated_at->toW3cString(),
                'priority' => '0.5',
                'changefreq' => 'monthly',
            ];
        }
        
        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
    
    /**
     * Generate robots.txt
     */
    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /storage/\n";
        $content .= "\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
        
        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
