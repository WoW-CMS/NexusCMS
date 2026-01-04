<?php

namespace Modules\Armory\Domain\Interfaces;

interface ArmoryRepositoryInterface
{
    public function getCharacter(int $guid);
    public function getCharacterItems(int $guid);
    public function getAllCharacters();
    public function search(string $q, string $faction, string $class, int $minLevel);
    public function getGuildByMember(int $memberGuid);
    public function getGuildRankMember(int $guildId, int $memberGuid);
    public function getAchievementsCharacter(int $guid);
    public function getSkillCharacter(int $guid);
    public function getArenaTeam(int $guid);
    public function getRealmById(int $id);
}