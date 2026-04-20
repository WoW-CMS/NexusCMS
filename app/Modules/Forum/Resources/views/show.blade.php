@extends('layouts.main')

@section('title', $forum->name)

@section('content')
<div class="pt-16 min-h-screen bg-slate-950">
    <div class="bg-slate-900 border-b border-slate-800/70">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6">
            <nav class="flex items-center gap-1.5 text-sm text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-slate-300"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('forum.index') }}" class="hover:text-slate-300">Forums</a>
                @if($forum->parent)
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-500">{{ $forum->parent->name }}</span>
                @endif
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-slate-300">{{ $forum->name }}</span>
            </nav>
            <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $forum->name }}</h1>
                    @if($forum->description)
                        <p class="text-slate-400 text-sm mt-0.5">{{ $forum->description }}</p>
                    @endif
                </div>
                @auth
                <a href="{{ route('forum.create-thread', $forum->slug) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-lg shadow-indigo-900/30 flex-shrink-0">
                    <i class="fas fa-plus text-xs"></i>
                    New Thread
                </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6">
        @if($forum->subforums->isNotEmpty())
        <div class="mb-6 rounded-xl border border-slate-800 overflow-hidden">
            <div class="bg-slate-900 px-5 py-3 border-b border-slate-800 flex items-center gap-2">
                <i class="fas fa-sitemap text-slate-500 text-xs"></i>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Subforums</span>
            </div>
            @foreach($forum->subforums as $sub)
            <a href="{{ route('forum.show', $sub->slug) }}"
               class="flex items-center gap-3 px-5 py-3.5 bg-slate-900/60 hover:bg-slate-800/50 border-b border-slate-800/50 last:border-b-0">
                <i class="fas fa-comments text-indigo-400/60 text-sm flex-shrink-0"></i>
                <span class="text-slate-200 text-sm font-medium">{{ $sub->name }}</span>
                @if($sub->description)
                    <span class="text-slate-500 text-xs truncate">— {{ $sub->description }}</span>
                @endif
                <span class="ml-auto text-slate-600 text-xs">{{ $sub->threads_count }} threads</span>
            </a>
            @endforeach
        </div>
        @endif

        @if($threads->isEmpty())
        <div class="rounded-xl border border-slate-800 bg-slate-900/50 px-8 py-16 text-center">
            <i class="fas fa-comment-slash text-4xl text-slate-700 mb-4"></i>
            <p class="text-slate-400 font-medium">No threads yet.</p>
            @auth
            <a href="{{ route('forum.create-thread', $forum->slug) }}"
               class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg">
                <i class="fas fa-plus text-xs"></i> Start the first thread
            </a>
            @else
            <p class="text-slate-600 text-sm mt-1"><a href="{{ route('login') }}" class="text-indigo-400 hover:underline">Log in</a> to create a thread.</p>
            @endauth
        </div>
        @else

        <div class="hidden sm:grid grid-cols-[1fr_auto_auto_auto] gap-4 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-slate-600 border-b border-slate-800 mb-1">
            <span>Thread</span>
            <span class="text-center w-16">Replies</span>
            <span class="text-center w-16">Views</span>
            <span class="text-right w-44">Last post</span>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden">
            @foreach($threads as $thread)
            <div class="flex items-center gap-4 px-4 py-4 bg-slate-900/60 hover:bg-slate-800/50 border-b border-slate-800/50 last:border-b-0">
                <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                    {{ $thread->is_locked ? 'bg-slate-800 text-slate-500' : 'bg-indigo-600/15 text-indigo-400' }}">
                    @if($thread->is_sticky)
                        <i class="fas fa-thumbtack text-amber-400 text-xs"></i>
                    @elseif($thread->is_locked)
                        <i class="fas fa-lock text-xs"></i>
                    @else
                        <i class="fas fa-comment text-xs"></i>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center flex-wrap gap-2">
                        @if($thread->is_sticky)
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-amber-500/15 text-amber-400 text-[10px] font-semibold rounded uppercase tracking-wide">
                                <i class="fas fa-thumbtack text-[9px]"></i> Pinned
                            </span>
                        @endif
                        @if($thread->is_locked)
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-slate-700/60 text-slate-400 text-[10px] font-semibold rounded uppercase tracking-wide">
                                <i class="fas fa-lock text-[9px]"></i> Locked
                            </span>
                        @endif
                        <a href="{{ route('forum.thread', ['forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}"
                           class="text-slate-100 font-semibold text-sm hover:text-indigo-400 truncate">
                            {{ $thread->title }}
                        </a>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($thread->user->name ?? '?') }}&background=334155&color=94a3b8&size=24&bold=true"
                             class="w-4 h-4 rounded-full" alt="{{ $thread->user->name ?? '' }}">
                        <span class="text-slate-500 text-xs">
                            by <span class="text-slate-400">{{ $thread->user->name ?? 'Unknown' }}</span>
                            · {{ $thread->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <div class="hidden sm:flex flex-shrink-0 w-16 flex-col items-center gap-0.5">
                    <span class="text-slate-200 font-semibold text-sm">{{ max(0, ($thread->posts_count ?? 1) - 1) }}</span>
                    <span class="text-slate-600 text-[10px]">replies</span>
                </div>
                <div class="hidden sm:flex flex-shrink-0 w-16 flex-col items-center gap-0.5">
                    <span class="text-slate-200 font-semibold text-sm">{{ number_format($thread->view_count) }}</span>
                    <span class="text-slate-600 text-[10px]">views</span>
                </div>
                <div class="hidden sm:block flex-shrink-0 w-44 text-right">
                    @if($thread->latestPost && $thread->latestPost->user)
                        <div class="flex items-center justify-end gap-2">
                            <div class="min-w-0">
                                <span class="text-slate-400 text-xs truncate block">{{ $thread->latestPost->user->name }}</span>
                                <span class="text-slate-600 text-xs">{{ $thread->latestPost->created_at->diffForHumans() }}</span>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($thread->latestPost->user->name) }}&background=334155&color=94a3b8&size=24&bold=true"
                                 class="w-6 h-6 rounded-full flex-shrink-0" alt="{{ $thread->latestPost->user->name }}">
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($threads->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $threads->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
