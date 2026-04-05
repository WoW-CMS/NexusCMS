<?php

use Modules\Admin\Domain\Models\Setting;
use App\Services\ModuleRegistryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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

if (!function_exists('menu_defaults')) {
    function menu_defaults(): array
    {
        return [
            'web' => [
                ['label' => 'HOME', 'route' => 'home', 'url' => '', 'icon' => '', 'module' => '', 'auth' => 'any', 'permission' => '', 'enabled' => true],
                ['label' => 'NEWS', 'route' => 'news', 'url' => '', 'icon' => '', 'module' => '', 'auth' => 'any', 'permission' => '', 'enabled' => true],
                ['label' => 'HOW TO PLAY', 'route' => 'howtoplay', 'url' => '', 'icon' => '', 'module' => '', 'auth' => 'any', 'permission' => '', 'enabled' => true],
                ['label' => 'ARMORY', 'route' => 'armory', 'url' => '', 'icon' => '', 'module' => 'Armory', 'auth' => 'any', 'permission' => '', 'enabled' => true],
                ['label' => 'DONATE', 'route' => 'donate', 'url' => '', 'icon' => '', 'module' => 'Donate', 'auth' => 'any', 'permission' => '', 'enabled' => true],
            ],
            'ucp' => [
                ['label' => 'Dashboard', 'route' => 'ucp.dashboard', 'url' => '', 'icon' => 'fas fa-home w-5', 'module' => '', 'auth' => 'auth', 'permission' => '', 'enabled' => true],
                ['label' => 'Game Account', 'route' => 'ucp.gameaccount', 'url' => '', 'icon' => 'fas fa-gamepad w-5', 'module' => '', 'auth' => 'auth', 'permission' => '', 'enabled' => true],
                ['label' => 'Donations', 'route' => 'ucp.transaction', 'url' => '', 'icon' => 'fas fa-hand-holding-usd w-5', 'module' => 'Donate', 'auth' => 'auth', 'permission' => '', 'enabled' => true],
            ],
        ];
    }
}

if (!function_exists('menu_module_states')) {
    function menu_module_states(): array
    {
        static $states = null;

        if (is_array($states)) {
            return $states;
        }

        $states = [];

        foreach (ModuleRegistryService::getAllModules() as $module) {
            $moduleName = trim((string) ($module['folder'] ?? ''));
            if ($moduleName === '') {
                continue;
            }

            $states[$moduleName] = (bool) ($module['enabled'] ?? true);
        }

        return $states;
    }
}

if (!function_exists('menu_module_enabled')) {
    function menu_module_enabled(string $module): bool
    {
        $module = trim($module);
        if ($module === '') {
            return true;
        }

        $states = menu_module_states();

        return array_key_exists($module, $states) && $states[$module] === true;
    }
}

if (!function_exists('menu_config')) {
    function menu_config(string $menu): array
    {
        $defaults = menu_defaults();
        $fallback = $defaults[$menu] ?? [];
        $value = settings("menu.{$menu}");

        if (!is_string($value) || trim($value) === '') {
            return $fallback;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : $fallback;
    }
}

if (!function_exists('menu_items')) {
    function menu_items(string $menu): array
    {
        $items = menu_config($menu);
        $user = auth()->user();

        return array_values(array_filter($items, function ($item) use ($user) {
            if (!is_array($item)) {
                return false;
            }

            if (!(bool) ($item['enabled'] ?? true)) {
                return false;
            }

            $authRule = $item['auth'] ?? 'any';
            if ($authRule === 'auth' && !$user) {
                return false;
            }

            if ($authRule === 'guest' && $user) {
                return false;
            }

            $permission = trim((string) ($item['permission'] ?? ''));
            if ($permission !== '' && (!$user || !$user->can($permission))) {
                return false;
            }

            $module = trim((string) ($item['module'] ?? ''));
            if (!menu_module_enabled($module)) {
                return false;
            }

            $routeName = trim((string) ($item['route'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));

            if ($routeName !== '') {
                return Route::has($routeName);
            }

            return $url !== '';
        }));
    }
}

if (!function_exists('menu_item_href')) {
    function menu_item_href(array $item): string
    {
        $routeName = trim((string) ($item['route'] ?? ''));
        if ($routeName !== '' && Route::has($routeName)) {
            return route($routeName);
        }

        $url = trim((string) ($item['url'] ?? ''));
        return $url !== '' ? $url : '#';
    }
}
