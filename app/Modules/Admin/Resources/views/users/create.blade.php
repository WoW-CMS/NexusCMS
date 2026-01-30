@extends('admin::layouts.app')

@section('title', 'Create User')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Create New User</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Fill in the details to create a user account</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    ← Back
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-6xl mx-auto px-6 py-8">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: User Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Account Information -->
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-base font-semibold text-gray-900">Account Information</h2>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Full Name</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
                                    placeholder="e.g., John Doe"
                                    required
                                >
                                @error('name')<p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Email Address</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
                                    placeholder="email@example.com"
                                    required
                                >
                                @error('email')<p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
                                    placeholder="Minimum 8 characters"
                                    required
                                >
                                @error('password')<p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <!-- Selectable roles -->
                                 <label class="block text-sm font-medium text-gray-900 mb-2">Roles</label>
                                 <select 
                                    name="roles[]" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
                                    required
                                >
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ strtolower($role->name) === 'user' ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('roles')<p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Points -->
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-base font-semibold text-gray-900">Initial Points</h2>
                            <p class="text-xs text-gray-500 mt-1">Optional: Set starting balance for the user</p>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                            </svg>
                                            DP (Donation Points)
                                        </span>
                                    </label>
                                    <input 
                                        type="number" 
                                        name="dp" 
                                        value="{{ old('dp', 0) }}" 
                                        min="0"
                                        step="1"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
                                        placeholder="0"
                                    >
                                    @error('dp')<p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            VP (Vote Points)
                                        </span>
                                    </label>
                                    <input 
                                        type="number" 
                                        name="vp" 
                                        value="{{ old('vp', 0) }}" 
                                        min="0"
                                        step="1"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
                                        placeholder="0"
                                    >
                                    @error('vp')<p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Role Selection -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden sticky top-6">
                        <div class="p-4 border-t border-gray-200 space-y-2">
                            <button type="submit" class="w-full px-4 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-100">
                                Create User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="block w-full px-4 py-3 text-sm font-medium text-center text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection