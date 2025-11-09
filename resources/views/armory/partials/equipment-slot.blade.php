@php
    $qualityName = $equip['wowhead']['quality']['name'] ?? 'Common';
    $qualityClass = 'item-quality-' . strtolower($qualityName);
    $icon = $equip['wowhead']['icon']['name'] ?? 'inv_misc_questionmark';
    $iconUrl = "https://wow.zamimg.com/images/wow/icons/large/{$icon}.jpg";
@endphp

<div class="item-slot rounded-xl p-3 flex items-center gap-3 cursor-pointer
    {{ $equip ? $qualityClass : 'border border-gray-700 text-gray-500' }}"
    @if($equip) data-wowhead="item={{ $equip['entry'] }}" @endif>
    
    <img src="{{ $iconUrl }}" class="w-16 h-16 rounded-lg">
    
    <div class="flex-1 min-w-0">
        <div class="text-xs text-gray-400">{{ $label }}</div>
        <div class="font-bold truncate
            @switch(strtolower($qualityName))
                @case('legendary') text-orange-400 @break
                @case('epic') text-purple-400 @break
                @case('rare') text-blue-400 @break
                @case('uncommon') text-green-400 @break
                @default text-gray-200
            @endswitch">
            {{ $equip['wowhead']['name'] ?? 'No equipado' }}
        </div>
        @if(isset($equip['wowhead']['level']))
            <div class="text-xs text-gray-500">Item Level {{ $equip['wowhead']['level'] }}</div>
        @endif
    </div>

    @if($equip && in_array(strtolower($qualityName), ['epic', 'legendary']))
        <div class="text-2xl {{ strtolower($qualityName) === 'legendary' ? 'text-orange-400' : 'text-purple-400' }}">
            <i class="fas fa-gem"></i>
        </div>
    @endif
</div>
