<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfoKantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class InfoKantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InfoKantor::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $infoKantors = $query->latest()->paginate(10);

        return view('admin.info-kantor.index', compact('infoKantors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.info-kantor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string',
            'status' => 'boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('info-kantor', 'public');
        }

        $validated['created_by'] = Auth::id();
        $validated['status'] = $request->has('status');

        InfoKantor::create($validated);

        return redirect()->route('admin.info-kantor.index')
            ->with('success', 'Info kantor berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(InfoKantor $infoKantor)
    {
        return view('admin.info-kantor.show', compact('infoKantor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfoKantor $infoKantor)
    {
        return view('admin.info-kantor.edit', compact('infoKantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InfoKantor $infoKantor)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string',
            'status' => 'boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($infoKantor->gambar) {
                Storage::disk('public')->delete($infoKantor->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('info-kantor', 'public');
        }

        $validated['updated_by'] = Auth::id();
        $validated['status'] = $request->has('status');

        $infoKantor->update($validated);

        return redirect()->route('admin.info-kantor.index')
            ->with('success', 'Info kantor berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfoKantor $infoKantor)
    {
        // Delete associated image if exists
        if ($infoKantor->gambar) {
            Storage::disk('public')->delete($infoKantor->gambar);
        }

        $infoKantor->delete();

        return redirect()->route('admin.info-kantor.index')
            ->with('success', 'Info kantor berhasil dihapus');
    }
}