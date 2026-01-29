<div id="professions-tab" class="tab-content hidden">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($skill as $item)
        <div class="stat-card rounded-xl p-6">
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ $item['icon'] }}" 
                    class="w-20 h-20 rounded-lg border-2 border-orange-500">
                <div>
                    <h3 class="text-2xl font-bold">{{ $item['name'] }}</h3>
                    <p class="text-gray-400">{{ ucfirst($item['type']) }}</p>
                </div>
            </div>
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-400 font-medium">Skill Level</span>
                    <span class="font-bold text-green-400 text-xl">{{ $item['value'] }} / {{ $item['max'] }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-4">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-4 rounded-full" style="width: {{ $item['value'] / $item['max'] * 100 }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>