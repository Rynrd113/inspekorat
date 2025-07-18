<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

trait HasFileUpload
{
    /**
     * Get file upload validation rules
     */
    public static function getFileUploadRules(string $fieldName = 'file', array $options = []): array
    {
        $maxSize = $options['max_size'] ?? 2048; // 2MB default
        $mimeTypes = $options['mime_types'] ?? ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif'];
        $required = $options['required'] ?? false;
        
        $rules = [
            'file',
            'mimes:' . implode(',', $mimeTypes),
            'max:' . $maxSize
        ];
        
        if ($required) {
            array_unshift($rules, 'required');
        } else {
            array_unshift($rules, 'nullable');
        }
        
        return [$fieldName => $rules];
    }

    /**
     * Get document upload validation rules
     */
    public static function getDocumentUploadRules(string $fieldName = 'file', bool $required = false): array
    {
        return self::getFileUploadRules($fieldName, [
            'max_size' => 5120, // 5MB
            'mime_types' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            'required' => $required
        ]);
    }

    /**
     * Get image upload validation rules
     */
    public static function getImageUploadRules(string $fieldName = 'image', bool $required = false): array
    {
        return self::getFileUploadRules($fieldName, [
            'max_size' => 2048, // 2MB
            'mime_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'required' => $required
        ]);
    }

    /**
     * Get video upload validation rules
     */
    public static function getVideoUploadRules(string $fieldName = 'video', bool $required = false): array
    {
        return self::getFileUploadRules($fieldName, [
            'max_size' => 51200, // 50MB
            'mime_types' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
            'required' => $required
        ]);
    }

    /**
     * Upload file to storage
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $filename = $this->generateUniqueFilename($file);
        $path = $file->storeAs($directory, $filename, $disk);

        return $path;
    }

    /**
     * Upload image with resize option
     */
    public function uploadImage(UploadedFile $file, string $directory = 'images', array $sizes = []): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $filename = $this->generateUniqueFilename($file);
        $path = $file->storeAs($directory, $filename, 'public');

        // If sizes are specified, create thumbnails
        if (!empty($sizes)) {
            $this->createImageThumbnails($path, $sizes);
        }

        return $path;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultipleFiles(array $files, string $directory = 'uploads'): array
    {
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $path = $this->uploadFile($file, $directory);
                if ($path) {
                    $uploadedFiles[] = $path;
                }
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        
        return false;
    }

    /**
     * Delete multiple files
     */
    public function deleteMultipleFiles(array $paths, string $disk = 'public'): bool
    {
        $success = true;
        
        foreach ($paths as $path) {
            if (!$this->deleteFile($path, $disk)) {
                $success = false;
            }
        }
        
        return $success;
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $sanitizedName = Str::slug($originalName);
        $timestamp = now()->format('Y-m-d_His');
        $randomString = Str::random(8);
        
        return "{$sanitizedName}_{$timestamp}_{$randomString}.{$extension}";
    }

    /**
     * Create image thumbnails
     */
    protected function createImageThumbnails(string $path, array $sizes): void
    {
        // This would require image manipulation library like Intervention Image
        // For now, we'll just store the original size
        // Implementation would depend on the image library used
    }

    /**
     * Get file URL
     */
    public function getFileUrl(string $path, string $disk = 'public'): string
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }
        
        return '';
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSize(string $path, string $disk = 'public'): string
    {
        if (Storage::disk($disk)->exists($path)) {
            $bytes = Storage::disk($disk)->size($path);
            return $this->formatBytes($bytes);
        }
        
        return '';
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Validate uploaded file
     */
    public function validateUploadedFile(UploadedFile $file, array $rules = []): bool
    {
        if (!$file->isValid()) {
            return false;
        }

        $maxSize = $rules['max_size'] ?? 2048;
        $allowedMimes = $rules['mime_types'] ?? ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        
        // Check file size (in KB)
        if ($file->getSize() > $maxSize * 1024) {
            return false;
        }
        
        // Check MIME type
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, $allowedMimes)) {
            return false;
        }
        
        return true;
    }
}