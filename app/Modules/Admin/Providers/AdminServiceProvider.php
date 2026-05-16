<?php

namespace Modules\Admin\Providers;

use App\Providers\BaseModuleServiceProvider;
use App\Services\ModuleRegistryService;
use Illuminate\Support\Facades\View;

class AdminServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Admin';

    public function boot(): void
    {
        parent::boot();

        View::composer('admin::layouts.app', function (\Illuminate\View\View $view) {
            $view->with('adminMenuItems', ModuleRegistryService::getAdminMenuItems());
        });
    }
}
