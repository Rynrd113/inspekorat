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
        // Implementation for listing FAQs
        return view('admin.faq.index');
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
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban' => 'required|string',
            'kategori' => 'required|string|max:100',
            'urutan' => 'nullable|integer|min:1',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Store FAQ (you'll need to create the model)
        // Faq::create($validated);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ berhasil ditambahkan');
    }

    /**
     * Display the specified FAQ
     */
    public function show($id)
    {
        return view('admin.faq.show');
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit($id)
    {
        return view('admin.faq.edit');
    }

    /**
     * Update the specified FAQ
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban' => 'required|string',
            'kategori' => 'required|string|max:100',
            'urutan' => 'nullable|integer|min:1',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Update FAQ
        // $faq->update($validated);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ berhasil diperbarui');
    }

    /**
     * Remove the specified FAQ
     */
    public function destroy($id)
    {
        // Delete FAQ
        // $faq->delete();

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
            // Faq::where('id', $item['id'])->update(['urutan' => $item['urutan']]);
        }

        return response()->json(['success' => true]);
    }
}
