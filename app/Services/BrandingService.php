<?php

namespace App\Services;

use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BrandingService
{
    /**
     * Cache duration for branding configurations (in minutes)
     */
    const CACHE_DURATION = 60;

    /**
     * Get all branding configurations
     */
    public function getBrandingConfig(): array
    {
        return Cache::remember('branding_config', self::CACHE_DURATION, function () {
            $config = SystemConfiguration::where('group', 'branding')->get();
            
            return [
                'logo_header' => $this->getLogoUrl($config->where('key', 'brand_logo_header')->first()?->value),
                'logo_footer' => $this->getLogoUrl($config->where('key', 'brand_logo_footer')->first()?->value),
                'logo_icon' => $this->getLogoUrl($config->where('key', 'brand_logo_icon')->first()?->value),
                'favicon' => $this->getLogoUrl($config->where('key', 'brand_favicon')->first()?->value) ?: asset('favicon.ico'),
                'primary_color' => $config->where('key', 'brand_primary_color')->first()?->value ?? '#1e40af',
                'secondary_color' => $config->where('key', 'brand_secondary_color')->first()?->value ?? '#059669',
                'accent_color' => $config->where('key', 'brand_accent_color')->first()?->value ?? '#dc2626',
                'gradient_start' => $config->where('key', 'brand_gradient_start')->first()?->value ?? '#1e40af',
                'gradient_end' => $config->where('key', 'brand_gradient_end')->first()?->value ?? '#3730a3',
                'theme_mode' => $config->where('key', 'brand_theme_mode')->first()?->value ?? 'light',
            ];
        });
    }

    /**
     * Get social media configurations
     */
    public function getSocialConfig(): array
    {
        return Cache::remember('social_config', self::CACHE_DURATION, function () {
            $config = SystemConfiguration::where('group', 'social')->get();
            
            return [
                'facebook' => $config->where('key', 'social_facebook_url')->first()?->value,
                'twitter' => $config->where('key', 'social_twitter_url')->first()?->value,
                'instagram' => $config->where('key', 'social_instagram_url')->first()?->value,
                'youtube' => $config->where('key', 'social_youtube_url')->first()?->value,
                'linkedin' => $config->where('key', 'social_linkedin_url')->first()?->value,
                'tiktok' => $config->where('key', 'social_tiktok_url')->first()?->value,
                'whatsapp' => $config->where('key', 'social_whatsapp_number')->first()?->value,
                'telegram' => $config->where('key', 'social_telegram_url')->first()?->value,
                'share_enabled' => $config->where('key', 'social_share_enabled')->first()?->value ?? true,
            ];
        });
    }

    /**
     * Generate CSS variables for branding
     */
    public function generateCssVariables(): string
    {
        $branding = $this->getBrandingConfig();
        
        return "
        :root {
            --brand-primary: {$branding['primary_color']};
            --brand-secondary: {$branding['secondary_color']};
            --brand-accent: {$branding['accent_color']};
            --brand-gradient-start: {$branding['gradient_start']};
            --brand-gradient-end: {$branding['gradient_end']};
            --brand-gradient: linear-gradient(135deg, {$branding['gradient_start']} 0%, {$branding['gradient_end']} 100%);
        }
        ";
    }

    /**
     * Update branding configuration
     */
    public function updateBrandingConfig(array $data): bool
    {
        try {
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'brand_')) {
                    SystemConfiguration::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => $value,
                            'type' => $this->getConfigType($key),
                            'group' => 'branding',
                            'updated_by' => auth()->id()
                        ]
                    );
                }
            }

            // Clear cache
            Cache::forget('branding_config');
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update social media configuration
     */
    public function updateSocialConfig(array $data): bool
    {
        try {
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'social_')) {
                    SystemConfiguration::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => $value,
                            'type' => $this->getConfigType($key),
                            'group' => 'social',
                            'updated_by' => auth()->id()
                        ]
                    );
                }
            }

            // Clear cache
            Cache::forget('social_config');
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Upload and process branding image
     */
    public function uploadBrandingImage($file, string $type): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $allowedTypes = ['brand_logo_header', 'brand_logo_footer', 'brand_logo_icon', 'brand_favicon'];
        
        if (!in_array($type, $allowedTypes)) {
            return null;
        }

        // Validate file type
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg', 'gif', 'ico'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            return null;
        }

        // Generate filename
        $filename = $type . '_' . time() . '.' . $extension;
        
        // Ensure branding directory exists
        Storage::disk('public')->makeDirectory('branding');
        
        // Store in public storage
        $path = $file->storeAs('branding', $filename, 'public');
        
        if ($path) {
            // Delete old file if exists
            $oldConfig = SystemConfiguration::where('key', $type)->first();
            if ($oldConfig && $oldConfig->value && Storage::disk('public')->exists($oldConfig->value)) {
                Storage::disk('public')->delete($oldConfig->value);
            }

            // Update configuration
            SystemConfiguration::updateOrCreate(
                ['key' => $type],
                [
                    'value' => $path,
                    'type' => 'image',
                    'group' => 'branding',
                    'updated_by' => auth()->id()
                ]
            );

            // Clear cache
            Cache::forget('branding_config');
            
            return $path;
        }

        return null;
    }

    /**
     * Get logo URL
     */
    private function getLogoUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Handle legacy favicon reference
        if ($path === 'favicon.png' || $path === 'favicon.ico') {
            return asset($path);
        }

        // If it's already a relative path with storage structure, use Storage::url
        if (str_contains($path, '/')) {
            return Storage::url($path);
        }

        // Otherwise, assume it's in branding directory
        return Storage::url('branding/' . $path);
    }

    /**
     * Get configuration type based on key
     */
    private function getConfigType(string $key): string
    {
        $imageTypes = ['brand_logo_header', 'brand_logo_footer', 'brand_logo_icon', 'brand_favicon'];
        $urlTypes = ['social_facebook_url', 'social_twitter_url', 'social_instagram_url', 'social_youtube_url', 'social_linkedin_url', 'social_tiktok_url', 'social_telegram_url'];
        $booleanTypes = ['social_share_enabled'];

        if (in_array($key, $imageTypes)) {
            return 'image';
        }

        if (in_array($key, $urlTypes)) {
            return 'url';
        }

        if (in_array($key, $booleanTypes)) {
            return 'boolean';
        }

        return 'string';
    }

    /**
     * Clear all branding cache
     */
    public function clearCache(): void
    {
        Cache::forget('branding_config');
        Cache::forget('social_config');
    }

    /**
     * Get branding preview data
     */
    public function getPreviewData(): array
    {
        $branding = $this->getBrandingConfig();
        $social = $this->getSocialConfig();

        return [
            'branding' => $branding,
            'social' => $social,
            'css_variables' => $this->generateCssVariables(),
        ];
    }
}
