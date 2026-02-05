@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ ucfirst($view) }} Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Configure language, time, and currency preferences</p>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="default_locale" class="block text-sm font-semibold text-gray-700 mb-2">Default Locale</label>
                <select id="default_locale" name="default_locale" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(config('app.available_locales', ['en' => 'English', 'es' => 'Español', 'fr' => 'Français']) as $code => $name)
                        <option value="{{ $code }}" {{ ($settings['default_locale'] ?? config('app.locale')) == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">The default language for the application.</p>
            </div>

            <div>
                <label for="fallback_locale" class="block text-sm font-semibold text-gray-700 mb-2">Fallback Locale</label>
                <select id="fallback_locale" name="fallback_locale" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(config('app.available_locales', ['en' => 'English', 'es' => 'Español', 'fr' => 'Français']) as $code => $name)
                        <option value="{{ $code }}" {{ ($settings['fallback_locale'] ?? config('app.fallback_locale')) == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Language used when a translation is missing.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="timezone" class="block text-sm font-semibold text-gray-700 mb-2">Timezone</label>
                <select id="timezone" name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" {{ ($settings['timezone'] ?? config('app.timezone')) == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">System-wide timezone setting.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="date_format" class="block text-sm font-semibold text-gray-700 mb-2">Date Format</label>
                <select id="date_format" name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>Y-m-d ({{ date('Y-m-d') }})</option>
                    <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>d/m/Y ({{ date('d/m/Y') }})</option>
                    <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>m/d/Y ({{ date('m/d/Y') }})</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Display format for dates.</p>
            </div>

            <div>
                <label for="time_format" class="block text-sm font-semibold text-gray-700 mb-2">Time Format</label>
                <select id="time_format" name="time_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="H:i" {{ ($settings['time_format'] ?? 'H:i') == 'H:i' ? 'selected' : '' }}>24-hour ({{ date('H:i') }})</option>
                    <option value="h:i A" {{ ($settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>12-hour ({{ date('h:i A') }})</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Display format for times.</p>
            </div>
        </div>
    </div>
</div>
@endsection
