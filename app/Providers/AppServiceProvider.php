<?php

namespace App\Providers;

use App\Libraries\Redis\RedisLibrary;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        $isInstalled = file_exists(storage_path('installer.lock')) || file_exists(storage_path('installed.lock'));

        if (!$isInstalled) {
            Config::set('session.driver', 'file');
            Config::set('cache.default', 'file');
            Config::set('queue.default', 'sync');
        }

        $this->configureRateLimiters();
    }

    /**
     * Register named rate limiters used by the donate module and elsewhere.
     *
     * - donate-checkout: per authenticated user, 5 attempts/min. Hard cap
     *   against scripted donation abuse; reasonable for legit double-clicks.
     * - donate-webhook: per IP, 30/min. Legit gateways can retry a handful
     *   of times; this stops flood / replay attempts.
     * - donate-receipt: per authenticated user, 20/min. Generous because
     *   the user may open the receipt several times (email link, browser
     *   back, etc.).
     */
    protected function configureRateLimiters(): void
    {
        RateLimiter::for('donate-checkout', function (Request $request) {
            return Limit::perMinute(5)
                ->by('donate-checkout:' . optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('donate-webhook', function (Request $request) {
            return Limit::perMinute(30)
                ->by('donate-webhook:' . $request->ip());
        });

        RateLimiter::for('donate-receipt', function (Request $request) {
            return Limit::perMinute(20)
                ->by('donate-receipt:' . optional($request->user())->id ?: $request->ip());
        });
    }
}
