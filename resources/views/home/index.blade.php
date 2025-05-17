@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <div class="bg-wow relative">
        <div class="bg-blur">
            <div class="max-w-7xl mx-auto py-32 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold text-white text-shadow mb-4">
                    Welcome to <span class="text-blue-400">NexusCMS</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 text-shadow max-w-3xl mb-8">
                    Experience the ultimate World of Warcraft private server with custom content, balanced gameplay, and an amazing community.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:text-lg">
                        Create Account
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-200 md:text-lg">
                        How to Connect
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: News and Realm Status -->
    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- News Column (Larger) -->
                <div class="lg:w-2/3">
                    <div class="text-center lg:text-left mb-8">
                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                            Latest News
                        </h2>
                        <p class="mt-3 max-w-2xl mx-auto lg:mx-0 text-xl text-gray-400 sm:mt-4">
                            Stay up to date with the latest server news, events, and updates.
                        </p>
                    </div>
                    
                    <div class="space-y-8">
                        @forelse ($data as $news)
                        <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg news-card flex flex-col md:flex-row">
                            <div class="md:w-1/3">
                                <img class="w-full h-48 md:h-full object-cover" 
                                     src="{{ asset('storage/' . $news->image) }}" 
                                     alt="{{ $news->title }}">
                            </div>
                            <div class="md:w-2/3 p-6">
                                <div class="text-xs text-gray-400 mb-2">{{ $news->published_at ? $news->published_at->format('F d, Y') : 'Draft' }}</div>
                                <h3 class="text-xl font-bold text-white mb-2">{{ $news->title }}</h3>
                                <p class="text-gray-300 mb-4">{{ Str::limit($news->content, 200) }}</p>
                                <a href="{{ route('news.show', $news->id) }}" class="text-blue-400 hover:text-blue-300 font-medium">Read More →</a>
                            </div>
                        </div>
                        @empty
                        <div class="bg-gray-800 rounded-lg p-6 text-center">
                            <p class="text-gray-400">No news available at the moment.</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <div class="text-center mt-8">
                        <a href="{{ route('news') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            View All News
                        </a>
                    </div>
                </div>
                
                <!-- Realm Status Column (Smaller) -->
                <div class="lg:w-1/3">
                    <div class="sticky top-20">
                        <div class="text-center lg:text-left mb-8">
                            <h2 class="text-2xl font-extrabold text-white">
                                Server Status
                            </h2>
                            <p class="mt-2 text-sm text-gray-400">
                                Last updated: {{ now()->format('M d, Y H:i') }}
                            </p>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Realm 1 -->
                            <div class="bg-gray-800 rounded-lg overflow-hidden border-l-4 border-green-500">
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-medium text-white">Realm 1 (PvP)</h3>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-900 text-green-300">Online</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-400 mb-3">
                                        <span>Wrath of the Lich King</span>
                                        <span>3.3.5a</span>
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
                            </div>
                            
                            <!-- Realm 2 -->
                            <div class="bg-gray-800 rounded-lg overflow-hidden border-l-4 border-red-500">
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-medium text-white">Realm 2 (PvE)</h3>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-900 text-red-300">Offline</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-400 mb-3">
                                        <span>Cataclysm</span>
                                        <span>4.3.4</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="mr-3">
                                                <div class="text-xs text-gray-500">PLAYERS</div>
                                                <div class="text-xl font-bold text-white">0</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">UPTIME</div>
                                                <div class="text-xl font-bold text-white">0h</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-600 rounded-full h-2.5">
                                                <div class="bg-red-500 h-2.5 rounded-full" style="width: 0%"></div>
                                            </div>
                                            <span class="ml-2 text-xs text-gray-400">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Realm 3 -->
                            <div class="bg-gray-800 rounded-lg overflow-hidden border-l-4 border-yellow-500">
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-medium text-white">Realm 3 (RP)</h3>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-900 text-yellow-300">Maintenance</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-400 mb-3">
                                        <span>Legion</span>
                                        <span>7.3.5</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="mr-3">
                                                <div class="text-xs text-gray-500">PLAYERS</div>
                                                <div class="text-xl font-bold text-white">0</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">ETA</div>
                                                <div class="text-xl font-bold text-white">2h</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-600 rounded-full h-2.5">
                                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 65%"></div>
                                            </div>
                                            <span class="ml-2 text-xs text-gray-400">65%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Server Statistics -->
                            <div class="bg-gray-800 rounded-lg p-4 mt-4">
                                <h3 class="text-lg font-medium text-white mb-3">Server Statistics</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-500">ONLINE PLAYERS</div>
                                        <div class="text-2xl font-bold text-blue-400">1,250</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">ACCOUNTS</div>
                                        <div class="text-2xl font-bold text-blue-400">15,430</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">CHARACTERS</div>
                                        <div class="text-2xl font-bold text-blue-400">42,680</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">UPTIME</div>
                                        <div class="text-2xl font-bold text-blue-400">98.7%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-12 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    Server Features
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-400 sm:mt-4">
                    Discover what makes our server unique and exciting.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-700 p-6 rounded-lg shadow-lg feature-card">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-code text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-white">Custom Content</h3>
                    </div>
                    <p class="text-gray-300 text-center">Experience unique dungeons, raids, and quests found nowhere else.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-gray-700 p-6 rounded-lg shadow-lg feature-card">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-balance-scale text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-white">Balanced Gameplay</h3>
                    </div>
                    <p class="text-gray-300 text-center">Carefully tuned classes and encounters for the best gaming experience.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-gray-700 p-6 rounded-lg shadow-lg feature-card">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-users text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-white">Active Community</h3>
                    </div>
                    <p class="text-gray-300 text-center">Join thousands of players in a friendly and helpful community.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-gray-700 p-6 rounded-lg shadow-lg feature-card">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-server text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-white">Stable Servers</h3>
                    </div>
                    <p class="text-gray-300 text-center">Enjoy high uptime and low latency on our professionally maintained servers.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Join Section -->
    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center mb-12">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    How to Join
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-400 sm:mt-4">
                    Follow these simple steps to start your adventure.
                </p>
            </div>
            <div class="flex flex-col md:flex-row justify-center items-center md:items-start space-y-8 md:space-y-0 md:space-x-12">
                <!-- Step 1 -->
                <div class="flex flex-col items-center max-w-xs text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-500 text-white text-2xl font-bold mb-4">1</div>
                    <h3 class="text-xl font-medium text-white mb-2">Create an Account</h3>
                    <p class="text-gray-400">Register for free on our website to create your game account.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="flex flex-col items-center max-w-xs text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-500 text-white text-2xl font-bold mb-4">2</div>
                    <h3 class="text-xl font-medium text-white mb-2">Download the Client</h3>
                    <p class="text-gray-400">Download our custom WoW client or use your existing installation.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="flex flex-col items-center max-w-xs text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-500 text-white text-2xl font-bold mb-4">3</div>
                    <h3 class="text-xl font-medium text-white mb-2">Configure & Connect</h3>
                    <p class="text-gray-400">Set up your realmlist.wtf file with our server address and log in.</p>
                </div>
            </div>
            <div class="text-center mt-12">
                <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Detailed Connection Guide
                </a>
            </div>
        </div>
    </div>

    <!-- Discord Section -->
    <div class="py-12 bg-indigo-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-8 md:mb-0 md:mr-8">
                    <h2 class="text-3xl font-extrabold text-white mb-4">Join Our Discord Community</h2>
                    <p class="text-xl text-indigo-200 mb-6">Connect with other players, get support, and stay updated on server news.</p>
                    <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fab fa-discord mr-2"></i> Join Discord
                    </a>
                </div>
                <div class="flex-shrink-0">
                    <img src="https://assets-global.website-files.com/6257adef93867e50d84d30e2/636e0a6a49cf127bf92de1e2_icon_clyde_blurple_RGB.png" alt="Discord" class="h-32">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection