<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewOpdController extends Controller
{
    public function index(Request $request)
    {
        $query = ReviewOpd::latest('tanggal_review');

        if ($request->filled('search')) {
            $query->where('nama_opd', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status_review', $request->status);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun_anggaran', $request->tahun);
        }

        $reviews   = $query->paginate(15)->withQueryString();
        $tahunList = ReviewOpd::selectRaw('DISTINCT tahun_anggaran')
            ->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran');

        return view('admin.review-opd.index', compact('reviews', 'tahunList'));
    }

    public function create()
    {
        return view('admin.review-opd.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_opd'        => 'required|string|max:255',
            'tahun_anggaran'  => 'required|integer|min:2000|max:2100',
            'tanggal_review'  => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_review',
            'status_review'   => 'required|in:dijadwalkan,sedang_berjalan,selesai',
            'hasil_review'    => 'nullable|string|max:255',
            'keterangan'      => 'nullable|string',
            'dokumen'         => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->only(['nama_opd', 'tahun_anggaran', 'tanggal_review', 'tanggal_selesai',
                                 'status_review', 'hasil_review', 'keterangan']);

        if ($request->hasFile('dokumen')) {
            $data['dokumen_path'] = $request->file('dokumen')
                ->store('review-opd-dokumen', 'public');
        }

        ReviewOpd::create($data);

        return redirect()->route('admin.review-opd.index')
            ->with('success', 'Data review OPD berhasil ditambahkan.');
    }

    public function edit(ReviewOpd $reviewOpd)
    {
        return view('admin.review-opd.edit', compact('reviewOpd'));
    }

    public function update(Request $request, ReviewOpd $reviewOpd)
    {
        $request->validate([
            'nama_opd'        => 'required|string|max:255',
            'tahun_anggaran'  => 'required|integer|min:2000|max:2100',
            'tanggal_review'  => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_review',
            'status_review'   => 'required|in:dijadwalkan,sedang_berjalan,selesai',
            'hasil_review'    => 'nullable|string|max:255',
            'keterangan'      => 'nullable|string',
            'dokumen'         => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->only(['nama_opd', 'tahun_anggaran', 'tanggal_review', 'tanggal_selesai',
                                 'status_review', 'hasil_review', 'keterangan']);

        if ($request->hasFile('dokumen')) {
            if ($reviewOpd->dokumen_path) {
                Storage::disk('public')->delete($reviewOpd->dokumen_path);
            }
            $data['dokumen_path'] = $request->file('dokumen')
                ->store('review-opd-dokumen', 'public');
        }

        if ($request->boolean('hapus_dokumen') && $reviewOpd->dokumen_path) {
            Storage::disk('public')->delete($reviewOpd->dokumen_path);
            $data['dokumen_path'] = null;
        }

        $reviewOpd->update($data);

        return redirect()->route('admin.review-opd.index')
            ->with('success', 'Data review OPD berhasil diperbarui.');
    }

    public function destroy(ReviewOpd $reviewOpd)
    {
        if ($reviewOpd->dokumen_path) {
            Storage::disk('public')->delete($reviewOpd->dokumen_path);
        }

        $reviewOpd->delete();

        return redirect()->route('admin.review-opd.index')
            ->with('success', 'Data review OPD berhasil dihapus.');
    }
}
