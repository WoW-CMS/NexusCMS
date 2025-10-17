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
                    @foreach ($data as $player)
                    <div class="bg-gray-800 rounded-lg p-6 mb-8 border border-gray-700">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-6">
                                <img src="{{ App\Helpers\RealmHelper::getWoWConstant('avatar', $player->race) }}" 
                                    alt="Character" class="w-24 h-24 rounded-lg border-2 border-blue-500">
                                <div>
                                    <h2 class="text-3xl font-bold cinzel mb-2">{{ $player->name }}</h2>
                                    <div class="flex items-center gap-4 text-gray-400">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-shield-alt text-blue-500"></i> Level {{ $player->level }} {{ App\Helpers\RealmHelper::getWoWConstant('race', $player->race) }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-user text-green-500"></i> {{ App\Helpers\RealmHelper::getWoWConstant('class', $player->class) }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-server text-purple-500"></i> Realm Name
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="inline-block {{ App\Helpers\RealmHelper::getFactionByRace($player->race) === 'Alliance' ? 'bg-blue-600' : 'bg-red-600' }} text-white px-3 py-1 rounded text-sm font-medium">
                                            @if (App\Helpers\RealmHelper::getFactionByRace($player->race) === 'Alliance')
                                                <i class="fas fa-shield-alt mr-1"></i> Alliance
                                            @else
                                                <i class="fas fa-fist-raised mr-1"></i> Horde
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-yellow-500 mb-1">2,456</div>
                                <div class="text-gray-400 text-sm">Achievement Points</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
@endsection
