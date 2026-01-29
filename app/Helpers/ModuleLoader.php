<?php

namespace App\Helpers;

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
