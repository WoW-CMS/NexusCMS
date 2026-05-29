<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\ModuleRegistryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Module;

class ModuleManagerController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Module::class);

        $discoveredModules = $this->discoverModules();
        ModuleRegistryService::syncDiscoveredModules($discoveredModules);

        $modules = collect($discoveredModules)
            ->sortBy('name')
            ->values();

        return view('admin::modules.index', compact('modules'));
    }

    public function edit(string $module)
    {
        $this->authorize('update', Module::class);

        $moduleData = $this->getModuleDataOrFail($module);

        return view('admin::modules.edit', ['module' => $moduleData]);
    }

    public function update(Request $request, string $module)
    {
        $this->authorize('update', Module::class);

        $moduleData = $this->getModuleDataOrFail($module);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'module_type' => ['required', 'string', 'in:core,third_party'],
        ]);

        ModuleRegistryService::upsertState(
            $moduleData['folder'],
            (bool) $moduleData['enabled'],
            $validated['module_type']
        );

        $config = $moduleData['config'];
        $config['name'] = $validated['name'];
        $config['module_type'] = $validated['module_type'];

        $this->saveConfig($moduleData, $config);

        return redirect()
            ->route('admin.modules.edit', $moduleData['folder'])
            ->with('success', "Module '{$moduleData['folder']}' updated successfully.");
    }

    public function uninstall(string $module)
    {
        $this->authorize('uninstall', Module::class);

        $moduleData = $this->getModuleDataOrFail($module);

        if ($moduleData['folder'] === 'Admin') {
            return redirect()
                ->route('admin.modules.index')
                ->with('error', 'The Admin module cannot be uninstalled.');
        }

        // Delete the DB record so the module loses its overridden state.
        // syncDiscoveredModules() will recreate it with defaults from module.json,
        // so we immediately re-register it as disabled to give visible feedback.
        ModuleRegistryService::deleteState($moduleData['folder']);
        ModuleRegistryService::upsertState(
            $moduleData['folder'],
            false,
            (string) $moduleData['module_type']
        );

        return redirect()
            ->route('admin.modules.index')
            ->with('success', "Module '{$moduleData['folder']}' uninstalled — state reset and disabled. The code files were not removed.");
    }

    public function toggle(string $module)
    {
        $this->authorize('toggle', Module::class);

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
        $this->authorize('migrate', Module::class);

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
        $this->authorize('delete', Module::class);

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
        $modules = [];

        foreach (ModuleRegistryService::getAllModules() as $module) {
            $modules[] = $this->hydrateModuleData($module);
        }

        return $modules;
    }

    protected function getModuleDataOrFail(string $module): array
    {
        if (!$this->isValidModuleName($module)) {
            abort(404);
        }

        ModuleRegistryService::syncDiscoveredModules();

        $moduleData = ModuleRegistryService::getModule($module);
        if (!is_array($moduleData)) {
            abort(404);
        }

        return $this->hydrateModuleData($moduleData);
    }

    protected function hydrateModuleData(array $module): array
    {
        $folder = (string) ($module['folder'] ?? '');
        $name = (string) ($module['name'] ?? $folder);
        $config = is_array($module['config'] ?? null) ? $module['config'] : [];
        $migrationsPath = (string) ($module['migrations_path'] ?? '');

        return [
            'folder' => $folder,
            'name' => $name,
            'enabled' => (bool) ($module['enabled'] ?? true),
            'module_type' => (string) ($module['module_type'] ?? 'core'),
            'routes' => (bool) ($module['routes'] ?? false),
            'migrations' => (bool) ($module['migrations'] ?? false),
            'views' => (bool) ($module['views'] ?? false),
            'translations' => (bool) ($module['translations'] ?? false),
            'namespace' => (string) ($module['namespace'] ?? "Modules\\{$folder}"),
            'has_migrations' => $migrationsPath !== '' && is_dir($migrationsPath),
            'migrations_path' => $migrationsPath,
            'config_path' => (string) ($module['config_path'] ?? ''),
            'config' => $config,
            'path' => (string) ($module['path'] ?? ''),
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
