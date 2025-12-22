<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Requests\UpdatePengaduanRequest;
use App\Http\Resources\PengaduanResource;
use App\Models\Pengaduan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Pengaduan::query();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->byStatus($request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pengadu', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%");
            });
        }

        $pengaduans = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => PengaduanResource::collection($pengaduans),
            'meta' => [
                'current_page' => $pengaduans->currentPage(),
                'last_page' => $pengaduans->lastPage(),
                'per_page' => $pengaduans->perPage(),
                'total' => $pengaduans->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengaduanRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('pengaduan-attachments', 'public');
        }

        $pengaduan = Pengaduan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dikirim',
            'data' => new PengaduanResource($pengaduan)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new PengaduanResource($pengaduan)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengaduanRequest $request, Pengaduan $pengaduan): JsonResponse
    {
        $pengaduan->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil diupdate',
            'data' => new PengaduanResource($pengaduan->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengaduan $pengaduan): JsonResponse
    {
        // Delete attachment if exists
        if ($pengaduan->attachment) {
            Storage::disk('public')->delete($pengaduan->attachment);
        }

        $pengaduan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dihapus'
        ]);
    }
}
