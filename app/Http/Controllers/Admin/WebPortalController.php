<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebPortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebPortalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WebPortal::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_portal', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $webPortals = $query->orderBy('urutan')->paginate(10);

        return view('admin.web-portal.index', compact('webPortals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.web-portal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_portal' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'url' => 'required|url|max:255',
            'kategori' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:1',
            'status' => 'nullable|boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $request->has('status');

        // Set urutan if not provided
        if (!isset($validated['urutan'])) {
            $validated['urutan'] = WebPortal::max('urutan') + 1;
        }

        WebPortal::create($validated);

        return redirect()->route('admin.web-portal.index')
            ->with('success', 'Web Portal berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(WebPortal $webPortal)
    {
        return view('admin.web-portal.show', compact('webPortal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebPortal $webPortal)
    {
        return view('admin.web-portal.edit', compact('webPortal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebPortal $webPortal)
    {
        $validated = $request->validate([
            'nama_portal' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'url' => 'required|url|max:255',
            'kategori' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:1',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status');

        $webPortal->update($validated);

        return redirect()->route('admin.web-portal.index')
            ->with('success', 'Web Portal berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebPortal $webPortal)
    {
        $webPortal->delete();

        return redirect()->route('admin.web-portal.index')
            ->with('success', 'Web Portal berhasil dihapus');
    }
}
