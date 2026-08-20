<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbsRequest;
use App\Http\Requests\UpdateWbsRequest;
use App\Http\Resources\WbsResource;
use App\Models\Wbs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $resource = WbsResource::collection($wbsReports)->response()->getData();

        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            $data = collect($resource->data ?? $resource);
            $data->each(function ($item) {
                $item->nama_pelapor = ($item->is_anonymous ?? false) ? '[Anonim]' : $item->nama_pelapor;
                $item->email = ($item->is_anonymous ?? false) ? '[tersembunyi]' : $item->email;
                $item->nomor_telepon = ($item->is_anonymous ?? false) ? '[tersembunyi]' : $item->nomor_telepon;
            });
            $resource->data = $data->all();
        }

        return response()->json([
            'success' => true,
            'data' => $resource
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWbsRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Handle multiple file uploads
        if ($request->hasFile('attachments')) {
            $filePaths = [];
            foreach ($request->file('attachments') as $file) {
                $filePaths[] = $file->store('wbs-attachments', 'public');
            }
            $data['bukti_files'] = $filePaths;
        }

        // Legacy single file support
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
        $resource = new WbsResource($wbs);
        $data = $resource->resolve(request());

        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            $data['nama_pelapor'] = ($wbs->is_anonymous ?? false) ? '[Anonim]' : $data['nama_pelapor'];
            $data['email'] = ($wbs->is_anonymous ?? false) ? '[tersembunyi]' : $data['email'];
            $data['nomor_telepon'] = ($wbs->is_anonymous ?? false) ? '[tersembunyi]' : $data['nomor_telepon'];
        }

        return response()->json([
            'success' => true,
            'data' => $data
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
        // Delete single attachment if exists
        if ($wbs->bukti_file) {
            Storage::disk('public')->delete($wbs->bukti_file);
        }
        
        // Delete multiple attachments if exist
        if ($wbs->bukti_files && is_array($wbs->bukti_files)) {
            foreach ($wbs->bukti_files as $filePath) {
                Storage::disk('public')->delete($filePath);
            }
        }
        
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
