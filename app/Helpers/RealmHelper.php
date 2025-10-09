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
            default:
                return null;
        }
    }
}
