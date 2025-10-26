@extends('layouts.main')

@section('content')
    <section class="relative pt-32 pb-16 overflow-hidden">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">
                Latest <span class="gradient-text">News</span>
            </h1>
            <p class="text-xl text-slate-400 max-w-2xl">Stay informed with the latest updates, events, and announcements from NexusCMS</p>
        </div>
    </section>
    <section class="relative py-12 bg-gradient-to-b from-slate-950 to-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- News Articles -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Featured Article -->
                    @foreach($data as $item)
                    @if($loop->first)
                        <article class="bg-slate-800/50 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300 ">
                            <div class="relative">
                                <img src="{{ asset('storage/images/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-4 left-4">
                                    <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Featured</span>
                                </div>
                            </div>
                            <div class="p-8 space-y-4">
                                <div class="flex items-center gap-4 text-sm text-slate-400">
                                    <span>{{ $item->published_at->format('F j, Y') }}</span>
                                    <span>•</span>
                                    <span>By {{ $item->author->name ?? 'Admin' }}</span>
                                    <span>•</span>
                                    <span class="text-blue-400">{{ $item->category->name ?? 'Announcements' }}</span>
                                </div>
                                <h2 class="text-3xl font-bold text-white hover:text-blue-400 transition-colors duration-200">{{ $item->title }}</h2>
                                <p class="text-slate-300 leading-relaxed text-lg">{{ Str::limit($item->excerpt ?? $item->content, 200) }}</p>
                                <a href="{{ route('news.show', $item->slug) }}" class="mt-4 text-blue-400 hover:text-blue-300 font-medium inline-flex items-center gap-2">
                                    Read Full Article
                                    <svg class="w-4 h-4 hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @else
                    <article class="bg-slate-800/30 backdrop-blur-sm rounded-xl overflow-hidden border border-slate-700/50 hover:border-slate-600 transition-all duration-300 ">
                        <div class="md:flex">
                            <div class="md:w-64 h-48 md:h-auto">
                                <img src="{{ asset('storage/images/' . $item->image) }}" alt="Tournament" class="w-full h-full object-cover -hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-6 flex-1 space-y-3">
                                <div class="flex items-center gap-3 text-sm text-slate-400">
                                    <span>{{ $item->published_at->format('F j, Y') }}</span>
                                    <span>•</span>
                                    <span class="text-red-400">{{ $item->category->name ?? 'Announcements' }}</span>
                                </div>
                                <h3 class="text-2xl font-bold text-white -hover:text-blue-400 transition-colors">{{ $item->title }}</h3>
                                <p class="text-slate-300">
                                    {{ Str::limit($item->excerpt ?? $item->content, 200) }}
                                </p>
                                <a href="{{ route('news.show', $item->slug) }}" class="text-blue-400 hover:text-blue-300 font-medium inline-flex items-center gap-2 /btn">
                                    Read More
                                    <svg class="w-4 h-4 -hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endif
                    @endforeach
                    <div class="flex justify-center items-center gap-2 pt-8">
                        <div class="mt-16">
                            <x-pagination :paginator="$data" />
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Categories -->
                    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 top-24">
                        <h3 class="text-xl font-bold text-white mb-4">Categories</h3>
                        <div class="space-y-2">
                            <a href="{{ route('news', ['category' => 'all']) }}" class="flex items-center justify-between p-3 rounded-lg {{ request('category') == 'all' || request('category') == null ? 'bg-blue-500/10 border border-blue-500/30 text-blue-400' : 'hover:bg-slate-700/30 text-slate-300 hover:text-white transition-all' }}">
                                <span class="font-medium">All News</span>
                                <span class="text-sm text-slate-400">{{ App\Models\News::count() }}</span>
                            </a>
                            @foreach($category as $item)
                            <a href="{{ route('news', ['category' => $item->id]) }}" class="flex items-center justify-between p-3 rounded-lg {{ request('category') == $item->id ? 'bg-blue-500/10 border border-blue-500/30 text-blue-400' : 'hover:bg-slate-700/30 text-slate-300 hover:text-white transition-all' }}">
                                <span>{{ $item->name }}</span>
                                <span class="text-sm text-slate-400">{{ $item->news_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50">
                        <h3 class="text-xl font-bold text-white mb-4">Recent Posts</h3>
                        <div class="space-y-4">
                            @foreach($recentNews as $item)
                            <a href="#" class="block ">
                                <div class="text-sm text-slate-400 mb-1">{{ $item->published_at->format('M d, Y') }}</div>
                                <h4 class="text-white -hover:text-blue-400 transition-colors font-medium">{{ $item->title }}</h4>
                            </a>
                            @if(!$loop->last)
                            <div class="border-t border-slate-700"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-xl p-6 border border-blue-500/20">
                        <h3 class="text-xl font-bold text-white mb-3">Stay Updated</h3>
                        <p class="text-slate-300 text-sm mb-4">Subscribe to our newsletter and never miss an update!</p>
                        <form action="{{ route('subscribe') }}" method="POST">
                        @csrf
                        <input type="email" id="email" placeholder="Your email" class="w-full px-4 py-2 bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors mb-3">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Subscribe
                        </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection