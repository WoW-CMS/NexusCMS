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
    public const RACE_BLOODELF = 9;
    public const RACE_DRAENEI = 10;
    public const RACE_WOLF = 11;
    public const RACE_GOBLIN = 12;
    public const RACE_PANDAREN = 13;
    public const RACE_DARK_IRON_DWARF = 14;
    public const RACE_HIGHMOUNTAIN_TAUREN = 15;
    public const RACE_VOID_ELF = 16;
    public const RACE_MAGHAR_ORC = 17;
    
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
    
    public const CLASS_NAMES = [
        self::CLASS_WARRIOR => 'Warrior',
        self::CLASS_PALADIN => 'Paladin',
        self::CLASS_HUNTER => 'Hunter',
        self::CLASS_ROGUE => 'Rogue',
        self::CLASS_PRIEST => 'Priest',
        self::CLASS_DEATH_KNIGHT => 'Death Knight',
        self::CLASS_SHAMAN => 'Shaman',
        self::CLASS_MAGE => 'Mage',
        self::CLASS_WARLOCK => 'Warlock',
        self::CLASS_MONK => 'Monk',
        self::CLASS_DRUID => 'Druid',
        self::CLASS_DEMON_HUNTER => 'Demon Hunter',
        self::CLASS_EVOKER => 'Evoker',
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
        self::RACE_HUMAN => 'Human',
        self::RACE_ORC => 'Orc',
        self::RACE_DWARF => 'Dwarf',
        self::RACE_NIGHT_ELF => 'Night Elf',
        self::RACE_UNDEAD => 'Undead',
        self::RACE_TAUREN => 'Tauren',
        self::RACE_GNOME => 'Gnome',
        self::RACE_TROLL => 'Troll',
        self::RACE_BLOODELF => 'Blood Elf',
        self::RACE_DRAENEI => 'Draenei',
        self::RACE_WOLF => 'Worgen',
        self::RACE_GOBLIN => 'Goblin',
        self::RACE_PANDAREN => 'Pandaren',
        self::RACE_DARK_IRON_DWARF => 'Dark Iron Dwarf',
        self::RACE_HIGHMOUNTAIN_TAUREN => 'Highmountain Tauren',
        self::RACE_VOID_ELF => 'Void Elf',
        self::RACE_MAGHAR_ORC => 'Mag\'har Orc',
    ];

    public const RACE_ICONS = [
        self::RACE_HUMAN => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_human_male.jpg',
        self::RACE_ORC => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_orc_male.jpg',
        self::RACE_DWARF => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_dwarf_male.jpg',
        self::RACE_NIGHT_ELF => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_night_elf_male.jpg',
        self::RACE_UNDEAD => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_scourge_male.jpg',
        self::RACE_TAUREN => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_tauren_male.jpg',
        self::RACE_GNOME => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_gnome_male.jpg',
        self::RACE_TROLL => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_troll_male.jpg',
        self::RACE_BLOODELF => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_blood_elf_male.jpg',
        self::RACE_DRAENEI => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_draenei_male.jpg',
        self::RACE_WOLF => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_worgen_male.jpg',
        self::RACE_GOBLIN => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_goblin_male.jpg',
        self::RACE_PANDAREN => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_pandaren_male.jpg',
        self::RACE_DARK_IRON_DWARF => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_dark_iron_dwarf_male.jpg',
        self::RACE_HIGHMOUNTAIN_TAUREN => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_highmountain_tauren_male.jpg',
        self::RACE_VOID_ELF => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_void_elf_male.jpg',
        self::RACE_MAGHAR_ORC => 'https://wow.zamimg.com/images/wow/icons/large/achievement_character_maghar_orc_male.jpg',
    ];
}
