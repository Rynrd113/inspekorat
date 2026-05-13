<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageOptimizationService
{
    private const QUALITY = 75;
    private const THUMB_WIDTH = 480;
    private const IMAGE_EXTS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function processGalleryImage(UploadedFile $file, string $folder = 'galeri'): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, self::IMAGE_EXTS) || !extension_loaded('gd') || !function_exists('imagewebp')) {
            $path = $file->store($folder, 'public');
            return ['file_path' => $path, 'thumbnail' => $path, 'file_type' => $ext, 'file_name' => $file->getClientOriginalName()];
        }

        $gd = $this->loadGdImage($file->getRealPath(), $ext);
        if (!$gd) {
            $path = $file->store($folder, 'public');
            return ['file_path' => $path, 'thumbnail' => $path, 'file_type' => $ext, 'file_name' => $file->getClientOriginalName()];
        }

        $stem = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $uid  = uniqid('', false);

        // Full-size WebP
        $fullRelPath = "{$folder}/{$stem}_{$uid}.webp";
        $this->ensureDir(storage_path("app/public/{$folder}"));
        imagewebp($gd, storage_path("app/public/{$fullRelPath}"), self::QUALITY);

        // Thumbnail WebP (480px wide)
        $origW = imagesx($gd);
        $origH = imagesy($gd);

        if ($origW > self::THUMB_WIDTH) {
            $tH    = (int) round($origH * self::THUMB_WIDTH / $origW);
            $thumb = imagecreatetruecolor(self::THUMB_WIDTH, $tH);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            imagecopyresampled($thumb, $gd, 0, 0, 0, 0, self::THUMB_WIDTH, $tH, $origW, $origH);

            $thumbRelPath = "{$folder}/thumbs/{$stem}_{$uid}_thumb.webp";
            $this->ensureDir(storage_path("app/public/{$folder}/thumbs"));
            imagewebp($thumb, storage_path("app/public/{$thumbRelPath}"), self::QUALITY);
            imagedestroy($thumb);
        } else {
            $thumbRelPath = $fullRelPath;
        }

        imagedestroy($gd);

        return [
            'file_path' => $fullRelPath,
            'thumbnail' => $thumbRelPath,
            'file_type' => 'webp',
            'file_name' => "{$stem}_{$uid}.webp",
        ];
    }

    private function loadGdImage(string $path, string $ext)
    {
        return match($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png'         => @imagecreatefrompng($path),
            'gif'         => @imagecreatefromgif($path),
            'webp'        => @imagecreatefromwebp($path),
            default       => false,
        };
    }

    private function ensureDir(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
