<?php

namespace App\Providers;

use Illuminate\Filesystem\FilesystemManager;
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
            // Document uploads
            'dokumen/files',
            'dokumen/covers',

            // Gallery uploads
            'galeri',

            // Profile uploads
            'profile',
            'profile/avatar',

            // Temporary uploads
            'temp',
            'temp/images',
            'temp/documents',

            // Berita/News uploads
            'berita/thumbnail',
            'berita/content',

            // Settings
            'settings',
        ];

        foreach ($directories as $directory) {
            $fullPath = $basePath . DIRECTORY_SEPARATOR . $directory;
            if (!is_dir($fullPath)) {
                @mkdir($fullPath, 0775, true);
            }
        }
    }
}


