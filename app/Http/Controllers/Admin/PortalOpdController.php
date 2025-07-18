<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortalOpd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortalOpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = PortalOpd::with(['creator', 'updater'])
                           ->search($request)
                           ->filter($request)
                           ->sorted($request);

        $portalOpds = $query->paginated($request);

        return view('admin.portal-opd.index', compact('portalOpds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.portal-opd.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_opd' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'kepala_opd' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|array',
            'misi.*' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'boolean'
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('portal-opd/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('portal-opd/banners', 'public');
        }

        $validated['created_by'] = Auth::id();
        $validated['status'] = $request->has('status');

        PortalOpd::create($validated);

        return redirect()->route('admin.portal-opd.index')
            ->with('success', 'Portal OPD berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PortalOpd $portalOpd): View
    {
        $portalOpd->load(['creator', 'updater']);
        return view('admin.portal-opd.show', compact('portalOpd'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PortalOpd $portalOpd): View
    {
        return view('admin.portal-opd.edit', compact('portalOpd'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PortalOpd $portalOpd): RedirectResponse
    {
        $validated = $request->validate([
            'nama_opd' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'kepala_opd' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|array',
            'misi.*' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'boolean'
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($portalOpd->logo) {
                Storage::disk('public')->delete($portalOpd->logo);
            }
            $validated['logo'] = $request->file('logo')->store('portal-opd/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($portalOpd->banner) {
                Storage::disk('public')->delete($portalOpd->banner);
            }
            $validated['banner'] = $request->file('banner')->store('portal-opd/banners', 'public');
        }

        $validated['updated_by'] = Auth::id();
        $validated['status'] = $request->has('status');

        $portalOpd->update($validated);

        return redirect()->route('admin.portal-opd.index')
            ->with('success', 'Portal OPD berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortalOpd $portalOpd): RedirectResponse
    {
        // Delete associated files
        if ($portalOpd->logo) {
            Storage::disk('public')->delete($portalOpd->logo);
        }
        if ($portalOpd->banner) {
            Storage::disk('public')->delete($portalOpd->banner);
        }

        $portalOpd->delete();

        return redirect()->route('admin.portal-opd.index')
            ->with('success', 'Portal OPD berhasil dihapus.');
    }
}
