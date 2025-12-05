<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /**
     * Get the storage disk to use based on environment
     */
    protected function getStorageDisk()
    {
        // Use public_root disk in production if document root is not in 'public' folder
        return env('STORAGE_DISK', 'public');
    }

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

        // Filter by album_id
        if ($request->filled('album_id')) {
            $query->where('album_id', $request->album_id);
        }

        $galeris = $query->with(['creator', 'updater', 'album'])
                         ->whereNotNull('id')
                         ->where('id', '>', 0)
                         ->latest()
                         ->paginate(12)
                         ->appends($request->query());

        // Get all albums for filter dropdown
        $albums = \App\Models\Album::active()->orderBy('nama_album')->get();

        return view('admin.galeri.index', compact('galeris', 'albums'));
    }

    /**
     * Show the form for creating a new gallery item
     */
    public function create()
    {
        $albums = \App\Models\Album::active()->orderBy('nama_album')->get();
        return view('admin.galeri.create', compact('albums'));
    }

    /**
     * Store a newly created gallery item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'album_id' => 'nullable|exists:albums,id',
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
            $filePath = $file->store($folder, $this->getStorageDisk());
            
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
            $thumbnailPath = $thumbnailFile->store('galeri/thumbnails', $this->getStorageDisk());
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
            $filePath = $file->store($folder, $this->getStorageDisk());
            
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
                \Storage::disk($this->getStorageDisk())->delete($galeri->thumbnail);
            }
            
            $thumbnailFile = $request->file('thumbnail');
            $thumbnailPath = $thumbnailFile->store('galeri/thumbnails', $this->getStorageDisk());
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

    /**
     * Bulk move photos to another album
     */
    public function bulkMove(Request $request)
    {
        $request->validate([
            'photo_ids' => 'required|array|min:1',
            'photo_ids.*' => 'exists:galeris,id',
            'album_id' => 'required|exists:albums,id',
        ]);

        $count = \App\Models\Galeri::whereIn('id', $request->photo_ids)
            ->update(['album_id' => $request->album_id]);

        return back()->with('success', "{$count} foto berhasil dipindahkan ke album!");
    }
}
