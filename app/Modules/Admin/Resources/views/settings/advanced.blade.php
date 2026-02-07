@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ __('admin::settings.advanced.title') }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ __('admin::settings.advanced.description') }}</p>
    </div>
    <div class="p-6">
        <p class="text-gray-500 italic">{{ __('admin::settings.advanced.under_construction') }}</p>
    </div>
</div>
@endsection
