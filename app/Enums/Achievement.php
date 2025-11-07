<?php

namespace App\Enums;

use App\Enums\WoWConstants;

class Achievement extends WoWConstants
{
    /**
     * Block achievements that should not appear (invalid, debug, or hidden ones)
     */
    public const BLOCK_ACHIEVEMENTS = [
        13169, // Remove Tailoring Legion Luffa
        10050, // Learn primary professions
        10051, // Learn two primary professions
        13068, // War Campaing Auto-Launch - World Quest
        13149, // [DNT] Player Expansion level is BFA
        13152, // Magni Heart of Azeroth Intro
        13155, // 8.0 Teldrassil Push
        13166, // Remove Alchemy Anti-Venoms
        13167, // Remove Tailoring Bandages
        13170, // Remove inscription recipes.
        16520, // 10.0 DragonFlight - Expansion Quest Launch.
        17730, // Random Login
        18390, // 10.1.5 Warlock Expansion
        18394, // Deleted
    ];

    /**
     * Check if an achievement should be hidden.
     */
    public static function isHidden(int $achievementId): bool
    {
        return in_array($achievementId, self::BLOCK_ACHIEVEMENTS, true);
    }
}
