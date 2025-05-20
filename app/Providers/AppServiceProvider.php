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

            $routeFile = $modulesPath . '/' . $module . '/routes.php';

            if (is_file($routeFile)) {
                require $routeFile;
            }
        }
        
        // Diğer route loader vs varsa silme
        $modulesPath = app_path('Modules');
    
        // View namespace tanımı
        foreach (scandir($modulesPath) as $module) {
            $viewPath = $modulesPath . '/' . $module . '/Views';
            if (is_dir($viewPath)) {
                View::addNamespace(strtolower($module), $viewPath);
            }
    
            $routeFile = $modulesPath . '/' . $module . '/routes.php';
            if (is_file($routeFile)) {
                require $routeFile;
            }
        }
    }
    
    
}
