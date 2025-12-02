<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePelayananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin', 'service_manager']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prosedur' => 'nullable|array',
            'prosedur.*' => 'nullable|string',
            'persyaratan' => 'nullable|array',
            'persyaratan.*' => 'nullable|string',
            'waktu_pelayanan' => 'nullable|string',
            'biaya' => 'nullable|string',
            'dasar_hukum' => 'nullable|string',
            'kategori' => 'required|string|in:audit,konsultasi,reviu,evaluasi,pengawasan,lainnya',
            'status' => 'boolean',
            'kontak_penanggung_jawab' => 'nullable|string',
            'file_formulir' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'urutan' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.max' => 'Nama layanan maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi layanan wajib diisi.',
            'prosedur.required' => 'Prosedur layanan wajib diisi.',
            'prosedur.array' => 'Prosedur harus berupa array.',
            'prosedur.*.required' => 'Setiap prosedur wajib diisi.',
            'persyaratan.required' => 'Persyaratan layanan wajib diisi.',
            'persyaratan.array' => 'Persyaratan harus berupa array.',
            'persyaratan.*.required' => 'Setiap persyaratan wajib diisi.',
            'waktu_pelayanan.required' => 'Waktu pelayanan wajib diisi.',
            'kategori.required' => 'Kategori layanan wajib dipilih.',
            'kategori.in' => 'Kategori layanan tidak valid.',
            'file_formulir.file' => 'File formulir harus berupa file.',
            'file_formulir.mimes' => 'File formulir harus berformat PDF, DOC, atau DOCX.',
            'file_formulir.max' => 'Ukuran file formulir maksimal 2MB.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 1.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Filter empty values from arrays
        $prosedur = $this->input('prosedur', []);
        $persyaratan = $this->input('persyaratan', []);
        
        if (is_array($prosedur)) {
            $prosedur = array_values(array_filter($prosedur, function($value) {
                return !empty(trim($value));
            }));
        }
        
        if (is_array($persyaratan)) {
            $persyaratan = array_values(array_filter($persyaratan, function($value) {
                return !empty(trim($value));
            }));
        }
        
        $this->merge([
            'status' => $this->has('status'),
            'prosedur' => $prosedur,
            'persyaratan' => $persyaratan,
        ]);
    }
}
