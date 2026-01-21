@extends('admin::layouts.app')

@section('title', 'Permission List')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Permission List</h1>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ url('/acp') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-home text-xl"></i>
        </a>
    </div>
    </header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Available Permissions</h2>
                <p class="text-sm text-gray-600">Based on the current system configuration</p>
            </div>
            <div class="relative">
                <input type="search" id="search-permissions" placeholder="Search permissions..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        @php
            $grouped = $permissions->groupBy(fn($p) => explode('.', $p->name)[0]);
        @endphp
        <div class="p-6 space-y-8" id="permissions-list">
            @foreach($grouped as $group => $items)
                <div class="space-y-3 permission-group" data-group="{{ $group }}">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-800 capitalize">{{ $group }}</h3>
                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">Total: {{ $items->count() }}</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($items as $permission)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200 permission-item">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded flex items-center justify-center">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $permission->name }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $permission->id }}</p>
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">{{ explode('.', $permission->name)[0] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            @if($permissions->isEmpty())
                <div class="p-6 text-center text-gray-600">No permissions are configured.</div>
            @endif
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-permissions');
    const items = document.querySelectorAll('#permissions-list .permission-item');
    input.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        items.forEach(el => {
            const text = el.textContent.toLowerCase();
            el.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>
@endsection

