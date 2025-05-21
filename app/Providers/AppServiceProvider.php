<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services here if needed
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

            $routeFile = $modulesPath . '/' . $module . '/Config/routes.php';
            $viewPath = $modulesPath . '/' . $module . '/Views';

            if (is_dir($viewPath)) {
                View::addNamespace(strtolower($module), $viewPath);
            }

            if (file_exists($routeFile)) {
                require $routeFile;
            } else {
                throw new InvalidArgumentException(
                    "Failed to resolve model class for the route file: {$routeFile}. " .
                    "Please check the file structure and namespace definitions."
                );
            }
        }
    }
}