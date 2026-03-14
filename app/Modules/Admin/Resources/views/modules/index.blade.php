@extends('admin::layouts.app')

@section('title', 'Modules')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Modules</h1>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
            <p class="text-yellow-700 text-sm font-medium">{{ session('warning') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600"></i>
            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-puzzle-piece text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Modules</p>
                <p class="text-xl font-bold text-gray-800">{{ $modules->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-toggle-on text-green-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Enabled</p>
                <p class="text-xl font-bold text-gray-800">{{ $modules->where('enabled', true)->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-toggle-off text-red-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Disabled</p>
                <p class="text-xl font-bold text-gray-800">{{ $modules->where('enabled', false)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-cubes text-indigo-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Core</p>
                <p class="text-xl font-bold text-gray-800">{{ $modules->where('module_type', 'core')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-plug text-amber-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Third Party</p>
                <p class="text-xl font-bold text-gray-800">{{ $modules->where('module_type', 'third_party')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Installed Modules</h2>
            <span class="text-sm text-gray-500">{{ $modules->count() }} modules</span>
        </div>

        <div class="p-6">
            @if($modules->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capabilities</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($modules as $module)
                                <tr class="hover:bg-gray-50 align-top">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm font-semibold text-gray-900">{{ $module['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $module['folder'] }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($module['enabled'])
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Enabled</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">Disabled</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($module['module_type'] === 'third_party')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Third Party</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Core</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-2 py-1 rounded bg-gray-100 {{ $module['routes'] ? 'text-blue-700' : 'text-gray-400' }}">Routes</span>
                                            <span class="px-2 py-1 rounded bg-gray-100 {{ $module['migrations'] ? 'text-blue-700' : 'text-gray-400' }}">Migrations</span>
                                            <span class="px-2 py-1 rounded bg-gray-100 {{ $module['views'] ? 'text-blue-700' : 'text-gray-400' }}">Views</span>
                                            <span class="px-2 py-1 rounded bg-gray-100 {{ $module['translations'] ? 'text-blue-700' : 'text-gray-400' }}">Translations</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            @if($module['folder'] !== 'Admin')
                                            <form method="POST" action="{{ route('admin.modules.toggle', $module['folder']) }}">
                                                @csrf
                                                @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ $module['enabled'] ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                                                        <i class="fas {{ $module['enabled'] ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-1"></i>
                                                        {{ $module['enabled'] ? 'Disable' : 'Enable' }}
                                                    </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.modules.migrate', $module['folder']) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 {{ !$module['has_migrations'] ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$module['has_migrations'] ? 'disabled' : '' }}>
                                                    <i class="fas fa-database mr-1"></i>Migrate
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.modules.edit', $module['folder']) }}" class="px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                                                <i class="fas fa-cog mr-1"></i>Configure
                                            </a>

                                            <form method="POST" action="{{ route('admin.modules.destroy', $module['folder']) }}" onsubmit="return confirmModuleDeletion(this, '{{ $module['folder'] }}');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="confirmation" value="">
                                                <button type="submit" class="px-3 py-1.5 rounded-lg text-sm font-medium bg-red-50 text-red-700 hover:bg-red-100 {{ $module['folder'] === 'Admin' ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $module['folder'] === 'Admin' ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box-open text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No modules found.</p>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function confirmModuleDeletion(form, moduleName) {
        const confirmation = prompt(`Type ${moduleName} to confirm module deletion.`);

        if (confirmation === null) {
            return false;
        }

        form.querySelector('input[name="confirmation"]').value = confirmation;
        return true;
    }
</script>
@endpush
