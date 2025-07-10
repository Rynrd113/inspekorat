<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    /**
     * Display a listing of documents
     */
    public function index(Request $request)
    {
        // Implementation for listing documents
        return view('admin.dokumen.index');
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
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'file_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('file_dokumen')) {
            $validated['file_dokumen'] = $request->file('file_dokumen')
                ->store('dokumen/files', 'public');
        }

        if ($request->hasFile('file_cover')) {
            $validated['file_cover'] = $request->file('file_cover')
                ->store('dokumen/covers', 'public');
        }

        // Store document (you'll need to create the model)
        // Dokumen::create($validated);

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }

    /**
     * Display the specified document
     */
    public function show($id)
    {
        return view('admin.dokumen.show');
    }

    /**
     * Show the form for editing the specified document
     */
    public function edit($id)
    {
        return view('admin.dokumen.edit');
    }

    /**
     * Update the specified document
     */
    public function update(Request $request, $id)
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
            $validated['file_dokumen'] = $request->file('file_dokumen')
                ->store('dokumen/files', 'public');
        }

        if ($request->hasFile('file_cover')) {
            $validated['file_cover'] = $request->file('file_cover')
                ->store('dokumen/covers', 'public');
        }

        // Update document
        // $dokumen->update($validated);

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    /**
     * Remove the specified document
     */
    public function destroy($id)
    {
        // Delete document and associated files
        // $dokumen->delete();

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }

    /**
     * Download document file
     */
    public function download($id)
    {
        // Implementation for downloading document
        // return Storage::download($dokumen->file_dokumen);
    }
}
