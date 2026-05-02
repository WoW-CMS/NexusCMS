@extends('admin::layouts.app')

@section('Title', 'Settings')

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Top Bar -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-800">System Settings</h1>
        </div>
        <div class="flex items-center gap-3">
            <button id="save-settings-btn" form="settings-form" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-save"></i> Save All Changes
            </button>
        </div>
    </header>
    <main class="flex-1 flex overflow-hidden bg-gray-50">
        @include('admin::settings.extends.menu')

        <div class="flex-1 p-6 space-y-6 overflow-y-auto">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            <form id="settings-form" action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="view" value="{{ request('view', 'general') }}">
                @yield('settings_content')
            </form>
        </div>

    </main>
</div>
@endsection
