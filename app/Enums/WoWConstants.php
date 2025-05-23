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
}
