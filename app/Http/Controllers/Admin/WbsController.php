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

        if (!auth()->user()->hasRole('super_admin')) {
            $wbsReports->getCollection()->transform(function ($item) {
                if ($item->is_anonymous) {
                    $item->nama_pelapor = '[Anonim]';
                    $item->email       = '[tersembunyi]';
                    $item->no_telepon  = '[tersembunyi]';
                }
                return $item;
            });
        }

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

        // Handle multiple file uploads
        if ($request->hasFile('attachments')) {
            $filePaths = [];
            foreach ($request->file('attachments') as $file) {
                $filePaths[] = $file->store('wbs-attachments', 'public');
            }
            $data['bukti_files'] = $filePaths;
        }

        // Legacy single file support
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
        if ($wbs->is_anonymous && !auth()->user()->hasRole('super_admin')) {
            $wbs->nama_pelapor = '[Anonim]';
            $wbs->email        = '[tersembunyi]';
            $wbs->no_telepon   = '[tersembunyi]';
        }
        return view('admin.wbs.show', compact('wbs'));
    }

    public function edit(Wbs $wbs)
    {
        if ($wbs->is_anonymous && !auth()->user()->hasRole('super_admin')) {
            $wbs->nama_pelapor = '[Anonim]';
            $wbs->email        = '[tersembunyi]';
            $wbs->no_telepon   = '[tersembunyi]';
        }
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

    public function downloadFile(Wbs $wbs, int $index = 0)
    {
        $files = $wbs->bukti_files ?? [];
        if ($index === 0 && $wbs->bukti_file) {
            $path = $wbs->bukti_file;
        } elseif (isset($files[$index])) {
            $path = $files[$index];
        } else {
            abort(404);
        }

        if (!\Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return \Storage::disk('public')->download($path, basename($path));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wbs $wbs)
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

        return redirect()->route('admin.wbs.index')
            ->with('success', 'Laporan WBS berhasil dihapus');
    }
}
