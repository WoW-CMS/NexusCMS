@extends('layouts.main')

@section('title', $thread->title . ' - Forums')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.0/dist/typography.min.css">
<style>
    .post-card {
        transition: all 0.3s ease;
    }
    .post-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1);
    }
    .user-avatar {
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
    .user-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.2);
    }
    .editor-container {
        transition: all 0.3s ease;
    }
    .editor-container:focus-within {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-3 text-shadow">{{ $thread->title }}</h1>
        <div class="flex flex-wrap items-center text-gray-400 text-sm mb-4">
            <a href="{{ route('home') }}" class="hover:text-blue-400 transition duration-200">Home</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <a href="{{ route('forums') }}" class="hover:text-blue-400 transition duration-200">Forums</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <a href="{{ route('forums.show', $forum->slug) }}" class="hover:text-blue-400 transition duration-200">{{ $forum->name }}</a>
            <span class="mx-2 text-gray-600">&raquo;</span>
            <span class="text-gray-300">{{ $thread->title }}</span>
        </div>
        <div class="flex flex-wrap items-center justify-between bg-gradient-to-r from-blue-900 to-gray-800 rounded-lg p-4 border border-gray-700 shadow-lg">
            <div class="flex flex-wrap items-center text-sm text-gray-400 space-x-6">
                <span class="flex items-center bg-gray-750 px-3 py-1 rounded-full border border-gray-700">
                    <i class="fas fa-eye mr-2 text-blue-400"></i> {{ $thread->view_count }} views
                </span>
                <span class="flex items-center bg-gray-750 px-3 py-1 rounded-full border border-gray-700">
                    <i class="fas fa-comments mr-2 text-green-400"></i> {{ $posts->total() }} {{ Str::plural('reply', $posts->total()) }}
                </span>
                @if($thread->is_sticky)
                <span class="flex items-center bg-yellow-900 text-yellow-300 px-3 py-1 rounded-full border border-yellow-800">
                    <i class="fas fa-thumbtack mr-2"></i> Sticky
                </span>
                @endif
                @if($thread->is_locked)
                <span class="flex items-center bg-red-900 text-red-300 px-3 py-1 rounded-full border border-red-800">
                    <i class="fas fa-lock mr-2"></i> Locked
                </span>
                @endif
            </div>
            <div class="mt-3 sm:mt-0">
                @auth
                @if(!$thread->is_locked)
                <a href="#reply" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 transform hover:scale-105 flex items-center shadow-md">
                    <i class="fas fa-reply mr-2"></i> Reply
                </a>
                @endif
                @else
                <a href="{{ route('login') }}" class="bg-gray-700 hover:bg-gray-600 text-blue-400 hover:text-blue-300 font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center border border-gray-600">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login to reply
                </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="space-y-8">
        @foreach($posts as $post)
        <div id="post-{{ $post->id }}" class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700 transition duration-300 hover:shadow-blue-900/20 post-card">
            <div class="bg-gradient-to-r from-blue-900 to-gray-800 px-6 py-4 flex flex-wrap justify-between items-center border-b border-gray-700">
                <div class="text-gray-200 flex items-center">
                    <span class="font-semibold">{{ $post->user->name }}</span>
                    @if($post->is_first_post)
                    <span class="ml-2 bg-blue-600 text-xs text-white px-2 py-1 rounded-full font-medium shadow-sm">OP</span>
                    @endif
                    @if($post->user->hasRole('Admin'))
                    <span class="ml-2 bg-red-600 text-xs text-white px-2 py-1 rounded-full font-medium shadow-sm">Admin</span>
                    @elseif($post->user->hasRole('Moderator'))
                    <span class="ml-2 bg-green-600 text-xs text-white px-2 py-1 rounded-full font-medium shadow-sm">Mod</span>
                    @endif
                </div>
                <div class="text-sm text-gray-400 flex items-center bg-gray-750 px-3 py-1 rounded-full border border-gray-700">
                    <i class="far fa-clock mr-2"></i> {{ $post->created_at->format('M d, Y g:i A') }}
                </div>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-48 flex-shrink-0 text-center mb-6 md:mb-0">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gray-700 to-gray-600 rounded-full flex items-center justify-center border-4 border-gray-600 shadow-lg user-avatar">
                            <i class="fas fa-user text-3xl text-gray-300"></i>
                        </div>
                        <div class="mt-4 text-sm text-gray-400 bg-gradient-to-r from-gray-750 to-gray-700 rounded-lg py-3 px-4 space-y-2 border border-gray-600 shadow-md">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Posts:</span>
                                <span class="font-medium">{{ $post->user->posts()->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Joined:</span>
                                <span class="font-medium">{{ $post->user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow md:pl-8 text-white max-w-none">
                        {!! $post->content !!}
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
    <div id="reply" class="mt-10 bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700 post-card">
        <div class="bg-gradient-to-r from-blue-900 to-gray-800 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-reply mr-3 text-blue-400"></i>
                Post a Reply
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('forums.store_post', ['forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <div class="editor-container rounded-lg overflow-hidden transition duration-200" style="min-height: 300px;">
                        <div class="tiptap-toolbar" id="toolbar"></div>
                        <div class="tiptap-editor" id="reply-editor" data-placeholder="Write your reply here..."></div>
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
                    <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 flex items-center shadow-lg">
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
