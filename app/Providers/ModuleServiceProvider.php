<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ModuleRegistryService;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach (ModuleRegistryService::getEnabledProviders() as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot(): void
    {
        if (!file_exists(storage_path('installed.lock'))) {
            $this->app->setLocale(config('app.locale', 'en'));

            return;
        }

        $locale = settings('default_locale') ?? 'es';
        $this->app->setLocale($locale);
    }
}
