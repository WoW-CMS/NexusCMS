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

        /**
         * Check if the module is enabled.
         * If not, skip loading any resources.
         */
        if (!($this->config['enabled'] ?? true)) {
            return;
        }

        /**
         * Load module routes if enabled.
         */
        if ($this->config['routes'] ?? false) {
            $this->loadRoutes();
        }

        /**
         * Load module migrations if enabled.
         */
        if ($this->config['migrations'] ?? false) {
            $this->loadMigrations();
        }

        /**
         * Load module views if enabled.
         */
        if ($this->config['views'] ?? false) {
            $this->loadViews();
        }

        /**
         * Load module translations if enabled.
         */
        if ($this->config['translations'] ?? false) {
            $this->loadTranslations();
        }
    }

    /**
     * Load module configuration from the module.json file.
     * 
     * @return void
     */
    private function loadModuleConfig(): void
    {
        $configFile = $this->modulePath . '/module.json';
        if (File::exists($configFile)) {
            $this->config = json_decode(File::get($configFile), true) ?? [];
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
        if (File::exists($routesPath)) {
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
