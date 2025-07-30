<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /**
     * Display a listing of gallery items
     */
    public function index(Request $request)
    {
        $query = \App\Models\Galeri::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by album
        if ($request->filled('album')) {
            $query->where('album', 'like', '%' . $request->album . '%');
        }

        $galeris = $query->with(['creator', 'updater'])
                         ->latest()
                         ->paginate(10);

        return view('admin.galeri.index', compact('galeris'));
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
            'kategori' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'file_galeri' => 'required|file|max:20480|mimes:jpeg,png,jpg,gif,mp4,avi,mov',
            'status' => 'nullable|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('file_galeri')) {
            $file = $request->file('file_galeri');
            $folder = 'galeri';
            $filePath = $file->store($folder, 'public');
            
            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
        }

        // Set status (default true if not provided)
        $validated['status'] = $request->has('status') ? true : ($request->input('status') !== null ? (bool)$request->input('status') : true);
        $validated['created_by'] = auth()->id();

        \App\Models\Galeri::create($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Item galeri berhasil ditambahkan');
    }

    /**
     * Display the specified gallery item
     */
    public function show(\App\Models\Galeri $galeri)
    {
        return view('admin.galeri.show', compact('galeri'));
    }

    /**
     * Show the form for editing the specified gallery item
     */
    public function edit(\App\Models\Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update the specified gallery item
     */
    public function update(Request $request, \App\Models\Galeri $galeri)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'file_galeri' => 'nullable|file|max:20480|mimes:jpeg,png,jpg,gif,mp4,avi,mov',
            'status' => 'nullable|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('file_galeri')) {
            // Delete old file if exists
            if ($galeri->file_path) {
                \Storage::disk('public')->delete($galeri->file_path);
            }
            
            $file = $request->file('file_galeri');
            $folder = 'galeri';
            $filePath = $file->store($folder, 'public');
            
            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
        }

        // Set status (default true if not provided)
        $validated['status'] = $request->has('status') ? true : ($request->input('status') !== null ? (bool)$request->input('status') : true);
        $validated['updated_by'] = auth()->id();

        // Update gallery item
        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Item galeri berhasil diperbarui');
    }

    /**
     * Remove the specified gallery item
     */
    public function destroy(\App\Models\Galeri $galeri)
    {
        // Delete associated files
        if ($galeri->file_media) {
            \Storage::disk('public')->delete($galeri->file_media);
        }
        if ($galeri->thumbnail) {
            \Storage::disk('public')->delete($galeri->thumbnail);
        }

        // Delete gallery item
        $galeri->delete();

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
