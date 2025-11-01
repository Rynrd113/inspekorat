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
        
        // Filter by tipe
        if ($request->filled('tipe')) {
            if ($request->tipe === 'foto') {
                $query->whereIn('file_type', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            } elseif ($request->tipe === 'video') {
                $query->whereIn('file_type', ['mp4', 'avi', 'mov', 'wmv', 'flv']);
            }
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
                         ->whereNotNull('id')
                         ->where('id', '>', 0)
                         ->latest()
                         ->paginate(12)
                         ->appends($request->query());

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
            'thumbnail' => 'nullable|file|max:5120|mimes:jpeg,png,jpg,gif',
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
            
            // Auto-generate thumbnail for images if no custom thumbnail is uploaded
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $imageExtensions) && !$request->hasFile('thumbnail')) {
                // For images, use the main image as thumbnail (copy the same file)
                $validated['thumbnail'] = $filePath;
            }
        }

        // Handle custom thumbnail upload (overrides auto-generated thumbnail)
        if ($request->hasFile('thumbnail')) {
            $thumbnailFile = $request->file('thumbnail');
            $thumbnailPath = $thumbnailFile->store('galeri/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
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
    public function show($id)
    {
        $galeri = \App\Models\Galeri::findOrFail($id);
        return view('admin.galeri.show', compact('galeri'));
    }

    /**
     * Show the form for editing the specified gallery item
     */
    public function edit($id)
    {
        $galeri = \App\Models\Galeri::findOrFail($id);
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update the specified gallery item
     */
    public function update(Request $request, $id)
    {
        $galeri = \App\Models\Galeri::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'file_galeri' => 'nullable|file|max:20480|mimes:jpeg,png,jpg,gif,mp4,avi,mov',
            'thumbnail' => 'nullable|file|max:5120|mimes:jpeg,png,jpg,gif',
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
            
            // Auto-generate thumbnail for images if no custom thumbnail is uploaded
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $imageExtensions) && !$request->hasFile('thumbnail')) {
                // For images, use the main image as thumbnail
                $validated['thumbnail'] = $filePath;
            }
        }

        // Handle custom thumbnail upload (overrides auto-generated thumbnail)
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists and it's different from main file
            if ($galeri->thumbnail && $galeri->thumbnail !== $galeri->file_path) {
                \Storage::disk('public')->delete($galeri->thumbnail);
            }
            
            $thumbnailFile = $request->file('thumbnail');
            $thumbnailPath = $thumbnailFile->store('galeri/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
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
    public function destroy($id)
    {
        $galeri = \App\Models\Galeri::findOrFail($id);
        
        // Delete associated files
        if ($galeri->file_path) {
            \Storage::disk('public')->delete($galeri->file_path);
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
