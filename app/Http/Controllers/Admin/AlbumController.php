<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    /**
     * Get the storage disk to use based on environment
     */
    protected function getStorageDisk()
    {
        return env('STORAGE_DISK', 'public');
    }

    /**
     * Display a listing of albums
     */
    public function index(Request $request)
    {
        $query = Album::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_album', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        // Filter by parent (root albums only or specific parent)
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        $albums = $query->with(['parent', 'children', 'photos'])
                        ->orderBy('urutan')
                        ->orderBy('nama_album')
                        ->paginate(12)
                        ->appends($request->query());

        // Get parent albums for filter dropdown
        $parentAlbums = Album::whereNull('parent_id')
                            ->orderBy('nama_album')
                            ->get();

        return view('admin.albums.index', compact('albums', 'parentAlbums'));
    }

    /**
     * Show the form for creating a new album
     */
    public function create()
    {
        $parentAlbums = Album::whereNull('parent_id')
                            ->orderBy('nama_album')
                            ->get();

        return view('admin.albums.create', compact('parentAlbums'));
    }

    /**
     * Store a newly created album
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_album' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:albums,slug',
            'deskripsi' => 'nullable|string',
            'parent_id' => 'nullable|exists:albums,id',
            'tanggal_kegiatan' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama_album']);
            
            // Ensure slug is unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Album::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filePath = $file->store('albums/covers', $this->getStorageDisk());
            $validated['cover_image'] = $filePath;
        }

        // Set default values
        $validated['status'] = $request->has('status') ? true : false;
        $validated['urutan'] = $validated['urutan'] ?? 0;

        Album::create($validated);

        return redirect()->route('admin.albums.index')
            ->with('success', 'Album berhasil ditambahkan!');
    }

    /**
     * Display the specified album
     */
    public function show(Album $album)
    {
        $album->load(['parent', 'children', 'photos']);

        // Get photos in this album with pagination
        $photos = Galeri::where('album_id', $album->id)
                       ->orderBy('tanggal_publikasi', 'desc')
                       ->paginate(20);

        return view('admin.albums.show', compact('album', 'photos'));
    }

    /**
     * Show the form for editing the specified album
     */
    public function edit(Album $album)
    {
        $parentAlbums = Album::whereNull('parent_id')
                            ->where('id', '!=', $album->id)
                            ->orderBy('nama_album')
                            ->get();

        return view('admin.albums.edit', compact('album', 'parentAlbums'));
    }

    /**
     * Update the specified album
     */
    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'nama_album' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:albums,slug,' . $album->id,
            'deskripsi' => 'nullable|string',
            'parent_id' => 'nullable|exists:albums,id',
            'tanggal_kegiatan' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        // Prevent setting self as parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $album->id) {
            return back()->withErrors(['parent_id' => 'Album tidak bisa menjadi parent dari dirinya sendiri.']);
        }

        // Generate slug if name changed and slug is empty
        if (empty($validated['slug']) && $album->nama_album !== $validated['nama_album']) {
            $validated['slug'] = Str::slug($validated['nama_album']);
            
            // Ensure slug is unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Album::where('slug', $validated['slug'])->where('id', '!=', $album->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($album->cover_image) {
                Storage::disk($this->getStorageDisk())->delete($album->cover_image);
            }
            
            $file = $request->file('cover_image');
            $filePath = $file->store('albums/covers', $this->getStorageDisk());
            $validated['cover_image'] = $filePath;
        }

        // Handle cover image removal
        if ($request->has('remove_cover') && $request->remove_cover) {
            if ($album->cover_image) {
                Storage::disk($this->getStorageDisk())->delete($album->cover_image);
            }
            $validated['cover_image'] = null;
        }

        // Set status
        $validated['status'] = $request->has('status') ? true : false;

        $album->update($validated);

        return redirect()->route('admin.albums.index')
            ->with('success', 'Album berhasil diperbarui!');
    }

    /**
     * Remove the specified album
     */
    public function destroy(Album $album)
    {
        // Check if album has photos
        if ($album->photos()->count() > 0) {
            return back()->with('error', 'Album tidak dapat dihapus karena masih memiliki foto. Pindahkan atau hapus foto terlebih dahulu.');
        }

        // Check if album has sub-albums
        if ($album->children()->count() > 0) {
            return back()->with('error', 'Album tidak dapat dihapus karena masih memiliki sub-album. Hapus sub-album terlebih dahulu.');
        }

        // Delete cover image
        if ($album->cover_image) {
            Storage::disk($this->getStorageDisk())->delete($album->cover_image);
        }

        $album->delete();

        return redirect()->route('admin.albums.index')
            ->with('success', 'Album berhasil dihapus!');
    }

    /**
     * Upload multiple photos to an album
     */
    public function uploadPhotos(Request $request, Album $album)
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'kategori' => 'nullable|string|max:255',
        ]);

        $uploadedCount = 0;
        $kategori = $request->input('kategori', 'kegiatan');

        foreach ($request->file('photos') as $photo) {
            $filePath = $photo->store('galeri', $this->getStorageDisk());
            
            Galeri::create([
                'album_id' => $album->id,
                'judul' => pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME),
                'deskripsi' => null,
                'kategori' => $kategori,
                'tanggal_publikasi' => $album->tanggal_kegiatan ?? now(),
                'file_path' => $filePath,
                'file_name' => $photo->getClientOriginalName(),
                'file_type' => $photo->getClientOriginalExtension(),
                'file_size' => $photo->getSize(),
                'thumbnail' => $filePath, // Use same image as thumbnail
                'status' => true,
                'created_by' => Auth::id(),
            ]);

            $uploadedCount++;
        }

        return back()->with('success', "{$uploadedCount} foto berhasil diupload ke album {$album->nama_album}!");
    }
}
