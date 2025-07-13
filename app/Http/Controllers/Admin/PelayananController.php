<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelayananController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $query = \App\Models\Pelayanan::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('nama_layanan', 'like', '%' . $request->search . '%')
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

        $pelayanans = $query->latest()->paginate(10);

        return view('admin.pelayanan.index', compact('pelayanans'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.pelayanan.create');
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prosedur' => 'required|array',
            'prosedur.*' => 'required|string',
            'persyaratan' => 'required|array',
            'persyaratan.*' => 'required|string',
            'waktu_pelayanan' => 'required|string',
            'biaya' => 'nullable|string',
            'dasar_hukum' => 'nullable|string',
            'kategori' => 'required|string',
            'status' => 'boolean',
            'kontak_penanggung_jawab' => 'nullable|string',
            'file_formulir' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('file_formulir')) {
            $validated['file_formulir'] = $request->file('file_formulir')
                ->store('pelayanan/formulir', 'public');
        }

        // Store service
        $validated['created_by'] = auth()->id();
        $validated['status'] = $request->has('status');

        \App\Models\Pelayanan::create($validated);

        return redirect()->route('admin.pelayanan.index')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    /**
     * Display the specified service
     */
    public function show(\App\Models\Pelayanan $pelayanan)
    {
        return view('admin.pelayanan.show', compact('pelayanan'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(\App\Models\Pelayanan $pelayanan)
    {
        return view('admin.pelayanan.edit', compact('pelayanan'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, \App\Models\Pelayanan $pelayanan)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prosedur' => 'required|array',
            'prosedur.*' => 'required|string',
            'persyaratan' => 'required|array',
            'persyaratan.*' => 'required|string',
            'waktu_pelayanan' => 'required|string',
            'biaya' => 'nullable|string',
            'dasar_hukum' => 'nullable|string',
            'kategori' => 'required|string',
            'status' => 'boolean',
            'kontak_penanggung_jawab' => 'nullable|string',
            'file_formulir' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('file_formulir')) {
            // Delete old file if exists
            if ($pelayanan->file_formulir) {
                \Storage::disk('public')->delete($pelayanan->file_formulir);
            }
            $validated['file_formulir'] = $request->file('file_formulir')
                ->store('pelayanan/formulir', 'public');
        }

        $validated['status'] = $request->has('status');
        $validated['updated_by'] = auth()->id();

        // Update service
        $pelayanan->update($validated);

        return redirect()->route('admin.pelayanan.index')
            ->with('success', 'Layanan berhasil diperbarui');
    }

    /**
     * Remove the specified service
     */
    public function destroy(\App\Models\Pelayanan $pelayanan)
    {
        // Delete associated file if exists
        if ($pelayanan->file_formulir) {
            \Storage::disk('public')->delete($pelayanan->file_formulir);
        }

        // Delete service
        $pelayanan->delete();

        return redirect()->route('admin.pelayanan.index')
            ->with('success', 'Layanan berhasil dihapus');
    }
}
