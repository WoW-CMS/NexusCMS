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
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2">
                <i class="fas fa-save"></i> Save All Changes
            </button>
        </div>
    </header>
    <main class="flex-1 flex overflow-hidden bg-gray-50">
        @include('admin::settings.extends.menu')

        <div class="flex-1 p-6 space-y-6 overflow-y-auto">
            @yield('settings_content')
        </div>
    </main>
</div>
@endsection
