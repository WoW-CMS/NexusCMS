@extends('admin::layouts.app')

@section('title', 'Configure Module')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Configure Module</h1>
    </div>
    <a href="{{ route('admin.modules.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i>Back to Modules
    </a>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <p class="text-red-700 text-sm font-medium mb-2">Please fix the following errors:</p>
            <ul class="text-sm text-red-700 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">{{ $module['name'] }} <span class="text-sm font-medium text-gray-500">({{ $module['folder'] }})</span></h2>

            <form method="POST" action="{{ route('admin.modules.update', $module['folder']) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Display Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $module['name']) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="module_type" class="block text-sm font-medium text-gray-700 mb-1">Module Type</label>
                    <select id="module_type" name="module_type" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="core" {{ old('module_type', $module['module_type']) === 'core' ? 'selected' : '' }}>Core</option>
                        <option value="third_party" {{ old('module_type', $module['module_type']) === 'third_party' ? 'selected' : '' }}>Third Party</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Save Configuration
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-md font-bold text-gray-800 mb-3">Module Info</h3>
            <dl class="space-y-2 text-sm">
                <div>
                    <dt class="text-gray-500">Folder</dt>
                    <dd class="text-gray-800 font-medium">{{ $module['folder'] }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Config file</dt>
                    <dd class="text-gray-800 break-all">{{ $module['config_path'] }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Namespace</dt>
                    <dd class="text-gray-800 font-medium break-all">{{ $module['namespace'] }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Capabilities</dt>
                    <dd class="mt-1">
                        <div class="flex flex-wrap gap-1">
                            <span class="px-2 py-0.5 rounded text-xs {{ $module['routes'] ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">Routes</span>
                            <span class="px-2 py-0.5 rounded text-xs {{ $module['migrations'] ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">Migrations</span>
                            <span class="px-2 py-0.5 rounded text-xs {{ $module['views'] ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">Views</span>
                            <span class="px-2 py-0.5 rounded text-xs {{ $module['translations'] ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">Translations</span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</main>
@endsection
