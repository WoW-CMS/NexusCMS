<?php

// Core providers
$providers = [
    App\Providers\AppServiceProvider::class,
    App\Providers\ArmoryServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    App\Providers\ModuleServiceProvider::class,
];

return $providers;
