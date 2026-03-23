<?php

namespace App\Providers;

use App\Libraries\Redis\RedisLibrary;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Modules\Armory\Services\ArmoryService;
use Modules\Armory\Services\WowheadParserService;
use Modules\Armory\Domain\Interfaces\ArmoryRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register ArmoryService
        $this->app->singleton(ArmoryService::class, function ($app) {
            return new ArmoryService(
                $app->make(ArmoryRepositoryInterface::class),
                $app->make(WowheadParserService::class)
            );
        });

        // Register RedisLibrary (solo prefix)
        $this->app->singleton(RedisLibrary::class, function ($app) {
            $prefix = config('cache.prefix', '');
            return new RedisLibrary($prefix);
        });

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

    public function boot(): void
    {
        if (!file_exists(storage_path('installed.lock'))) {
            Config::set('session.driver', 'file');
            Config::set('cache.default', 'file');
            Config::set('queue.default', 'sync');
        }
    }
}
