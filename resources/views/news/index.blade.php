@extends('layouts.main')

@section('content')
    <section class="relative pt-32 pb-16 overflow-hidden">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">
                Latest <span class="gradient-text">News</span>
            </h1>
            <p class="text-xl text-slate-400 max-w-2xl">Stay informed with the latest updates, events, and announcements from NexusCMS</p>

            {{-- Locale switcher (only when multilingual is enabled and there are multiple locales) --}}
            @if($isMultilingual && count($activeLocales) > 1)
            <div class="flex items-center gap-2 mt-4">
                @foreach($activeLocales as $locale)
                    <a href="{{ route('news', array_merge(request()->except('lang'), ['lang' => $locale])) }}"
                       class="px-3 py-1 rounded-full text-xs font-medium border transition
                              {{ $activeLocale === $locale
                                 ? 'bg-blue-600 border-blue-600 text-white'
                                 : 'border-slate-600 text-slate-400 hover:border-blue-500 hover:text-blue-400' }}">
                        {{ strtoupper($locale) }}
                    </a>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    <section class="relative py-12 bg-gradient-to-b from-slate-950 to-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- News Articles -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Featured Article -->
                    @foreach($data as $item)
                    @php $articleUrl = route('news.show', $item->slug) . ($isMultilingual && count($activeLocales) > 1 ? '?lang=' . $activeLocale : ''); @endphp
                    @if($loop->first)
                        <a href="{{ $articleUrl }}" class="block bg-slate-800/50 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-700/50 hover:border-blue-500/50">
                            <div class="relative overflow-hidden">
                                <img src="{{ $item->image ? asset('storage/images/' . $item->image) : '' }}"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                     alt="{{ $item->display_title }}" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="w-full h-80 bg-slate-700/50 items-center justify-center hidden">
                                    <svg class="w-16 h-16 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Featured</span>
                                </div>
                            </div>
                            <div class="p-8 space-y-4">
                                <div class="flex items-center gap-4 text-sm text-slate-400">
                                    <span>{{ ($item->published_at ?? $item->created_at)->format('F j, Y') }}</span>
                                    <span>•</span>
                                    <span>By {{ $item->author?->name ?? 'Admin' }}</span>
                                    <span>•</span>
                                    <span class="text-blue-400">{{ $item->category->name ?? 'Announcements' }}</span>
                                </div>
                                <h2 class="text-3xl font-bold text-white group-hover:text-blue-400 transition-colors duration-200">{{ $item->display_title }}</h2>
                                <p class="text-slate-300 leading-relaxed text-lg">{{ Str::limit(strip_tags($item->display_excerpt ?? $item->display_content), 200) }}</p>
                                <div class="mt-4 text-blue-400 group-hover:text-blue-300 font-medium inline-flex items-center gap-2">
                                    Read Full Article
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @else
                    <a href="{{ $articleUrl }}" class="block bg-slate-800/30 backdrop-blur-sm rounded-xl overflow-hidden border border-slate-700/50 hover:border-slate-600">
                        <div class="md:flex">
                            <div class="md:w-64 h-48 md:h-auto overflow-hidden flex-shrink-0">
                                <img src="{{ $item->image ? asset('storage/images/' . $item->image) : '' }}"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                     alt="{{ $item->display_title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="w-full h-full min-h-[12rem] bg-slate-700/50 items-center justify-center hidden">
                                    <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            </div>
                            <div class="p-6 flex-1 space-y-3">
                                <div class="flex items-center gap-3 text-sm text-slate-400">
                                    <span>{{ ($item->published_at ?? $item->created_at)->format('F j, Y') }}</span>
                                    <span>•</span>
                                    <span class="text-blue-400">{{ $item->category->name ?? 'Announcements' }}</span>
                                </div>
                                <h3 class="text-2xl font-bold text-white group-hover:text-blue-400 transition-colors">{{ $item->display_title }}</h3>
                                <p class="text-slate-300">
                                    {{ Str::limit(strip_tags($item->display_excerpt ?? $item->display_content), 200) }}
                                </p>
                                <div class="text-blue-400 group-hover:text-blue-300 font-medium inline-flex items-center gap-2">
                                    Read More
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
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
                                <span class="text-sm text-slate-400">{{ App\Models\News::where('is_published', true)->count() }}</span>
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
                            <a href="{{ route('news.show', $item->slug) }}" class="flex items-start gap-3">
                                @if($item->image)
                                <img src="{{ asset('storage/images/' . $item->image) }}" alt="{{ $item->display_title }}"
                                     class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                @else
                                <div class="w-16 h-16 rounded-lg flex-shrink-0 bg-slate-700/60 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <div class="text-xs text-slate-400 mb-1">{{ ($item->published_at ?? $item->created_at)->format('M d, Y') }}</div>
                                    <h4 class="text-white group-hover:text-blue-400 transition-colors font-medium text-sm leading-snug">{{ $item->display_title }}</h4>
                                </div>
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