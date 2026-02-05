@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ __('Security Settings') }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ __('Configure authentication, password policies, and account protection settings') }}</p>
    </div>
    <div class="p-6 space-y-8">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">{{ __('Password Policies') }}</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Minimum Password Length') }}</label>
                    <select name="password_min_length" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @php $min = (int)($settings['password_min_length'] ?? 12); @endphp
                        <option value="8" {{ $min === 8 ? 'selected' : '' }}>8</option>
                        <option value="10" {{ $min === 10 ? 'selected' : '' }}>10</option>
                        <option value="12" {{ $min === 12 ? 'selected' : '' }}>12</option>
                        <option value="14" {{ $min === 14 ? 'selected' : '' }}>14</option>
                        <option value="16" {{ $min === 16 ? 'selected' : '' }}>16</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="password_require_uppercase" value="0">
                        <input type="checkbox" name="password_require_uppercase" value="1" id="password_uppercase" {{ ($settings['password_require_uppercase'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="password_uppercase" class="text-sm text-gray-700">{{ __('Require Uppercase Letters') }}</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="password_require_numbers" value="0">
                        <input type="checkbox" name="password_require_numbers" value="1" id="password_numbers" {{ ($settings['password_require_numbers'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="password_numbers" class="text-sm text-gray-700">{{ __('Require Numbers') }}</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="password_require_special" value="0">
                        <input type="checkbox" name="password_require_special" value="1" id="password_special" {{ ($settings['password_require_special'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="password_special" class="text-sm text-gray-700">{{ __('Require Special Characters') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">{{ __('Account Protection') }}</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Max Login Attempts') }}</label>
                    <input type="number" min="1" max="20" name="max_login_attempts" value="{{ $settings['max_login_attempts'] ?? 5 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Lockout Duration (minutes)') }}</label>
                    <input type="number" min="1" max="240" name="lockout_duration_minutes" value="{{ $settings['lockout_duration_minutes'] ?? 15 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">{{ __('Session and Authentication') }}</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Session Timeout (minutes)') }}</label>
                    <input type="number" min="5" max="720" name="session_timeout_minutes" value="{{ $settings['session_timeout_minutes'] ?? 30 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="force_https" value="0">
                        <input type="checkbox" name="force_https" value="1" id="force_https" {{ ($settings['force_https'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="force_https" class="text-sm text-gray-700">{{ __('Force HTTPS') }}</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="enable_2fa" value="0">
                        <input type="checkbox" disabled name="enable_2fa" value="1" id="enable_2fa" {{ ($settings['enable_2fa'] ?? '0') == '1' ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="enable_2fa" class="text-sm text-gray-700">{{ __('Enable 2FA') }}</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="require_email_verification" value="0">
                        <input type="checkbox" disabled name="require_email_verification" value="1" id="require_email_verification" {{ ($settings['require_email_verification'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="require_email_verification" class="text-sm text-gray-700">{{ __('Require Email Verification') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
