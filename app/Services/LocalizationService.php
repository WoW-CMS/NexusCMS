<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Admin\Domain\Models\Setting;

class LocalizationService
{
    private const CACHE_KEY = 'localization_settings';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Whether multilingual support is enabled.
     */
    public static function isMultilingualEnabled(): bool
    {
        return (bool) static::getSetting('multilingual_enabled', '0');
    }

    /**
     * Get the list of active locales.
     * Returns the default locale only when multilingual is disabled.
     *
     * @return string[]
     */
    public static function getActiveLocales(): array
    {
        if (!static::isMultilingualEnabled()) {
            return [static::getDefaultLocale()];
        }

        $raw = static::getSetting('available_locales', '["en"]');
        $decoded = json_decode($raw, true);

        return (is_array($decoded) && count($decoded) > 0)
            ? $decoded
            : [static::getDefaultLocale()];
    }

    /**
     * Get the configured default locale.
     */
    public static function getDefaultLocale(): string
    {
        return static::getSetting('default_locale', config('app.locale', 'en'));
    }

    /**
     * Get locale display names map, e.g. ['en' => 'English', 'es' => 'Español'].
     *
     * @return array<string, string>
     */
    public static function getLocaleNames(): array
    {
        return [
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'it' => 'Italiano',
            'pt' => 'Português',
            'ru' => 'Русский',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
            'ar' => 'العربية',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'tr' => 'Türkçe',
        ];
    }

    /**
     * Read a setting value from cache-backed DB.
     */
    private static function getSetting(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Clear the localization cache (called after settings update).
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
