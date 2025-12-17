<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeroSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/hero-sliders
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = HeroSlider::query();

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by kategori
            if ($request->has('kategori')) {
                $query->byKategori($request->kategori);
            }

            // Filter by prioritas
            if ($request->has('prioritas')) {
                $query->byPrioritas($request->prioritas);
            }

            // Filter active only
            if ($request->boolean('active_only')) {
                $query->active();
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('subjudul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            $perPage = $request->input('per_page', 15);
            $sliders = $query->ordered()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $sliders->items(),
                'meta' => [
                    'current_page' => $sliders->currentPage(),
                    'last_page' => $sliders->lastPage(),
                    'per_page' => $sliders->perPage(),
                    'total' => $sliders->total(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching hero sliders', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data hero slider',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/hero-sliders
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'subjudul' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'link_url' => 'nullable|url|max:255',
                'link_text' => 'nullable|string|max:100',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'prioritas' => 'required|in:urgent,tinggi,normal',
                'kategori' => 'required|in:pengumuman,event,layanan,berita',
                'status' => 'required|in:draft,published,archived',
                'urutan' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('gambar')) {
                $disk = env('STORAGE_DISK', 'public');
                $path = $request->file('gambar')->store('hero-sliders', $disk);
                $data['gambar'] = $path;
            }

            $slider = HeroSlider::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Hero slider berhasil dibuat',
                'data' => $slider
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error creating hero slider', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat hero slider',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/hero-sliders/{id}
     */
    public function show(HeroSlider $heroSlider): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $heroSlider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hero slider tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/hero-sliders/{id}
     */
    public function update(Request $request, HeroSlider $heroSlider): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'judul' => 'sometimes|required|string|max:255',
                'subjudul' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'link_url' => 'nullable|url|max:255',
                'link_text' => 'nullable|string|max:100',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'prioritas' => 'sometimes|required|in:urgent,tinggi,normal',
                'kategori' => 'sometimes|required|in:pengumuman,event,layanan,berita',
                'status' => 'sometimes|required|in:draft,published,archived',
                'urutan' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('gambar')) {
                // Delete old image
                if ($heroSlider->gambar && !str_starts_with($heroSlider->gambar, 'http')) {
                    $disk = env('STORAGE_DISK', 'public');
                    Storage::disk($disk)->delete($heroSlider->gambar);
                }

                $disk = env('STORAGE_DISK', 'public');
                $path = $request->file('gambar')->store('hero-sliders', $disk);
                $data['gambar'] = $path;
            }

            $heroSlider->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Hero slider berhasil diupdate',
                'data' => $heroSlider->fresh()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating hero slider', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate hero slider',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/hero-sliders/{id}
     */
    public function destroy(HeroSlider $heroSlider): JsonResponse
    {
        try {
            $heroSlider->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hero slider berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting hero slider', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus hero slider',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get active sliders for homepage
     * GET /api/hero-sliders/public
     */
    public function publicSliders(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 5);
            
            $sliders = \Cache::remember("hero_sliders_homepage", 3600, function () use ($limit) {
                return HeroSlider::forHomepage($limit)
                    ->select(['id', 'judul', 'subjudul', 'deskripsi', 'gambar', 'link_url', 'link_text', 'prioritas', 'kategori'])
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $sliders
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching public sliders', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data slider',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Increment views counter
     * POST /api/hero-sliders/{id}/view
     */
    public function incrementView(HeroSlider $heroSlider): JsonResponse
    {
        try {
            $heroSlider->incrementViews();

            return response()->json([
                'success' => true,
                'message' => 'View count updated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update view count'
            ], 500);
        }
    }
}
