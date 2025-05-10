@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="relative text-center mb-16">
            <div class="absolute inset-0 flex items-center justify-center opacity-5">
                <div class="w-96 h-96 bg-blue-500 rounded-full filter blur-3xl"></div>
            </div>
            <div class="relative">
                <h2 class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 sm:text-6xl mb-4">
                    Latest News
                </h2>
                <p class="mt-4 text-xl text-gray-400 max-w-3xl mx-auto">
                    Stay up to date with the latest updates, events and news from our server.
                </p>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-900/30 border-l-4 border-green-500 backdrop-blur-sm p-4 mb-8 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-900/30 border-l-4 border-red-500 backdrop-blur-sm p-4 mb-8 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse ($data as $news)
                <div class="group bg-gray-800/40 rounded-2xl overflow-hidden backdrop-blur-sm border border-gray-700/50 hover:border-blue-500/50 transition-all duration-500 flex flex-col h-full">
                    @if($news->featured_image)
                        <div class="relative h-64 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent z-10"></div>
                            <img class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" 
                                 src="{{ asset('storage/' . $news->featured_image) }}" 
                                 alt="{{ $news->title }}">
                        </div>
                    @endif
                    <div class="p-6 flex-grow">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm font-medium backdrop-blur-sm">
                                News
                            </span>
                            <span class="text-sm text-gray-400">
                                {{ $news->published_at ? $news->published_at->diffForHumans() : 'Unpublished' }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors duration-300">
                            {{ $news->title }}
                        </h3>
                        <p class="text-gray-300 mb-4 line-clamp-3">
                            {{ Str::limit($news->excerpt ?? $news->content, 150) }}
                        </p>
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-700/50">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="{{ $news->author->avatar ?? asset('images/default-avatar.png') }}" 
                                     alt="Author">
                                <span class="ml-2 text-sm text-gray-400">
                                    {{ $news->author->name ?? 'Anonymous' }}
                                </span>
                            </div>
                            <a href="{{ route('news.show', $news->id) }}" 
                               class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors duration-300">
                                Read More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-gray-800/40 rounded-2xl p-12 text-center backdrop-blur-sm border border-gray-700/50">
                    <div class="animate-pulse mb-8">
                        <svg class="w-24 h-24 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-4">No News Available</h3>
                    <p class="text-gray-500">Check back later for the latest updates.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-12">
                <div class="flex justify-center">
                    @if ($data->hasPages())
                        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
                            {{-- Previous Page Link --}}
                            @if ($data->onFirstPage())
                                <span class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-800/40 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $data->previousPageUrl() }}" rel="prev" class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                @if ($page == $data->currentPage())
                                    <span class="px-4 py-2 text-sm font-medium text-indigo-400 bg-indigo-600/20 rounded-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($data->hasMorePages())
                                <a href="{{ $data->nextPageUrl() }}" rel="next" class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-800/40 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection