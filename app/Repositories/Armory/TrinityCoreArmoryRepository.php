<?php

namespace App\Repositories\Armory;

use App\Interfaces\ArmoryRepositoryInterface;
use App\Traits\ConnectsToExternalDatabase;
use Illuminate\Database\Connection;

class TrinityCoreArmoryRepository implements ArmoryRepositoryInterface
{
    use ConnectsToExternalDatabase;

    protected ?Connection $auth = null;
    protected ?Connection $characters = null;
    protected ?Connection $world = null;

    public function __construct(array $realmConfig)
    {
        $this->auth = $this->connectToExternalDatabase($realmConfig['auth_database'], 'auth');
        $this->characters = $this->connectToExternalDatabase($realmConfig['character_database'], 'characters');
        $this->world = $this->connectToExternalDatabase($realmConfig['world_database'], 'world');
    }

    public function getCharacter(int $guid)
    {
        return $this->characters
            ->table('characters')
            ->where('guid', $guid)
            ->first();
    }

    public function getCharacterItems(int $guid)
    {
        return $this->characters
            ->table('character_inventory')
            ->where('guid', $guid)
            ->get();
    }

    public function getGuild(int $guildId)
    {
        return $this->characters
            ->table('guild')
            ->where('guildid', $guildId)
            ->first();
    }
}
