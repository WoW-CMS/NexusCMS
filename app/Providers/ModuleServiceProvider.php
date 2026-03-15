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
        $locale = settings('default_locale') ?? 'es';
        $this->app->setLocale($locale);
    }
}
