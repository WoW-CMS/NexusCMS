@extends('admin::layouts.app')

@section('title', 'Roles')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.permissions') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
            View Permissions
        </a>
        <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            Create Role
        </a>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Role List</h2>
        </div>
        @if(session('success'))
            <div class="mx-6 mt-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded">
                {{ session('success') }}
            </div>
        @endif
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($roles as $role)
                    <div class="p-4 bg-gray-50 rounded border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded flex items-center justify-center">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $role->name }}</p>
                                    <p class="text-xs text-gray-500">Permissions: {{ $role->permissions_count }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-xs">Edit</a>
                                <button class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs">Delete</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-600">No roles created.</div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
