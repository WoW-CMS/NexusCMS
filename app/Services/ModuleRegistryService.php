<?php

namespace App\Services;

use App\Models\ManagedModule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ModuleRegistryService
{
    private static ?array $stateCache = null;
    private static ?array $manifestCache = null;

    public static function getEnabledProviders(): array
    {
        $providers = [];

        foreach (self::getEnabledModules() as $module) {
            $provider = trim((string) ($module['provider'] ?? ''));
            if ($provider !== '') {
                $providers[] = $provider;
            }
        }

        return array_values(array_unique($providers));
    }

    public static function getEnabledModules(): array
    {
        return array_values(array_filter(
            self::getAllModules(),
            static fn (array $module): bool => (bool) ($module['enabled'] ?? true)
        ));
    }

    public static function getAllModules(): array
    {
        return array_values(self::loadManifest());
    }

    public static function getModule(string $module): ?array
    {
        $module = trim($module);
        if ($module === '') {
            return null;
        }

        $manifest = self::loadManifest();

        return $manifest[$module] ?? null;
    }

    public static function getModuleState(string $module, ?array $fallbackConfig = null): array
    {
        $module = trim($module);
        $fallbackConfig = $fallbackConfig ?? [];

        $manifest = self::getModule($module);
        if (is_array($manifest)) {
            $fallbackConfig = array_merge($manifest, $fallbackConfig);
        }

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

        $discoveredModules = $discoveredModules ?? self::getAllModules();

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
        self::$manifestCache = null;
    }

    private static function loadManifest(): array
    {
        if (is_array(self::$manifestCache)) {
            return self::$manifestCache;
        }

        $modulesPath = base_path('app/Modules');

        if (!is_dir($modulesPath)) {
            self::$manifestCache = [];

            return self::$manifestCache;
        }

        $states = self::loadStates();
        $manifest = [];

        foreach (glob($modulesPath . '/*', GLOB_ONLYDIR) as $moduleDir) {
            $moduleName = basename($moduleDir);
            $config = self::readModuleConfig($moduleDir);
            $state = $states[$moduleName] ?? [];
            $namespace = (string) ($config['namespace'] ?? "Modules\\{$moduleName}");

            $manifest[$moduleName] = [
                'folder' => $moduleName,
                'name' => (string) ($config['name'] ?? $moduleName),
                'path' => $moduleDir,
                'config_path' => $moduleDir . DIRECTORY_SEPARATOR . 'module.json',
                'routes_path' => $moduleDir . DIRECTORY_SEPARATOR . 'Http/routes.php',
                'migrations_path' => $moduleDir . DIRECTORY_SEPARATOR . 'Infrastructure/Database/migrations',
                'views_path' => $moduleDir . DIRECTORY_SEPARATOR . 'Resources/views',
                'translations_path' => $moduleDir . DIRECTORY_SEPARATOR . 'Resources/lang',
                'namespace' => $namespace,
                'provider' => self::resolveProviderClass($moduleName, $moduleDir, $namespace, $config),
                'enabled' => (bool) ($state['enabled'] ?? $config['enabled'] ?? true),
                'module_type' => self::normalizeType((string) ($state['module_type'] ?? $config['module_type'] ?? 'core')),
                'routes' => (bool) ($config['routes'] ?? false),
                'migrations' => (bool) ($config['migrations'] ?? false),
                'views' => (bool) ($config['views'] ?? false),
                'translations' => (bool) ($config['translations'] ?? false),
                'config' => $config,
            ];
        }

        self::$manifestCache = $manifest;

        return self::$manifestCache;
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
        return self::getAllModules();
    }

    private static function readModuleConfig(string $moduleDir): array
    {
        $configPath = $moduleDir . DIRECTORY_SEPARATOR . 'module.json';

        if (!File::exists($configPath)) {
            return [];
        }

        $decoded = json_decode(File::get($configPath), true);

        return is_array($decoded) ? $decoded : [];
    }

    private static function resolveProviderClass(string $moduleName, string $moduleDir, string $namespace, array $config): ?string
    {
        $configuredProvider = trim((string) ($config['provider'] ?? ''));
        if ($configuredProvider !== '' && class_exists($configuredProvider)) {
            return $configuredProvider;
        }

        $conventionalProvider = "{$namespace}\\Providers\\{$moduleName}ServiceProvider";
        if (class_exists($conventionalProvider)) {
            return $conventionalProvider;
        }

        $providersDir = $moduleDir . DIRECTORY_SEPARATOR . 'Providers';
        if (!is_dir($providersDir)) {
            return null;
        }

        foreach (glob($providersDir . DIRECTORY_SEPARATOR . '*ServiceProvider.php') as $providerFile) {
            $className = pathinfo($providerFile, PATHINFO_FILENAME);
            $fullClass = "{$namespace}\\Providers\\{$className}";

            if (class_exists($fullClass)) {
                return $fullClass;
            }
        }

        return null;
    }

    private static function normalizeType(string $moduleType): string
    {
        return in_array($moduleType, ['core', 'third_party'], true) ? $moduleType : 'core';
    }
}
