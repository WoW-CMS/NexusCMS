<?php

use App\Modules\Admin\Domain\Models\Setting;
use Illuminate\Support\Facades\Cache;

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
