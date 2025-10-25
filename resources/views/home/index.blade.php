@extends('layouts.main')

<section class="relative min-h-screen flex items-center justify-center pt-16 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl float" style="animation-delay: -1.5s;"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="space-y-8">
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight">
                Welcome to <span class="gradient-text">NexusCMS</span>
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
        <style>
            @keyframes gentleBounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-8px); }
            }
            .group {
                animation: gentleBounce 2s ease-in-out infinite;
            }
            .group:hover {
                animation-play-state: paused;
            }
        </style>
    </a>
</section>

<section id="features">
@include('home.partials.features')
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

                @include('home.partials.featurednews', ['news' => $data['featuredNews']])

                <div class="grid md:grid-cols-2 gap-6">
                    @include('home.partials.newsitem', ['news' => $data['news']])
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