<?php

namespace App\Helpers;

use App\Services\ModuleRegistryService;

class ModuleLoader
{
    public static function getProviders(): array
    {
        return ModuleRegistryService::getEnabledProviders();
    }
}
