<?php

namespace App\Services;

use App\Models\ManagedModule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ModuleRegistryService
{
    private static ?array $stateCache = null;

    public static function getModuleState(string $module, ?array $fallbackConfig = null): array
    {
        $module = trim($module);
        $fallbackConfig = $fallbackConfig ?? [];

        $fallback = [
            'enabled' => (bool) ($fallbackConfig['enabled'] ?? true),
            'module_type' => self::normalizeType((string) ($fallbackConfig['module_type'] ?? $fallbackConfig['type'] ?? 'core')),
        ];

        if ($module === '') {
            return $fallback;
        }

        $states = self::loadStates();

        return $states[$module] ?? $fallback;
    }

    public static function isModuleEnabled(string $module, bool $fallbackEnabled = true): bool
    {
        $state = self::getModuleState($module, ['enabled' => $fallbackEnabled]);

        return (bool) ($state['enabled'] ?? true);
    }

    public static function upsertState(string $module, bool $enabled, string $moduleType): void
    {
        if (!self::tableExists()) {
            return;
        }

        $module = trim($module);
        if ($module === '') {
            return;
        }

        try {
            ManagedModule::query()->updateOrCreate(
                ['module_name' => $module],
                [
                    'enabled' => $enabled,
                    'module_type' => self::normalizeType($moduleType),
                ]
            );
        } catch (\Throwable $exception) {
            return;
        }

        self::flushCache();
    }

    public static function deleteState(string $module): void
    {
        if (!self::tableExists()) {
            return;
        }

        try {
            ManagedModule::query()
                ->where('module_name', trim($module))
                ->delete();
        } catch (\Throwable $exception) {
            return;
        }

        self::flushCache();
    }

    public static function syncDiscoveredModules(?array $discoveredModules = null): void
    {
        if (!self::tableExists()) {
            return;
        }

        $discoveredModules = $discoveredModules ?? self::discoverFromFilesystem();

        foreach ($discoveredModules as $module) {
            if (!is_array($module)) {
                continue;
            }

            $moduleName = trim((string) ($module['folder'] ?? $module['module_name'] ?? ''));
            if ($moduleName === '') {
                continue;
            }

            $enabled = (bool) ($module['enabled'] ?? true);
            $type = self::normalizeType((string) ($module['module_type'] ?? 'core'));

            try {
                ManagedModule::query()->firstOrCreate(
                    ['module_name' => $moduleName],
                    [
                        'enabled' => $enabled,
                        'module_type' => $type,
                    ]
                );
            } catch (\Throwable $exception) {
                continue;
            }
        }

        self::flushCache();
    }

    public static function flushCache(): void
    {
        self::$stateCache = null;
    }

    private static function loadStates(): array
    {
        if (is_array(self::$stateCache)) {
            return self::$stateCache;
        }

        if (!self::tableExists()) {
            self::$stateCache = [];

            return self::$stateCache;
        }

        try {
            self::$stateCache = ManagedModule::query()
                ->get(['module_name', 'enabled', 'module_type'])
                ->mapWithKeys(function (ManagedModule $module) {
                    return [
                        $module->module_name => [
                            'enabled' => (bool) $module->enabled,
                            'module_type' => self::normalizeType((string) $module->module_type),
                        ],
                    ];
                })
                ->all();
        } catch (\Throwable $exception) {
            self::$stateCache = [];
        }

        return self::$stateCache;
    }

    private static function tableExists(): bool
    {
        try {
            if (!self::databaseResolverAvailable()) {
                return false;
            }

            return Schema::hasTable('managed_modules');
        } catch (\Throwable $exception) {
            return false;
        }
    }

    private static function databaseResolverAvailable(): bool
    {
        try {
            return ManagedModule::getConnectionResolver() !== null;
        } catch (\Throwable $exception) {
            return false;
        }
    }

    private static function discoverFromFilesystem(): array
    {
        $modulesPath = base_path('app/Modules');

        if (!is_dir($modulesPath)) {
            return [];
        }

        $modules = [];

        foreach (glob($modulesPath . '/*', GLOB_ONLYDIR) as $moduleDir) {
            $moduleName = basename($moduleDir);
            $configPath = $moduleDir . DIRECTORY_SEPARATOR . 'module.json';
            $config = [];

            if (File::exists($configPath)) {
                $decoded = json_decode(File::get($configPath), true);
                if (is_array($decoded)) {
                    $config = $decoded;
                }
            }

            $modules[] = [
                'folder' => $moduleName,
                'enabled' => (bool) ($config['enabled'] ?? true),
                'module_type' => self::normalizeType((string) ($config['module_type'] ?? 'core')),
            ];
        }

        return $modules;
    }

    private static function normalizeType(string $moduleType): string
    {
        return in_array($moduleType, ['core', 'third_party'], true) ? $moduleType : 'core';
    }
}
