<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelayananController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        // Implementation for listing services
        return view('admin.pelayanan.index');
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

        // Store service (you'll need to create the model)
        // Pelayanan::create($validated);

        return redirect()->route('admin.pelayanan.index')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    /**
     * Display the specified service
     */
    public function show($id)
    {
        return view('admin.pelayanan.show');
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit($id)
    {
        return view('admin.pelayanan.edit');
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, $id)
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

        // Update service
        // $pelayanan->update($validated);

        return redirect()->route('admin.pelayanan.index')
            ->with('success', 'Layanan berhasil diperbarui');
    }

    /**
     * Remove the specified service
     */
    public function destroy($id)
    {
        // Delete service
        // $pelayanan->delete();

        return redirect()->route('admin.pelayanan.index')
            ->with('success', 'Layanan berhasil dihapus');
    }
}
