<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortalPapuaTengahRequest;
use App\Http\Requests\UpdatePortalPapuaTengahRequest;
use App\Models\PortalPapuaTengah;
use App\Models\ContentApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortalPapuaTengahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PortalPapuaTengah::query();

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori !== '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status === 'published');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $portalNews = $query->latest()->paginate(10);

        return view('admin.portal-papua-tengah.index', compact('portalNews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.portal-papua-tengah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortalPapuaTengahRequest $request)
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portal-thumbnails', 'public');
        }

        // Sync status and tanggal_publikasi from form fields
        $data['status'] = isset($data['is_published']) ? !empty($data['is_published']) : false;
        if (!empty($data['published_at'])) {
            $data['tanggal_publikasi'] = $data['published_at'];
        }

        $isContentAdmin = auth()->user()->hasRole('content_admin');

        // content_admin tidak bisa langsung publish — selalu draft
        if ($isContentAdmin) {
            $data['status'] = false;
        }

        $berita = PortalPapuaTengah::create($data);

        // Buat approval request jika content_admin
        if ($isContentAdmin) {
            ContentApproval::create([
                'approvable_type' => PortalPapuaTengah::class,
                'approvable_id'   => $berita->id,
                'submitted_by'    => auth()->id(),
                'status'          => ContentApproval::STATUS_PENDING,
                'submitted_at'    => now(),
            ]);

            return redirect()->route('admin.portal-papua-tengah.index')
                ->with('success', 'Berita berhasil dikirim dan menunggu persetujuan admin.');
        }

        return redirect()->route('admin.portal-papua-tengah.index')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PortalPapuaTengah $portalPapuaTengah)
    {
        return view('admin.portal-papua-tengah.show', compact('portalPapuaTengah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PortalPapuaTengah $portalPapuaTengah)
    {
        return view('admin.portal-papua-tengah.edit', compact('portalPapuaTengah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortalPapuaTengahRequest $request, PortalPapuaTengah $portalPapuaTengah)
    {
        $data = $request->validated();

        // Remove delete_thumbnail from validated data as it's not a field
        $data = array_diff_key($data, array_flip(['delete_thumbnail']));

        // Handle delete thumbnail checkbox
        if ($request->has('delete_thumbnail') && $request->delete_thumbnail) {
            if ($portalPapuaTengah->thumbnail) {
                Storage::disk('public')->delete($portalPapuaTengah->thumbnail);
            }
            $data['thumbnail'] = null;
        }
        // Handle new thumbnail upload
        elseif ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($portalPapuaTengah->thumbnail) {
                Storage::disk('public')->delete($portalPapuaTengah->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('portal-thumbnails', 'public');
        }

        // Sync status only if is_published was explicitly submitted
        if (isset($data['is_published'])) {
            $data['status'] = !empty($data['is_published']);
        }
        if (!empty($data['published_at'])) {
            $data['tanggal_publikasi'] = $data['published_at'];
        }

        $isContentAdmin = auth()->user()->hasRole('content_admin');

        // content_admin tidak bisa publish langsung
        if ($isContentAdmin) {
            $data['status'] = false;
        }

        $portalPapuaTengah->update($data);

        // Buat/perbarui approval request jika content_admin
        if ($isContentAdmin) {
            ContentApproval::updateOrCreate(
                [
                    'approvable_type' => PortalPapuaTengah::class,
                    'approvable_id'   => $portalPapuaTengah->id,
                    'status'          => ContentApproval::STATUS_PENDING,
                ],
                [
                    'submitted_by' => auth()->id(),
                    'submitted_at' => now(),
                    'notes'        => null,
                ]
            );

            return redirect()->route('admin.portal-papua-tengah.index')
                ->with('success', 'Berita berhasil diperbarui dan menunggu persetujuan admin.');
        }

        return redirect()->route('admin.portal-papua-tengah.index')
            ->with('success', 'Berita berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortalPapuaTengah $portalPapuaTengah)
    {
        // Delete thumbnail if exists
        if ($portalPapuaTengah->thumbnail) {
            Storage::disk('public')->delete($portalPapuaTengah->thumbnail);
        }

        $portalPapuaTengah->delete();

        return redirect()->route('admin.portal-papua-tengah.index')
            ->with('success', 'Berita berhasil dihapus');
    }
}
