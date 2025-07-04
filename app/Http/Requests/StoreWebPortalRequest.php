<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebPortalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_portal' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'url_portal' => 'required|url|max:255',
            'kategori' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_portal.required' => 'Nama portal wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'url_portal.required' => 'URL portal wajib diisi.',
            'url_portal.url' => 'Format URL tidak valid.',
            'kategori.required' => 'Kategori wajib dipilih.',
        ];
    }
}
