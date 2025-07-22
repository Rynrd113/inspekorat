<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortalPapuaTengahRequest;
use App\Http\Requests\UpdatePortalPapuaTengahRequest;
use App\Http\Resources\PortalPapuaTengahResource;
use App\Models\PortalPapuaTengah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PortalPapuaTengahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PortalPapuaTengah::query();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'published' || $request->status === 'true' || $request->status === true) {
                $query->where('status', true);
            } elseif ($request->status === 'draft' || $request->status === 'false' || $request->status === false) {
                $query->where('status', false);
            }
        }

        // Filter by kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $portalNews = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => PortalPapuaTengahResource::collection($portalNews)->response()->getData()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortalPapuaTengahRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portal-thumbnails', 'public');
        }

        $portal = PortalPapuaTengah::create($data);

        // Clear cache
        Cache::forget('portal_featured');
        Cache::forget('portal_latest');

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil ditambahkan',
            'data' => new PortalPapuaTengahResource($portal)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PortalPapuaTengah $portalPapuaTengah): JsonResponse
    {
        // Increment view count
        $portalPapuaTengah->increment('views');

        return response()->json([
            'success' => true,
            'data' => new PortalPapuaTengahResource($portalPapuaTengah)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortalPapuaTengahRequest $request, PortalPapuaTengah $portalPapuaTengah): JsonResponse
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($portalPapuaTengah->thumbnail) {
                Storage::disk('public')->delete($portalPapuaTengah->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('portal-thumbnails', 'public');
        }

        $portalPapuaTengah->update($data);

        // Clear cache
        Cache::forget('portal_featured');
        Cache::forget('portal_latest');

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diupdate',
            'data' => new PortalPapuaTengahResource($portalPapuaTengah->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortalPapuaTengah $portalPapuaTengah): JsonResponse
    {
        // Delete thumbnail if exists
        if ($portalPapuaTengah->thumbnail) {
            Storage::disk('public')->delete($portalPapuaTengah->thumbnail);
        }

        $portalPapuaTengah->delete();

        // Clear cache
        Cache::forget('portal_featured');
        Cache::forget('portal_latest');

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus'
        ]);
    }

    /**
     * Get public berita for homepage with filter
     */
    public function publicBerita(Request $request): JsonResponse
    {
        $filter = $request->get('filter', 'terbaru');
        $limit = $request->get('limit', 5);
        
        $query = PortalPapuaTengah::published();
        
        if ($filter === 'terpopuler') {
            $query->orderBy('views', 'desc');
        } else {
            $query->orderBy('tanggal_publikasi', 'desc');
        }
        
        $berita = $query->take($limit)->get();
        
        return response()->json([
            'success' => true,
            'data' => PortalPapuaTengahResource::collection($berita),
            'filter' => $filter,
            'total' => $berita->count()
        ]);
    }
}
