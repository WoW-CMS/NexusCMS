<?php

namespace Modules\Armory\Services;

use Modules\Armory\Domain\Interfaces\ArmoryRepositoryInterface;
use App\Helpers\RealmHelper;
use App\Services\Parser\WowheadParserService;
use Illuminate\Support\Collection;

class ArmoryService
{
    protected ArmoryRepositoryInterface $armoryRepo;
    protected WowheadParserService $wowheadParser;

    public function __construct(
        ArmoryRepositoryInterface $armoryRepo,
        WowheadParserService $wowheadParser
    ) {
        $this->armoryRepo = $armoryRepo;
        $this->wowheadParser = $wowheadParser;
    }

    public function getCharacterProfile(int $guid): array
    {
        $character = $this->armoryRepo->getCharacter($guid);
        
        if (!$character) {
            return [];
        }

        $items = $this->getEnrichedCharacterItems($guid);
        $guild = $this->armoryRepo->getGuildByMember($character->guid);
        $memberRank = $guild ? $this->armoryRepo->getGuildRankMember($guild->guildid, $character->guid) : null;
        $skill = $this->armoryRepo->getSkillCharacter($character->guid);
        $achievement = $this->armoryRepo->getAchievementsCharacter($guid);
        $promedItemLevel = $this->calculateAverageItemLevel($items);
        $arenaTeam = $this->armoryRepo->getArenaTeam($guid);

        return [
            'character' => $character,
            'items' => $items,
            'guild' => $guild,
            'memberRank' => $memberRank,
            'skill' => $skill,
            'achievement' => $achievement,
            'promedItemLevel' => $promedItemLevel,
            'arenaTeam' => $arenaTeam,
        ];
    }

    public function getEnrichedCharacterItems(int $guid): Collection
    {
        $items = $this->armoryRepo->getCharacterItems($guid);
        
        return $items->map(function ($equip) {
            $data = $this->wowheadParser->parse('item', (string) $equip['entry']);
            return array_merge($equip, ['wowhead' => $data]);
        });
    }

    public function calculateAverageItemLevel(Collection $items): int
    {
        $itemLevels = $items->pluck('wowhead.level')
            ->filter(fn($lvl) => is_numeric($lvl))
            ->toArray();
            
        return (int) RealmHelper::calculateItemLevelPromed($itemLevels);
    }

    public function searchCharacters(
        string $q = '',
        ?string $faction = null,
        ?string $class = null,
        ?int $minLevel = null
    ): Collection {
        return $this->armoryRepo->search($q, $faction, $class, $minLevel);
    }
}