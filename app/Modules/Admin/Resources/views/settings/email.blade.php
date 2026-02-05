@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ __('Email Settings') }}</h2> 
        <p class="text-sm text-gray-600 mt-1">{{ __('Configure SMTP settings for outgoing emails') }}</p>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('SMTP Host') }}</label>
                <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}" placeholder="smtp.example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('SMTP Port') }}</label>
                <input type="number" name="smtp_port" value="{{ $settings['smtp_port'] ?? '587' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('SMTP Username') }}</label>
                <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}" placeholder="noreply@nexuscms.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('SMTP Password') }}</label>
                <input type="password" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Encryption') }}</label>
                <select name="smtp_encryption" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="tls" {{ ($settings['smtp_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="none" {{ ($settings['smtp_encryption'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('From Name') }}</label>
                <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? 'NexusCMS' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                <i class="fas fa-paper-plane mr-2"></i>{{ __('Send Test Email') }}
            </button>
        </div>
    </div>
</div>
@endsection
