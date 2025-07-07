<?php

namespace App\Http\Controllers;

use App\Models\PortalOpd;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalOpdController extends Controller
{
    /**
     * Display portal OPD listing
     */
    public function index(Request $request): View
    {
        $query = PortalOpd::active();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_opd', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $portalOpds = $query->latest()->paginate(12);

        return view('public.portal-opd.index', compact('portalOpds'));
    }

    /**
     * Display specific OPD detail
     */
    public function show(PortalOpd $portalOpd): View
    {
        // Only show active OPDs
        if (!$portalOpd->status) {
            abort(404);
        }

        return view('public.portal-opd.show', compact('portalOpd'));
    }
}
