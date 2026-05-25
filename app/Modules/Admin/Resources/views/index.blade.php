@extends('admin::layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Top Bar -->
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <button class="md:hidden text-gray-600">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative">
            <input type="search" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <button class="relative text-gray-600 hover:text-gray-800">
            <i class="fas fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">3</span>
        </button>
        <a href="#" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-external-link-alt text-xl"></i>
        </a>
    </div>
</header>

<!-- Content Area -->
<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <span class="text-sm font-medium text-green-600 flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i> 12%
                </span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Total Accounts</h3>
            <p class="text-3xl font-bold text-gray-800">15,430</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-circle text-2xl text-green-600"></i>
                </div>
                <span class="text-sm font-medium text-green-600 flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i> 8%
                </span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Online Players</h3>
            <p class="text-3xl font-bold text-gray-800">1,250</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-yellow-600"></i>
                </div>
                <span class="text-sm font-medium text-green-600 flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i> 24%
                </span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Revenue (Month)</h3>
            <p class="text-3xl font-bold text-gray-800">$8,420</p>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-2xl text-purple-600"></i>
                </div>
                <span class="text-sm font-medium text-green-600 flex items-center gap-1">
                    <i class="fas fa-check"></i> Stable
                </span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Server Uptime</h3>
            <p class="text-3xl font-bold text-gray-800">99.8%</p>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-3 gap-4">
                        <button class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                            <i class="fas fa-plus-circle text-blue-600 text-xl"></i>
                            <span class="font-semibold text-gray-700">Create News</span>
                        </button>
                        <button class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                            <i class="fas fa-user-plus text-green-600 text-xl"></i>
                            <span class="font-semibold text-gray-700">Add Account</span>
                        </button>
                        <button class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                            <i class="fas fa-envelope text-purple-600 text-xl"></i>
                            <span class="font-semibold text-gray-700">Send Mail</span>
                        </button>
                        <button class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:bg-red-50 transition">
                            <i class="fas fa-ban text-red-600 text-xl"></i>
                            <span class="font-semibold text-gray-700">Ban Player</span>
                        </button>
                        <button class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition">
                            <i class="fas fa-box text-yellow-600 text-xl"></i>
                            <span class="font-semibold text-gray-700">Add Item</span>
                        </button>
                        <button class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-cyan-500 hover:bg-cyan-50 transition">
                            <i class="fas fa-sync text-cyan-600 text-xl"></i>
                            <span class="font-semibold text-gray-700">Restart Realm</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Recent Activity</h2>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-plus text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">New Registration</p>
                                <p class="text-sm text-gray-600">PlayerName123 created an account</p>
                                <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shopping-cart text-yellow-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Store Purchase</p>
                                <p class="text-sm text-gray-600">Player purchased Level 80 Boost for $35</p>
                                <p class="text-xs text-gray-400 mt-1">15 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-ban text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Ban Applied</p>
                                <p class="text-sm text-gray-600">Hacker99 banned for botting (permanent)</p>
                                <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-newspaper text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">News Published</p>
                                <p class="text-sm text-gray-600">New patch notes released by Admin</p>
                                <p class="text-xs text-gray-400 mt-1">3 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Realms Status -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Realms Status</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="font-bold text-gray-800">Realm 1 - FireStorm</p>
                                    <p class="text-sm text-gray-600">Version 3.3.5a | 850 players</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Manage</button>
                                <button class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="font-bold text-gray-800">Realm 2 - Frostmourne</p>
                                    <p class="text-sm text-gray-600">Version 3.3.5a | 320 players</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Manage</button>
                                <button class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="font-bold text-gray-800">Realm 3 - IceCrown</p>
                                    <p class="text-sm text-gray-600">Version 3.3.5a | 80 players</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Manage</button>
                                <button class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- CMS Update and version -->
            <!-- CMS Update & Version -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-lg border border-blue-100 overflow-hidden">
                <div class="px-6 py-4 bg-white/60 backdrop-blur-sm border-b border-blue-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-rocket text-blue-600"></i>
                        System Update
                    </h2>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold shadow-sm">v{{ config('app.version') }}</span>
                </div>
                <div class="p-6">
                    @if($latestRelease === null)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-plug text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-base text-gray-800 font-semibold mb-1">Could not connect to the update server</p>
                            <p class="text-sm text-gray-500">Version information is unavailable. Check your internet connection or verify the repository and GitHub token in <a href="{{ route('admin.settings.index') }}#updates" class="text-blue-600 hover:underline">Settings → Updates</a>.</p>
                        </div>
                    </div>
                    @elseif($outdated)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-rocket text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-base text-gray-800 font-semibold mb-1">NexusCMS {{ $latestRelease['tag_name'] }} is now available</p>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($latestRelease['body'] ?? '', 120) }}</p>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.updates.index') }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                                    <i class="fas fa-cloud-arrow-up text-xs"></i> Update now
                                </a>
                                <a href="{{ $latestRelease['html_url'] }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline transition">Release notes</a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-circle-check text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-base text-gray-800 font-semibold mb-1">You are up to date</p>
                            <p class="text-sm text-gray-600">Version {{ $latestRelease['tag_name'] }} —
                                released {{ \Carbon\Carbon::parse($latestRelease['published_at'])->diffForHumans() }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <a href="{{ $latestRelease['html_url'] }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline transition">Release notes</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pending Reports -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Pending Reports</h2>
                    <span class="px-2.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">12</span>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                            <div class="flex items-start justify-between mb-1">
                                <p class="font-semibold text-gray-800 text-sm">Cheating Report</p>
                                <span class="text-xs font-medium text-red-600">High</span>
                            </div>
                            <p class="text-xs text-gray-600">Player using speed hacks in BG</p>
                            <p class="text-xs text-gray-400 mt-1">5 min ago</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <div class="flex items-start justify-between mb-1">
                                <p class="font-semibold text-gray-800 text-sm">Harassment</p>
                                <span class="text-xs font-medium text-yellow-600">Medium</span>
                            </div>
                            <p class="text-xs text-gray-600">Toxic behavior in global chat</p>
                            <p class="text-xs text-gray-400 mt-1">12 min ago</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <div class="flex items-start justify-between mb-1">
                                <p class="font-semibold text-gray-800 text-sm">Bug Report</p>
                                <span class="text-xs font-medium text-blue-600">Low</span>
                            </div>
                            <p class="text-xs text-gray-600">Quest NPC not responding</p>
                            <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                        </div>
                    </div>
                    <button class="w-full mt-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        View All Reports
                    </button>
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Recent Donations</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">PlayerOne</p>
                                <p class="text-xs text-gray-600">Gold Package</p>
                            </div>
                            <div class="text-right">
                                <p class="text-green-600 font-bold">$30</p>
                                <p class="text-xs text-gray-400">Just now</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">CoolGamer99</p>
                                <p class="text-xs text-gray-600">Silver Package</p>
                            </div>
                            <div class="text-right">
                                <p class="text-green-600 font-bold">$15</p>
                                <p class="text-xs text-gray-400">10 min ago</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">EpicWarrior</p>
                                <p class="text-xs text-gray-600">Custom Amount</p>
                            </div>
                            <div class="text-right">
                                <p class="text-green-600 font-bold">$50</p>
                                <p class="text-xs text-gray-400">30 min ago</p>
                            </div>
                        </div>
                    </div>
                    <button class="w-full mt-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        View All Donations
                    </button>
                </div>
            </div>
        </div>
    </div>
    </main>
@endsection