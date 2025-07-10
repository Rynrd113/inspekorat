<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display the organization profile management
     */
    public function index()
    {
        return view('admin.profil.index');
    }

    /**
     * Show the form for editing organization profile
     */
    public function edit()
    {
        return view('admin.profil.edit');
    }

    /**
     * Update organization profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'visi' => 'required|string',
            'misi' => 'required|array',
            'misi.*' => 'required|string',
            'sejarah' => 'nullable|string',
            'struktur_organisasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'jam_operasional' => 'required|string',
        ]);

        // Handle file upload for struktur organisasi
        if ($request->hasFile('struktur_organisasi')) {
            $validated['struktur_organisasi'] = $request->file('struktur_organisasi')
                ->store('profil/struktur', 'public');
        }

        // Update or create profile
        // Implementation depends on whether you want to store in database or config

        return redirect()->route('admin.profil.index')
            ->with('success', 'Profil organisasi berhasil diperbarui');
    }
}
