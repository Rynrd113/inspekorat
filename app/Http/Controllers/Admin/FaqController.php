<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs
     */
    public function index(Request $request)
    {
        $query = \App\Models\Faq::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('pertanyaan', 'like', '%' . $request->search . '%')
                  ->orWhere('jawaban', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $faqs = $query->ordered()->paginate(10);

        return view('admin.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ
     */
    public function create()
    {
        return view('admin.faq.create');
    }

    /**
     * Store a newly created FAQ
     */
    public function store(Request $request)
    {
        $categories = array_keys(\App\Models\Faq::getKategoriOptions());
        
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban' => 'required|string',
            'kategori' => 'required|string|in:' . implode(',', $categories),
            'urutan' => 'nullable|integer|min:1',
            'status' => 'required|string|in:0,1',
            'is_popular' => 'nullable|string|in:0,1',
            'tags' => 'nullable|string',
        ]);

        // Convert string values to appropriate types
        $validated['created_by'] = auth()->id();
        $validated['status'] = $request->status === '1';
        $validated['is_popular'] = $request->is_popular === '1';

        \App\Models\Faq::create($validated);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ berhasil ditambahkan');
    }

    /**
     * Display the specified FAQ
     */
    public function show(\App\Models\Faq $faq)
    {
        return view('admin.faq.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit(\App\Models\Faq $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ
     */
    public function update(Request $request, \App\Models\Faq $faq)
    {
        $categories = array_keys(\App\Models\Faq::getKategoriOptions());
        
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban' => 'required|string',
            'kategori' => 'required|string|in:' . implode(',', $categories),
            'urutan' => 'nullable|integer|min:1',
            'status' => 'required|string|in:0,1',
            'is_popular' => 'nullable|string|in:0,1',
            'tags' => 'nullable|string',
        ]);

        // Convert string values to appropriate types
        $validated['updated_by'] = auth()->id();
        $validated['status'] = $request->status === '1';
        $validated['is_popular'] = $request->is_popular === '1';

        $faq->update($validated);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ berhasil diperbarui');
    }

    /**
     * Remove the specified FAQ
     */
    public function destroy(\App\Models\Faq $faq)
    {
        // Delete FAQ
        $faq->delete();

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ berhasil dihapus');
    }

    /**
     * Reorder FAQs
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.urutan' => 'required|integer|min:1',
        ]);

        // Update order for each FAQ
        foreach ($request->items as $item) {
            \App\Models\Faq::where('id', $item['id'])->update(['urutan' => $item['urutan']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle FAQ status
     */
    public function toggleStatus(\App\Models\Faq $faq)
    {
        $faq->update(['status' => !$faq->status]);
        
        return redirect()->route('admin.faq.index')
            ->with('success', 'Status FAQ berhasil diubah');
    }

    /**
     * Move FAQ up in order
     */
    public function moveUp(\App\Models\Faq $faq)
    {
        $upperFaq = \App\Models\Faq::where('urutan', '<', $faq->urutan)
            ->orderBy('urutan', 'desc')
            ->first();

        if ($upperFaq) {
            $tempUrutan = $faq->urutan;
            $faq->update(['urutan' => $upperFaq->urutan]);
            $upperFaq->update(['urutan' => $tempUrutan]);
        }

        return redirect()->route('admin.faq.index')
            ->with('success', 'Urutan FAQ berhasil diubah');
    }

    /**
     * Move FAQ down in order
     */
    public function moveDown(\App\Models\Faq $faq)
    {
        $lowerFaq = \App\Models\Faq::where('urutan', '>', $faq->urutan)
            ->orderBy('urutan', 'asc')
            ->first();

        if ($lowerFaq) {
            $tempUrutan = $faq->urutan;
            $faq->update(['urutan' => $lowerFaq->urutan]);
            $lowerFaq->update(['urutan' => $tempUrutan]);
        }

        return redirect()->route('admin.faq.index')
            ->with('success', 'Urutan FAQ berhasil diubah');
    }
}
