<?php

namespace App\Interfaces;

interface ArmoryRepositoryInterface
{
    public function getCharacter(int $guid);
    public function getCharacterItems(int $guid);
    public function getGuild(int $guid);
}