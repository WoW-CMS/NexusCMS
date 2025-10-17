@extends('layouts.main')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-950 to-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="relative text-center mb-16">
                <div class="absolute inset-0 flex items-center justify-center opacity-5">
                    <div class="w-96 h-96 bg-blue-500 rounded-full filter blur-3xl"></div>
                </div>
                <div class="relative">
                    <h2
                        class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 sm:text-6xl mb-4">
                        WoW Armory
                    </h2>
                    <p class="mt-4 text-xl text-gray-400 max-w-3xl mx-auto">
                        Search for World of Warcraft characters
                    </p>
                </div>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('armory') }}" method="GET" class="mb-12">
                <div class="flex justify-center">
                    <div class="relative w-full max-w-xl">
                        @php
                            $search = request('q');
                        @endphp
                        <input name="q" type="text" placeholder="Search by player name"
                            value="{{ old('q', $search) }}"
                            class="w-full bg-gray-800 bg-opacity-50 placeholder-gray-500 text-gray-200 rounded-full py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 12.65z" />
                            </svg>
                        </div>
                    </div>
                    <button type="submit"
                        class="ml-4 inline-block px-6 py-3 bg-indigo-600 text-white rounded-full hover:bg-indigo-500 transition">
                        Search
                    </button>
                </div>
            </form>

            @if (filled($search))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Character Header -->
                @if ($data->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($data as $player)
                    <a href="" class="group relative bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-indigo-500 hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 overflow-hidden">
                        {{-- Faction glow --}}
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-300
                            {{ App\Helpers\RealmHelper::getFactionByRace($player->race) === 'Alliance' ? 'bg-blue-500' : 'bg-red-500' }}"></div>

                        <div class="relative z-10 flex flex-col h-full">
                            <!-- Header -->
                            <div class="flex items-center gap-4 mb-4">
                                <img src="{{ App\Helpers\RealmHelper::getWoWConstant('avatar', $player->race) }}" 
                                    alt="{{ $player->name }}" 
                                    class="w-16 h-16 rounded-full border-2 border-gray-700 group-hover:border-indigo-500 transition duration-300">
                                <div>
                                    <h3 class="text-xl font-bold cinzel text-gray-100 group-hover:text-indigo-400 transition">{{ $player->name }}</h3>
                                    <span class="inline-flex items-center gap-2 px-2 py-1 rounded-full text-xs font-semibold
                                        {{ App\Helpers\RealmHelper::getFactionByRace($player->race) === 'Alliance' ? 'bg-blue-900/50 text-blue-300' : 'bg-red-900/50 text-red-300' }}">
                                        @if (App\Helpers\RealmHelper::getFactionByRace($player->race) === 'Alliance')
                                            <i class="fas fa-shield-alt"></i> Alliance
                                        @else
                                            <i class="fas fa-fist-raised"></i> Horde
                                        @endif
                                    </span>
                                    <span class="inline-flex items-center gap-2 px-2 py-1 text-xs">
                                        <i class="fas fa-home text-purple-400"></i>
                                        <span>{{ $player->guild_name ?? 'No Guild' }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="flex-1 grid grid-cols-2 gap-3 text-sm text-gray-300">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-blue-400"></i>
                                    <span>Level {{ $player->level }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-green-400"></i>
                                    <span>{{ App\Helpers\RealmHelper::getWoWConstant('race', $player->race) }} / {{ App\Helpers\RealmHelper::getWoWConstant('class', $player->class) }}</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-4 pt-3 border-t border-gray-700 text-center">
                                <div class="text-yellow-400 font-bold text-lg">2,456</div>
                                <div class="text-gray-500 text-xs">Achievement Points</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                    <div class="max-w-md mx-auto">
                        <div class="bg-yellow-900/30 border border-yellow-700 rounded-lg p-4 text-center">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 class="text-yellow-400 font-semibold">No Character Found</h3>
                            </div>
                            <p class="text-yellow-200 text-sm">We couldn't find any character with that name. Please check the spelling and try again.</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
