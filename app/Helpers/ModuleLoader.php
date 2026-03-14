<?php

namespace App\Helpers;

use App\Services\ModuleRegistryService;
use Illuminate\Support\Facades\File;

class ModuleLoader
{
    public static function getProviders(): array
    {
        $providers = [];
        $modulesPath = base_path('app/Modules');

        if (!is_dir($modulesPath)) {
            return $providers;
        }

        foreach (glob("$modulesPath/*", GLOB_ONLYDIR) as $moduleDir) {
            $moduleName = basename($moduleDir);
            $configPath = $moduleDir . DIRECTORY_SEPARATOR . 'module.json';
            $config = [];

            if (File::exists($configPath)) {
                $decoded = json_decode(File::get($configPath), true);
                if (is_array($decoded)) {
                    $config = $decoded;
                }
            }

            if (!ModuleRegistryService::isModuleEnabled($moduleName, (bool) ($config['enabled'] ?? true))) {
                continue;
            }

            $namespace = "Modules\\{$moduleName}\\Providers";
            $expectedFile = "{$moduleDir}/Providers/{$moduleName}ServiceProvider.php";
            $expectedClass = "{$namespace}\\{$moduleName}ServiceProvider";

            if (file_exists($expectedFile)) {
                require_once $expectedFile;
                if (class_exists($expectedClass)) {
                    $providers[] = $expectedClass;
                    continue;
                }
            }

            $providersDir = "{$moduleDir}/Providers";
            if (is_dir($providersDir)) {
                foreach (glob("{$providersDir}/*ServiceProvider.php") as $providerFile) {
                    require_once $providerFile;
                    $className = pathinfo($providerFile, PATHINFO_FILENAME);
                    $fullClass = "{$namespace}\\{$className}";
                    if (class_exists($fullClass)) {
                        $providers[] = $fullClass;
                    }
                }
            }
        }

        return $providers;
    }
}
