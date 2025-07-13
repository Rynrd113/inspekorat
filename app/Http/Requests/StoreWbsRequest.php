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
            'nama_pelapor' => 'required_unless:is_anonymous,1|string|max:255',
            'email' => 'required_unless:is_anonymous,1|email|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'subjek' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_kejadian' => 'nullable|date',
            'lokasi_kejadian' => 'nullable|string|max:255',
            'pihak_terlibat' => 'nullable|string',
            'kronologi' => 'nullable|string',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'is_anonymous' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_pelapor.required_unless' => 'Nama pelapor harus diisi jika tidak anonymous',
            'email.required_unless' => 'Email harus diisi jika tidak anonymous',
            'email.email' => 'Format email tidak valid',
            'subjek.required' => 'Subjek harus diisi',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'attachments.max' => 'Maksimal 5 file yang dapat dilampirkan',
            'attachments.*.file' => 'File tidak valid',
            'attachments.*.mimes' => 'File harus berformat pdf, doc, docx, jpg, jpeg, atau png',
            'attachments.*.max' => 'Ukuran file maksimal 10MB',
        ];
    }
}
