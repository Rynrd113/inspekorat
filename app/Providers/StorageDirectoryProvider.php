<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class StorageDirectoryProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->ensureStorageDirectoriesExist();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Ensure all necessary storage directories exist
     */
    protected function ensureStorageDirectoriesExist(): void
    {
        $basePath = storage_path('app/public');

        $directories = [
            'dokumen/files',
            'dokumen/covers',
            'galeri',
            'profile',
            'profile/avatar',
            'temp',
            'temp/images',
            'temp/documents',
            'berita/thumbnail',
            'berita/content',
            'settings',
            'pengaduan/bukti',
            'pengaduan-attachments',
            'wbs-attachments',
        ];

        foreach ($directories as $directory) {
            $fullPath = $basePath . DIRECTORY_SEPARATOR . $directory;
            if (!is_dir($fullPath)) {
                File::makeDirectory($fullPath, 0775, true, true);
            }
        }
    }
}


