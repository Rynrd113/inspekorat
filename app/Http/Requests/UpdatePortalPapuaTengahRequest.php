<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\HasValidationMessages;

class UpdatePortalPapuaTengahRequest extends FormRequest
{
    use HasValidationMessages;

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
            'judul' => 'sometimes|required|string|max:255',
            'konten' => 'sometimes|required|string',
            'penulis' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|in:berita,pengumuman,kegiatan,regulasi,layanan',
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
        return $this->getAllMessages(
            ['thumbnail' => ['type' => 'image', 'maxSizeMB' => 2]],
            [
                'penulis.required' => 'Penulis harus diisi',
                'kategori.in' => 'Kategori tidak valid',
            ]
        );
    }
}
