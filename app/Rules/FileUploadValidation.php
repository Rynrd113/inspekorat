<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class FileUploadValidation implements Rule
{
    protected $maxSize;
    protected $allowedMimes;
    protected $errorMessage;

    public function __construct(int $maxSize = 2048, array $allowedMimes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
    {
        $this->maxSize = $maxSize;
        $this->allowedMimes = $allowedMimes;
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value)
    {
        if (!$value instanceof UploadedFile) {
            $this->errorMessage = 'File harus berupa file yang valid.';
            return false;
        }

        if (!$value->isValid()) {
            $this->errorMessage = 'File tidak valid atau rusak.';
            return false;
        }

        // Check file size
        $fileSizeKB = $value->getSize() / 1024;
        if ($fileSizeKB > $this->maxSize) {
            $this->errorMessage = "Ukuran file maksimal {$this->maxSize} KB.";
            return false;
        }

        // Check MIME type
        $extension = $value->getClientOriginalExtension();
        if (!in_array(strtolower($extension), $this->allowedMimes)) {
            $allowedTypes = implode(', ', $this->allowedMimes);
            $this->errorMessage = "File harus berformat: {$allowedTypes}.";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return $this->errorMessage ?: 'File tidak valid.';
    }
}