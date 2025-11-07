@extends('layouts.main')

@section('content')
<div class="hero-bg pt-32 pb-16 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex items-start gap-8 flex-wrap lg:flex-nowrap">
            <!-- Character Portrait -->
            <div class="flex-shrink-0">
                <div class="relative">
                    <img src="https://wow.zamimg.com/images/wow/icons/large/achievement_character_{{ strtolower(App\Helpers\RealmHelper::getWoWConstant('race', $character->race)) }}_{{ strtolower($character->gender == 1 ? 'male' : 'female') }}.jpg" 
                        alt="Character" class="w-48 h-48 rounded-2xl border-4 border-red-600 shadow-2xl">
                    <div class="absolute -bottom-4 -right-4 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full w-20 h-20 flex items-center justify-center border-4 border-gray-900 shadow-xl">
                        <span class="text-3xl font-bold">{{ $character->level }}</span>
                    </div>
                </div>
            </div>

            <!-- Character Info -->
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <h1 class="text-6xl font-bold cinzel class-{{ strtolower(App\Helpers\RealmHelper::getWoWConstant('class', $character->class)) }}">{{ $character->name }}</h1>
                    <span class="px-4 py-2 {{ App\Helpers\RealmHelper::getFactionByRace($character->race) === 'horde' ? 'bg-red-600' : 'bg-blue-600' }} rounded-lg font-bold text-lg">
                        <i class="fas fa-fist-raised mr-2"></i> {{ strtoupper(App\Helpers\RealmHelper::getFactionByRace($character->race)) }}
                    </span>
                </div>
                
                <div class="flex items-center gap-6 text-xl mb-6">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-user text-red-400"></i> {{ App\Helpers\RealmHelper::getWoWConstant('race', $character->race) }} / {{ App\Helpers\RealmHelper::getWoWConstant('class', $character->class) }}
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-server text-purple-400"></i> {{ App\Helpers\RealmHelper::find($realm)->name }}
                    </span>
                </div>

                <!-- Guild -->
                <div class="guild-banner inline-block px-6 py-3 rounded-xl mb-6">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shield-alt text-2xl"></i>
                        <div>
                            <div class="text-sm text-purple-200">{{ $memberRank->rname }} of</div>
                            <div class="text-xl font-bold">&lt;{{ $guild->name }}&gt;</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="stat-card p-4 rounded-xl">
                        <div class="text-gray-400 text-sm mb-1">Item Level</div>
                        <div class="text-3xl font-bold text-purple-400">{{ $promedItemLevel }}</div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="text-gray-400 text-sm mb-1">Achievement Points</div>
                        <div class="text-3xl font-bold text-yellow-500">2,456</div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="text-gray-400 text-sm mb-1">Arena Rating</div>
                        <div class="text-3xl font-bold text-purple-500">2,456</div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="text-gray-400 text-sm mb-1">Honorable Kills</div>
                        <div class="text-3xl font-bold text-red-400">15,234</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Tabs -->
    <div class="mb-8 overflow-x-full">
        <div class="flex gap-2 border-b border-gray-700 min-w-max">
            <button class="tab-btn active px-8 py-4 font-bold rounded-t-lg" onclick="switchTab('equipment')">
                <i class="fas fa-vest mr-2"></i> Equipment
            </button>
            <button class="tab-btn px-8 py-4 font-bold rounded-t-lg text-gray-400 hover:text-white hover:bg-gray-800" onclick="switchTab('stats')">
                <i class="fas fa-chart-bar mr-2"></i> Statistics
            </button>
            <button class="tab-btn px-8 py-4 font-bold rounded-t-lg text-gray-400 hover:text-white hover:bg-gray-800" onclick="switchTab('achievements')">
                <i class="fas fa-trophy mr-2"></i> Achievements
            </button>
            <button class="tab-btn px-8 py-4 font-bold rounded-t-lg text-gray-400 hover:text-white hover:bg-gray-800" onclick="switchTab('pvp')">
                <i class="fas fa-skull-crossbones mr-2"></i> PvP
            </button>
            <button class="tab-btn px-8 py-4 font-bold rounded-t-lg text-gray-400 hover:text-white hover:bg-gray-800" onclick="switchTab('professions')">
                <i class="fas fa-hammer mr-2"></i> Professions
            </button>
        </div>
    </div>

    @include('armory.partials.equipment', ['item' => $item])
    @include('armory.partials.statistics', ['achievement' => $achievement])
    @include('armory.partials.achievements', ['achievement' => $achievement])   
    @include('armory.partials.pvp', ['achievement' => $achievement])
    @include('armory.partials.professions', ['achievement' => $achievement])
</div>

<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('text-gray-400', 'hover:text-white', 'hover:bg-gray-800');
        });
        
        // Show selected tab
        document.getElementById(tabName + '-tab').classList.remove('hidden');
        
        // Add active class to clicked button
        event.target.closest('.tab-btn').classList.add('active');
        event.target.closest('.tab-btn').classList.remove('text-gray-400', 'hover:text-white', 'hover:bg-gray-800');
    }
    
    const whTooltips = {
        colorLinks: true,
        iconizeLinks: true,
        renameLinks: true
    };
</script>
<script src="https://wow.zamimg.com/js/tooltips.js"></script>
@endsection