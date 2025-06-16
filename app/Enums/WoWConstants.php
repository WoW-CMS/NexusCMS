<?php

namespace App\Enums;

class WoWConstants
{
    public const EXPANSION_VANILLA = 0;
    public const EXPANSION_TBC = 1;
    public const EXPANSION_WOTLK = 2;
    public const EXPANSION_CATA = 3;
    public const EXPANSION_MOP = 4;
    public const EXPANSION_WOD = 5;
    public const EXPANSION_LEGION = 6;
    public const EXPANSION_BFA = 7;
    public const EXPANSION_SHADOWLANDS = 8;
    public const EXPANSION_DRAGONFLIGHT = 9;
    public const EXPANSION_WAR_WITHIN = 10;

    public const EXPANSION_NAMES = [
        self::EXPANSION_VANILLA => 'Vanilla',
        self::EXPANSION_TBC => 'The Burning Crusade',
        self::EXPANSION_WOTLK => 'Wrath of the Lich King',
        self::EXPANSION_CATA => 'Cataclysm',
        self::EXPANSION_MOP => 'Mists of Pandaria',
        self::EXPANSION_WOD => 'Warlords of Draenor',
        self::EXPANSION_LEGION => 'Legion',
        self::EXPANSION_BFA => 'Battle for Azeroth',
        self::EXPANSION_SHADOWLANDS => 'Shadowlands',
        self::EXPANSION_DRAGONFLIGHT => 'Dragonflight',
        self::EXPANSION_WAR_WITHIN   => 'The War Within',
    ];

    public const EXPANSION_VERSIONS = [
        self::EXPANSION_VANILLA => '1.x',
        self::EXPANSION_TBC => '2.4.3',
        self::EXPANSION_WOTLK => '3.3.5a',
        self::EXPANSION_CATA => '4.3.4',
        self::EXPANSION_MOP => '5.4.8',
        self::EXPANSION_WOD => '6.2.3',
        self::EXPANSION_LEGION => '7.3.5',
        self::EXPANSION_BFA => '8.3.7',
        self::EXPANSION_SHADOWLANDS => '9.2.7',
        self::EXPANSION_DRAGONFLIGHT => '10.x',
        self::EXPANSION_WAR_WITHIN   => '11.x',
    ];

    public const EXPANSION_COLORS = [
        self::EXPANSION_VANILLA => 'border-purple-500',
        self::EXPANSION_TBC => 'border-blue-500',
        self::EXPANSION_WOTLK => 'border-green-500',
        self::EXPANSION_CATA => 'border-yellow-500',
        self::EXPANSION_MOP => 'border-orange-500',
        self::EXPANSION_WOD => 'border-red-500',
        self::EXPANSION_LEGION => 'border-pink-500',
        self::EXPANSION_BFA => 'border-purple-500',
        self::EXPANSION_SHADOWLANDS => 'border-blue-500',
        self::EXPANSION_DRAGONFLIGHT => 'border-green-500',
        self::EXPANSION_WAR_WITHIN   => 'border-yellow-500',
    ];

    public const EXPANSION_MAX_LEVEL = [
        self::EXPANSION_VANILLA => 60,
        self::EXPANSION_TBC => 70,
        self::EXPANSION_WOTLK => 80,
        self::EXPANSION_CATA => 85,
        self::EXPANSION_MOP => 90, 
        self::EXPANSION_WOD => 100,
        self::EXPANSION_LEGION => 110,
        self::EXPANSION_BFA => 120,
        self::EXPANSION_SHADOWLANDS => 60,
        self::EXPANSION_DRAGONFLIGHT => 70,
        self::EXPANSION_WAR_WITHIN   => 80,
    ];
}
