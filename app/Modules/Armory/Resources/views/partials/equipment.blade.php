<div id="equipment-tab" class="tab-content">
    @php
        $slotOrder = [
            'Head', 'Neck', 'Shoulders', 'Back', 'Chest', 'Wrist',
            'Hands', 'Waist', 'Legs', 'Feet', 'Finger 1', 'Finger 2',
            'Trinket 1', 'Trinket 2', 'Main Hand', 'Off Hand'
        ];

        $equippedBySlot = [];
        foreach ($item as $equip) {
            $equippedBySlot[$equip['slot']] = $equip;
        }

        $slotNames = [
            0 => 'Head', 1 => 'Neck', 2 => 'Shoulders', 14 => 'Back',
            4 => 'Chest', 8 => 'Wrist', 9 => 'Hands', 5 => 'Waist',
            6 => 'Legs', 7 => 'Feet', 10 => 'Finger 1', 11 => 'Finger 2',
            12 => 'Trinket 1', 13 => 'Trinket 2', 15 => 'Main Hand', 16 => 'Off Hand'
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="space-y-3">
            @foreach (array_slice($slotNames, 0, 6, true) as $slot => $label)
                @php $equip = $equippedBySlot[$slot] ?? null; @endphp
                @include('armory::partials.equipment-slot', ['equip' => $equip, 'label' => $label])
            @endforeach
        </div>
        <div class="character-model rounded-2xl p-8 flex items-center justify-center relative overflow-hidden border border-gray-700">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-gray-900 opacity-30"></div>
            <div class="relative z-10 text-center">
                <img src="https://wow.zamimg.com/images/wow/icons/large/achievement_character_{{ strtolower(App\Helpers\RealmHelper::getWoWConstant('race', $character->race)) }}_{{ strtolower($character->gender == 1 ? 'male' : 'female') }}.jpg"
                     alt="{{ $character->name }}" class="w-80 h-80 mx-auto mb-6 opacity-90 rounded-full border-4 border-red-600 shadow-2xl">
            </div>
        </div>
        <div class="space-y-3">
            @foreach (array_slice($slotNames, 6, 6, true) as $slot => $label)
                @php $equip = $equippedBySlot[$slot] ?? null; @endphp
                @include('armory::partials.equipment-slot', ['equip' => $equip, 'label' => $label])
            @endforeach
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
        @foreach (array_slice($slotNames, 12, 4, true) as $slot => $label)
            @php $equip = $equippedBySlot[$slot] ?? null; @endphp
            <div class="item-slot rounded-xl p-3 cursor-pointer text-center {{ $equip ? 'item-quality-' . strtolower($equip['wowhead']['quality']['name'] ?? 'common') : 'border border-gray-700 text-gray-500' }}"
                 @if($equip) data-wowhead="item={{ $equip['entry'] }}" @endif>
                <img src="{{ $equip && isset($equip['wowhead']['icon']['name'])
                    ? 'https://wow.zamimg.com/images/wow/icons/large/' . $equip['wowhead']['icon']['name'] . '.jpg'
                    : 'https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg' }}"
                    class="w-16 h-16 rounded-lg mx-auto mb-2">
                <div>
                    <div class="text-xs text-gray-400">{{ $label }}</div>
                    <div class="font-bold text-sm truncate @switch(strtolower($equip['wowhead']['quality']['name'] ?? 'common'))
                        @case('legendary') text-orange-400 @break
                        @case('epic') text-purple-400 @break
                        @case('rare') text-blue-400 @break
                        @case('uncommon') text-green-400 @break
                        @default text-gray-200
                    @endswitch">
                        {{ $equip['wowhead']['name'] ?? 'No equipado' }}
                    </div>
                    @if(isset($equip['wowhead']['level']))
                        <div class="text-xs text-gray-500">iLvl {{ $equip['wowhead']['level'] }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>