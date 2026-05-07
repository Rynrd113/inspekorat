<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanKontak;
use Illuminate\Http\Request;

class PesanKontakController extends Controller
{
    public function index(Request $request)
    {
        $query = PesanKontak::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('subjek', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('sudah_dibaca', $request->status === 'dibaca');
        }

        $pesans = $query->paginate(15)->withQueryString();
        $belumDibaca = PesanKontak::where('sudah_dibaca', false)->count();

        return view('admin.pesan-kontak.index', compact('pesans', 'belumDibaca'));
    }

    public function show(PesanKontak $pesanKontak)
    {
        if (!$pesanKontak->sudah_dibaca) {
            $pesanKontak->update(['sudah_dibaca' => true]);
        }

        return view('admin.pesan-kontak.show', compact('pesanKontak'));
    }

    public function destroy(PesanKontak $pesanKontak)
    {
        $pesanKontak->delete();

        return redirect()->route('admin.pesan-kontak.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
