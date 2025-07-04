<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWbsRequest;
use App\Http\Requests\UpdateWbsRequest;
use App\Models\Wbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WbsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Wbs::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelapor', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%");
            });
        }

        $wbsReports = $query->latest()->paginate(10);

        return view('admin.wbs.index', compact('wbsReports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.wbs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWbsRequest $request)
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('bukti_file')) {
            $data['bukti_file'] = $request->file('bukti_file')->store('wbs-attachments', 'public');
        }

        Wbs::create($data);

        return redirect()->route('admin.wbs.index')
            ->with('success', 'Laporan WBS berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wbs $wbs)
    {
        return view('admin.wbs.show', compact('wbs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wbs $wbs)
    {
        return view('admin.wbs.edit', compact('wbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWbsRequest $request, Wbs $wbs)
    {
        $data = $request->validated();
        
        // Add timestamp when response is provided
        if (!empty($data['response']) && $data['response'] !== $wbs->response) {
            $data['responded_at'] = now();
        }

        $wbs->update($data);

        return redirect()->route('admin.wbs.show', $wbs)
            ->with('success', 'Status laporan WBS berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wbs $wbs)
    {
        // Delete attachment if exists
        if ($wbs->bukti_file) {
            Storage::disk('public')->delete($wbs->bukti_file);
        }
        
        if ($wbs->attachment) {
            Storage::disk('public')->delete($wbs->attachment);
        }

        $wbs->delete();

        return redirect()->route('admin.wbs.index')
            ->with('success', 'Laporan WBS berhasil dihapus');
    }
}
