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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'tanggal_kegiatan' => 'nullable|date',
            'status' => 'boolean',
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
}
