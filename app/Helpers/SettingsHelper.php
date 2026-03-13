<?php

use App\Modules\Admin\Domain\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('formatBytes')) {
    function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $pow = min($pow, count($units) - 1);

        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('settings')) {
    /**
     * Get a setting value or all settings.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        // Cache settings for performance (forever until updated)
        $settings = Cache::rememberForever('site_settings', function () {
            try {
                // Check if table exists to avoid errors during migration/installation
                if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    return collect([]);
                }
                return Setting::all()->pluck('value', 'key');
            } catch (\Exception $e) {
                return collect([]);
            }
        });

        if (is_null($key)) {
            return $settings;
        }

        return $settings->get($key, $default);
    }
}
