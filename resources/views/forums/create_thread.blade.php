@extends('layouts.main')

@section('title', 'Create New Thread - ' . $forum->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Create New Thread</h1>
        <div class="flex items-center text-gray-400 text-sm">
            <a href="{{ route('home') }}" class="hover:text-blue-400">Home</a>
            <span class="mx-2">&raquo;</span>
            <a href="{{ route('forums.index') }}" class="hover:text-blue-400">Forums</a>
            <span class="mx-2">&raquo;</span>
            <a href="{{ route('forums.show', $forum->slug) }}" class="hover:text-blue-400">{{ $forum->name }}</a>
            <span class="mx-2">&raquo;</span>
            <span>Create Thread</span>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gray-700 px-4 py-3">
            <h2 class="text-lg font-bold text-white">New Thread in {{ $forum->name }}</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('forums.store_thread', $forum->slug) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-gray-300 text-sm font-bold mb-2">Thread Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full px-3 py-2 text-gray-300 border rounded-lg bg-gray-700 border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter a descriptive title for your thread">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="content" class="block text-gray-300 text-sm font-bold mb-2">Content</label>
                    <textarea name="content" id="content" rows="10" class="w-full px-3 py-2 text-gray-300 border rounded-lg bg-gray-700 border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Write your post content here...">{{ old('content') }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <a href="{{ route('forums.show', $forum->slug) }}" class="text-blue-400 hover:text-blue-300">
                        <i class="fas fa-arrow-left mr-1"></i> Back to {{ $forum->name }}
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                        <i class="fas fa-plus mr-2"></i> Create Thread
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection