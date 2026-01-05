<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    /**
     * Get the storage disk to use based on environment
     */
    protected function getStorageDisk()
    {
        return env('STORAGE_DISK', 'public');
    }

    /**
     * Display a listing of hero sliders
     */
    public function index(Request $request)
    {
        $query = HeroSlider::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('subjudul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'true');
        }

        // Filter by prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $heroSliders = $query->ordered()
                             ->paginate(10)
                             ->appends($request->query());

        return view('admin.hero-sliders.index', compact('heroSliders'));
    }

    /**
     * Show the form for creating a new hero slider
     */
    public function create()
    {
        return view('admin.hero-sliders.create');
    }

    /**
     * Store a newly created hero slider
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'prioritas' => 'nullable|string|in:tinggi,sedang,rendah',
            'kategori' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:draft,published',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filePath = $file->store('hero-sliders', $this->getStorageDisk());
            $validated['gambar'] = $filePath;
        }

        // Set defaults
        $validated['status'] = $validated['status'] ?? 'published';
        $validated['prioritas'] = $validated['prioritas'] ?? 'sedang';
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['urutan'] = $validated['urutan'] ?? 0;

        HeroSlider::create($validated);

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Hero slider berhasil ditambahkan!');
    }

    /**
     * Display the specified hero slider
     */
    public function show(HeroSlider $heroSlider)
    {
        return view('admin.hero-sliders.show', compact('heroSlider'));
    }

    /**
     * Show the form for editing the specified hero slider
     */
    public function edit(HeroSlider $heroSlider)
    {
        return view('admin.hero-sliders.edit', compact('heroSlider'));
    }

    /**
     * Update the specified hero slider
     */
    public function update(Request $request, HeroSlider $heroSlider)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'prioritas' => 'nullable|string|in:tinggi,sedang,rendah',
            'kategori' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:draft,published',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($heroSlider->gambar) {
                Storage::disk($this->getStorageDisk())->delete($heroSlider->gambar);
            }
            
            $file = $request->file('gambar');
            $filePath = $file->store('hero-sliders', $this->getStorageDisk());
            $validated['gambar'] = $filePath;
        }

        // Set is_active
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $heroSlider->update($validated);

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Foto slider berhasil diperbarui!');
    }

    /**
     * Remove the specified hero slider
     */
    public function destroy(HeroSlider $heroSlider)
    {
        // Delete image
        if ($heroSlider->gambar) {
            Storage::disk($this->getStorageDisk())->delete($heroSlider->gambar);
        }

        $heroSlider->delete();

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Foto slider berhasil dihapus!');
    }
}
