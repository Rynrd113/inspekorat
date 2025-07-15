<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInfoKantorRequest;
use App\Http\Resources\InfoKantorResource;
use App\Models\InfoKantor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InfoKantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'info_kantor_' . md5($request->getQueryString() ?? '');
        
        $data = Cache::remember($cacheKey, 300, function () use ($request) {
            $query = InfoKantor::query();

            // Filter by kategori
            if ($request->has('kategori')) {
                $query->byKategori($request->kategori);
            }

            // Only active for public
            if (!auth('sanctum')->check()) {
                $query->active();
            }

            return $query->with(['creator:id,name,email', 'updater:id,name,email'])
                ->select(['id', 'judul', 'konten', 'kategori', 'is_active', 'icon', 'urutan', 'created_by', 'updated_by', 'created_at', 'updated_at'])
                ->ordered()
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => InfoKantorResource::collection($data)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInfoKantorRequest $request): JsonResponse
    {
        $infoKantor = InfoKantor::create($request->validated());
        
        // Clear cache
        Cache::forget('info_kantor_*');

        return response()->json([
            'success' => true,
            'message' => 'Info kantor berhasil ditambahkan',
            'data' => new InfoKantorResource($infoKantor)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InfoKantor $infoKantor): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new InfoKantorResource($infoKantor)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInfoKantorRequest $request, InfoKantor $infoKantor): JsonResponse
    {
        $infoKantor->update($request->validated());
        
        // Clear cache
        Cache::forget('info_kantor_*');

        return response()->json([
            'success' => true,
            'message' => 'Info kantor berhasil diupdate',
            'data' => new InfoKantorResource($infoKantor->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfoKantor $infoKantor): JsonResponse
    {
        $infoKantor->delete();
        
        // Clear cache
        Cache::forget('info_kantor_*');

        return response()->json([
            'success' => true,
            'message' => 'Info kantor berhasil dihapus'
        ]);
    }
}
