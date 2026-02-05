<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\ModuleLoader;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach (ModuleLoader::getProviders() as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot(): void
    {
        $locale = settings('default_locale') ?? 'es';
        $this->app->setLocale($locale);
    }
}
