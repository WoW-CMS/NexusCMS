@extends('admin::settings.index')

@section('settings_content')
<div class="space-y-6">

{{-- Existing locale/timezone settings --}}
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ __('admin::settings.localization.title') }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ __('admin::settings.localization.description') }}</p>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="default_locale" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin::settings.localization.default_locale.label') }}</label>
                <select id="default_locale" name="default_locale" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(config('app.available_locales', ['en' => 'English', 'es' => 'Español', 'fr' => 'Français']) as $code => $name)
                        <option value="{{ $code }}" {{ ($settings['default_locale'] ?? config('app.locale')) == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">{{ __('admin::settings.localization.default_locale.help') }}</p>
            </div>

            <div>
                <label for="timezone" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin::settings.localization.timezone.label') }}</label>
                <select id="timezone" name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" {{ ($settings['timezone'] ?? config('app.timezone')) == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">{{ __('admin::settings.localization.timezone.help') }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="date_format" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin::settings.localization.date_format.label') }}</label>
                <select id="date_format" name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>Y-m-d ({{ date('Y-m-d') }})</option>
                    <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>d/m/Y ({{ date('d/m/Y') }})</option>
                    <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>m/d/Y ({{ date('m/d/Y') }})</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">{{ __('admin::settings.localization.date_format.help') }}</p>
            </div>

            <div>
                <label for="time_format" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin::settings.localization.time_format.label') }}</label>
                <select id="time_format" name="time_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="H:i" {{ ($settings['time_format'] ?? 'H:i') == 'H:i' ? 'selected' : '' }}>24-hour ({{ date('H:i') }})</option>
                    <option value="h:i A" {{ ($settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>12-hour ({{ date('h:i A') }})</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">{{ __('admin::settings.localization.time_format.help') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Multilingual content settings --}}
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ __('admin::settings.localization.multilingual.section_title') }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ __('admin::settings.localization.multilingual.section_description') }}</p>
    </div>
    <div class="p-6 space-y-6">

        {{-- Toggle --}}
        <div class="flex items-start gap-3">
            <input type="hidden" name="multilingual_enabled" value="0">
            <input type="checkbox"
                   id="multilingual_enabled"
                   name="multilingual_enabled"
                   value="1"
                   {{ ($settings['multilingual_enabled'] ?? '0') == '1' ? 'checked' : '' }}
                   class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                   onchange="toggleLocalesPanel(this.checked)">
            <div>
                <label for="multilingual_enabled" class="text-sm font-semibold text-gray-700 cursor-pointer">
                    {{ __('admin::settings.localization.multilingual.enabled_label') }}
                </label>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('admin::settings.localization.multilingual.enabled_help') }}</p>
            </div>
        </div>

        {{-- Available locales manager --}}
        <div id="locales-panel" class="{{ ($settings['multilingual_enabled'] ?? '0') != '1' ? 'hidden' : '' }} space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    {{ __('admin::settings.localization.multilingual.locales_label') }}
                </label>
                <p class="text-xs text-gray-500 mb-3">{{ __('admin::settings.localization.multilingual.locales_help') }}</p>

                {{-- Hidden input stores the JSON array --}}
                <input type="hidden" id="available_locales_input" name="available_locales" value="{{ $settings['available_locales'] ?? '["en"]' }}">

                {{-- Visual tag list --}}
                <div id="locales-tags" class="flex flex-wrap gap-2 mb-3 min-h-[36px] p-2 border border-gray-200 rounded-lg bg-gray-50">
                    {{-- Rendered by JS --}}
                </div>

                {{-- Add locale input --}}
                <div class="flex gap-2">
                    <input type="text"
                           id="locale-add-input"
                           placeholder="{{ __('admin::settings.localization.multilingual.locale_placeholder') }}"
                           maxlength="10"
                           class="w-40 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button"
                            onclick="addLocale()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-1"></i> {{ __('admin::settings.localization.multilingual.add_locale') }}
                    </button>
                </div>

                {{-- Known locale quick-add --}}
                @php
                    $knownLocales = ['en' => 'English', 'es' => 'Español', 'fr' => 'Français', 'de' => 'Deutsch', 'it' => 'Italiano', 'pt' => 'Português', 'ru' => 'Русский'];
                @endphp
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($knownLocales as $code => $name)
                        <button type="button"
                                onclick="addLocaleCode('{{ $code }}')"
                                class="px-2 py-1 text-xs bg-white border border-gray-300 rounded hover:bg-blue-50 hover:border-blue-400 transition text-gray-700">
                            + {{ $code }} ({{ $name }})
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

</div>

@push('scripts')
<script>
    const defaultLocale = '{{ $settings['default_locale'] ?? config('app.locale', 'en') }}';

    function getLocales() {
        try {
            const val = document.getElementById('available_locales_input').value;
            return JSON.parse(val) || [];
        } catch(e) { return []; }
    }

    function saveLocales(arr) {
        // Always ensure the default locale is present
        if (!arr.includes(defaultLocale)) arr.unshift(defaultLocale);
        document.getElementById('available_locales_input').value = JSON.stringify(arr);
        renderTags(arr);
    }

    function renderTags(arr) {
        const container = document.getElementById('locales-tags');
        container.innerHTML = '';
        arr.forEach(code => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium ' +
                (code === defaultLocale ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700');
            tag.innerHTML = `<span>${code}</span>` +
                (code === defaultLocale
                    ? `<span class="text-blue-500 text-[10px] ml-1">(default)</span>`
                    : `<button type="button" onclick="removeLocale('${code}')" class="ml-1 text-gray-400 hover:text-red-500 leading-none">&times;</button>`);
            container.appendChild(tag);
        });
    }

    function addLocale() {
        const input = document.getElementById('locale-add-input');
        addLocaleCode(input.value.trim().toLowerCase());
        input.value = '';
    }

    function addLocaleCode(code) {
        if (!code || !/^[a-z]{2,5}$/.test(code)) return;
        const arr = getLocales();
        if (!arr.includes(code)) {
            arr.push(code);
            saveLocales(arr);
        }
    }

    function removeLocale(code) {
        if (code === defaultLocale) return; // cannot remove default
        const arr = getLocales().filter(c => c !== code);
        saveLocales(arr);
    }

    function toggleLocalesPanel(enabled) {
        const panel = document.getElementById('locales-panel');
        panel.classList.toggle('hidden', !enabled);
    }

    // Init on page load
    document.addEventListener('DOMContentLoaded', function() {
        renderTags(getLocales());

        // Allow Enter key to add locale
        document.getElementById('locale-add-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); addLocale(); }
        });
    });
</script>
@endpush
@endsection
