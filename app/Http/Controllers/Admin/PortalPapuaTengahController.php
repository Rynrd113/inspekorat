<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortalPapuaTengahRequest;
use App\Http\Requests\UpdatePortalPapuaTengahRequest;
use App\Models\PortalPapuaTengah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortalPapuaTengahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PortalPapuaTengah::query();

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori !== '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_published', $request->status === 'published');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $portalNews = $query->latest()->paginate(10);

        return view('admin.portal-papua-tengah.index', compact('portalNews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.portal-papua-tengah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortalPapuaTengahRequest $request)
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portal-thumbnails', 'public');
        }

        PortalPapuaTengah::create($data);

        return redirect()->route('admin.portal-papua-tengah.index')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PortalPapuaTengah $portalPapuaTengah)
    {
        return view('admin.portal-papua-tengah.show', compact('portalPapuaTengah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PortalPapuaTengah $portalPapuaTengah)
    {
        return view('admin.portal-papua-tengah.edit', compact('portalPapuaTengah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortalPapuaTengahRequest $request, PortalPapuaTengah $portalPapuaTengah)
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($portalPapuaTengah->thumbnail) {
                Storage::disk('public')->delete($portalPapuaTengah->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('portal-thumbnails', 'public');
        }

        $portalPapuaTengah->update($data);

        return redirect()->route('admin.portal-papua-tengah.index')
            ->with('success', 'Berita berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortalPapuaTengah $portalPapuaTengah)
    {
        // Delete thumbnail if exists
        if ($portalPapuaTengah->thumbnail) {
            Storage::disk('public')->delete($portalPapuaTengah->thumbnail);
        }

        $portalPapuaTengah->delete();

        return redirect()->route('admin.portal-papua-tengah.index')
            ->with('success', 'Berita berhasil dihapus');
    }
}
