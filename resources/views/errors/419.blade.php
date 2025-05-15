@extends('layouts.main')

@section('content')
<div class="bg-gray-900 min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <div class="text-center">
                    <svg class="text-purple-500 w-24 h-24 mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h1 class="text-6xl font-extrabold text-purple-500 mb-4">419</h1>
                    <h2 class="text-2xl font-bold text-white mb-6">Page Expired</h2>
                    <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                        <p class="text-xl text-gray-300 mb-6">{{ $message ?? 'Your session has expired. Please refresh the page and try again.' }}</p>
                        <hr class="border-gray-700 my-6">
                        <div class="text-gray-400 mb-8">
                            <p class="mb-3">This page has expired due to inactivity or security reasons.</p>
                            <p>Reload the page to continue.</p>
                        </div>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-300">
                                <i class="fas fa-home mr-2"></i>Back to Home
                            </a>
                            <button onclick="location.reload()" class="inline-flex items-center px-6 py-3 border border-gray-600 text-base font-medium rounded-md text-gray-300 hover:text-white hover:bg-gray-700 transition-colors duration-300">
                                <i class="fas fa-sync mr-2"></i>Reload Page
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection