<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\ModuleRegistryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ModuleManagerController extends Controller
{
    protected string $modulesPath;

    public function __construct()
    {
        $this->modulesPath = base_path('app/Modules');
    }

    public function index()
    {
        $discoveredModules = $this->discoverModules();
        ModuleRegistryService::syncDiscoveredModules($discoveredModules);

        $modules = collect($discoveredModules)
            ->sortBy('name')
            ->values();

        return view('admin::modules.index', compact('modules'));
    }

    public function edit(string $module)
    {
        $moduleData = $this->getModuleDataOrFail($module);

        return view('admin::modules.edit', ['module' => $moduleData]);
    }

    public function update(Request $request, string $module)
    {
        $moduleData = $this->getModuleDataOrFail($module);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'namespace' => ['required', 'string', 'max:255'],
            'module_type' => ['required', 'string', 'in:core,third_party'],
            'enabled' => ['nullable', 'boolean'],
            'routes' => ['nullable', 'boolean'],
            'migrations' => ['nullable', 'boolean'],
            'views' => ['nullable', 'boolean'],
            'translations' => ['nullable', 'boolean'],
        ]);

        $config = $moduleData['config'];
        $config['name'] = $validated['name'];
        $config['namespace'] = $validated['namespace'];
        $config['enabled'] = $request->boolean('enabled');
        $config['module_type'] = $validated['module_type'];
        $config['routes'] = $request->boolean('routes');
        $config['migrations'] = $request->boolean('migrations');
        $config['views'] = $request->boolean('views');
        $config['translations'] = $request->boolean('translations');

        ModuleRegistryService::upsertState(
            $moduleData['folder'],
            $request->boolean('enabled'),
            $validated['module_type']
        );

        $this->saveConfig($moduleData, $config);

        return redirect()
            ->route('admin.modules.edit', $moduleData['folder'])
            ->with('success', "Module '{$moduleData['folder']}' configuration updated successfully.");
    }

    public function toggle(string $module)
    {
        $moduleData = $this->getModuleDataOrFail($module);

        $config = $moduleData['config'];
        $isEnabled = (bool) $moduleData['enabled'];
        $config['enabled'] = !$isEnabled;

        ModuleRegistryService::upsertState(
            $moduleData['folder'],
            !$isEnabled,
            (string) $moduleData['module_type']
        );

        $this->saveConfig($moduleData, $config);

        $status = $config['enabled'] ? 'enabled' : 'disabled';

        return redirect()
            ->route('admin.modules.index')
            ->with('success', "Module '{$moduleData['folder']}' {$status} successfully.");
    }

    public function migrate(string $module)
    {
        $moduleData = $this->getModuleDataOrFail($module);
        $migrationsPath = $moduleData['migrations_path'];

        if (!is_dir($migrationsPath)) {
            return redirect()
                ->route('admin.modules.index')
                ->with('warning', "Module '{$moduleData['folder']}' does not have migrations.");
        }

        Artisan::call('migrate', [
            '--path' => $migrationsPath,
            '--realpath' => true,
            '--force' => true,
        ]);

        $output = trim(Artisan::output());

        return redirect()
            ->route('admin.modules.index')
            ->with('success', "Migrations executed for '{$moduleData['folder']}'. " . ($output !== '' ? $output : 'Done.'));
    }

    public function destroy(Request $request, string $module)
    {
        $moduleData = $this->getModuleDataOrFail($module);

        if ($moduleData['folder'] === 'Admin') {
            return redirect()
                ->route('admin.modules.index')
                ->with('error', 'The Admin module cannot be deleted.');
        }

        $request->validate([
            'confirmation' => ['required', 'string'],
        ]);

        if ($request->input('confirmation') !== $moduleData['folder']) {
            return redirect()
                ->route('admin.modules.index')
                ->with('error', "Confirmation text must match '{$moduleData['folder']}'.");
        }

        File::deleteDirectory($moduleData['path']);
        ModuleRegistryService::deleteState($moduleData['folder']);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', "Module '{$moduleData['folder']}' deleted successfully.");
    }

    protected function discoverModules(): array
    {
        if (!is_dir($this->modulesPath)) {
            return [];
        }

        $modules = [];

        foreach (glob($this->modulesPath . '/*', GLOB_ONLYDIR) as $moduleDir) {
            $modules[] = $this->buildModuleData(basename($moduleDir));
        }

        return $modules;
    }

    protected function getModuleDataOrFail(string $module): array
    {
        if (!$this->isValidModuleName($module)) {
            abort(404);
        }

        $modulePath = $this->modulesPath . DIRECTORY_SEPARATOR . $module;

        if (!is_dir($modulePath)) {
            abort(404);
        }

        ModuleRegistryService::syncDiscoveredModules();

        return $this->buildModuleData($module);
    }

    protected function buildModuleData(string $module): array
    {
        $modulePath = $this->modulesPath . DIRECTORY_SEPARATOR . $module;
        $configPath = $modulePath . DIRECTORY_SEPARATOR . 'module.json';
        $migrationsPath = $modulePath . DIRECTORY_SEPARATOR . 'Infrastructure/Database/migrations';

        $config = [];

        if (File::exists($configPath)) {
            $decoded = json_decode(File::get($configPath), true);
            if (is_array($decoded)) {
                $config = $decoded;
            }
        }

        $moduleState = ModuleRegistryService::getModuleState($module, [
            'enabled' => (bool) ($config['enabled'] ?? true),
            'module_type' => (string) ($config['module_type'] ?? 'core'),
        ]);

        return [
            'folder' => $module,
            'name' => $config['name'] ?? $module,
            'enabled' => (bool) ($moduleState['enabled'] ?? true),
            'module_type' => (string) ($moduleState['module_type'] ?? 'core'),
            'routes' => (bool) ($config['routes'] ?? false),
            'migrations' => (bool) ($config['migrations'] ?? false),
            'views' => (bool) ($config['views'] ?? false),
            'translations' => (bool) ($config['translations'] ?? false),
            'namespace' => $config['namespace'] ?? "Modules\\{$module}",
            'has_migrations' => is_dir($migrationsPath),
            'migrations_path' => $migrationsPath,
            'config_path' => $configPath,
            'config' => $config,
            'path' => $modulePath,
        ];
    }

    protected function saveConfig(array $moduleData, array $config): void
    {
        File::put(
            $moduleData['config_path'],
            json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
        );
    }

    protected function isValidModuleName(string $module): bool
    {
        return preg_match('/^[A-Za-z0-9_]+$/', $module) === 1;
    }
}
