<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbsRequest;
use App\Http\Requests\UpdateWbsRequest;
use App\Http\Resources\WbsResource;
use App\Models\Wbs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class WbsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Wbs::query();

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelapor', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%");
            });
        }

        $wbsReports = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => WbsResource::collection($wbsReports)->response()->getData()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWbsRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('wbs-attachments', 'public');
        }

        $wbs = Wbs::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Laporan WBS berhasil dikirim',
            'data' => new WbsResource($wbs)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Wbs $wbs): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new WbsResource($wbs)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWbsRequest $request, Wbs $wbs): JsonResponse
    {
        $wbs->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Laporan WBS berhasil diupdate',
            'data' => new WbsResource($wbs->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wbs $wbs): JsonResponse
    {
        // Delete attachment if exists
        if ($wbs->attachment) {
            Storage::disk('public')->delete($wbs->attachment);
        }

        $wbs->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan WBS berhasil dihapus'
        ]);
    }
}
