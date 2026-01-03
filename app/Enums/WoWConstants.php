<?php

namespace App\Enums;

class WoWConstants
{
    // Expansions
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
    public const EXPANSION_MIDNIGHT = 11;
    
    // Classes
    public const CLASS_WARRIOR = 1;
    public const CLASS_PALADIN = 2;
    public const CLASS_HUNTER = 3;
    public const CLASS_ROGUE = 4;
    public const CLASS_PRIEST = 5;
    public const CLASS_DEATH_KNIGHT = 6;
    public const CLASS_SHAMAN = 7;
    public const CLASS_MAGE = 8;
    public const CLASS_WARLOCK = 9;
    public const CLASS_MONK = 10;
    public const CLASS_DRUID = 11;
    public const CLASS_DEMON_HUNTER = 12;
    public const CLASS_EVOKER = 13;

    // Races
    public const RACE_HUMAN = 1;
    public const RACE_ORC = 2;
    public const RACE_DWARF = 3;
    public const RACE_NIGHT_ELF = 4;
    public const RACE_UNDEAD = 5;
    public const RACE_TAUREN = 6;
    public const RACE_GNOME = 7;
    public const RACE_TROLL = 8;
    public const RACE_GOBLIN = 9;
    public const RACE_BLOODELF = 10;
    public const RACE_DRAENEI = 11;
    public const RACE_WORGEN = 22;
    public const RACE_PANDAREN = 24; // Neutral Pandaren
    public const RACE_PANDAREN_ALLIANCE = 25; // Alliance Pandaren
    public const RACE_PANDAREN_HORDE = 26; // Horde Pandaren
    public const RACE_NIGHTBORNE = 27; // Nightborne
    public const RACE_HIGHMOUNTAIN_TAUREN = 28; // Highmountain Tauren
    public const RACE_VOID_ELF = 29; // Void Elf
    public const RACE_LIGHTFORGED_DRAENEI = 30; // Lightforged Draenei
    public const RACE_ZANDALARI_TROLL = 31; // Zandalari Troll
    public const RACE_KUL_TIRAN = 32; // Kul'Tiran
    public const RACE_DARK_IRON_DWARF = 34; // Dark Iron Dwarf
    public const RACE_VULPERA = 35; // Vulpera
    public const RACE_MAGHAR_ORC = 36; // Maghar Orc
    public const RACE_MECAGHOME = 37; // Mecaghome
    public const RACE_DRACTHYR_ALLIANCE = 52; // Draconic
    public const RACE_DRACTHYR_HORDE = 70; // Draconic
    public const RACE_EARTHEN_HORDE  = 84; // Earthen
    public const RACE_EARTHEN_ALLIANCE  = 85; // Earthen

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
        self::EXPANSION_MIDNIGHT     => 'Midnight',
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
        self::EXPANSION_MIDNIGHT     => 'border-pink-500',
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
        self::EXPANSION_MIDNIGHT     => 90,
    ];
    
    public const CLASS_NAMES = [
        1  => 'Warrior',
        2  => 'Paladin',
        3  => 'Hunter',
        4  => 'Rogue',
        5  => 'Priest',
        6  => 'Death Knight',
        7  => 'Shaman',
        8  => 'Mage',
        9  => 'Warlock',
        10 => 'Monk',
        11 => 'Druid',
        12 => 'Demon Hunter',
        13 => 'Evoker',
    ];

    public const CLASS_COLORS = [
        self::CLASS_WARRIOR => 'border-red-500',
        self::CLASS_PALADIN => 'border-yellow-500',
        self::CLASS_HUNTER => 'border-green-500',
        self::CLASS_ROGUE => 'border-orange-500',
        self::CLASS_PRIEST => 'border-purple-500',
        self::CLASS_DEATH_KNIGHT => 'border-blue-500',
        self::CLASS_SHAMAN => 'border-pink-500',
        self::CLASS_MAGE => 'border-cyan-500',
        self::CLASS_WARLOCK => 'border-gray-500',
        self::CLASS_MONK => 'border-lime-500',
        self::CLASS_DRUID => 'border-orange-500',
        self::CLASS_DEMON_HUNTER => 'border-purple-500',
        self::CLASS_EVOKER => 'border-yellow-500',
    ];

    public const RACE_NAMES = [
        1  => 'Human',
        2  => 'Orc',
        3  => 'Dwarf',
        4  => 'Night Elf',
        5  => 'Undead',
        6  => 'Tauren',
        7  => 'Gnome',
        8  => 'Troll',
        9  => 'Goblin',
        10 => 'Blood Elf',
        11 => 'Draenei',
        22 => 'Worgen',
        24 => 'Pandaren',
        25 => 'Pandaren Alliance',
        26 => 'Pandaren Horde',
        27 => 'Nightborne',
        28 => 'Highmountain Tauren',
        29 => 'Void Elf',
        30 => 'Lightforged Draenei',
        31 => 'Zandalari Troll',
        32 => 'Kul Tiran Human',
        34 => 'Dark Iron Dwarf',
        35 => 'Vulpera',
        36 => "Mag'har Orc",
        37 => 'Mechagnome',
        52 => 'Draconic Alliance',
        70 => 'Draconic Horde',
        84 => 'Earthen Horde',
        85 => 'Earthen Alliance',
    ];
}
