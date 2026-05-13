<?php

use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Cache;

if (!function_exists('get_config')) {
    /**
     * Retrieve a system configuration value, cached permanently until explicitly invalidated.
     * Cache is invalidated whenever the value is updated via SystemConfigurationController.
     */
    function get_config(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever('sys_config_' . $key, function () use ($key, $default) {
            $record = SystemConfiguration::where('key', $key)->first();
            return $record ? $record->getConvertedValue() : $default;
        });
    }
}
