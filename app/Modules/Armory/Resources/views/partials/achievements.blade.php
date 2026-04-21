<div id="achievements-tab" class="tab-content hidden">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($achievements as $item)
        <div class="achievement-badge rounded-xl p-6">
            <div class="flex items-start gap-4">
                <a class="text-xl font-bold text-yellow-400 mb-2" href="https://www.wowhead.com/achievement={{ $item['id'] }}" data-wh-icon-size="large" data-wowhead="achievement={{ $item['id'] }}">
                    <img src="https://wow.zamimg.com/images/wow/icons/large/{{ $item['icon'] ?? 'achievement_boss_lichking' }}.jpg" 
                        class="w-20 h-20 rounded-lg border-2 border-yellow-500">
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>