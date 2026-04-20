@extends('layouts.main')

@section('title', 'Community Forums')

@php
    $categoryColors = ['indigo','sky','emerald','amber','rose','violet','teal','orange'];
@endphp

@section('content')
<div class="pt-16 min-h-screen bg-slate-950">
    <div class="bg-slate-900 border-b border-slate-800/70">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-sm text-slate-500 mb-3">
                    <a href="{{ route('home') }}" class="hover:text-slate-300"><i class="fas fa-home"></i></a>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-300">Forums</span>
                </nav>
                <h1 class="text-2xl font-bold text-white tracking-tight">Community Forums</h1>
                <p class="text-slate-400 text-sm mt-1">Discussions, announcements &amp; support</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 text-sm text-slate-400">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>
                        <strong class="text-emerald-400">{{ App\Models\User::where('updated_at', '>=', now()->subMinutes(15))->count() }}</strong>
                        online
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">
        @forelse($categories as $i => $category)
            @php $color = $categoryColors[$i % count($categoryColors)]; @endphp
            <div class="rounded-xl overflow-hidden border border-slate-800 shadow-xl">

                <div class="bg-slate-900 px-5 py-3.5 flex items-center gap-3 border-b border-slate-800">
                    <span class="w-1 h-5 rounded-full bg-{{ $color }}-500 flex-shrink-0"></span>
                    <h2 class="text-slate-100 font-semibold text-sm uppercase tracking-widest">{{ $category->name }}</h2>
                    <span class="ml-auto text-xs text-slate-500">{{ $category->subforums->count() }} {{ Str::plural('section', $category->subforums->count()) }}</span>
                </div>

                @forelse($category->subforums as $forum)
                <div class="flex items-center gap-4 px-5 py-4 bg-slate-900/60 hover:bg-slate-800/60 border-b border-slate-800/50 last:border-b-0">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-{{ $color }}-600/15 border border-{{ $color }}-500/20 flex items-center justify-center">
                        <i class="fas fa-comments text-{{ $color }}-400 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('forum.show', $forum->slug) }}"
                           class="text-slate-100 font-semibold text-sm hover:text-{{ $color }}-400">
                            {{ $forum->name }}
                        </a>
                        @if($forum->description)
                            <p class="text-slate-500 text-xs mt-0.5 truncate">{{ $forum->description }}</p>
                        @endif
                    </div>
                    <div class="hidden md:flex flex-shrink-0 items-center gap-5 text-xs text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-comment-alt text-slate-600"></i>
                            <strong class="text-slate-300">{{ number_format($forum->threads_count) }}</strong>
                            {{ Str::plural('thread', $forum->threads_count) }}
                        </span>
                    </div>
                    <div class="hidden lg:block flex-shrink-0 w-52 text-right">
                        @if($forum->latestThread)
                            <a href="{{ route('forum.thread', ['forumSlug' => $forum->slug, 'threadSlug' => $forum->latestThread->slug]) }}"
                               class="text-slate-300 text-xs font-medium hover:text-{{ $color }}-400 line-clamp-1 block">
                                {{ Str::limit($forum->latestThread->title, 38) }}
                            </a>
                            <div class="flex items-center justify-end gap-1.5 mt-1">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($forum->latestThread->user->name ?? '?') }}&background=334155&color=94a3b8&size=24&bold=true"
                                     alt="{{ $forum->latestThread->user->name ?? '' }}"
                                     class="w-4 h-4 rounded-full">
                                <span class="text-slate-500 text-xs">{{ $forum->latestThread->updated_at->diffForHumans(null, true) }}</span>
                            </div>
                        @else
                            <span class="text-slate-600 text-xs italic">No threads yet</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-5 py-6 text-center text-slate-600 text-sm bg-slate-900/40">No forums in this category yet.</div>
                @endforelse
            </div>
        @empty
        <div class="rounded-xl border border-slate-800 bg-slate-900/50 px-8 py-16 text-center">
            <i class="fas fa-comments text-4xl text-slate-700 mb-4"></i>
            <p class="text-slate-400 font-medium">No forums available yet.</p>
        </div>
        @endforelse

        <div class="flex flex-wrap gap-5 items-center justify-center py-3 border-t border-slate-800/50 text-sm text-slate-500">
            <span class="flex items-center gap-2">
                <i class="fas fa-users text-indigo-500/70"></i>
                <strong class="text-slate-300">{{ number_format(App\Models\User::count()) }}</strong> members
            </span>
            <span class="text-slate-700">·</span>
            <span class="flex items-center gap-2">
                <i class="fas fa-comment-dots text-sky-500/70"></i>
                <strong class="text-slate-300">{{ number_format(\Modules\Forum\Domain\Models\Thread::count()) }}</strong> threads
            </span>
            <span class="text-slate-700">·</span>
            <span class="flex items-center gap-2">
                <i class="fas fa-comment text-emerald-500/70"></i>
                <strong class="text-slate-300">{{ number_format(\Modules\Forum\Domain\Models\Post::count()) }}</strong> posts
            </span>
        </div>
    </div>
</div>
@endsection
