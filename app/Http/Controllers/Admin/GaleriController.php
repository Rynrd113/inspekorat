<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentApproval;
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
            $ext  = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $optimized = (new \App\Services\ImageOptimizationService())->processGalleryImage($file);
                $validated['file_path'] = $optimized['file_path'];
                $validated['file_name'] = $optimized['file_name'];
                $validated['file_type'] = $optimized['file_type'];
                $validated['file_size'] = $file->getSize();
                if (!$request->hasFile('thumbnail')) {
                    $validated['thumbnail'] = $optimized['thumbnail'];
                }
            } else {
                $filePath = $file->store('galeri', $this->getStorageDisk());
                $validated['file_path'] = $filePath;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['file_type'] = $ext;
                $validated['file_size'] = $file->getSize();
            }
        }

        // Handle custom thumbnail upload (overrides auto-generated thumbnail)
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('galeri/thumbnails', $this->getStorageDisk());
            $validated['thumbnail'] = $thumbnailPath;
        }

        $isContentAdmin = auth()->user()->hasRole('content_admin');

        // content_admin tidak bisa langsung publish
        $validated['status'] = $isContentAdmin ? false : (bool) $request->has('status');
        $validated['created_by'] = auth()->id();

        $galeri = \App\Models\Galeri::create($validated);

        if ($isContentAdmin) {
            ContentApproval::create([
                'approvable_type' => \App\Models\Galeri::class,
                'approvable_id'   => $galeri->id,
                'submitted_by'    => auth()->id(),
                'status'          => ContentApproval::STATUS_PENDING,
                'submitted_at'    => now(),
            ]);

            return redirect()->route('admin.galeri.index')
                ->with('success', 'Item galeri berhasil dikirim dan menunggu persetujuan admin.');
        }

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
        $albums = \App\Models\Album::orderBy('nama_album', 'asc')->get();
        return view('admin.galeri.edit', compact('galeri', 'albums'));
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
            'album_id' => 'nullable|exists:albums,id',
            'tanggal_publikasi' => 'required|date',
            'file_galeri' => 'nullable|file|max:20480|mimes:jpeg,png,jpg,gif,mp4,avi,mov',
            'thumbnail' => 'nullable|file|max:5120|mimes:jpeg,png,jpg,gif',
            'status' => 'nullable|boolean',
            'delete_file' => 'nullable|boolean',
        ]);

        // Handle delete file checkbox
        if ($request->has('delete_file') && $request->delete_file) {
            if ($galeri->file_path) {
                \Storage::disk('public')->delete($galeri->file_path);
            }
            $validated['file_path'] = null;
            $validated['file_name'] = null;
            $validated['file_type'] = null;
            $validated['file_size'] = null;
            // Also delete thumbnail if it's same as file
            if ($galeri->thumbnail === $galeri->file_path) {
                $validated['thumbnail'] = null;
            }
        }
        // Handle file upload
        elseif ($request->hasFile('file_galeri')) {
            if ($galeri->file_path) {
                \Storage::disk('public')->delete($galeri->file_path);
            }

            $file = $request->file('file_galeri');
            $ext  = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $optimized = (new \App\Services\ImageOptimizationService())->processGalleryImage($file);
                $validated['file_path'] = $optimized['file_path'];
                $validated['file_name'] = $optimized['file_name'];
                $validated['file_type'] = $optimized['file_type'];
                $validated['file_size'] = $file->getSize();
                if (!$request->hasFile('thumbnail')) {
                    $validated['thumbnail'] = $optimized['thumbnail'];
                }
            } else {
                $filePath = $file->store('galeri', $this->getStorageDisk());
                $validated['file_path'] = $filePath;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['file_type'] = $ext;
                $validated['file_size'] = $file->getSize();
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

        $isContentAdmin = auth()->user()->hasRole('content_admin');

        // content_admin tidak bisa langsung publish
        $validated['status'] = $isContentAdmin ? false : (bool) $request->has('status');
        $validated['updated_by'] = auth()->id();

        $galeri->update($validated);

        if ($isContentAdmin) {
            ContentApproval::updateOrCreate(
                [
                    'approvable_type' => \App\Models\Galeri::class,
                    'approvable_id'   => $galeri->id,
                    'status'          => ContentApproval::STATUS_PENDING,
                ],
                [
                    'submitted_by' => auth()->id(),
                    'submitted_at' => now(),
                    'notes'        => null,
                ]
            );

            return redirect()->route('admin.galeri.index')
                ->with('success', 'Item galeri berhasil diperbarui dan menunggu persetujuan admin.');
        }

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
