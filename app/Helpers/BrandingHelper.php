<?php

if (!function_exists('branding_config')) {
    /**
     * Get branding configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function branding_config(string $key, $default = null)
    {
        static $brandingService = null;
        
        if ($brandingService === null) {
            $brandingService = app(\App\Services\BrandingService::class);
        }
        
        $config = $brandingService->getBrandingConfig();
        
        return $config[$key] ?? $default;
    }
}

if (!function_exists('social_config')) {
    /**
     * Get social media configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function social_config(string $key, $default = null)
    {
        static $brandingService = null;
        
        if ($brandingService === null) {
            $brandingService = app(\App\Services\BrandingService::class);
        }
        
        $config = $brandingService->getSocialConfig();
        
        return $config[$key] ?? $default;
    }
}

if (!function_exists('brand_colors')) {
    /**
     * Get brand colors as CSS variables
     *
     * @return string
     */
    function brand_colors(): string
    {
        static $brandingService = null;
        
        if ($brandingService === null) {
            $brandingService = app(\App\Services\BrandingService::class);
        }
        
        return $brandingService->generateCssVariables();
    }
}

if (!function_exists('site_logo')) {
    /**
     * Get site logo URL
     *
     * @param string $variant (header, footer, icon)
     * @param string $default
     * @return string
     */
    function site_logo(string $variant = 'header', string $default = '/logo.svg'): string
    {
        $logoKey = 'logo_' . $variant;
        $logo = branding_config($logoKey);
        
        return $logo ?: asset($default);
    }
}

if (!function_exists('social_links')) {
    /**
     * Get enabled social media links
     *
     * @return array
     */
    function social_links(): array
    {
        static $brandingService = null;
        
        if ($brandingService === null) {
            $brandingService = app(\App\Services\BrandingService::class);
        }
        
        $social = $brandingService->getSocialConfig();
        $links = [];
        
        $platforms = [
            'facebook' => ['icon' => 'fab fa-facebook', 'name' => 'Facebook'],
            'twitter' => ['icon' => 'fab fa-twitter', 'name' => 'Twitter'],
            'instagram' => ['icon' => 'fab fa-instagram', 'name' => 'Instagram'],
            'youtube' => ['icon' => 'fab fa-youtube', 'name' => 'YouTube'],
            'linkedin' => ['icon' => 'fab fa-linkedin', 'name' => 'LinkedIn'],
            'tiktok' => ['icon' => 'fab fa-tiktok', 'name' => 'TikTok'],
            'telegram' => ['icon' => 'fab fa-telegram', 'name' => 'Telegram'],
        ];
        
        foreach ($platforms as $platform => $details) {
            if (!empty($social[$platform])) {
                $links[$platform] = [
                    'url' => $social[$platform],
                    'icon' => $details['icon'],
                    'name' => $details['name']
                ];
            }
        }
        
        // Special handling for WhatsApp
        if (!empty($social['whatsapp'])) {
            $links['whatsapp'] = [
                'url' => 'https://wa.me/' . $social['whatsapp'],
                'icon' => 'fab fa-whatsapp',
                'name' => 'WhatsApp'
            ];
        }
        
        return $links;
    }
}

if (!function_exists('brand_css_vars')) {
    /**
     * Generate inline CSS with brand variables
     *
     * @return string
     */
    function brand_css_vars(): string
    {
        $primary = branding_config('primary_color', '#1e40af');
        $secondary = branding_config('secondary_color', '#059669');
        $accent = branding_config('accent_color', '#dc2626');
        $gradientStart = branding_config('gradient_start', '#1e40af');
        $gradientEnd = branding_config('gradient_end', '#3730a3');
        
        return "
        <style>
            :root {
                --brand-primary: {$primary};
                --brand-secondary: {$secondary};
                --brand-accent: {$accent};
                --brand-gradient: linear-gradient(135deg, {$gradientStart} 0%, {$gradientEnd} 100%);
            }
        </style>
        ";
    }
}
