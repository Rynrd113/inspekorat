<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWebPortalRequest;
use App\Http\Resources\WebPortalResource;
use App\Models\WebPortal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebPortalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'web_portals_' . md5($request->getQueryString() ?? '');
        
        $data = Cache::remember($cacheKey, 300, function () use ($request) {
            $query = WebPortal::query();

            // Filter by kategori
            if ($request->has('kategori')) {
                $query->byKategori($request->kategori);
            }

            // Only active for public
            if (!auth('sanctum')->check()) {
                $query->active();
            }

            return $query->ordered()->get();
        });

        return response()->json([
            'success' => true,
            'data' => WebPortalResource::collection($data)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebPortalRequest $request): JsonResponse
    {
        $webPortal = WebPortal::create($request->validated());
        
        // Clear cache
        Cache::forget('web_portals_*');

        return response()->json([
            'success' => true,
            'message' => 'Web portal berhasil ditambahkan',
            'data' => new WebPortalResource($webPortal)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(WebPortal $webPortal): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new WebPortalResource($webPortal)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWebPortalRequest $request, WebPortal $webPortal): JsonResponse
    {
        $webPortal->update($request->validated());
        
        // Clear cache
        Cache::forget('web_portals_*');

        return response()->json([
            'success' => true,
            'message' => 'Web portal berhasil diupdate',
            'data' => new WebPortalResource($webPortal->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebPortal $webPortal): JsonResponse
    {
        $webPortal->delete();
        
        // Clear cache
        Cache::forget('web_portals_*');

        return response()->json([
            'success' => true,
            'message' => 'Web portal berhasil dihapus'
        ]);
    }
}
