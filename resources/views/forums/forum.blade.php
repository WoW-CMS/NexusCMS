@extends('layouts.main')

@section('title', $forum->name . ' - Forums')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.0/dist/typography.min.css">
<style>
    .thread-card {
        transition: all 0.3s ease;
    }
    .thread-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1);
    }
    .forum-header {
        position: relative;
        overflow: hidden;
    }
    .forum-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 30%;
        height: 100%;
        background: linear-gradient(90deg, rgba(30, 58, 138, 0) 0%, rgba(30, 58, 138, 0.3) 100%);
        pointer-events: none;
    }
    .subforum-tag {
        transition: all 0.3s ease;
    }
    .subforum-tag:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px -2px rgba(59, 130, 246, 0.2);
    }
    .thread-info {
        transition: all 0.3s ease;
    }
    .thread-card:hover .thread-info {
        background-color: rgba(30, 58, 138, 0.1);
    }
    .empty-state-icon {
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.5));
        transition: all 0.5s ease;
    }
    .empty-state:hover .empty-state-icon {
        transform: scale(1.1) rotate(10deg);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-3 text-shadow forum-header">{{ $forum->name }}</h1>
        <div class="flex flex-wrap items-center text-gray-400 text-sm mb-4">
            <a href="{{ route('home') }}" class="hover:text-blue-400 transition duration-200">Home</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <a href="{{ route('forums') }}" class="hover:text-blue-400 transition duration-200">Forums</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <span class="text-gray-300">{{ $forum->name }}</span>
        </div>        
        <div class="bg-gradient-to-r from-blue-900 to-gray-800 px-6 py-4 category-header">
            <div class="flex items-start">
                <div class="text-blue-400 mr-4 bg-blue-900 bg-opacity-30 p-3 rounded-full border border-blue-800 shadow-md hidden sm:block">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-info-circle text-blue-400 mr-2 sm:hidden"></i>
                        <h3 class="text-white font-medium">About this forum</h3>
                    </div>
                    <p class="text-gray-300">{{ $forum->description }}</p>
                    <div class="flex items-center mt-3 text-sm text-gray-400">
                        <span class="flex items-center mr-4">
                            <i class="fas fa-comments mr-1 text-blue-400"></i> 
                            {{ $threads->total() }} {{ Str::plural('thread', $threads->total()) }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-eye mr-1 text-green-400"></i> 
                            {{ $forum->threads->sum('view_count') }} views
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            @if($forum->subforums->count() > 0)
            <div class="bg-gradient-to-r from-gray-800 to-gray-750 rounded-lg p-4 border border-gray-700 shadow-lg">
                <h3 class="text-white font-semibold mb-3 flex items-center">
                    <i class="fas fa-folder-tree mr-2 text-blue-400"></i> Subforums
                </h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($forum->subforums as $subforum)
                    <a href="{{ route('forums.show', $subforum->slug) }}" class="inline-flex items-center bg-gradient-to-r from-gray-750 to-gray-700 hover:from-blue-900 hover:to-blue-800 text-blue-400 hover:text-white px-3 py-2 rounded-full text-sm transition duration-200 border border-gray-700 shadow-md subforum-tag">
                        <i class="fas fa-folder-open mr-2"></i>
                        {{ $subforum->name }}
                        <span class="ml-2 bg-gray-800 text-xs px-2 py-0.5 rounded-full">{{ $subforum->threads()->count() }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @auth
        <a href="{{ route('forums.create_thread', $forum->slug) }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 flex items-center shadow-lg">
            <i class="fas fa-plus mr-2"></i> New Thread
        </a>
        @else
        <a href="{{ route('login') }}" class="bg-gradient-to-r from-gray-700 to-gray-750 hover:from-gray-650 hover:to-gray-700 text-blue-400 hover:text-blue-300 font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center border border-gray-600 shadow-md">
            <i class="fas fa-sign-in-alt mr-2"></i> Login to create a thread
        </a>
        @endauth
    </div>

    @if($threads->count() > 0)
    <div class="space-y-4">
        @foreach($threads as $thread)
        <div class="bg-gradient-to-r from-gray-800 to-gray-750 rounded-lg shadow-lg overflow-hidden border border-gray-700 thread-card">
            <div class="p-4 md:p-5 flex flex-col md:flex-row gap-4">
                <!-- Thread Info -->                
                <div class="flex-grow">
                    <div class="flex flex-wrap items-center mb-2">
                        @if($thread->is_sticky)
                        <span class="bg-gradient-to-r from-yellow-900 to-yellow-800 text-yellow-300 text-xs px-2 py-1 rounded-full mr-2 flex items-center shadow-sm border border-yellow-800">
                            <i class="fas fa-thumbtack mr-1"></i> Sticky
                        </span>
                        @endif
                        @if($thread->is_locked)
                        <span class="bg-gradient-to-r from-red-900 to-red-800 text-red-300 text-xs px-2 py-1 rounded-full mr-2 flex items-center shadow-sm border border-red-800">
                            <i class="fas fa-lock mr-1"></i> Locked
                        </span>
                        @endif
                        <a href="{{ route('forums.thread', [$forum->slug, $thread->slug]) }}" class="text-blue-400 hover:text-blue-300 font-medium text-lg transition duration-200">
                            {{ $thread->title }}
                        </a>
                    </div>
                    <div class="flex flex-wrap items-center text-sm text-gray-400 mb-3 thread-info rounded-full py-1 px-2">
                        <span class="flex items-center">
                            <i class="fas fa-user-circle mr-1 text-blue-500"></i>
                            {{ $thread->user->name }}
                        </span>
                        <span class="mx-2 text-gray-600">•</span>
                        <span class="flex items-center">
                            <i class="far fa-clock mr-1 text-green-500"></i>
                            {{ $thread->created_at->diffForHumans() }}
                        </span>
                        <span class="mx-2 text-gray-600">•</span>
                        <span class="flex items-center">
                            <i class="far fa-eye mr-1 text-purple-500"></i>
                            {{ $thread->view_count }} views
                        </span>
                        <span class="mx-2 text-gray-600">•</span>
                        <span class="flex items-center">
                            <i class="far fa-comment mr-1 text-yellow-500"></i>
                            {{ $thread->posts()->count() - 1 }} {{ Str::plural('reply', $thread->posts()->count() - 1) }}
                        </span>
                    </div>
                </div>
                
                <!-- Latest Post Info -->                
                <div class="bg-gradient-to-r from-gray-750 to-gray-700 rounded-lg p-3 border border-gray-700 min-w-[200px] flex-shrink-0 shadow-md">
                    <div class="text-xs uppercase text-gray-500 font-semibold mb-1 flex items-center">
                        <i class="fas fa-reply-all mr-1 text-blue-400"></i> Latest Reply
                    </div>
                    @if($thread->latestPost && !$thread->latestPost->is_first_post)
                    <div class="text-sm">
                        <div class="text-gray-300 flex items-center">
                            <i class="far fa-clock mr-1 text-green-500"></i>
                            {{ $thread->latestPost->created_at->diffForHumans() }}
                        </div>
                        <div class="text-blue-400 flex items-center">
                            <i class="fas fa-user-circle mr-1 text-blue-500"></i>
                            {{ $thread->latestPost->user->name }}
                        </div>
                    </div>
                    @else
                    <div class="text-gray-500 text-sm italic flex items-center">
                        <i class="fas fa-comment-slash mr-1"></i> No replies yet
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $threads->links() }}
    </div>
    @else
    <div class="bg-gradient-to-r from-gray-800 to-gray-750 rounded-lg shadow-xl p-10 text-center border border-gray-700 empty-state">
        <div class="flex justify-center mb-6">
            <div class="relative">
                <i class="fas fa-comment-slash text-6xl text-gray-600 empty-state-icon"></i>
                <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-plus text-xs text-white"></i>
                </div>
            </div>
        </div>
        <h3 class="text-white text-2xl font-semibold mb-3">No threads yet</h3>
        <p class="text-gray-300 text-lg mb-8 max-w-lg mx-auto">This forum is waiting for its first conversation. Be the pioneer and start a discussion that others can join!</p>
        @auth
        <a href="{{ route('forums.create_thread', $forum->slug) }}" class="inline-block mt-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-8 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus mr-2"></i> Create the first thread
        </a>
        @else
        <div class="text-gray-400 mt-6 bg-gray-750 inline-block py-3 px-6 rounded-lg border border-gray-600 shadow-md">
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium">Sign in</a> to create a thread
        </div>
        @endauth
    </div>
    @endif
</div>
@endsection