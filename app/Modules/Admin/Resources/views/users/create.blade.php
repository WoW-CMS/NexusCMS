@extends('admin::layouts.app')

@section('title', 'Crear Administrador')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Crear administrador</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
            Volver
        </a>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Datos</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input name="name" value="{{ old('name') }}" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Roles</h2>
                <p class="text-sm text-gray-600">Admin se asigna automáticamente.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded">
                            <span class="text-sm text-gray-800">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex items-center justify-end gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    Crear
                </button>
            </div>
        </div>
    </form>
</main>
@endsection

