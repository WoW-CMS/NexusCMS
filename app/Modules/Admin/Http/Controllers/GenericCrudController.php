<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ModuleCrudService;
use App\Services\ModuleRegistryService;
use Illuminate\Http\Request;

class GenericCrudController extends Controller
{
    protected function getCrudConfigOrFail(string $module): array
    {
        $manifest = ModuleRegistryService::getModule($module);

        if (!is_array($manifest) || ($manifest['admin_crud'] ?? '') !== 'json') {
            abort(404);
        }

        $config = ModuleCrudService::getConfig($module);

        if (!is_array($config)) {
            abort(404, "admin-crud.json not found or invalid for module '{$module}'.");
        }

        return $config;
    }

    protected function crudRouteBase(string $module): string
    {
        return 'admin.' . strtolower($module) . '.crud.';
    }

    public function index(Request $request, string $module)
    {
        $config  = $this->getCrudConfigOrFail($module);
        $records = ModuleCrudService::paginate($config, $request->query('search'));
        $base    = $this->crudRouteBase($module);

        return view('admin::generic-crud.index', compact('config', 'records', 'module', 'base'));
    }

    public function create(string $module)
    {
        $config = $this->getCrudConfigOrFail($module);
        $base   = $this->crudRouteBase($module);

        return view('admin::generic-crud.create', compact('config', 'module', 'base'));
    }

    public function store(Request $request, string $module)
    {
        $config = $this->getCrudConfigOrFail($module);
        $data   = $request->validate(ModuleCrudService::buildValidationRules($config));

        ModuleCrudService::store($config, $data);

        return redirect()
            ->route($this->crudRouteBase($module) . 'index')
            ->with('success', 'Record created successfully.');
    }

    public function edit(int|string $id, string $module)
    {
        $config = $this->getCrudConfigOrFail($module);

        try {
            $record = ModuleCrudService::find($config, $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return redirect()
                ->route($this->crudRouteBase($module) . 'index')
                ->with('error', "Record #{$id} was not found.");
        }

        $base = $this->crudRouteBase($module);

        return view('admin::generic-crud.edit', compact('config', 'record', 'module', 'base'));
    }

    public function update(Request $request, int|string $id, string $module)
    {
        $config = $this->getCrudConfigOrFail($module);

        try {
            ModuleCrudService::find($config, $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return redirect()
                ->route($this->crudRouteBase($module) . 'index')
                ->with('error', "Record #{$id} was not found.");
        }

        $data = $request->validate(ModuleCrudService::buildValidationRules($config, isUpdate: true));
        ModuleCrudService::update($config, $id, $data);

        return redirect()
            ->route($this->crudRouteBase($module) . 'edit', ['id' => $id])
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(int|string $id, string $module)
    {
        $config = $this->getCrudConfigOrFail($module);

        try {
            ModuleCrudService::destroy($config, $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return redirect()
                ->route($this->crudRouteBase($module) . 'index')
                ->with('error', "Record #{$id} was not found.");
        }

        return redirect()
            ->route($this->crudRouteBase($module) . 'index')
            ->with('success', 'Record deleted successfully.');
    }
}
