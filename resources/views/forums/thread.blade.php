@extends('layouts.main')

@section('title', $thread->title . ' - Forums')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">{{ $thread->title }}</h1>
        <div class="flex items-center text-gray-400 text-sm">
            <a href="{{ route('home') }}" class="hover:text-blue-400">Home</a>
            <span class="mx-2">&raquo;</span>
            <a href="{{ route('forums.index') }}" class="hover:text-blue-400">Forums</a>
            <span class="mx-2">&raquo;</span>
            <a href="{{ route('forums.show', $forum->slug) }}" class="hover:text-blue-400">{{ $forum->name }}</a>
            <span class="mx-2">&raquo;</span>
            <span>{{ $thread->title }}</span>
        </div>
    </div>

    <div class="mb-4 flex items-center justify-between">
        <div class="text-sm text-gray-400">
            <span><i class="fas fa-eye mr-1"></i> {{ $thread->view_count }} views</span>
            <span class="ml-4"><i class="fas fa-comments mr-1"></i> {{ $posts->total() }} {{ Str::plural('reply', $posts->total()) }}</span>
            @if($thread->is_sticky)
            <span class="ml-4 text-yellow-400"><i class="fas fa-thumbtack mr-1"></i> Sticky</span>
            @endif
            @if($thread->is_locked)
            <span class="ml-4 text-red-400"><i class="fas fa-lock mr-1"></i> Locked</span>
            @endif
        </div>
        <div>
            @auth
            @if(!$thread->is_locked)
            <a href="#reply" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-reply mr-2"></i> Reply
            </a>
            @endif
            @else
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-sign-in-alt mr-1"></i> Login to reply
            </a>
            @endauth
        </div>
    </div>

    <div class="space-y-6">
        @foreach($posts as $post)
        <div id="post-{{ $post->id }}" class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                <div class="text-gray-300">
                    <span class="font-semibold">{{ $post->user->name }}</span>
                    @if($post->is_first_post)
                    <span class="ml-2 bg-blue-600 text-xs text-white px-2 py-1 rounded">OP</span>
                    @endif
                    @if($post->user->hasRole('Admin'))
                    <span class="ml-2 bg-red-600 text-xs text-white px-2 py-1 rounded">Admin</span>
                    @elseif($post->user->hasRole('Moderator'))
                    <span class="ml-2 bg-green-600 text-xs text-white px-2 py-1 rounded">Mod</span>
                    @endif
                </div>
                <div class="text-sm text-gray-400">
                    {{ $post->created_at->format('M d, Y g:i A') }}
                </div>
            </div>
            <div class="p-6">
                <div class="flex">
                    <div class="w-32 flex-shrink-0 text-center">
                        <div class="w-20 h-20 mx-auto bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-3xl text-gray-500"></i>
                        </div>
                        <div class="mt-2 text-sm text-gray-400">
                            <div>Posts: {{ $post->user->posts()->count() }}</div>
                            <div>Joined: {{ $post->user->created_at->format('M Y') }}</div>
                        </div>
                    </div>
                    <div class="flex-grow pl-6 text-gray-300">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $posts->links('components.pagination') }}
    </div>

    @auth
    @if(!$thread->is_locked)
    <div id="reply" class="mt-8 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gray-700 px-4 py-3">
            <h2 class="text-lg font-bold text-white">Post a Reply</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('forums.store_post', ['forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="content" rows="6" class="w-full px-3 py-2 text-gray-300 border rounded-lg bg-gray-700 border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Write your reply here...">{{ old('content') }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                        <i class="fas fa-paper-plane mr-2"></i> Post Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endauth
</div>
@endsection