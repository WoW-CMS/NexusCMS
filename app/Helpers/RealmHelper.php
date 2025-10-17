<?php

namespace App\Helpers;

use App\Models\Realm;
use App\Enums\WowExpansion;
use App\Enums\WowVersion;
use App\Enums\WoWConstants;

class RealmHelper
{
    /**
     * Get all realms from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Realm>
     */
    public static function all()
    {
        return Realm::all();
    }

    /**
     * Find a realm by its ID
     *
     * @param int $id The ID of the realm to find
     * @return Realm|null Returns the realm if found, null otherwise
     */
    public static function find($id)
    {
        return Realm::find($id);
    }

    /**
     * Get the enum WoWConstants for a given type and ID
     * 
     * @param string $type The type of constant to get (expansion, version, color, class)
     * @param int|null $id The ID of the constant to get
     * @return string|null Returns the constant value for the given type and ID, or null if not found
     */
    public static function getWoWConstant(string $type = 'expansion', ?int $id = null)
    {
        if ($id === null) {
            return null;
        }

        switch ($type) {
            case 'expansion':
                return WoWConstants::EXPANSION_NAMES[$id] ?? null;
            case 'version':
                return WoWConstants::EXPANSION_VERSIONS[$id] ?? null;
            case 'color':
                return WoWConstants::EXPANSION_COLORS[$id] ?? null;
            case 'class':
                return WoWConstants::CLASS_NAMES[$id] ?? null;
            case 'race':
                return WoWConstants::RACE_NAMES[$id] ?? null;
            default:
                return null;
        }
    }

    /**
     * Get the faction of a race
     * 
     * @param int $race The race ID
     * @return string|null Returns the faction of the race, or null if not found
     */
    public static function getFactionByRace(int $race): ?string
    {
        $allianceRaces = [
            WoWConstants::RACE_HUMAN,
            WoWConstants::RACE_DWARF,
            WoWConstants::RACE_NIGHT_ELF,
            WoWConstants::RACE_GNOME,
            WoWConstants::RACE_DRAENEI,
            WoWConstants::RACE_VOID_ELF,
        ];

        $hordeRaces = [
            WoWConstants::RACE_ORC,
            WoWConstants::RACE_UNDEAD,
            WoWConstants::RACE_TAUREN,
            WoWConstants::RACE_TROLL,
            WoWConstants::RACE_BLOODELF,
            WoWConstants::RACE_GOBLIN,
            WoWConstants::RACE_MAGHAR_ORC,
            WoWConstants::RACE_HIGHMOUNTAIN_TAUREN,
            WoWConstants::RACE_DARK_IRON_DWARF,
            WoWConstants::RACE_PANDAREN, // Pandaren pueden elegir, tal vez devuelves null
            WoWConstants::RACE_WOLF,     // si es un NPC
        ];

        if (in_array($race, $allianceRaces, true)) {
            return 'Alliance';
        }

        if (in_array($race, $hordeRaces, true)) {
            return 'Horde';
        }

        return null;
    }

}
