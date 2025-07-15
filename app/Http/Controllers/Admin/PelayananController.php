<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\PelayananServiceInterface;
use App\Http\Requests\StorePelayananRequest;
use App\Http\Requests\UpdatePelayananRequest;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelayananController extends Controller
{
    protected $pelayananService;

    public function __construct(PelayananServiceInterface $pelayananService)
    {
        $this->pelayananService = $pelayananService;
    }

    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'kategori' => $request->kategori,
            'status' => $request->status,
        ];

        $pelayanans = $this->pelayananService->getAllPaginated($filters, 10);

        return view('admin.pelayanan.index', compact('pelayanans'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.pelayanan.create');
    }

    /**
     * Store a newly created service
     */
    public function store(StorePelayananRequest $request)
    {
        $pelayanan = $this->pelayananService->createPelayanan($request);

        return redirect()->route('admin.pelayanan.index')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    /**
     * Display the specified service
     */
    public function show(\App\Models\Pelayanan $pelayanan)
    {
        return view('admin.pelayanan.show', compact('pelayanan'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(\App\Models\Pelayanan $pelayanan)
    {
        return view('admin.pelayanan.edit', compact('pelayanan'));
    }

    /**
     * Update the specified service
     */
    public function update(UpdatePelayananRequest $request, \App\Models\Pelayanan $pelayanan)
    {
        $result = $this->pelayananService->updatePelayanan($pelayanan->id, $request);

        if ($result) {
            return redirect()->route('admin.pelayanan.index')
                ->with('success', 'Layanan berhasil diperbarui');
        }

        return redirect()->back()
            ->with('error', 'Gagal memperbarui layanan');
    }

    /**
     * Remove the specified service
     */
    public function destroy(\App\Models\Pelayanan $pelayanan)
    {
        $result = $this->pelayananService->deletePelayanan($pelayanan->id);

        if ($result) {
            return redirect()->route('admin.pelayanan.index')
                ->with('success', 'Layanan berhasil dihapus');
        }

        return redirect()->back()
            ->with('error', 'Gagal menghapus layanan');
    }
}
