@extends('layouts.main')

@section('title', 'Forums')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">Forums</h1>
        <div class="flex items-center text-gray-400 text-sm">
            <a href="{{ route('home') }}" class="hover:text-blue-400">Home</a>
            <span class="mx-2">&raquo;</span>
            <span>Forums</span>
        </div>
    </div>

    @foreach($categories as $category)
    <div class="bg-gray-800 rounded-lg shadow-lg mb-6 overflow-hidden">
        <div class="bg-gray-700 px-4 py-3">
            <h2 class="text-xl font-bold text-white">{{ $category->name }}</h2>
        </div>
        
        <div class="divide-y divide-gray-700">
            @foreach($category->subforums as $forum)
            <div class="p-4 hover:bg-gray-750 transition duration-150">
                <div class="flex items-start">
                    <div class="text-blue-400 mr-4">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <div class="flex-grow">
                        <a href="{{ route('forums.show', $forum->slug) }}" class="text-lg font-semibold text-blue-400 hover:text-blue-300">
                            {{ $forum->name }}
                        </a>
                        <p class="text-gray-400 text-sm mt-1">{{ $forum->description }}</p>
                    </div>
                    <div class="text-right text-sm text-gray-400">
                        @if($forum->latest_thread)
                        <div>
                            <span>Latest: </span>
                            <a href="{{ route('forums.thread', ['forumSlug' => $forum->slug, 'threadSlug' => $forum->latestThread->slug]) }}" class="text-blue-400 hover:text-blue-300">
                                {{ Str::limit($forum->latestThread->title, 30) }}
                            </a>
                        </div>
                        <div class="mt-1">
                            by {{ $forum->latestThread->user->name }}
                            <span class="text-xs">{{ $forum->latestThread->created_at->diffForHumans() }}</span>
                        </div>
                        @else
                        <div>No threads yet</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="mt-8 text-center">
        <p class="text-gray-400">
            <i class="fas fa-users mr-2"></i> Currently online: {{ App\Models\User::where('updated_at', '>=', now()->subMinutes(15))->count() }} users
        </p>
    </div>
</div>
@endsection