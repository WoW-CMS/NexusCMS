@extends('admin::layouts.app')

@section('title', 'Menu Manager')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Menu Manager</h1>
        <p class="text-sm text-gray-500">Configure Web and UCP menus with module-aware safety rules.</p>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs uppercase tracking-wider text-gray-500">Modules</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ count($modules) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs uppercase tracking-wider text-gray-500">Enabled Modules</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ collect($modules)->filter(fn ($enabled) => $enabled === true)->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs uppercase tracking-wider text-gray-500">Disabled Modules</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ collect($modules)->filter(fn ($enabled) => $enabled === false)->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs uppercase tracking-wider text-gray-500">Menu Items</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ count($webMenu) + count($ucpMenu) }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.menus.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-amber-900">Disabled module safety tools</p>
                <p class="text-xs text-amber-800">Use these actions to avoid broken links when a module is disabled or removed.</p>
            </div>
            <div class="flex gap-2">
                <button type="submit" name="action" value="disable-disabled-modules" class="px-3 py-2 rounded-lg text-sm bg-yellow-100 text-yellow-800 hover:bg-yellow-200">
                    <i class="fas fa-toggle-off mr-1"></i>Disable Items of Disabled Modules
                </button>
                <button type="submit" name="action" value="remove-disabled-modules" class="px-3 py-2 rounded-lg text-sm bg-red-100 text-red-800 hover:bg-red-200" onclick="return confirm('This will permanently remove all menu items linked to disabled or missing modules. Continue?')">
                    <i class="fas fa-trash mr-1"></i>Delete Items of Disabled Modules
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Web Menu</h2>
                    <p class="text-sm text-gray-500">Top navigation in frontend layout.</p>
                </div>
                <button type="button" onclick="addMenuRow('web-menu-body', 'web_items')" class="px-3 py-2 rounded-lg text-sm bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200">
                    <i class="fas fa-plus mr-1"></i>Add Item
                </button>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Label</th>
                            <th class="px-3 py-2 text-left">Module</th>
                            <th class="px-3 py-2 text-left">Route</th>
                            <th class="px-3 py-2 text-left">URL</th>
                            <th class="px-3 py-2 text-left">Icon</th>
                            <th class="px-3 py-2 text-left">Auth</th>
                            <th class="px-3 py-2 text-left">Permission</th>
                            <th class="px-3 py-2 text-left">Enabled</th>
                            <th class="px-3 py-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="web-menu-body" class="divide-y divide-gray-100">
                        @foreach($webMenu as $index => $item)
                            @include('admin::menus.partials.row', ['prefix' => 'web_items', 'index' => $index, 'item' => $item, 'modules' => $modules])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">UCP Menu</h2>
                    <p class="text-sm text-gray-500">Sidebar navigation in UCP pages.</p>
                </div>
                <button type="button" onclick="addMenuRow('ucp-menu-body', 'ucp_items')" class="px-3 py-2 rounded-lg text-sm bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200">
                    <i class="fas fa-plus mr-1"></i>Add Item
                </button>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Label</th>
                            <th class="px-3 py-2 text-left">Module</th>
                            <th class="px-3 py-2 text-left">Route</th>
                            <th class="px-3 py-2 text-left">URL</th>
                            <th class="px-3 py-2 text-left">Icon</th>
                            <th class="px-3 py-2 text-left">Auth</th>
                            <th class="px-3 py-2 text-left">Permission</th>
                            <th class="px-3 py-2 text-left">Enabled</th>
                            <th class="px-3 py-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="ucp-menu-body" class="divide-y divide-gray-100">
                        @foreach($ucpMenu as $index => $item)
                            @include('admin::menus.partials.row', ['prefix' => 'ucp_items', 'index' => $index, 'item' => $item, 'modules' => $modules])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" name="action" value="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                <i class="fas fa-save mr-2"></i>Save Menus
            </button>
        </div>
    </form>
</main>

<template id="menu-row-template">
    <tr>
        <td class="px-3 py-2"><input type="text" class="w-full rounded border-gray-300" data-field="label" placeholder="Label"></td>
        <td class="px-3 py-2">
            <select class="w-full rounded border-gray-300" data-field="module">
                <option value="">Core / No Module</option>
                @foreach($modules as $moduleName => $isEnabled)
                    <option value="{{ $moduleName }}">{{ $moduleName }} {{ $isEnabled ? '(enabled)' : '(disabled)' }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-3 py-2"><input type="text" class="w-full rounded border-gray-300" data-field="route" placeholder="route.name"></td>
        <td class="px-3 py-2"><input type="text" class="w-full rounded border-gray-300" data-field="url" placeholder="/custom-url"></td>
        <td class="px-3 py-2"><input type="text" class="w-full rounded border-gray-300" data-field="icon" placeholder="fas fa-home"></td>
        <td class="px-3 py-2">
            <select class="w-full rounded border-gray-300" data-field="auth">
                <option value="any">Any</option>
                <option value="auth">Auth</option>
                <option value="guest">Guest</option>
            </select>
        </td>
        <td class="px-3 py-2"><input type="text" class="w-full rounded border-gray-300" data-field="permission" placeholder="permission.name"></td>
        <td class="px-3 py-2 text-center"><input type="checkbox" value="1" data-field="enabled" checked></td>
        <td class="px-3 py-2 text-right">
            <button type="button" class="px-2 py-1 text-xs rounded bg-red-50 text-red-700 hover:bg-red-100" onclick="this.closest('tr').remove()">Remove</button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
    function bindRowInputs(row, prefix, index) {
        row.querySelectorAll('[data-field]').forEach((element) => {
            const field = element.getAttribute('data-field');
            element.setAttribute('name', `${prefix}[${index}][${field}]`);
        });
    }

    function addMenuRow(tbodyId, prefix) {
        const tbody = document.getElementById(tbodyId);
        const template = document.getElementById('menu-row-template');
        const row = template.content.firstElementChild.cloneNode(true);
        const index = tbody.querySelectorAll('tr').length;
        bindRowInputs(row, prefix, index);
        tbody.appendChild(row);
    }
</script>
@endpush
