<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengaduan::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pengadu', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%")
                  ->orWhere('isi_pengaduan', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pengaduans = $query->latest()->paginate(10);

        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengaduan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengadu' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'subjek' => 'required|string|max:255',
            'isi_pengaduan' => 'required|string',
            'kategori' => 'required|string',
            'bukti_files' => 'nullable|array',
            'bukti_files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Handle file uploads
        $files = [];
        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $file) {
                $files[] = $file->store('pengaduan/bukti', 'public');
            }
            $validated['bukti_files'] = $files;
        }

        $validated['status'] = 'pending';
        $validated['tanggal_pengaduan'] = now();

        Pengaduan::create($validated);

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Pengaduan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan)
    {
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengaduan $pengaduan)
    {
        return view('admin.pengaduan.edit', compact('pengaduan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'nama_pengadu' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'subjek' => 'required|string|max:255',
            'isi_pengaduan' => 'required|string',
            'kategori' => 'required|string',
            'status' => 'required|string|in:pending,in_progress,resolved,closed',
            'tanggapan' => 'nullable|string',
        ]);

        $pengaduan->update($validated);

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengaduan $pengaduan)
    {
        // Delete attached files
        if ($pengaduan->bukti_files) {
            foreach ($pengaduan->bukti_files as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }
}
