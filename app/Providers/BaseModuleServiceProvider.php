<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = '';
    protected string $modulePath = '';
    protected array $config = [];

    public function boot(): void
    {
        if (empty($this->moduleName)) {
            throw new \RuntimeException('Module name not defined in ' . static::class);
        }

        $this->modulePath = base_path("app/Modules/{$this->moduleName}");
        $this->loadModuleConfig();

        if (!($this->config['enabled'] ?? true)) {
            return;
        }

        if ($this->config['routes'] ?? false) {
            $this->loadRoutes();
        }

        if ($this->config['migrations'] ?? false) {
            $this->loadMigrations();
        }

        if ($this->config['views'] ?? false) {
            $this->loadViews();
        }

        if ($this->config['translations'] ?? false) {
            $this->loadTranslations();
        }
    }

    private function loadModuleConfig(): void
    {
        $configFile = $this->modulePath . '/module.json';
        if (File::exists($configFile)) {
            $this->config = json_decode(File::get($configFile), true) ?? [];
        }
    }

    private function loadRoutes(): void
    {
        $routesPath = $this->modulePath . '/Http/routes.php';
        if (File::exists($routesPath)) {
            Route::middleware('web')
                ->namespace(($this->config['namespace'] ?? "Modules\\{$this->moduleName}") . '\\Http\\Controllers')
                ->group($routesPath);
        }
    }

    private function loadMigrations(): void
    {
        $path = $this->modulePath . '/Infrastructure/Database/migrations';
        if (is_dir($path)) {
            $this->loadMigrationsFrom($path);
        }
    }

    private function loadViews(): void
    {
        $path = $this->modulePath . '/Resources/views';
        if (is_dir($path)) {
            $this->loadViewsFrom($path, strtolower($this->moduleName));
        }
    }

    private function loadTranslations(): void
    {
        $path = $this->modulePath . '/Resources/lang';
        if (is_dir($path)) {
            $this->loadJsonTranslationsFrom($path, strtolower($this->moduleName));
        }
    }
}
