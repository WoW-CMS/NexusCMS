@extends('admin::settings.index')

@section('settings_content')
<div class="space-y-6">

    {{-- Seeders --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-database text-indigo-500"></i>
                {{ __('admin::settings.advanced.seeders_title') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('admin::settings.advanced.seeders_description') }}</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Roles & Permissions --}}
            <div class="border border-gray-200 rounded-lg p-4 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-purple-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ __('admin::settings.advanced.seeder_roles_title') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('admin::settings.advanced.seeder_roles_desc') }}</p>
                </div>
                <button form="seeder-roles" type="submit"
                    class="flex-shrink-0 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 flex items-center gap-1"
                    onclick="return confirm('{{ __('admin::settings.advanced.confirm_run') }}')">
                    <i class="fas fa-play"></i> {{ __('admin::settings.advanced.run') }}
                </button>
            </div>

            {{-- Forums --}}
            <div class="border border-gray-200 rounded-lg p-4 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comments text-blue-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ __('admin::settings.advanced.seeder_forums_title') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('admin::settings.advanced.seeder_forums_desc') }}</p>
                </div>
                <button form="seeder-forums" type="submit"
                    class="flex-shrink-0 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 flex items-center gap-1"
                    onclick="return confirm('{{ __('admin::settings.advanced.confirm_run') }}')">
                    <i class="fas fa-play"></i> {{ __('admin::settings.advanced.run') }}
                </button>
            </div>

            {{-- Donation Plans --}}
            <div class="border border-gray-200 rounded-lg p-4 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-donate text-green-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ __('admin::settings.advanced.seeder_donations_title') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('admin::settings.advanced.seeder_donations_desc') }}</p>
                </div>
                <button form="seeder-donations" type="submit"
                    class="flex-shrink-0 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 flex items-center gap-1"
                    onclick="return confirm('{{ __('admin::settings.advanced.confirm_run') }}')">
                    <i class="fas fa-play"></i> {{ __('admin::settings.advanced.run') }}
                </button>
            </div>

            {{-- All Seeders --}}
            <div class="border border-orange-200 bg-orange-50 rounded-lg p-4 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-layer-group text-orange-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ __('admin::settings.advanced.seeder_all_title') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('admin::settings.advanced.seeder_all_desc') }}</p>
                </div>
                <button form="seeder-all" type="submit"
                    class="flex-shrink-0 px-3 py-1.5 bg-orange-600 text-white text-xs font-medium rounded-lg hover:bg-orange-700 flex items-center gap-1"
                    onclick="return confirm('{{ __('admin::settings.advanced.confirm_run_all') }}')">
                    <i class="fas fa-play"></i> {{ __('admin::settings.advanced.run_all') }}
                </button>
            </div>

        </div>
    </div>

    {{-- Cache Management --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-broom text-yellow-500"></i>
                {{ __('admin::settings.advanced.cache_title') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('admin::settings.advanced.cache_description') }}</p>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @php
            $cacheItems = [
                ['key' => 'config',  'icon' => 'fa-cog',        'color' => 'gray',   'form' => 'cache-config'],
                ['key' => 'routes',  'icon' => 'fa-route',      'color' => 'blue',   'form' => 'cache-routes'],
                ['key' => 'views',   'icon' => 'fa-eye',        'color' => 'teal',   'form' => 'cache-views'],
                ['key' => 'app',     'icon' => 'fa-memory',     'color' => 'purple', 'form' => 'cache-app'],
                ['key' => 'all',     'icon' => 'fa-trash-alt',  'color' => 'red',    'form' => 'cache-all'],
            ];
            @endphp

            @foreach($cacheItems as $item)
            <div class="border border-gray-200 rounded-lg p-4 flex items-center gap-3">
                <div class="flex-shrink-0 w-9 h-9 bg-{{ $item['color'] }}-100 rounded-lg flex items-center justify-center">
                    <i class="fas {{ $item['icon'] }} text-{{ $item['color'] }}-600 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ __('admin::settings.advanced.cache_' . $item['key'] . '_title') }}</p>
                    <p class="text-xs text-gray-400">{{ __('admin::settings.advanced.cache_' . $item['key'] . '_desc') }}</p>
                </div>
                <button form="{{ $item['form'] }}" type="submit"
                    class="flex-shrink-0 px-2.5 py-1.5 text-xs font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 flex items-center gap-1"
                    onclick="return confirm('{{ __('admin::settings.advanced.confirm_cache') }}')">
                    <i class="fas fa-trash text-xs"></i> {{ __('admin::settings.advanced.clear') }}
                </button>
            </div>
            @endforeach

        </div>
    </div>

</div>
@endsection

@push('after_settings_form')
{{-- Seeder forms (outside main settings form to avoid nesting) --}}
<form id="seeder-roles"     method="POST" action="{{ route('admin.settings.advanced.seeder') }}">@csrf<input type="hidden" name="seeder" value="roles_permissions"></form>
<form id="seeder-forums"    method="POST" action="{{ route('admin.settings.advanced.seeder') }}">@csrf<input type="hidden" name="seeder" value="forums"></form>
<form id="seeder-donations" method="POST" action="{{ route('admin.settings.advanced.seeder') }}">@csrf<input type="hidden" name="seeder" value="donation_plans"></form>
<form id="seeder-all"       method="POST" action="{{ route('admin.settings.advanced.seeder') }}">@csrf<input type="hidden" name="seeder" value="all"></form>

{{-- Cache forms --}}
<form id="cache-config" method="POST" action="{{ route('admin.settings.advanced.cache') }}">@csrf<input type="hidden" name="cache" value="config"></form>
<form id="cache-routes" method="POST" action="{{ route('admin.settings.advanced.cache') }}">@csrf<input type="hidden" name="cache" value="routes"></form>
<form id="cache-views"  method="POST" action="{{ route('admin.settings.advanced.cache') }}">@csrf<input type="hidden" name="cache" value="views"></form>
<form id="cache-app"    method="POST" action="{{ route('admin.settings.advanced.cache') }}">@csrf<input type="hidden" name="cache" value="app"></form>
<form id="cache-all"    method="POST" action="{{ route('admin.settings.advanced.cache') }}">@csrf<input type="hidden" name="cache" value="all"></form>
@endpush

