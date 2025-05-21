<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $modulesPath = app_path('Modules');

        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $routeFile = $modulesPath . '/' . $module . '/config/routes.php';

            $viewPath = $modulesPath . '/' . $module . '/Views';
            
            if (is_dir($viewPath)) {
                
                View::addNamespace(strtolower($module), $viewPath);
            }

            if (is_file($routeFile)) {
                require $routeFile;
            }
        }
    }
    
    
}
