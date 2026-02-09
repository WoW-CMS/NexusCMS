@extends('layouts.main')

@section('content')
<section class="relative min-h-screen flex items-center justify-center pt-16 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
        <div
            class="absolute top-20 left-10 w-72 h-72 rounded-full bg-blue-500/10 opacity-60 animate-float-smooth"
            style="filter: blur(24px); will-change: transform; transform: translate3d(0, 0, 0);">
        </div>
        <div
            class="absolute bottom-20 right-10 w-96 h-96 rounded-full bg-purple-500/10 opacity-50 animate-float-smooth"
            style="filter: blur(28px); will-change: transform; animation-delay: -2s;">
        </div>
        <div
            class="absolute top-1/2 left-1/2 w-80 h-80 rounded-full bg-cyan-500/10 opacity-50 animate-float-smooth"
            style="filter: blur(32px); will-change: transform; animation-delay: -1s;">
        </div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="space-y-8">
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight">
                Welcome to <span class="gradient-text">{{ config('app.name', 'NexusCMS') }}</span>
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed">
                Experience the ultimate World of Warcraft private server with custom content, balanced gameplay, and an amazing community.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg rounded-xl transition-all duration-300 font-semibold shadow-2xl shadow-blue-900/50 hover:shadow-blue-900/70 hover:scale-105 glow">
                    Create Account
                </a>
                <a href="{{ route('howtoplay') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white text-lg rounded-xl transition-all duration-300 font-semibold border border-white/20 hover:border-white/40 hover:scale-105">
                    How to Connect
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <a href="#features" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 group">
        <svg class="w-6 h-6 text-slate-400 group-hover:text-white transition-all duration-300 transform group-hover:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </a>
</section>

<section id="features" class="relative py-20 bg-gradient-to-b from-slate-900 to-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">Why Choose {{ config('app.name', 'NexusCMS') }}?</h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Discover what makes our server stand out from the rest</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/5 rounded-2xl p-8 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300">
                <div class="w-14 h-14 bg-blue-500/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">High Performance</h3>
                <p class="text-slate-300 leading-relaxed">99.9% uptime with dedicated servers ensuring smooth gameplay without lag or downtime.</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/5 rounded-2xl p-8 border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300">
                <div class="w-14 h-14 bg-purple-500/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Custom Content</h3>
                <p class="text-slate-300 leading-relaxed">Unique quests, dungeons, and raids crafted exclusively for our community to explore.</p>
            </div>

            <div class="bg-gradient-to-br from-cyan-500/10 to-cyan-600/5 rounded-2xl p-8 border border-cyan-500/20 hover:border-cyan-500/40 transition-all duration-300">
                <div class="w-14 h-14 bg-cyan-500/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Active Community</h3>
                <p class="text-slate-300 leading-relaxed">Join thousands of players in a friendly, supportive environment with regular events.</p>
            </div>
        </div>
    </div>
</section>

