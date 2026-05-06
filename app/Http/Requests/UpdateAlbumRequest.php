<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAlbumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin', 'content_admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_album' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('albums', 'slug')->ignore($this->album),
            ],
            'parent_id' => [
                'nullable',
                'exists:albums,id',
                Rule::notIn([$this->album]), // Prevent self-parent
            ],
            'deskripsi' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tanggal_kegiatan' => 'nullable|date',
            'status' => 'nullable|boolean',
            'urutan' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom attribute names
     */
    public function attributes(): array
    {
        return [
            'nama_album' => 'nama album',
            'parent_id' => 'album induk',
            'cover_image' => 'gambar cover',
            'tanggal_kegiatan' => 'tanggal kegiatan',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'nama_album.required' => 'Nama album harus diisi.',
            'nama_album.max' => 'Nama album maksimal 255 karakter.',
            'slug.unique' => 'Slug sudah digunakan, silakan gunakan slug yang berbeda.',
            'parent_id.exists' => 'Album induk yang dipilih tidak valid.',
            'parent_id.not_in' => 'Album tidak bisa menjadi parent dari dirinya sendiri.',
            'cover_image.image' => 'File cover harus berupa gambar.',
            'cover_image.mimes' => 'Format gambar harus JPEG, PNG, JPG, GIF, atau WEBP.',
            'cover_image.max' => 'Ukuran gambar maksimal 5MB.',
            'tanggal_kegiatan.date' => 'Format tanggal kegiatan tidak valid.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 0.',
        ];
    }
}
