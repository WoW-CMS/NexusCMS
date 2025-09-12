@extends('layouts.main')

@section('title', 'Create New Thread - ' . $forum->name)

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.0/dist/typography.min.css">
@endsection

@section('scripts')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-3 text-shadow">Create New Thread</h1>
        <div class="flex flex-wrap items-center text-gray-400 text-sm mb-6">
            <a href="{{ route('home') }}" class="hover:text-blue-400 transition duration-200">Home</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <a href="{{ route('forums') }}" class="hover:text-blue-400 transition duration-200">Forums</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <a href="{{ route('forums.show', $forum->slug) }}" class="hover:text-blue-400 transition duration-200">{{ $forum->name }}</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <span class="text-gray-300">Create Thread</span>
        </div>
        <div class="bg-gray-800 bg-opacity-70 rounded-lg p-4 border border-gray-700 shadow-md mb-6">
            <div class="flex items-center space-x-4">
                <div class="text-blue-400">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <p class="text-gray-300">You are creating a new thread in <span class="text-blue-400 font-medium">{{ $forum->name }}</span>. Please provide a descriptive title and detailed content.</p>
            </div>
        </div>

    <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700 form-card">
        <div class="bg-gradient-to-r from-blue-900 to-gray-800 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-pen-fancy mr-3 text-blue-400"></i>
                Thread Details
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('forums.store_thread', $forum->slug) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="title" class="block text-gray-300 font-medium mb-2 flex items-center">
                        <i class="fas fa-heading mr-2 text-blue-400"></i>
                        Thread Title
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg py-3 px-4 text-gray-300 focus:outline-none focus:border-blue-500 form-input transition duration-200" 
                        placeholder="Enter a descriptive title for your thread" required>
                    @error('title')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="editor" class="block text-gray-300 font-medium mb-2 flex items-center">
                        <i class="fas fa-paragraph mr-2 text-blue-400"></i>
                        Content
                    </label>
                    <div class="editor-container rounded-lg overflow-hidden transition duration-200" style="min-height: 300px;">
                        <div class="tiptap-toolbar" id="toolbar"></div>
                        <div class="tiptap-editor" id="editor" data-placeholder="Write your thread content here..."></div>
                    </div>
                    <input type="hidden" name="content" id="content-input">
                    @error('content')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-700">
                    <a href="{{ route('forums.show', $forum->slug) }}" class="bg-gray-700 hover:bg-gray-600 text-gray-300 font-bold py-3 px-6 rounded-lg mr-3 transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Create Thread
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
