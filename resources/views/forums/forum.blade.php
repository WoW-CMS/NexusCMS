@extends('layouts.main')

@section('title', $forum->name . ' - Forums')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">{{ $forum->name }}</h1>
        <div class="flex items-center text-gray-400 text-sm">
            <a href="{{ route('home') }}" class="hover:text-blue-400">Home</a>
            <span class="mx-2">&raquo;</span>
            <a href="{{ route('forums.index') }}" class="hover:text-blue-400">Forums</a>
            <span class="mx-2">&raquo;</span>
            <span>{{ $forum->name }}</span>
        </div>
        <p class="text-gray-300 mt-2">{{ $forum->description }}</p>
    </div>

    <div class="flex justify-between items-center mb-4">
        <div>
            @if($forum->subforums->count() > 0)
            <div class="text-sm text-gray-400">
                <span class="font-semibold">Subforums:</span>
                @foreach($forum->subforums as $subforum)
                <a href="{{ route('forums.show', $subforum->slug) }}" class="text-blue-400 hover:text-blue-300 ml-2">
                    {{ $subforum->name }}
                </a>
                @if(!$loop->last), @endif
                @endforeach
            </div>
            @endif
        </div>
        @auth
        <a href="{{ route('forums.create_thread', $forum->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i> New Thread
        </a>
        @else
        <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-sign-in-alt mr-1"></i> Login to create a thread
        </a>
        @endauth
    </div>

    @if($threads->count() > 0)
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gray-700 px-4 py-3 flex">
            <div class="flex-grow">
                <h2 class="text-lg font-bold text-white">Threads</h2>
            </div>
            <div class="text-right text-sm text-gray-300 w-32">Last Post</div>
        </div>
        
        <div class="divide-y divide-gray-700">
            @foreach($threads as $thread)
            <div class="p-4 hover:bg-gray-750 transition duration-150 flex">
                <div class="flex-grow">
                    <div class="flex items-start">
                        <div class="text-blue-400 mr-3">
                            @if($thread->is_sticky)
                            <i class="fas fa-thumbtack text-yellow-400"></i>
                            @elseif($thread->is_locked)
                            <i class="fas fa-lock text-red-400"></i>
                            @else
                            <i class="fas fa-comment-alt"></i>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('forums.thread', ['forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" class="text-lg font-semibold text-blue-400 hover:text-blue-300">
                                {{ $thread->title }}
                            </a>
                            <div class="text-gray-400 text-sm mt-1">
                                Started by {{ $thread->user->name }}, {{ $thread->created_at->format('M d, Y') }}
                                <span class="ml-2 text-gray-500">
                                    <i class="fas fa-eye mr-1"></i> {{ $thread->view_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right text-sm text-gray-400 w-32">
                    @if($thread->latestPost)
                    <div>
                        by {{ $thread->latestPost->user->name }}
                    </div>
                    <div class="text-xs mt-1">
                        {{ $thread->latestPost->created_at->diffForHumans() }}
                    </div>
                    @else
                    <div>No replies yet</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        {{ $threads->links('components.pagination') }}
    </div>
    @else
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 text-center">
        <p class="text-gray-400 text-lg">No threads have been created in this forum yet.</p>
        @auth
        <a href="{{ route('forums.create_thread', $forum->slug) }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i> Be the first to create a thread
        </a>
        @endauth
    </div>
    @endif
</div>
@endsection