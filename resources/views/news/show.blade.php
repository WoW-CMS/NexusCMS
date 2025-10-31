@extends('layouts.main')

@section('content')
<section class="pt-24 pb-6 bg-slate-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-sm text-slate-400">
            <a href="#" class="hover:text-white transition-colors">Home</a>
            <span>›</span>
            <a href="{{ route('news', ['category' => $item->category->id]) }}" class="hover:text-white transition-colors">{{ $item->category->name }}</a>
            <span>›</span>
            <span class="text-white">{{ $item->title }}</span>
        </div>
    </div>
</section>

<!-- Article Header -->
<section class="relative pb-8 overflow-hidden bg-slate-950">
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <span class="inline-block bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">{{ $item->category->name }}</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
            {{ $item->title }}
        </h1>
        <div class="flex flex-wrap items-center gap-6 text-slate-400">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center font-bold text-white">
                    @if($item->author->avatar)
                    <img class="h-14 w-14 rounded-full ring-2 ring-indigo-500"
                        src="{{ $item->author->avatar }}" alt="{{ $item->author->name }}">
                    @else
                    <span>{{ $item->author->name[0] }}</span>
                    @endif
                </div>
                <div>
                    <div class="text-white font-medium">{{ $item->author->name }}</div>
                    <div class="text-sm">{{ $item->author->role ?? 'Editor' }}</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $item->published_at ? $item->published_at->format('M d, Y') : 'Unpublished' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $item->reading_time }} min read</span>
            </div>
        </div>
    </div>
</section>

<!-- Featured Image -->
@if($item->image)
<section class="bg-slate-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <img src="{{ asset('storage/images/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-96 object-cover rounded-2xl shadow-2xl">
    </div>
</section>
@endif

<!-- Article Content -->
<section class="py-12 bg-gradient-to-b from-slate-900 to-slate-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Share Sidebar -->
            <div class="hidden lg:block">
                <div class="sticky top-24 space-y-4">
                    <div class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-4">Share</div>
                    @foreach(['twitter', 'facebook', 'pinterest', 'reddit'] as $social)
                    <a href="{{ app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->title)['url'] }}"
                       target="_blank"
                       class="w-full flex items-center gap-3 px-4 py-3 bg-slate-800/50 hover:bg-blue-600 text-slate-300 hover:text-white rounded-lg transition-all">
                        <i class="{{ app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->title)['icon'] }} fa-lg"></i>
                        <span class="text-sm font-medium capitalize">{{ $social }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <article class="prose prose-lg prose-invert max-w-none">
                    {!! $item->content !!}
                </article>

                <div class="mt-8 pt-8 border-t border-slate-800">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-sm text-slate-400 font-medium">Tags:</span>
                    </div>
                </div>

                <!-- Share Mobile -->
                <div class="lg:hidden mt-8 pt-8 border-t border-slate-800">
                    <div class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-4">Share this article</div>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['twitter', 'facebook', 'pinterest', 'reddit'] as $social)
                            <a href="{{ app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->title)['url'] }}"
                               target="_blank"
                               class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-slate-800/50 hover:bg-blue-600 text-slate-300 hover:text-white rounded-lg transition-all">
                                <i class="{{ app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->title)['icon'] }} fa-lg"></i>
                                <span class="text-sm font-medium capitalize">{{ $social }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="mt-12 pt-12 border-t border-slate-800">
                    <h3 class="text-2xl font-bold text-white mb-6">Comments</h3>
                    
                    @auth
                    <!-- Comment Form -->
                    <form action="{{ route('news.comment.store', $item->slug) }}" method="POST" class="mb-10">
                        @csrf
                        <div class="bg-slate-800/30 rounded-xl p-6 mb-8">
                            <textarea placeholder="Write a comment..." id="editor" name="body" class="w-full h-32 px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors resize-none"></textarea>
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">Post Comment</button>
                            </div>
                        </div>
                    </form>
                    @endauth

                    <!-- Comments List -->
                    <div class="space-y-6">
                        @forelse($item->comments as $comment)
                        <!-- Comment 1 -->
                        <div class="bg-slate-800/30 rounded-xl p-6 border border-slate-700/50 hover:border-slate-600/50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center font-bold text-white">
                                        @if($item->author->avatar)
                                        <img class="h-12 w-12 rounded-full ring-2 ring-indigo-500 mt-1"
                                        src="{{ $comment->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                        alt="Avatar">
                                        @else
                                        {{ $comment->user->name[0] ?? 'Anonymous' }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-bold text-white"> {{ $comment->user->name ?? 'Anonymous' }}</h4>
                                        <span class="px-2 py-0.5 bg-blue-500/20 border border-blue-500/30 rounded text-xs text-blue-400 font-medium">{{ $comment->user->role }}</span>
                                        <span class="text-sm text-slate-500">{{ date('d/m/Y H:i', $comment->created_at) }}</span>
                                    </div>
                                    <p class="text-slate-300 leading-relaxed mb-3">
                                        {!! $comment->comment !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                            <p class="text-slate-400 text-center">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection