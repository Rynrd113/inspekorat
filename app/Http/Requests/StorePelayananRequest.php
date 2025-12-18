<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\HasValidationMessages;

class StorePelayananRequest extends FormRequest
{
    use HasValidationMessages;

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
        return $this->getAllMessages(
            ['file_formulir' => ['type' => 'document', 'maxSizeMB' => 2, 'formats' => ['pdf', 'doc', 'docx']]],
            [
                'nama_layanan.required' => 'Nama layanan wajib diisi.',
                'nama_layanan.max' => 'Nama layanan maksimal 255 karakter.',
                'deskripsi.required' => 'Deskripsi layanan wajib diisi.',
                'kategori.required' => 'Kategori layanan wajib dipilih.',
                'kategori.in' => 'Kategori layanan tidak valid.',
                'urutan.integer' => 'Urutan harus berupa angka.',
                'urutan.min' => 'Urutan minimal 1.',
            ]
        );
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->has('status'),
        ]);
    }
    
    /**
     * Get validated data with prosedur and persyaratan converted to arrays.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Convert textarea to array for prosedur
        $prosedur = $this->input('prosedur', '');
        if (is_string($prosedur) && !empty($prosedur)) {
            $validated['prosedur'] = array_values(array_filter(
                array_map('trim', explode("\n", $prosedur)),
                fn($line) => !empty($line)
            ));
        } else {
            $validated['prosedur'] = [];
        }
        
        // Convert textarea to array for persyaratan
        $persyaratan = $this->input('persyaratan', '');
        if (is_string($persyaratan) && !empty($persyaratan)) {
            $validated['persyaratan'] = array_values(array_filter(
                array_map('trim', explode("\n", $persyaratan)),
                fn($line) => !empty($line)
            ));
        } else {
            $validated['persyaratan'] = [];
        }
        
        return $validated;
    }
}
