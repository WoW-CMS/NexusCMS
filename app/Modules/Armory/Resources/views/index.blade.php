@extends('layouts.main')

@section('content')
<!-- Hero Section with Search -->
<div class="bg-gradient-to-b from-gray-800 to-gray-900 py-16 border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-6xl font-bold cinzel mb-4 bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                Character Armory
            </h1>
            <p class="text-gray-400 text-xl">Search and explore character profiles across all realms</p>
        </div>

        <!-- Advanced Search -->
        <form action="{{ route('armory.index') }}" method="GET" class="search-container max-w-4xl mx-auto p-8 rounded-2xl shadow-2xl border border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Character Name</label>
                    <input type="text"
                           name="q"
                           id="searchInput"
                           placeholder="Enter character name..."
                           value="{{ old('q', request('q')) }}"
                           class="w-full px-6 py-4 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Realm</label>
                    <select name="realm" class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Any</option>
                        @foreach(App\Models\Realm::all() as $realm)
                            <option value="{{ $realm->id }}" {{ request('realm') == $realm->id ? 'selected' : '' }}>{{ $realm->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Faction</label>
                    <select name="faction" class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Factions</option>
                        <option value="horde" {{ request('faction') == 'horde' ? 'selected' : '' }}>Horde</option>
                        <option value="alliance" {{ request('faction') == 'alliance' ? 'selected' : '' }}>Alliance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Class</label>
                    <select name="class" class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Classes</option>
                        <option value="1" {{ request('class') == '1' ? 'selected' : '' }}>Warrior</option>
                        <option value="2" {{ request('class') == '2' ? 'selected' : '' }}>Paladin</option>
                        <option value="3" {{ request('class') == '3' ? 'selected' : '' }}>Hunter</option>
                        <option value="4" {{ request('class') == '4' ? 'selected' : '' }}>Rogue</option>
                        <option value="5" {{ request('class') == '5' ? 'selected' : '' }}>Priest</option>
                        <option value="6" {{ request('class') == '6' ? 'selected' : '' }}>Death Knight</option>
                        <option value="7" {{ request('class') == '7' ? 'selected' : '' }}>Shaman</option>
                        <option value="8" {{ request('class') == '8' ? 'selected' : '' }}>Mage</option>
                        <option value="9" {{ request('class') == '9' ? 'selected' : '' }}>Warlock</option>
                        <option value="11" {{ request('class') == '11' ? 'selected' : '' }}>Druid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Min Level</label>
                    <select name="min_level" class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Any</option>
                        <option value="80" {{ request('min_level') == '80' ? 'selected' : '' }}>80</option>
                        <option value="70" {{ request('min_level') == '70' ? 'selected' : '' }}>70</option>
                        <option value="60" {{ request('min_level') == '60' ? 'selected' : '' }}>60</option>
                    </select>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-2xl">
                <i class="fas fa-search mr-3"></i> Search Characters
            </button>
        </form>
    </div>
    </div>

<!-- Results Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold cinzel mb-2">Search Results</h2>
            <p class="text-gray-400">
                Found <span class="text-blue-400 font-bold"></span> characters
            </p>
        </div>
    </div>

    @if(filled(request('q')) || filled(request('faction')) || filled(request('class')) || filled(request('min_level')))
        @if($data->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="characterGrid">
                @foreach($data as $player)
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-all">
                        <div class="flex items-start gap-4 mb-4">
                            <img src="https://wow.zamimg.com/images/wow/icons/large/achievement_character_{{ strtolower(App\Helpers\RealmHelper::getWoWConstant('race', $player->race)) }}_{{ strtolower($player->gender == 1 ? 'male' : 'female') }}.jpg"
                                 alt="{{ $player->name }}"
                                 class="w-20 h-20 rounded-lg border-2 {{ App\Helpers\RealmHelper::getFactionByRace($player->race) === 'horde' ? 'border-red-600' : 'border-blue-600' }}">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-2xl font-bold class-{{ strtolower($player->class) }}">{{ $player->name }}</h3>
                                    <span class="level-badge bg-gray-700 text-white px-2 py-1 rounded text-xs font-bold">{{ $player->level }}</span>
                                </div>
                                <p class="text-gray-400 text-sm mb-2">{{ App\Helpers\RealmHelper::getWoWConstant('race', $player->race) }} {{ App\Helpers\RealmHelper::getWoWConstant('class', $player->class) }}</p>
                                <div class="guild-tag inline-block px-3 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                    <i class="fas fa-shield-alt mr-1"></i> &lt;{{ $player->guild_name ?? 'N/A' }}&gt;
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-700 pt-4 mb-4">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-400">Realm</span>
                                <span class="text-white font-medium">{{ $player->realm_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-400">Item Level</span>
                                <span class="text-purple-400 font-bold">{{ $player->item_level ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Achievement Points</span>
                                <span class="text-yellow-500 font-bold">{{ number_format($player->achievement_points ?? 0) }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2 mb-4">
                            <div class="stats-pill flex-1 px-3 py-2 rounded-lg text-center bg-gray-700">
                                <div class="text-xs text-gray-400">Arena</div>
                                <div class="text-sm font-bold text-purple-400">{{ $player->arena_points ?? 0 }}</div>
                            </div>
                            <div class="stats-pill flex-1 px-3 py-2 rounded-lg text-center bg-gray-700">
                                <div class="text-xs text-gray-400">HKs</div>
                                <div class="text-sm font-bold text-red-400">{{ number_format($player->honorable_kills ?? 0) }}</div>
                            </div>
                        </div>

                        <a href="{{ route('armory.show.realm', [$player->guid, $player->realm_id ?? 1]) }}"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 inline-block text-center">
                            <i class="fas fa-eye mr-2"></i> View Profile
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
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
                    <p class="text-yellow-200 text-sm">We couldn't find any character matching your criteria. Please adjust your filters and try again.</p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection