<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWbsRequest extends FormRequest
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
            'nama_pelapor' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'subjek' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'is_anonymous' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_pelapor.required' => 'Nama pelapor harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'subjek.required' => 'Subjek harus diisi',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'attachment.file' => 'File tidak valid',
            'attachment.mimes' => 'File harus berformat pdf, doc, docx, jpg, jpeg, atau png',
            'attachment.max' => 'Ukuran file maksimal 10MB',
        ];
    }
}
