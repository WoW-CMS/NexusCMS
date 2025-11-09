<?php

namespace App\Enums;

use App\Enums\WoWConstants;

class Professions extends WoWConstants
{
    /**
     * Profession types
     */
    public const TYPE_PRIMARY = 'primary';
    public const TYPE_SECONDARY = 'secondary';

    /**
     * List of all professions with their official WoW IDs,
     * names, type (primary/secondary), and icon URLs.
     */
    public const PROFESSIONS = [
        // === Primary Professions ===
        164 => [
            'name' => 'Blacksmithing',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_blacksmithing.jpg',
        ],
        165 => [
            'name' => 'Leatherworking',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_leatherworking.jpg',
        ],
        171 => [
            'name' => 'Alchemy',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_alchemy.jpg',
        ],
        182 => [
            'name' => 'Herbalism',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_herbalism.jpg',
        ],
        186 => [
            'name' => 'Mining',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_mining.jpg',
        ],
        197 => [
            'name' => 'Tailoring',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_tailoring.jpg',
        ],
        202 => [
            'name' => 'Engineering',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_engineering.jpg',
        ],
        333 => [
            'name' => 'Enchanting',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_engraving.jpg',
        ],
        755 => [
            'name' => 'Jewelcrafting',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/inv_misc_gem_01.jpg',
        ],
        773 => [
            'name' => 'Inscription',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/inv_inscription_tradeskill01.jpg',
        ],
        393 => [
            'name' => 'Skinning',
            'type' => self::TYPE_PRIMARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/inv_misc_pelt_wolf_01.jpg',
        ],

        // === Secondary Professions ===
        129 => [
            'name' => 'First Aid',
            'type' => self::TYPE_SECONDARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/spell_holy_sealofsacrifice.jpg',
        ],
        185 => [
            'name' => 'Cooking',
            'type' => self::TYPE_SECONDARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/inv_misc_food_15.jpg',
        ],
        356 => [
            'name' => 'Fishing',
            'type' => self::TYPE_SECONDARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_fishing.jpg',
        ],
        794 => [
            'name' => 'Archaeology',
            'type' => self::TYPE_SECONDARY,
            'icon' => 'https://wow.zamimg.com/images/wow/icons/large/trade_archaeology.jpg',
        ],
    ];

    // === Utility Methods ===

    /**
     * Get the name of a profession by its ID.
     */
    public static function getName(int $id): ?string
    {
        return self::PROFESSIONS[$id]['name'] ?? null;
    }

    /**
     * Get the type (primary/secondary) of a profession by ID.
     */
    public static function getType(int $id): ?string
    {
        return self::PROFESSIONS[$id]['type'] ?? null;
    }

    /**
     * Get the icon URL for a given profession ID.
     */
    public static function getIcon(int $id): ?string
    {
        return self::PROFESSIONS[$id]['icon'] ?? null;
    }

    /**
     * Return all professions filtered by type.
     */
    public static function getByType(string $type): array
    {
        return array_filter(self::PROFESSIONS, fn($prof) => $prof['type'] === $type);
    }

    /**
     * Check if a profession ID exists in the list.
     */
    public static function isValid(int $id): bool
    {
        return isset(self::PROFESSIONS[$id]);
    }
}
