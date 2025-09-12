@extends('layouts.main')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700 p-8">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Login to Your Account</h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" id="email" required
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg py-2 px-4 text-black placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pl-10">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg py-2 px-4 text-black placeholder-gray-500
                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pl-10">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="h-4 w-4 rounded border-gray-700 bg-gray-900/50 text-blue-500 focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">Remember me</label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-2 px-4 transition-color">
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