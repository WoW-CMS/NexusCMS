@extends('layouts.main')

@section('title', 'Forums')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.0/dist/typography.min.css">
<style>
    .forum-card {
        transition: all 0.3s ease;
    }
    .forum-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1);
    }
    .category-header {
        position: relative;
        overflow: hidden;
    }
    .category-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 30%;
        height: 100%;
        background: linear-gradient(90deg, rgba(30, 58, 138, 0) 0%, rgba(30, 58, 138, 0.3) 100%);
        pointer-events: none;
    }
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(59, 130, 246, 0.15);
    }
    .forum-icon {
        transition: all 0.3s ease;
    }
    .forum-card:hover .forum-icon {
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-3 text-shadow">Community Forums</h1>
        <div class="flex items-center text-gray-400 text-sm mb-6">
            <a href="{{ route('home') }}" class="hover:text-blue-400 transition duration-200">Home</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <span class="text-gray-300">Forums</span>
        </div>
        
        <div class="bg-gradient-to-r from-gray-800 to-gray-750 rounded-lg p-5 border border-gray-700 shadow-lg">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-4 mb-3 sm:mb-0">
                    <div class="text-blue-400 bg-blue-900 bg-opacity-30 p-3 rounded-full">
                        <i class="fas fa-info-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">Welcome to our community!</h3>
                        <p class="text-gray-300">Join the discussion and connect with other members in our forums.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 bg-gray-750 px-4 py-2 rounded-lg border border-gray-700">
                    <span class="text-gray-300 text-sm font-medium flex items-center">
                        <i class="fas fa-users mr-2 text-blue-400"></i> 
                        <span class="text-blue-400 font-bold mr-1">{{ App\Models\User::where('updated_at', '>=', now()->subMinutes(15))->count() }}</span> online now
                    </span>
                </div>
            </div>
        </div>
    </div>

    @foreach($categories as $category)
    <div class="bg-gray-800 rounded-lg shadow-xl mb-8 overflow-hidden border border-gray-700">
        <div class="bg-gradient-to-r from-blue-900 to-gray-800 px-6 py-4 category-header">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-folder mr-3 text-blue-400"></i>
                {{ $category->name }}
            </h2>
        </div>
        
        <div class="divide-y divide-gray-700">
            @foreach($category->subforums as $forum)
            <div class="p-5 hover:bg-gray-750 transition duration-300 forum-card">
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="text-blue-400 mr-5 mb-3 md:mb-0">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-900 to-blue-800 bg-opacity-30 flex items-center justify-center border border-blue-700 shadow-md forum-icon">
                            <i class="fas fa-comments text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow mb-3 md:mb-0">
                        <a href="{{ route('forums.show', $forum->slug) }}" class="text-xl font-semibold text-blue-400 hover:text-blue-300 transition duration-200 flex items-center">
                            {{ $forum->name }}
                            <span class="ml-2 text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded-full">
                                {{ $forum->threads()->count() }} {{ Str::plural('thread', $forum->threads()->count()) }}
                            </span>
                        </a>
                        <p class="text-gray-400 text-sm mt-2">{{ $forum->description }}</p>
                    </div>
                    <div class="text-sm text-gray-400 md:text-right md:ml-4 bg-gradient-to-r from-gray-750 to-gray-700 rounded-lg p-3 border border-gray-700 shadow-md">
                        @if($forum->latest_thread)
                        <div class="flex items-center md:justify-end mb-1">
                            <span class="text-gray-500 mr-2">Latest:</span>
                            <a href="{{ route('forums.thread', ['forumSlug' => $forum->slug, 'threadSlug' => $forum->latestThread->slug]) }}" class="text-blue-400 hover:text-blue-300 transition duration-200 font-medium">
                                {{ Str::limit($forum->latestThread->title, 30) }}
                            </a>
                        </div>
                        <div class="flex items-center md:justify-end">
                            <span class="text-gray-500 mr-2">by</span>
                            <span class="font-medium">{{ $forum->latestThread->user->name }}</span>
                            <span class="text-xs ml-2 text-gray-500">{{ $forum->latestThread->created_at->diffForHumans() }}</span>
                        </div>
                        @else
                        <div class="text-center flex items-center justify-center text-gray-500">
                            <i class="fas fa-comment-slash mr-2"></i> No threads yet
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="mt-10 bg-gradient-to-r from-gray-800 to-gray-750 rounded-lg p-6 border border-gray-700 shadow-xl">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center mb-6 md:mb-0">
                <div class="text-blue-400 mr-4 bg-blue-900 bg-opacity-30 p-3 rounded-full border border-blue-800 shadow-md">
                    <i class="fas fa-chart-bar text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Forum Statistics</h3>
                    <p class="text-gray-400 text-sm">Join our growing community</p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-center w-full md:w-auto">
                <div class="bg-gradient-to-br from-gray-750 to-gray-700 rounded-lg p-4 border border-gray-700 shadow-md stat-card">
                    <div class="text-3xl font-bold text-blue-400 mb-1">{{ App\Models\User::count() }}</div>
                    <div class="text-gray-300 text-sm font-medium flex items-center justify-center">
                        <i class="fas fa-users mr-2 text-blue-500"></i> Members
                    </div>
                </div>
                <div class="bg-gradient-to-br from-gray-750 to-gray-700 rounded-lg p-4 border border-gray-700 shadow-md stat-card">
                    <div class="text-3xl font-bold text-green-400 mb-1">{{ App\Models\Thread::count() }}</div>
                    <div class="text-gray-300 text-sm font-medium flex items-center justify-center">
                        <i class="fas fa-comments mr-2 text-green-500"></i> Threads
                    </div>
                </div>
                <div class="bg-gradient-to-br from-gray-750 to-gray-700 rounded-lg p-4 border border-gray-700 shadow-md stat-card">
                    <div class="text-3xl font-bold text-purple-400 mb-1">{{ App\Models\Post::count() }}</div>
                    <div class="text-gray-300 text-sm font-medium flex items-center justify-center">
                        <i class="fas fa-comment-dots mr-2 text-purple-500"></i> Posts
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection