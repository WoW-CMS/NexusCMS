@extends('layouts.main')

@section('content')
    <div class="min-h-screen bg-slate-950 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center lg:text-left mb-16">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    Latest News
                </h2>
                <p class="mt-3 max-w-2xl mx-auto lg:mx-0 text-xl text-gray-400 sm:mt-4">
                    Stay up to date with the latest server news, events, and updates.
                </p>
            </div>
            
            <!-- Alerts -->
            @if (session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 p-4 mb-8 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-emerald-400">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500/10 border border-red-500/20 p-4 mb-8 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-red-400">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- News Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @forelse ($data as $news)
                    <article class="bg-gray-900/50 rounded-2xl overflow-hidden backdrop-blur-md shadow-lg border border-gray-800 hover:border-gray-700 transition-all duration-300 group">
                        <a href="{{ route('news.show', $news->slug) }}" class="block">
                            <!-- Featured Image -->
                            @if ($news->featured_image)
                                <div class="relative h-64 overflow-hidden">
                                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        src="{{ asset('storage/' . $news->featured_image) }}" 
                                        alt="{{ $news->title }}">
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Meta Information -->
                                <div class="flex flex-wrap items-center gap-3 text-gray-400 text-sm mb-4">
                                    <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-full">News</span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $news->published_at ? $news->published_at->format('M d, Y') : 'Unpublished' }}</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>5 min read</span>
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-2xl font-bold text-white leading-tight mb-3 group-hover:text-indigo-400 transition-colors">
                                    {{ $news->title }}
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-gray-400 leading-relaxed mb-6 line-clamp-3">
                                    {{ Str::limit($news->excerpt ?? $news->content, 150) }}
                                </p>

                                <!-- Author Information -->
                                <div class="flex items-center space-x-3 pt-4 border-t border-gray-800">
                                    <img class="h-10 w-10 rounded-full ring-2 ring-indigo-500/30"
                                        src="{{ $news->author->avatar ?? asset('images/default-avatar.png') }}" 
                                        alt="Author">
                                    <div>
                                        <p class="text-white font-medium text-sm">{{ $news->author->name ?? 'Anonymous' }}</p>
                                        <p class="text-xs text-gray-400">{{ $news->author->role ?? 'Editor' }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="col-span-2 text-center py-20">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2" />
                        </svg>
                        <h3 class="text-xl font-semibold text-slate-400 mb-2">No News Available</h3>
                        <p class="text-slate-500">Check back later for the latest updates.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-16">
                    <x-pagination :paginator="$data" />
                </div>
            @endif
        </div>
    </div>
@endsection