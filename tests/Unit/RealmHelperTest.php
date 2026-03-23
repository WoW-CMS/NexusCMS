<?php

namespace Tests\Unit;

use App\Enums\WoWConstants;
use App\Helpers\RealmHelper;
use PHPUnit\Framework\TestCase;

class RealmHelperTest extends TestCase
{
    public function test_get_wow_constant_returns_null_when_id_is_null(): void
    {
        $this->assertNull(RealmHelper::getWoWConstant('expansion', null));
    }

    public function test_get_wow_constant_returns_expected_values(): void
    {
        $this->assertSame('Vanilla', RealmHelper::getWoWConstant('expansion', WoWConstants::EXPANSION_VANILLA));
        $this->assertSame('3.3.5a', RealmHelper::getWoWConstant('version', WoWConstants::EXPANSION_WOTLK));
        $this->assertSame('Warrior', RealmHelper::getWoWConstant('class', WoWConstants::CLASS_WARRIOR));
        $this->assertSame('Human', RealmHelper::getWoWConstant('race', WoWConstants::RACE_HUMAN));
    }

    public function test_get_wow_constant_returns_null_for_unknown_type_or_id(): void
    {
        $this->assertNull(RealmHelper::getWoWConstant('unknown', 1));
        $this->assertNull(RealmHelper::getWoWConstant('expansion', 99999));
    }

    public function test_get_faction_by_race_returns_alliance_horde_or_null(): void
    {
        $this->assertSame('alliance', RealmHelper::getFactionByRace(WoWConstants::RACE_HUMAN));
        $this->assertSame('horde', RealmHelper::getFactionByRace(WoWConstants::RACE_ORC));
        $this->assertNull(RealmHelper::getFactionByRace(99999));
    }

    public function test_calculate_item_level_promed_handles_empty_and_valid_arrays(): void
    {
        $this->assertNull(RealmHelper::calculateItemLevelPromed([]));
        $this->assertSame(250.0, RealmHelper::calculateItemLevelPromed([200, 250, 300]));
    }
}
