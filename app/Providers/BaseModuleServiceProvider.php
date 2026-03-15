<?php

namespace App\Providers;

use App\Services\ModuleRegistryService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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

        $manifest = ModuleRegistryService::getModule($this->moduleName);

        if (is_array($manifest)) {
            $this->modulePath = (string) ($manifest['path'] ?? base_path("app/Modules/{$this->moduleName}"));
            $this->config = is_array($manifest['config'] ?? null) ? $manifest['config'] : [];
            $this->config['namespace'] = (string) ($manifest['namespace'] ?? ($this->config['namespace'] ?? "Modules\\{$this->moduleName}"));
            $this->config['routes'] = (bool) ($manifest['routes'] ?? ($this->config['routes'] ?? false));
            $this->config['migrations'] = (bool) ($manifest['migrations'] ?? ($this->config['migrations'] ?? false));
            $this->config['views'] = (bool) ($manifest['views'] ?? ($this->config['views'] ?? false));
            $this->config['translations'] = (bool) ($manifest['translations'] ?? ($this->config['translations'] ?? false));
        } else {
            $this->modulePath = base_path("app/Modules/{$this->moduleName}");
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

    /**
     * Load routes from the module's Http/routes.php file.
     * 
     * @return void
     */
    private function loadRoutes(): void
    {
        $routesPath = $this->modulePath . '/Http/routes.php';
        if (is_file($routesPath)) {
            Route::middleware('web')
                ->namespace(($this->config['namespace'] ?? "Modules\\{$this->moduleName}") . '\\Http\\Controllers')
                ->group($routesPath);
        }
    }

    /**
     * Load migrations from the module's Infrastructure/Database/migrations directory.
     * 
     * @return void
     */
    private function loadMigrations(): void
    {
        $path = $this->modulePath . '/Infrastructure/Database/migrations';
        if (is_dir($path)) {
            $this->loadMigrationsFrom($path);
        }
    }

    /**
     * Load views from the module's resources/views directory.
     * 
     * @return void
     */
    private function loadViews(): void
    {
        $path = $this->modulePath . '/Resources/views';
        if (is_dir($path)) {
            $this->loadViewsFrom($path, strtolower($this->moduleName));
        }
    }

    /**
     * Load translations from the module's lang directory.
     * 
     * @return void
     */
    private function loadTranslations(): void
    {
        $path = $this->modulePath . '/Resources/lang';

        if (is_dir($path)) {
            $this->loadTranslationsFrom(
                $path,
                strtolower($this->moduleName)
            );
        }
    }
}
