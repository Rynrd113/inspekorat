<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display a listing of gallery items
     */
    public function index(Request $request)
    {
        // Implementation for listing gallery items
        return view('admin.galeri.index');
    }

    /**
     * Show the form for creating a new gallery item
     */
    public function create()
    {
        return view('admin.galeri.create');
    }

    /**
     * Store a newly created gallery item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|in:foto,video',
            'album' => 'nullable|string|max:100',
            'tanggal_kegiatan' => 'required|date',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'file_media' => 'required|file|max:20480', // 20MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Validate file type based on category
        if ($validated['kategori'] === 'foto') {
            $request->validate([
                'file_media' => 'image|mimes:jpeg,png,jpg,gif,webp'
            ]);
        } elseif ($validated['kategori'] === 'video') {
            $request->validate([
                'file_media' => 'mimes:mp4,avi,mov,wmv,flv'
            ]);
        }

        // Handle file uploads
        if ($request->hasFile('file_media')) {
            $folder = $validated['kategori'] === 'foto' ? 'galeri/photos' : 'galeri/videos';
            $validated['file_media'] = $request->file('file_media')->store($folder, 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('galeri/thumbnails', 'public');
        }

        // Store gallery item (you'll need to create the model)
        // Galeri::create($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Item galeri berhasil ditambahkan');
    }

    /**
     * Display the specified gallery item
     */
    public function show($id)
    {
        return view('admin.galeri.show');
    }

    /**
     * Show the form for editing the specified gallery item
     */
    public function edit($id)
    {
        return view('admin.galeri.edit');
    }

    /**
     * Update the specified gallery item
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|in:foto,video',
            'album' => 'nullable|string|max:100',
            'tanggal_kegiatan' => 'required|date',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'file_media' => 'nullable|file|max:20480',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('file_media')) {
            $folder = $validated['kategori'] === 'foto' ? 'galeri/photos' : 'galeri/videos';
            $validated['file_media'] = $request->file('file_media')->store($folder, 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('galeri/thumbnails', 'public');
        }

        // Update gallery item
        // $galeri->update($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Item galeri berhasil diperbarui');
    }

    /**
     * Remove the specified gallery item
     */
    public function destroy($id)
    {
        // Delete gallery item and associated files
        // $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Item galeri berhasil dihapus');
    }

    /**
     * Bulk upload for multiple images
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'album' => 'required|string|max:100',
            'tanggal_kegiatan' => 'required|date',
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        // Implementation for bulk upload
        // Process multiple files...

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Bulk upload berhasil');
    }
}
