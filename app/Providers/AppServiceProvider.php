<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (app()->environment('production')) {
            $forbidden = [
                'laravel/telescope',
                'laravel/pulse',
                'barryvdh/laravel-debugbar',
            ];

        foreach ($forbidden as $package) {
            if (class_exists(str_replace('/', '\\', $package))) {
                abort(503, "Package {$package} is not allowed in production.");
            }
        }
    }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!file_exists(storage_path('installed.lock'))) {
            // Durante la instalación forzamos sesiones en archivos
            Config::set('session.driver', 'file');
            Config::set('cache.default', 'file');
            Config::set('queue.default', 'sync');
        }
    }
}
