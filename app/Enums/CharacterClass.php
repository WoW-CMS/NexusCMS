<?php
// app/Enums/CharacterClass.php

namespace App\Enums;

enum CharacterClass: int
{
    case WARRIOR = 1;
    case PALADIN = 2;
    case HUNTER = 3;
    case ROGUE = 4;
    case PRIEST = 5;
    case DEATH_KNIGHT = 6;
    case SHAMAN = 7;
    case MAGE = 8;
    case WARLOCK = 9;
    case DRUID = 11;

    public function label(): string
    {
        return match ($this) {
            self::WARRIOR => 'Warrior',
            self::PALADIN => 'Paladin',
            self::HUNTER => 'Hunter',
            self::ROGUE => 'Rogue',
            self::PRIEST => 'Priest',
            self::DEATH_KNIGHT => 'Death Knight',
            self::SHAMAN => 'Shaman',
            self::MAGE => 'Mage',
            self::WARLOCK => 'Warlock',
            self::DRUID => 'Druid',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::WARRIOR => '#C69B6D',
            self::PALADIN => '#F48CBA',
            self::HUNTER => '#AAD372',
            self::ROGUE => '#FFF468',
            self::PRIEST => '#FFFFFF',
            self::DEATH_KNIGHT => '#C41E3A',
            self::SHAMAN => '#0070DD',
            self::MAGE => '#3FC7EB',
            self::WARLOCK => '#8788EE',
            self::DRUID => '#FF7C0A',
        };
    }
}
