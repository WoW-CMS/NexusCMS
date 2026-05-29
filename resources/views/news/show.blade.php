@extends('layouts.main')

@section('content')

{{-- ── Hero: imagen de fondo + cabecera del artículo ─────────────────────── --}}
<section class="relative min-h-[60vh] flex flex-col justify-end overflow-hidden">

    {{-- Imagen de fondo (o gradiente si no hay imagen) --}}
    @if($item->image)
    <div class="absolute inset-0">
        <img src="{{ asset('storage/images/' . $item->image) }}"
             alt="{{ $item->display_title }}"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-slate-950/20"></div>
    </div>
    @else
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950/40"></div>
    @endif

    {{-- Breadcrumb --}}
    <div class="relative z-10 pt-28 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span>›</span>
                <a href="{{ route('news') }}" class="hover:text-white transition-colors">News</a>
                @if($item->category)
                <span>›</span>
                <a href="{{ route('news', ['category' => $item->category->id]) }}" class="hover:text-white transition-colors">{{ $item->category->name }}</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Cabecera --}}
    <div class="relative z-10 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-5">

            {{-- Badges --}}
            <div class="flex items-center flex-wrap gap-2">
                @if($item->category)
                <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                    {{ $item->category->name }}
                </span>
                @endif
                @if($isMultilingual && count($activeLocales) > 1)
                    @foreach($activeLocales as $locale)
                    <a href="{{ route('news.show', $item->slug) }}?lang={{ $locale }}"
                       class="px-3 py-1 rounded-full text-xs font-medium border transition
                              {{ $activeLocale === $locale
                                 ? 'bg-white/10 border-white/40 text-white'
                                 : 'border-slate-600 text-slate-400 hover:border-blue-400 hover:text-blue-300' }}">
                        {{ strtoupper($locale) }}
                    </a>
                    @endforeach
                @endif
            </div>

            {{-- Título --}}
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight drop-shadow-lg">
                {{ $item->display_title }}
            </h1>

            @if($item->display_excerpt)
            <p class="text-lg text-slate-300 leading-relaxed max-w-3xl">
                {{ strip_tags($item->display_excerpt) }}
            </p>
            @endif

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-4 pt-1">
                {{-- Autor --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full overflow-hidden flex items-center justify-center font-bold text-white text-sm flex-shrink-0">
                        @if($item->author?->avatar)
                        <img src="{{ $item->author->avatar }}" alt="{{ $item->author->name }}" class="w-full h-full object-cover">
                        @else
                        {{ strtoupper($item->author?->name[0] ?? '?') }}
                        @endif
                    </div>
                    <div>
                        <div class="text-white font-semibold text-sm">{{ $item->author?->name ?? 'Admin' }}</div>
                        <div class="text-xs text-slate-400">{{ $item->author?->role ?? 'Editor' }}</div>
                    </div>
                </div>

                <span class="text-slate-600">|</span>

                {{-- Fecha --}}
                <div class="flex items-center gap-1.5 text-slate-400 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $item->published_at ? $item->published_at->format('F j, Y') : 'Unpublished' }}</span>
                </div>

                {{-- Tiempo de lectura --}}
                <div class="flex items-center gap-1.5 text-slate-400 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $item->reading_time }} min read</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Contenido del artículo ──────────────────────────────────────────────── --}}
<section class="py-14 bg-gradient-to-b from-slate-950 to-slate-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-[56px_1fr] gap-8 items-start">

            {{-- Share sidebar (desktop) --}}
            <div class="hidden lg:flex flex-col items-center gap-3 sticky top-24">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-1">Share</span>
                @foreach(['twitter', 'facebook', 'reddit'] as $social)
                @php $s = app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->display_title); @endphp
                <a href="{{ $s['url'] }}" target="_blank"
                   title="{{ ucfirst($social) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/60 hover:bg-blue-600 text-slate-400 hover:text-white transition-all duration-200">
                    <i class="{{ $s['icon'] }}"></i>
                </a>
                @endforeach
            </div>

            {{-- Contenido principal --}}
            <div>
                {{-- Prose --}}
                <article class="prose prose-lg prose-invert max-w-none
                    prose-headings:font-bold prose-headings:text-white
                    prose-p:text-slate-300 prose-p:leading-relaxed
                    prose-a:text-blue-400 prose-a:no-underline hover:prose-a:text-blue-300
                    prose-strong:text-white
                    prose-blockquote:border-blue-500 prose-blockquote:bg-slate-800/40 prose-blockquote:rounded-r-lg prose-blockquote:py-1
                    prose-code:text-blue-300 prose-code:bg-slate-800/60 prose-code:rounded prose-code:px-1
                    prose-pre:bg-slate-800/80 prose-pre:border prose-pre:border-slate-700
                    prose-img:rounded-xl prose-img:shadow-2xl">
                    {{ $item->display_content }}
                </article>

                {{-- Share mobile --}}
                <div class="lg:hidden mt-10 pt-8 border-t border-slate-800">
                    <div class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-3">Share this article</div>
                    <div class="flex gap-3">
                        @foreach(['twitter', 'facebook', 'pinterest', 'reddit'] as $social)
                        @php $s = app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->display_title); @endphp
                        <a href="{{ $s['url'] }}" target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-slate-800/50 hover:bg-blue-600 text-slate-300 hover:text-white rounded-lg transition-all text-sm">
                            <i class="{{ $s['icon'] }}"></i>
                            <span class="capitalize hidden sm:inline">{{ $social }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Autor card --}}
                <div class="mt-12 pt-10 border-t border-slate-800">
                    <div class="flex items-center gap-4 bg-slate-800/30 rounded-2xl p-6 border border-slate-700/50">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full overflow-hidden flex items-center justify-center font-bold text-white text-xl flex-shrink-0">
                            @if($item->author?->avatar)
                            <img src="{{ $item->author->avatar }}" alt="{{ $item->author->name }}" class="w-full h-full object-cover">
                            @else
                            {{ strtoupper($item->author?->name[0] ?? '?') }}
                            @endif
                        </div>
                        <div>
                            <div class="text-xs text-slate-500 uppercase tracking-wide mb-0.5">Written by</div>
                            <div class="text-white font-bold text-lg">{{ $item->author?->name ?? 'Admin' }}</div>
                            <div class="text-slate-400 text-sm">{{ $item->author?->role ?? 'Editor' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Comments --}}
                <div class="mt-12 pt-10 border-t border-slate-800">
                    <h3 class="text-2xl font-bold text-white mb-8">
                        Comments
                        @if($item->comments->count())
                        <span class="text-base font-normal text-slate-500 ml-2">({{ $item->comments->count() }})</span>
                        @endif
                    </h3>

                    @auth
                    <form action="{{ route('news.comment.store', $item->slug) }}" method="POST" class="mb-10">
                        @csrf
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full overflow-hidden flex items-center justify-center font-bold text-white text-sm flex-shrink-0 mt-1">
                                {{ strtoupper(auth()->user()->name[0]) }}
                            </div>
                            <div class="flex-1">
                                <textarea name="body" rows="3"
                                    placeholder="Write a comment..."
                                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors resize-none text-sm"></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-sm">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="mb-8 p-4 bg-slate-800/30 rounded-xl border border-slate-700/50 text-center text-sm text-slate-400">
                        <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium">Sign in</a> to leave a comment.
                    </div>
                    @endauth

                    <div class="space-y-5">
                        @forelse($item->comments as $comment)
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full overflow-hidden flex items-center justify-center font-bold text-white text-sm flex-shrink-0 mt-1">
                                @if($comment->user?->avatar)
                                <img src="{{ $comment->user->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $comment->user->name }}" class="w-full h-full object-cover">
                                @else
                                {{ strtoupper($comment->user?->name[0] ?? '?') }}
                                @endif
                            </div>
                            <div class="flex-1 bg-slate-800/30 rounded-xl p-4 border border-slate-700/40">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="font-semibold text-white text-sm">{{ $comment->user?->name ?? 'Anonymous' }}</span>
                                    @if($comment->user?->role ?? null)
                                    <span class="px-2 py-0.5 bg-blue-500/20 border border-blue-500/30 rounded text-xs text-blue-400">{{ $comment->user->role }}</span>
                                    @endif
                                    <span class="text-xs text-slate-500 ml-auto">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="text-slate-300 text-sm leading-relaxed">
                                    {{ $comment->comment }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-slate-500 text-sm">No comments yet. Be the first!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>{{-- /main content --}}
        </div>
    </div>
</section>

@endsection
