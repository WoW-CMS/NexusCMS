@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 py-16">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                Welcome Back
            </h2>
            <p class="mt-2 text-gray-400">Sign in to your account</p>
        </div>

        <div class="bg-gray-900/50 rounded-2xl backdrop-blur-md shadow-lg border border-gray-800 p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full rounded-md bg-gray-800/50 border border-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full rounded-md bg-gray-800/50 border border-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="h-4 w-4 rounded border-gray-700 bg-gray-800/50 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">Remember me</label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                </button>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-400">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-indigo-400 hover:text-indigo-300">
                            Register here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection