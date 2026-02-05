@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ __('General Settings') }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ __('Configure basic site information and settings') }}</p>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Site Name') }}</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'NexusCMS' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Site URL') }}</label>
                <input type="text" name="site_url" value="{{ $settings['site_url'] ?? 'https://nexuscms.example.com' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Site Description') }}</label>
            <textarea name="site_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $settings['site_description'] ?? __('The ultimate World of Warcraft private server experience with custom content and balanced gameplay.') }}</textarea>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Admin Email') }}</label>
                <input type="email" name="admin_email" value="{{ $settings['admin_email'] ?? 'admin@nexuscms.com' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Timezone') }}</label>
                <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="Europe/Madrid" {{ ($settings['timezone'] ?? '') == 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid</option>
                    <option value="America/New_York" {{ ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                    <option value="America/Los_Angeles" {{ ($settings['timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Date Format') }}</label>
                <select name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>Y-m-d (2024-01-29)</option>
                    <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>d/m/Y (29/01/2024)</option>
                    <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>m/d/Y (01/29/2024)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Time Format') }}</label>
                <select name="time_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="H:i" {{ ($settings['time_format'] ?? '') == 'H:i' ? 'selected' : '' }}>24-hour (14:30)</option>
                    <option value="h:i A" {{ ($settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>12-hour (2:30 PM)</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="allow_registration" value="0">
            <input type="checkbox" name="allow_registration" value="1" id="registration" {{ ($settings['allow_registration'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300">
            <label for="registration" class="text-sm text-gray-700">{{ __('Allow new user registrations') }}</label>
        </div>
    </div>
</div>
@endsection
