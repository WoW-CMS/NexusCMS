@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ __('admin::settings.maintenance.title') }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ __('admin::settings.maintenance.description') }}</p>
    </div>
    <div class="p-6 space-y-6">
        <div class="flex items-center gap-2">
            <input type="hidden" name="maintenance_mode" value="0">
            <input type="checkbox" name="maintenance_mode" value="1" id="maintenance_mode" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="maintenance_mode" class="text-sm font-medium text-gray-700">{{ __('admin::settings.maintenance.enabled') }}</label>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin::settings.maintenance.message.label') }}</label>
            <textarea name="maintenance_message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $settings['maintenance_message'] ?? __('We are currently performing scheduled maintenance. We will be back shortly.') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">{{ __('admin::settings.maintenance.message.help') }}</p>
        </div>
    </div>
</div>
@endsection
