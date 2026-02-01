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
            @if(!in_array(request('view', 'general'), ['realms']))
                <button id="save-settings-btn" form="settings-form" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save"></i> Save All Changes
                </button>
            @endif
        </div>
    </header>
    <main class="flex-1 flex overflow-hidden bg-gray-50">
        @include('admin::settings.extends.menu')

        <div class="flex-1 p-6 space-y-6 overflow-y-auto">
            <form id="settings-form" action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="view" value="{{ request('view', 'general') }}">
                @yield('settings_content')
            </form>
        </div>
    </main>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('settings-form');
        const saveBtn = document.getElementById('save-settings-btn');
        
        if (!form || !saveBtn) return;

        function checkFormValidity() {
            const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"]), select, textarea');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                }
            });

            saveBtn.disabled = !isValid;
        }

        // Check initially
        checkFormValidity();

        // Listen for changes
        form.addEventListener('input', checkFormValidity);
        form.addEventListener('change', checkFormValidity);
    });
</script>
@endpush
@endsection
