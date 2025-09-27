<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\BrandingPresetService;

class BrandingImageUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && 
               (auth()->user()->isSuperAdmin() || auth()->user()->hasRole(['admin', 'branding_manager']));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $constraints = BrandingPresetService::getLogoConstraints();
        $type = $this->input('type');
        $typeKey = str_replace(['brand_logo_', 'brand_'], '', $type);
        
        if (!isset($constraints[$typeKey])) {
            return ['type' => 'required|in:brand_logo_header,brand_logo_footer,brand_logo_icon,brand_favicon'];
        }

        $constraint = $constraints[$typeKey];
        
        return [
            'type' => 'required|in:brand_logo_header,brand_logo_footer,brand_logo_icon,brand_favicon',
            'image' => [
                'required',
                'file',
                'image',
                'mimes:' . implode(',', $constraint['formats']),
                'max:' . $constraint['max_size'], // KB
                function ($attribute, $value, $fail) use ($constraint) {
                    if (!$value) return;
                    
                    // Validate dimensions
                    $imageInfo = getimagesize($value->getPathname());
                    if ($imageInfo) {
                        if ($imageInfo[0] > $constraint['max_width'] || $imageInfo[1] > $constraint['max_height']) {
                            $fail("Dimensi gambar maksimal {$constraint['max_width']}x{$constraint['max_height']}px");
                        }
                    }
                    
                    // Security validation
                    $securityErrors = BrandingPresetService::validateImageSecurity($value->getPathname());
                    if (!empty($securityErrors)) {
                        $fail('File tidak aman: ' . implode(', ', $securityErrors));
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Tipe logo harus ditentukan.',
            'type.in' => 'Tipe logo tidak valid.',
            'image.required' => 'File gambar harus dipilih.',
            'image.file' => 'File yang diupload harus berupa file.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format file tidak didukung.',
            'image.max' => 'Ukuran file terlalu besar.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type' => 'tipe logo',
            'image' => 'gambar',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Additional file validation
            if ($this->hasFile('image')) {
                $this->validateFileIntegrity($validator);
            }
        });
    }

    /**
     * Additional file integrity validation
     */
    private function validateFileIntegrity($validator): void
    {
        $file = $this->file('image');
        
        if (!$file) return;

        // Check file extension matches MIME type
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        $mimeExtensionMap = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/svg+xml' => ['svg'],
            'image/webp' => ['webp'],
            'image/x-icon' => ['ico'],
        ];

        $expectedExtensions = $mimeExtensionMap[$mimeType] ?? [];
        
        if (!in_array($extension, $expectedExtensions)) {
            $validator->errors()->add('image', 'Ekstensi file tidak sesuai dengan tipe file.');
        }

        // Check for double extensions (security risk)
        $filename = $file->getClientOriginalName();
        if (substr_count($filename, '.') > 1) {
            $validator->errors()->add('image', 'Nama file tidak boleh memiliki ekstensi ganda.');
        }

        // Check for suspicious filename patterns
        $suspiciousPatterns = ['.php.', '.asp.', '.jsp.', '.exe.', '.scr.', '.bat.'];
        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($filename, $pattern) !== false) {
                $validator->errors()->add('image', 'Nama file mengandung pola yang tidak diizinkan.');
                break;
            }
        }
    }
}