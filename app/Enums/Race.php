<?php
namespace App\Enums;

enum Race: int
{
    case HUMAN = 1;
    case ORC = 2;
    case DWARF = 3;
    case NIGHT_ELF = 4;
    case UNDEAD = 5;
    case TAUREN = 6;
    case GNOME = 7;
    case TROLL = 8;
    case GOBLIN = 9;
    case BLOOD_ELF = 10;
    case DRAENEI = 11;
    case FEL_ORC = 12;
    case NAGA = 13;
    case BROKEN = 14;
    case SKELETON = 15;
    case VRYKUL = 16;
    case TUSKARR = 17;
    case FOREST_TROLL = 18;
    case TAUNKA = 19;
    case N_SKELETON = 20;
    case ICE_TROLL = 21;

    public function label(): string
    {
        return match ($this) {
            self::HUMAN => 'Human',
            self::ORC => 'Orc',
            self::DWARF => 'Dwarf',
            self::NIGHT_ELF => 'Night Elf',
            self::UNDEAD => 'Undead',
            self::TAUREN => 'Tauren',
            self::GNOME => 'Gnome',
            self::TROLL => 'Troll',
            self::GOBLIN => 'Goblin',
            self::BLOOD_ELF => 'Blood Elf',
            self::DRAENEI => 'Draenei',
            self::FEL_ORC => 'Fel Orc',
            self::NAGA => 'Naga',
            self::BROKEN => 'Broken',
            self::SKELETON => 'Skeleton',
            self::VRYKUL => 'Vrykul',
            self::TUSKARR => 'Tuskarr',
            self::FOREST_TROLL => 'Forest Troll',
            self::TAUNKA => 'Taunka',
            self::N_SKELETON => 'Northrend Skeleton',
            self::ICE_TROLL => 'Ice Troll',
        };
    }
}
