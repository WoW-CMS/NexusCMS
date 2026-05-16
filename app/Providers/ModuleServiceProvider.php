<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
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
        $this->registerJsonCrudRoutes();

        $isInstalled = file_exists(storage_path('installer.lock')) || file_exists(storage_path('installed.lock'));

        if (!$isInstalled) {
            $this->app->setLocale(config('app.locale', 'en'));

            return;
        }

        $locale = settings('default_locale') ?? 'es';
        $this->app->setLocale($locale);
    }

    private function registerJsonCrudRoutes(): void
    {
        foreach (ModuleRegistryService::getEnabledModules() as $module) {
            if (($module['admin_crud'] ?? '') !== 'json') {
                continue;
            }

            $folder = (string) ($module['folder'] ?? '');
            if ($folder === '') {
                continue;
            }

            $slug      = strtolower($folder);
            $routeBase = 'admin.' . $slug . '.crud.';

            Route::middleware(['web', 'auth', 'permission:access.admin.panel'])
                ->prefix('acp/' . $slug . '/manage')
                ->name($routeBase)
                ->group(function () use ($folder) {
                    Route::get('/', [\Modules\Admin\Http\Controllers\GenericCrudController::class, 'index'])
                        ->name('index')
                        ->defaults('module', $folder);
                    Route::get('/create', [\Modules\Admin\Http\Controllers\GenericCrudController::class, 'create'])
                        ->name('create')
                        ->defaults('module', $folder);
                    Route::post('/', [\Modules\Admin\Http\Controllers\GenericCrudController::class, 'store'])
                        ->name('store')
                        ->defaults('module', $folder);
                    Route::get('/{id}/edit', [\Modules\Admin\Http\Controllers\GenericCrudController::class, 'edit'])
                        ->name('edit')
                        ->defaults('module', $folder);
                    Route::put('/{id}', [\Modules\Admin\Http\Controllers\GenericCrudController::class, 'update'])
                        ->name('update')
                        ->defaults('module', $folder);
                    Route::delete('/{id}', [\Modules\Admin\Http\Controllers\GenericCrudController::class, 'destroy'])
                        ->name('destroy')
                        ->defaults('module', $folder);
                });
        }
    }
}

