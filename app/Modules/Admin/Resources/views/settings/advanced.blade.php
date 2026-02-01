@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ ucfirst($view) }} Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Configure {{ $view }} settings</p>
    </div>
    <div class="p-6">
        <p class="text-gray-500 italic">This section is under construction.</p>
    </div>
</div>
@endsection