<section class="relative py-20 bg-gradient-to-b from-slate-950 to-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Latest News -->
            <div class="lg:col-span-2 space-y-8">
                <div class="space-y-2">
                    <h2 class="text-4xl font-bold text-white">Latest News</h2>
                    <p class="text-slate-400 text-lg">Stay up to date with the latest server news, events, and updates.</p>
                </div>

                @if(!empty($data['featuredNews']))
                <div id="features" class="bg-slate-800/50 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-700/50 hover:border-blue-500/50">
                    <div class="relative">
                        @if(!empty($data['featuredNews']->image))
                        <img src="{{ asset('storage/images/' . $data['featuredNews']->image) }}" alt="{{ $data['featuredNews']->title }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Featured</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="text-sm text-slate-400">{{ $data['featuredNews']->published_at ? $data['featuredNews']->published_at->format('F d, Y') : 'Draft' }}</div>
                        <h3 class="text-2xl font-bold text-white group-hover:text-blue-400 transition-colors duration-200">{{ $data['featuredNews']->title }}</h3>
                        <p class="text-slate-300 leading-relaxed">{{ Str::limit($data['featuredNews']->content, 200) }}</p>
                        <a href="{{ route('news.show', $data['featuredNews']->slug) }}" class="text-blue-400 hover:text-blue-300 font-medium inline-flex items-center gap-2 group/btn mt-2">
                            Read More 
                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif

                <div class="grid md:grid-cols-2 gap-6">
                    @forelse($data['news'] as $item)
                    <div class="bg-slate-800/30 backdrop-blur-sm rounded-xl p-5 border border-slate-700/50 hover:border-slate-600 cursor-pointer">
                        <div class="text-xs text-slate-400 mb-2">{{ $item->published_at->format('F d, Y') }}</div>
                        <h4 class="text-lg font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">{{ $item->title }}</h4>
                        <p class="text-slate-400 text-sm">{{ Str::limit($item->content, 100) }}</p>
                        <a href="{{ route('news.show', $item->slug) }}" class="text-blue-400 hover:text-blue-300 font-medium inline-flex items-center gap-2 group/btn mt-2">
                            Read More 
                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    @empty
                    <div class="col-span-full bg-slate-800/30 backdrop-blur-sm rounded-xl p-5 border border-slate-700/50 hover:border-slate-600 cursor-pointer">
                        <p class="text-slate-400 text-sm">No news available.</p>
                    </div>
                    @endforelse
                </div>
                
                <div class="text-center pt-4">
                    <a href="{{ route('news') }}" class="px-6 py-3 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-lg transition-all duration-300 font-medium border border-slate-700">
                        View All News
                    </a>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-white">Server Status</h2>
                </div>

                <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 space-y-6 top-24">
                    @forelse ($data['realms'] as $realm)    
                    <div class="bg-gray-800 rounded-lg overflow-hidden border-l-4 {{ App\Helpers\RealmHelper::getWoWConstant('color', $realm->expansion) }} p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-white">{{ $realm->name }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-900 text-green-300">Online</span>
                        <!-- <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-900 text-green-300">Offline</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-900 text-green-300">Mantenimiento</span> -->
                        </div>
                        <div class="flex justify-between text-sm text-gray-400 mb-3">
                            <span>{{ App\Helpers\RealmHelper::getWoWConstant('expansion', $realm->expansion) }}</span>
                            <span>{{ App\Helpers\RealmHelper::getWoWConstant('version', $realm->expansion) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="mr-3">
                                    <div class="text-xs text-gray-500">PLAYERS</div>
                                    <div class="text-xl font-bold text-white">756</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">UPTIME</div>
                                    <div class="text-xl font-bold text-white">14d 6h</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-600 rounded-full h-2.5">
                                    <div class="bg-green-500 h-2.5 rounded-full" style="width: 75%"></div>
                                </div>
                                <span class="ml-2 text-xs text-gray-400">75%</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-2 animate-pulse"></div>
                            <div>
                                <p class="text-red-400 font-medium">No realms available at the moment.</p>
                                <p class="text-slate-400 text-sm mt-1">Check back later for more details.</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                    <div>
                        <h3 class="text-xl font-bold text-white mb-6">Server Statistics</h3>
                        <div class="space-y-4">
                            <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/5 rounded-xl p-5 border border-blue-500/20">
                                <div class="text-sm text-slate-400 uppercase tracking-wide mb-1">Online Players</div>
                                <div class="text-4xl font-bold text-white">1,250</div>
                                <div class="text-xs text-blue-400 mt-2">↑ 15% from yesterday</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/5 rounded-xl p-5 border border-purple-500/20">
                                <div class="text-sm text-slate-400 uppercase tracking-wide mb-1">Total Accounts</div>
                                <div class="text-4xl font-bold text-white">15,430</div>
                                <div class="text-xs text-purple-400 mt-2">+47 new today</div>
                            </div>
                            <div class="bg-gradient-to-br from-cyan-500/10 to-cyan-600/5 rounded-xl p-5 border border-cyan-500/20">
                                <div class="text-sm text-slate-400 uppercase tracking-wide mb-1">Uptime</div>
                                <div class="text-4xl font-bold text-white">99.8%</div>
                                <div class="text-xs text-cyan-400 mt-2">Last 30 days</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all duration-300 font-medium shadow-lg shadow-green-900/30">
                            Check Server Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection