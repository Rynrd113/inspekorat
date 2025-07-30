<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;
use App\Rules\FileUploadValidation;

class DokumenController extends Controller
{
    use HasFileUpload;
    /**
     * Display a listing of documents
     */
    public function index(Request $request)
    {
        $query = \App\Models\Dokumen::query();

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

        $dokumens = $query->latest()->paginate(10);

        return view('admin.dokumen.index', compact('dokumens'));
    }

    /**
     * Show the form for creating a new document
     */
    public function create()
    {
        return view('admin.dokumen.create');
    }

    /**
     * Store a newly created document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'nomor_dokumen' => 'nullable|string|max:100',
            'tanggal_terbit' => 'required|date',
            'file_dokumen' => ['required', new FileUploadValidation(10240, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])],
            'file_cover' => ['nullable', new FileUploadValidation(2048, ['jpeg', 'png', 'jpg', 'gif'])],
            'status' => 'boolean',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Handle file uploads using the trait
        if ($request->hasFile('file_dokumen')) {
            $validated['file_dokumen'] = $this->uploadFile(
                $request->file('file_dokumen'), 
                'dokumen/files'
            );
        }

        if ($request->hasFile('file_cover')) {
            $validated['file_cover'] = $this->uploadImage(
                $request->file('file_cover'), 
                'dokumen/covers'
            );
        }

        // Store document
        $validated['created_by'] = auth()->id();
        $validated['status'] = $request->has('status');
        $validated['is_public'] = $request->has('is_public');

        \App\Models\Dokumen::create($validated);

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }

    /**
     * Display the specified document
     */
    public function show(\App\Models\Dokumen $dokumen)
    {
        return view('admin.dokumen.show', compact('dokumen'));
    }

    /**
     * Show the form for editing the specified document
     */
    public function edit(\App\Models\Dokumen $dokumen)
    {
        return view('admin.dokumen.edit', compact('dokumen'));
    }

    /**
     * Update the specified document
     */
    public function update(Request $request, \App\Models\Dokumen $dokumen)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'nomor_dokumen' => 'nullable|string|max:100',
            'tanggal_terbit' => 'required|date',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'file_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('file_dokumen')) {
            // Delete old file if exists
            if ($dokumen->file_dokumen) {
                \Storage::disk('public')->delete($dokumen->file_dokumen);
            }
            $validated['file_dokumen'] = $request->file('file_dokumen')
                ->store('dokumen/files', 'public');
        }

        if ($request->hasFile('file_cover')) {
            // Delete old cover if exists
            if ($dokumen->file_cover) {
                \Storage::disk('public')->delete($dokumen->file_cover);
            }
            $validated['file_cover'] = $request->file('file_cover')
                ->store('dokumen/covers', 'public');
        }

        // Update document
        $validated['updated_by'] = auth()->id();
        $validated['status'] = $request->has('status');
        $validated['is_public'] = $request->has('is_public');

        $dokumen->update($validated);

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    /**
     * Remove the specified document
     */
    public function destroy(\App\Models\Dokumen $dokumen)
    {
        // Delete associated files
        if ($dokumen->file_dokumen) {
            \Storage::disk('public')->delete($dokumen->file_dokumen);
        }
        if ($dokumen->file_cover) {
            \Storage::disk('public')->delete($dokumen->file_cover);
        }

        $dokumen->delete();

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }

    /**
     * Download document file
     */
    public function download(\App\Models\Dokumen $dokumen)
    {
        // Security checks
        if (!$dokumen->file_dokumen || !\Storage::disk('public')->exists($dokumen->file_dokumen)) {
            abort(404, 'File tidak ditemukan');
        }

        // Check if file is public or user has permission
        if (!$dokumen->is_public && !auth()->check()) {
            abort(403, 'Akses ditolak');
        }

        // Validate file type for security
        $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $filePath = storage_path('app/public/' . $dokumen->file_dokumen);
        
        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);
            if (!in_array($mimeType, $allowedMimes)) {
                abort(403, 'Tipe file tidak diizinkan');
            }
        }

        // Increment download counter
        $dokumen->increment('download_count');

        // Sanitize filename
        $filename = preg_replace('/[^a-zA-Z0-9\-_.]/', '', $dokumen->judul);
        $extension = pathinfo($dokumen->file_dokumen, PATHINFO_EXTENSION);
        
        return \Storage::disk('public')->download($dokumen->file_dokumen, $filename . '.' . $extension);
    }
}
