<?php

// Core providers
$providers = [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    App\Providers\ModuleServiceProvider::class,
];

return $providers;
