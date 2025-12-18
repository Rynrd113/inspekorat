<?php

namespace App\Traits;

/**
 * Trait HasValidationMessages
 * 
 * Provides centralized, reusable validation error messages
 * for Form Request classes to eliminate duplication.
 * 
 * Usage in Form Request:
 * use App\Traits\HasValidationMessages;
 * 
 * public function messages(): array
 * {
 *     return $this->getCommonMessages([
 *         'custom_field.required' => 'Custom message here',
 *     ]);
 * }
 */
trait HasValidationMessages
{
    /**
     * Get common validation messages
     *
     * @param array $customMessages Additional custom messages
     * @return array
     */
    public function getCommonMessages(array $customMessages = []): array
    {
        return array_merge($this->getBaseMessages(), $customMessages);
    }

    /**
     * Get base validation messages
     *
     * @return array
     */
    protected function getBaseMessages(): array
    {
        return [
            // Required messages
            '*.required' => ':attribute wajib diisi.',
            '*.required_if' => ':attribute wajib diisi.',
            '*.required_unless' => ':attribute wajib diisi.',
            '*.required_with' => ':attribute wajib diisi jika :values ada.',
            '*.required_without' => ':attribute wajib diisi jika :values tidak ada.',

            // String messages
            '*.string' => ':attribute harus berupa teks.',
            '*.min' => ':attribute minimal :min karakter.',
            '*.max' => ':attribute maksimal :max karakter.',
            '*.between' => ':attribute harus antara :min sampai :max karakter.',

            // Numeric messages
            '*.numeric' => ':attribute harus berupa angka.',
            '*.integer' => ':attribute harus berupa angka bulat.',
            '*.digits' => ':attribute harus terdiri dari :digits digit.',
            '*.digits_between' => ':attribute harus antara :min sampai :max digit.',

            // Email messages
            '*.email' => 'Format :attribute tidak valid.',
            '*.unique' => ':attribute sudah terdaftar.',

            // URL messages
            '*.url' => 'Format :attribute harus berupa URL yang valid.',
            '*.active_url' => ':attribute harus berupa URL yang aktif.',

            // Date messages
            '*.date' => 'Format :attribute tidak valid.',
            '*.date_format' => ':attribute harus dalam format :format.',
            '*.before' => ':attribute harus sebelum :date.',
            '*.after' => ':attribute harus setelah :date.',

            // Password messages
            '*.confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'password.min' => 'Password minimal :min karakter.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',

            // Boolean messages
            '*.boolean' => ':attribute harus berupa true atau false.',

            // Array messages
            '*.array' => ':attribute harus berupa array.',
            '*.in' => ':attribute yang dipilih tidak valid.',
            '*.not_in' => ':attribute yang dipilih tidak valid.',

            // File upload messages
            '*.file' => ':attribute harus berupa file.',
            '*.mimes' => ':attribute harus berformat :values.',
            '*.mimetypes' => ':attribute harus berformat :values.',
            '*.image' => ':attribute harus berupa gambar.',
            '*.dimensions' => 'Dimensi :attribute tidak valid.',

            // File size messages
            'file.max' => 'Ukuran :attribute maksimal :max KB.',
            'image.max' => 'Ukuran :attribute maksimal :max KB.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal :max KB.',
            'file_dokumen.max' => 'Ukuran file dokumen maksimal :max KB.',
            'gambar_preview.max' => 'Ukuran gambar preview maksimal :max KB.',

            // Specific field messages
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'judul.required' => 'Judul wajib diisi.',
            'konten.required' => 'Konten wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
            'status.boolean' => 'Status harus berupa true atau false.',
        ];
    }

    /**
     * Get file-specific validation messages
     *
     * @param string $fieldName Field name (e.g., 'thumbnail', 'file_dokumen')
     * @param int $maxSizeMB Maximum file size in MB
     * @param array $allowedFormats Allowed file formats
     * @return array
     */
    protected function getFileMessages(string $fieldName, int $maxSizeMB = 2, array $allowedFormats = []): array
    {
        $maxSizeKB = $maxSizeMB * 1024;
        $formatsStr = !empty($allowedFormats) ? implode(', ', $allowedFormats) : '';

        $messages = [
            "{$fieldName}.required" => ucfirst($fieldName) . ' wajib diunggah.',
            "{$fieldName}.file" => ucfirst($fieldName) . ' harus berupa file.',
            "{$fieldName}.max" => "Ukuran {$fieldName} maksimal {$maxSizeMB}MB.",
        ];

        if (!empty($allowedFormats)) {
            $messages["{$fieldName}.mimes"] = ucfirst($fieldName) . " harus berformat {$formatsStr}.";
            $messages["{$fieldName}.image"] = ucfirst($fieldName) . ' harus berupa gambar.';
        }

        return $messages;
    }

    /**
     * Get image-specific validation messages
     *
     * @param string $fieldName Field name
     * @param int $maxSizeMB Maximum size in MB (default 2MB)
     * @return array
     */
    protected function getImageMessages(string $fieldName = 'thumbnail', int $maxSizeMB = 2): array
    {
        return $this->getFileMessages(
            $fieldName,
            $maxSizeMB,
            ['jpg', 'jpeg', 'png', 'gif', 'webp']
        );
    }

    /**
     * Get document-specific validation messages
     *
     * @param string $fieldName Field name
     * @param int $maxSizeMB Maximum size in MB (default 10MB)
     * @return array
     */
    protected function getDocumentMessages(string $fieldName = 'file_dokumen', int $maxSizeMB = 10): array
    {
        return $this->getFileMessages(
            $fieldName,
            $maxSizeMB,
            ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']
        );
    }

    /**
     * Get all validation messages including file messages
     *
     * @param array $fileFields Array of file field configurations
     *                          Example: ['thumbnail' => ['type' => 'image', 'maxSizeMB' => 2]]
     * @param array $customMessages Additional custom messages
     * @return array
     */
    public function getAllMessages(array $fileFields = [], array $customMessages = []): array
    {
        $messages = $this->getBaseMessages();

        // Add file-specific messages
        foreach ($fileFields as $fieldName => $config) {
            $type = $config['type'] ?? 'file';
            $maxSizeMB = $config['maxSizeMB'] ?? 2;

            if ($type === 'image') {
                $messages = array_merge($messages, $this->getImageMessages($fieldName, $maxSizeMB));
            } elseif ($type === 'document') {
                $messages = array_merge($messages, $this->getDocumentMessages($fieldName, $maxSizeMB));
            } else {
                $formats = $config['formats'] ?? [];
                $messages = array_merge($messages, $this->getFileMessages($fieldName, $maxSizeMB, $formats));
            }
        }

        return array_merge($messages, $customMessages);
    }
}
