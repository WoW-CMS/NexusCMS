@extends('layouts.main')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-950 to-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            {{-- Character Header --}}
            <div
                class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border border-gray-700 rounded-3xl p-8 shadow-2xl backdrop-blur-lg">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

                    {{-- 1. Icon + Name + Details --}}
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <div class="p-4 bg-gray-700/80 rounded-full shadow-lg border-4"
                                style="border-color: {{ $item->class->color() }}">
                                <i class="fa-solid fa-shield text-4xl" style="color: {{ $item->class->color() }}"></i>
                            </div>
                            <span
                                class="absolute -bottom-2 -right-2 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full shadow">Lv
                                {{ $item->level }}</span>
                        </div>
                        <div>
                            <h1 class="text-4xl font-extrabold drop-shadow" style="color: {{ $item->class->color() }}">
                                {{ $item->name }}</h1>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-gray-300 text-lg font-medium">
                                <span style="color: {{ $item->class->color() }}">{{ $item->class->label() }}</span>
                                <span class="text-gray-500">|</span>
                                <span>{{ $item->race->label() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Achievement Points & Arena Rating --}}
                    <div class="flex flex-col sm:flex-row sm:space-x-8 space-y-4 sm:space-y-0 justify-end items-end">
                        <div
                            class="bg-gray-800/70 border border-yellow-400 rounded-xl px-6 py-4 text-center shadow-md hover:scale-105 transition">
                            <div class="text-3xl font-bold text-yellow-400 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-trophy"></i>
                                {{ $item->achievement_points ?? 0 }}
                            </div>
                            <div class="text-gray-300 text-sm mt-1">Achievement Points</div>
                        </div>
                        <div
                            class="bg-gray-800/70 border border-purple-400 rounded-xl px-6 py-4 text-center shadow-md hover:scale-105 transition">
                            <div class="text-3xl font-bold text-purple-400 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-medal"></i>
                                {{ $item->arena_rating ?? 0 }}
                            </div>
                            <div class="text-gray-300 text-sm mt-1">Arena Rating</div>
                        </div>
                    </div>

                    {{-- 3. Guild Info --}}
                    <div class="col-span-1 mt-6">
                        @if ($item->guild)
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-users text-blue-400 text-xl"></i>
                                <a href="#" class="text-blue-400 hover:underline font-semibold transition">
                                    {{ $item->guild->guild_name }}
                                </a>
                                <span
                                    class="bg-gray-700/60 text-gray-200 text-xs px-2 py-0.5 rounded ml-2">{{ $item->guild->rank_name }}</span>
                            </div>
                        @else
                            <div class="text-gray-500 text-sm">
                                Not a member of any guild
                            </div>
                        @endif
                    </div>

                    {{-- 4. Last Seen --}}
                    <div class="col-span-1 mt-6 text-gray-400 text-xs text-right">
                        <span class="inline-flex items-center gap-1">
                            <i class="fa-regular fa-clock"></i>
                            Last seen: {{ \Carbon\Carbon::createFromTimestamp($item->logout_time)->diffForHumans() }}
                        </span>
                    </div>

                </div>
            </div>

            {{-- Main Content: Stats & Equipment --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Left Column: Statistics --}}
                <div class="space-y-8 lg:col-span-1">

                    <!-- Statistics Card (Unified Style) -->
                    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6 shadow-lg backdrop-blur space-y-6">
                        <h2 class="text-2xl font-bold text-indigo-400 flex items-center gap-2">
                            <i class="fa-solid fa-chart-bar"></i>
                            Statistics
                        </h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-heart text-red-400 text-xl"></i>
                                    <span class="text-gray-300 font-medium">Health</span>
                                </div>
                                <div class="text-2xl font-extrabold text-white mt-2">{{ $item->health }}</div>
                            </div>
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-bolt text-blue-400 text-xl"></i>
                                    <span class="text-gray-300 font-medium">Mana</span>
                                </div>
                                <div class="text-2xl font-extrabold text-white mt-2">{{ $item->power1 }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <span class="text-gray-300 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-dumbbell text-orange-400"></i>
                                    Strength
                                </span>
                                <span class="text-xl font-bold text-white mt-1">{{ $item->strength }}</span>
                            </div>
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <span class="text-gray-300 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-person-running text-green-400"></i>
                                    Agility
                                </span>
                                <span class="text-xl font-bold text-white mt-1">{{ $item->agility }}</span>
                            </div>
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <span class="text-gray-300 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-hat-wizard text-cyan-400"></i>
                                    Intellect
                                </span>
                                <span class="text-xl font-bold text-white mt-1">{{ $item->intellect }}</span>
                            </div>
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <span class="text-gray-300 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-heart text-pink-400"></i>
                                    Stamina
                                </span>
                                <span class="text-xl font-bold text-white mt-1">{{ $item->stamina }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- PvP Card (Unified Style) -->
                    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6 shadow-lg backdrop-blur space-y-6">
                        <h2 class="text-2xl font-bold text-pink-300 flex items-center gap-2">
                            <i class="fa-solid fa-fire"></i>
                            PvP
                        </h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-medal text-purple-400 text-xl"></i>
                                    <span class="text-gray-300 font-medium">Arena</span>
                                </div>
                                <div class="text-2xl font-extrabold text-purple-400 mt-2">{{ $item->arena_rating ?? 0 }}
                                </div>
                            </div>
                            <div
                                class="flex flex-col items-center bg-gray-900/70 rounded-xl p-4 shadow border border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-skull-crossbones text-red-400 text-xl"></i>
                                    <span class="text-gray-300 font-medium">Kills</span>
                                </div>
                                <div class="text-2xl font-extrabold text-red-400 mt-2">{{ $item->totalKills ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Tabs Section --}}
                <div class="space-y-6 lg:col-span-2" x-data="{ tab: 'equipment' }">
                    <!-- Tabs Header -->
                    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-2 mb-4 flex backdrop-blur-sm shadow">
                        <button class="flex-1 px-4 py-2 rounded-xl font-semibold focus:outline-none transition text-white"
                            :class="tab === 'equipment' ? 'bg-indigo-600 shadow-inner' : 'bg-transparent'"
                            @click="tab = 'equipment'">
                            Equipment
                        </button>
                        <button class="flex-1 px-4 py-2 rounded-xl font-semibold focus:outline-none transition text-white"
                            :class="tab === 'achievements' ? 'bg-indigo-600 shadow-inner' : 'bg-transparent'"
                            @click="tab = 'achievements'">
                            Achievements
                        </button>
                        <button class="flex-1 px-4 py-2 rounded-xl font-semibold focus:outline-none transition text-white"
                            :class="tab === 'statistics' ? 'bg-indigo-600 shadow-inner' : 'bg-transparent'"
                            @click="tab = 'statistics'">
                            Statistics
                        </button>
                    </div>

                    <!-- Equipment Tab -->
                    <div x-show="tab === 'equipment'" x-transition x-cloak>
                        <div id="tab-equipment"
                            class="tab-pane bg-gray-800/50 border border-gray-700 rounded-2xl p-6 backdrop-blur-sm space-y-4 shadow">
                            <h2 class="text-xl font-semibold text-white">Equipment</h2>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($item->equipment as $equip)
                                    <div
                                        class="group flex items-center space-x-3 bg-gray-700/50 border border-gray-600 rounded-lg p-3 hover:bg-gray-700/60 hover:shadow-lg transition">
                                        <div>
                                            <p class="font-semibold">
                                                <a href="#" data-wh-icon-size="large"
                                                    data-wowhead="item={{ $equip->entry }}">
                                                    {{ $equip->name }}</a>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Achievements Tab -->
                    <div x-show="tab === 'achievements'" x-transition x-cloak>
                        <div
                            class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6 backdrop-blur-sm space-y-4 shadow">
                            <h2 class="text-xl font-semibold text-white">Achievements</h2>
                            <div class="text-gray-300">
                                {{-- Example achievements, replace with real data --}}
                                @if (isset($item->achievements) && count($item->achievements))
                                    <ul class="list-disc ml-6 space-y-2">
                                        @foreach ($item->achievements as $achievement)
                                            <li>
                                                <span class="font-medium text-yellow-400">{{ $achievement->title }}</span>
                                                <span
                                                    class="text-xs text-gray-400 ml-2">{{ \Carbon\Carbon::parse($achievement->date)->format('d/m/Y') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-gray-500">No achievements found.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Tab -->
                    <div x-show="tab === 'statistics'" x-transition x-cloak>
                        <div
                            class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6 backdrop-blur-sm space-y-4 shadow">
                            <h2 class="text-xl font-semibold text-white">Statistics</h2>
                            <div class="grid grid-cols-2 gap-4 items-center">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-heart text-red-400"></i>
                                    <span class="text-gray-200 font-medium">Health</span>
                                </div>
                                <div class="text-white text-right font-bold">{{ $item->health }}</div>

                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-bolt text-blue-400"></i>
                                    <span class="text-gray-200 font-medium">Mana</span>
                                </div>
                                <div class="text-white text-right font-bold">{{ $item->power1 }}</div>
                            </div>
                            <hr class="border-gray-700">
                            <div class="grid grid-cols-2 gap-4 text-gray-300">
                                <div class="flex flex-col items-start">
                                    <span>Strength</span>
                                    <span class="text-white font-bold">{{ $item->strength }}</span>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span>Agility</span>
                                    <span class="text-white font-bold">{{ $item->agility }}</span>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span>Intellect</span>
                                    <span class="text-white font-bold">{{ $item->intellect }}</span>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span>Stamina</span>
                                    <span class="text-white font-bold">{{ $item->stamina }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const whTooltips = {
            colorLinks: true,
            iconizeLinks: true,
            renameLinks: true
        };
    </script>
    <script src="https://wow.zamimg.com/js/tooltips.js"></script>
@endsection
