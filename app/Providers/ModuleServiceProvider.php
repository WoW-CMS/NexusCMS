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
}
