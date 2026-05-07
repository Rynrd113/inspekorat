<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewOpd;
use Illuminate\Http\Request;

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

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.review-opd.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.review-opd.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_opd'       => 'required|string|max:255',
            'tanggal_review' => 'required|date',
            'status_review'  => 'required|in:dijadwalkan,sedang_berjalan,selesai',
            'hasil_review'   => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
        ]);

        ReviewOpd::create($request->only(['nama_opd', 'tanggal_review', 'status_review', 'hasil_review', 'keterangan']));

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
            'nama_opd'       => 'required|string|max:255',
            'tanggal_review' => 'required|date',
            'status_review'  => 'required|in:dijadwalkan,sedang_berjalan,selesai',
            'hasil_review'   => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
        ]);

        $reviewOpd->update($request->only(['nama_opd', 'tanggal_review', 'status_review', 'hasil_review', 'keterangan']));

        return redirect()->route('admin.review-opd.index')
            ->with('success', 'Data review OPD berhasil diperbarui.');
    }

    public function destroy(ReviewOpd $reviewOpd)
    {
        $reviewOpd->delete();

        return redirect()->route('admin.review-opd.index')
            ->with('success', 'Data review OPD berhasil dihapus.');
    }
}
