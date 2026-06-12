<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RouteDiscoveryService;
use App\Services\ModuleRegistryService;
use Modules\Admin\Domain\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MenuManagerController extends Controller
{
    public function __construct(
        protected RouteDiscoveryService $routeDiscovery,
    ) {}

    public function index()
    {
        $webMenu = menu_config('web');
        $ucpMenu = menu_config('ucp');
        $modules = $this->discoverModules();
        $availableRoutes = $this->routeDiscovery->getRoutesForMenuPicker();
        $moduleRoutes = $this->routeDiscovery->getRoutesByModule();

        return view('admin::menus.index', compact(
            'webMenu', 'ucpMenu', 'modules', 'availableRoutes', 'moduleRoutes'
        ));
    }

    public function update(Request $request)
    {
        $action = (string) $request->input('action', 'save');
        $modules = $this->discoverModules();

        $webItems = $this->normalizeItems($request->input('web_items', []));
        $ucpItems = $this->normalizeItems($request->input('ucp_items', []));

        $affected = 0;
        if (in_array($action, ['disable-disabled-modules', 'remove-disabled-modules'], true)) {
            [$webItems, $webAffected] = $this->handleDisabledModuleItems($webItems, $modules, $action);
            [$ucpItems, $ucpAffected] = $this->handleDisabledModuleItems($ucpItems, $modules, $action);
            $affected = $webAffected + $ucpAffected;
        }

        Setting::updateOrCreate(
            ['key' => 'menu.web'],
            ['value' => json_encode($webItems, JSON_UNESCAPED_SLASHES)]
        );

        Setting::updateOrCreate(
            ['key' => 'menu.ucp'],
            ['value' => json_encode($ucpItems, JSON_UNESCAPED_SLASHES)]
        );

        Cache::forget('site_settings');

        $message = 'Menus updated successfully.';
        if ($action === 'disable-disabled-modules') {
            $message = "Menus updated. {$affected} item(s) were disabled because their module is disabled or missing.";
        }

        if ($action === 'remove-disabled-modules') {
            $message = "Menus updated. {$affected} item(s) were removed because their module is disabled or missing.";
        }

        return redirect()
            ->route('admin.menus.index')
            ->with('success', $message);
    }

    /**
     * Normalize items recursively — supports nested children.
     */
    protected function normalizeItems(array $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            $label = trim((string) ($item['label'] ?? ''));
            $route = trim((string) ($item['route'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));

            if ($label === '' || ($route === '' && $url === '')) {
                continue;
            }

            $normalizedItem = [
                'id' => $this->generateItemId(),
                'label' => $label,
                'route' => $route,
                'url' => $url,
                'icon' => trim((string) ($item['icon'] ?? '')),
                'module' => preg_replace('/[^A-Za-z0-9_]/', '', trim((string) ($item['module'] ?? ''))),
                'auth' => in_array(($item['auth'] ?? 'any'), ['any', 'auth', 'guest'], true)
                    ? $item['auth']
                    : 'any',
                'permission' => trim((string) ($item['permission'] ?? '')),
                'enabled' => in_array('1', (array) ($item['enabled'] ?? ['1'])),
                'children' => [],
            ];

            // Recursively normalize children
            if (!empty($item['children']) && is_array($item['children'])) {
                $normalizedItem['children'] = $this->normalizeItems($item['children']);
            }

            $normalized[] = $normalizedItem;
        }

        return $normalized;
    }

    /**
     * Generate a unique item ID (ULID-like).
     */
    protected function generateItemId(): string
    {
        return 'item_' . Str::random(13);
    }

    /**
     * Discover modules using ModuleRegistryService.
     */
    protected function discoverModules(): array
    {
        $modules = [];

        foreach (ModuleRegistryService::getAllModules() as $module) {
            $moduleName = trim((string) ($module['folder'] ?? ''));
            if ($moduleName === '') {
                continue;
            }

            $modules[$moduleName] = (bool) ($module['enabled'] ?? true);
        }

        ksort($modules);

        return $modules;
    }

    protected function handleDisabledModuleItems(array $items, array $modules, string $action): array
    {
        $result = [];
        $affected = 0;

        foreach ($items as $item) {
            $module = trim((string) ($item['module'] ?? ''));
            $isProblematic = $module !== '' && (!array_key_exists($module, $modules) || !$modules[$module]);

            if ($isProblematic) {
                if ($action === 'remove-disabled-modules') {
                    $affected++;
                    continue;
                }

                if ($action === 'disable-disabled-modules' && (bool) ($item['enabled'] ?? true)) {
                    $item['enabled'] = false;
                    $affected++;
                }
            }

            // Recursively handle children
            if (!empty($item['children'])) {
                [$item['children'], $childAffected] = $this->handleDisabledModuleItems(
                    $item['children'],
                    $modules,
                    $action
                );
                $affected += $childAffected;
            }

            $result[] = $item;
        }

        return [$result, $affected];
    }
}
