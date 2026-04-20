@extends('layouts.main')

@section('title', $thread->title)

@section('content')
<div class="pt-16 min-h-screen bg-slate-950">
    <div class="bg-slate-900 border-b border-slate-800/70">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-5">
            <nav class="flex items-center flex-wrap gap-1.5 text-sm text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-slate-300"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('forum.index') }}" class="hover:text-slate-300">Forums</a>
                @if($forum->parent)
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-500">{{ $forum->parent->name }}</span>
                @endif
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('forum.show', $forum->slug) }}" class="hover:text-slate-300">{{ $forum->name }}</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-slate-300 truncate max-w-xs">{{ $thread->title }}</span>
            </nav>

            <div class="flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center flex-wrap gap-2 mb-1">
                        @if($thread->is_sticky)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-500/15 text-amber-400 text-xs font-semibold rounded">
                                <i class="fas fa-thumbtack text-[10px]"></i> Pinned
                            </span>
                        @endif
                        @if($thread->is_locked)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-700 text-slate-400 text-xs font-semibold rounded">
                                <i class="fas fa-lock text-[10px]"></i> Locked
                            </span>
                        @endif
                    </div>
                    <h1 class="text-lg font-bold text-white leading-tight">{{ $thread->title }}</h1>
                    <p class="text-slate-500 text-xs mt-1">
                        {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}
                        · {{ number_format($thread->view_count) }} views
                    </p>
                </div>
                @if($posts->currentPage() === $posts->lastPage() && !$thread->is_locked)
                    @auth
                    <a href="#reply-form"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg flex-shrink-0">
                        <i class="fas fa-reply text-xs"></i> Reply
                    </a>
                    @endauth
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="max-w-4xl mx-auto px-4 sm:px-6 mt-4">
        <div class="flex items-center gap-3 bg-emerald-900/30 border border-emerald-700/50 text-emerald-300 text-sm px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle text-emerald-400"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 space-y-3">

        @foreach($posts as $post)
        @php $isOP = $post->is_first_post; @endphp
        <div id="post-{{ $post->id }}"
             class="flex gap-0 rounded-xl border {{ $isOP ? 'border-indigo-800/40' : 'border-slate-800' }} overflow-hidden">
            <div class="flex-shrink-0 w-36 sm:w-44 bg-slate-900 border-r {{ $isOP ? 'border-indigo-800/30' : 'border-slate-800' }} px-4 py-5 flex flex-col items-center gap-3 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? '?') }}&background={{ $isOP ? '4f46e5' : '1e293b' }}&color={{ $isOP ? 'ffffff' : '94a3b8' }}&size=80&bold=true"
                     alt="{{ $post->user->name ?? '' }}"
                     class="w-12 h-12 sm:w-14 sm:h-14 rounded-full ring-2 {{ $isOP ? 'ring-indigo-500/50' : 'ring-slate-700' }}">
                <div>
                    <p class="text-slate-100 font-semibold text-xs sm:text-sm leading-tight">{{ $post->user->name ?? 'Deleted user' }}</p>
                    @if($post->user && $post->user->roles->isNotEmpty())
                        <span class="inline-block mt-1 px-2 py-0.5 bg-indigo-600/20 text-indigo-400 text-[10px] rounded font-medium">
                            {{ $post->user->roles->first()->name }}
                        </span>
                    @elseif($isOP)
                        <span class="inline-block mt-1 px-2 py-0.5 bg-slate-700 text-slate-400 text-[10px] rounded font-medium">
                            Author
                        </span>
                    @endif
                </div>
                @if($post->user)
                <div class="text-[10px] text-slate-600 space-y-0.5">
                    <p>Joined {{ $post->user->created_at->format('M Y') }}</p>
                </div>
                @endif
                <div class="mt-auto pt-2 text-[10px] text-slate-600 border-t border-slate-800 w-full text-center">
                    #{{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}
                </div>
            </div>

            <div class="flex-1 min-w-0 flex flex-col {{ $isOP ? 'bg-slate-900/70' : 'bg-slate-900/40' }}">
                <div class="flex items-center justify-between gap-3 px-5 py-3 border-b {{ $isOP ? 'border-indigo-800/30' : 'border-slate-800/70' }}">
                    <span class="text-slate-500 text-xs">
                        <i class="far fa-clock mr-1"></i>
                        {{ $post->created_at->format('M j, Y \a\t g:i A') }}
                        @if($post->updated_at->ne($post->created_at))
                            <span class="text-slate-600 ml-2">(edited {{ $post->updated_at->diffForHumans() }})</span>
                        @endif
                    </span>
                    @auth
                    @if(!$thread->is_locked)
                    <button
                        onclick="document.getElementById('reply-form').scrollIntoView({behavior:'smooth'}); document.getElementById('reply-content').value += '{{ '@' }}{{ addslashes($post->user->name ?? '') }}: ';"
                        class="text-slate-600 hover:text-slate-300 text-xs flex items-center gap-1">
                        <i class="fas fa-reply text-[10px]"></i> Quote
                    </button>
                    @endif
                    @endauth
                </div>
                <div class="px-5 py-4 prose prose-sm prose-invert max-w-none flex-1
                            prose-p:text-slate-300 prose-a:text-indigo-400 prose-strong:text-slate-100
                            prose-code:bg-slate-800 prose-code:text-indigo-300 prose-code:rounded prose-code:px-1
                            prose-pre:bg-slate-800 prose-pre:border prose-pre:border-slate-700">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>
        </div>
        @endforeach

        @if($posts->hasPages())
        <div class="flex justify-center pt-2">
            {{ $posts->links() }}
        </div>
        @endif

        @if($thread->is_locked)
        <div class="rounded-xl border border-slate-800 bg-slate-900/50 px-6 py-8 text-center mt-4">
            <i class="fas fa-lock text-2xl text-slate-600 mb-3"></i>
            <p class="text-slate-400 text-sm font-medium">This thread is locked. No new replies can be posted.</p>
        </div>
        @else
            @auth
            <div id="reply-form" class="rounded-xl border border-slate-800 bg-slate-900 overflow-hidden mt-4">
                <div class="px-5 py-3.5 border-b border-slate-800 flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=ffffff&size=32&bold=true"
                         class="w-7 h-7 rounded-full" alt="{{ Auth::user()->name }}">
                    <span class="text-slate-300 text-sm font-medium">{{ Auth::user()->name }}</span>
                    <span class="text-slate-600 text-xs ml-1">— Post a reply</span>
                </div>
                <form action="{{ route('forum.store-reply', ['forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" method="POST" class="p-5">
                    @csrf
                    <textarea
                        id="reply-content"
                        name="content"
                        rows="5"
                        placeholder="Write your reply here…"
                        class="w-full bg-slate-800/60 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 text-sm placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 resize-y"
                        required minlength="2">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end mt-3">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg">
                            <i class="fas fa-paper-plane text-xs"></i> Post Reply
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="rounded-xl border border-slate-800 bg-slate-900/50 px-6 py-8 text-center mt-4">
                <p class="text-slate-400 text-sm">
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:underline font-medium">Log in</a>
                    or <a href="{{ route('register') }}" class="text-indigo-400 hover:underline font-medium">register</a> to reply.
                </p>
            </div>
            @endauth
        @endif
    </div>
</div>
@endsection
