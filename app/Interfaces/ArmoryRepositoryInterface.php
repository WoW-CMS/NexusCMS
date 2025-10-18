<?php

namespace App\Interfaces;

interface ArmoryRepositoryInterface
{
    public function getCharacter(int $guid);
    public function getCharacterItems(int $guid);
    public function getAllCharacters();
    public function search(string $q);
    public function getGuild(int $guid);
    public function getAchievementsCharacter(int $guid);
}