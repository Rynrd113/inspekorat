<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePortalPapuaTengahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|in:berita,pengumuman,kegiatan,regulasi,layanan',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul berita harus diisi',
            'konten.required' => 'Konten berita harus diisi',
            'penulis.required' => 'Penulis harus diisi',
            'kategori.required' => 'Kategori harus dipilih',
            'kategori.in' => 'Kategori tidak valid',
            'thumbnail.image' => 'Thumbnail harus berupa gambar',
            'thumbnail.mimes' => 'Thumbnail harus berformat jpg, jpeg, png, atau webp',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2MB',
        ];
    }
}
