<?php
namespace App\Repositories\Armory;

use App\Interfaces\ArmoryRepositoryInterface;
use App\Traits\ConnectsToExternalDatabase;
use Illuminate\Database\Connection;

class TrinityCoreArmoryRepository implements ArmoryRepositoryInterface
{
    /**
     * Connect to external databases
     */
    use ConnectsToExternalDatabase;

    /**
     * Connection to auth database
     */
    protected ?Connection $auth = null;
    
    /**
     * Connection to characters database
     */
    protected ?Connection $characters = null;
    
    /**
     * Connection to world database
     */
    protected ?Connection $world = null;
    
    /**
     * Array with realm configuration
     */
    protected array $realmConfig;

    /**
     * Constructor
     *
     * @param array $realmConfig Array with realm configuration
     */
    public function __construct(array $realmConfig)
    {
        $this->realmConfig = $realmConfig;
    }

    /**
     * Get connection to characters database
     *
     * @return ?Connection Connection to characters database or null if connection failed
     */
    protected function getCharacters(): ?Connection
    {
        if (!$this->characters) {
            $this->characters = $this->connectToExternalDatabase($this->realmConfig['character_database'], 'characters');
        }
        return $this->characters;
    }

    /**
     * Get all characters from characters database
     *
     * @return Collection Collection of all characters or empty collection if connection failed
     */
    public function getAllCharacters()
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('characters')->get();
    }

    /**
     * Search characters by name
     *
     * @param string $q Search query
     * @return Collection Collection of characters matching the query or empty collection if connection failed
     */
    public function search(string $q)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('characters')
                    ->where('name', 'like', "%{$q}%")
                    ->get();
    }

    /**
     * Get character by GUID
     *
     * @param int $guid Character GUID
     * @return Collection Collection of character or empty collection if connection failed
     */
    public function getCharacter(int $guid)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('characters')->where('guid', $guid)->first();
    }

    /**
     * Get character items by GUID
     *
     * @param int $guid Character GUID
     * @return Collection Collection of character items or empty collection if connection failed
     */
    public function getCharacterItems(int $guid)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('character_inventory')->where('guid', $guid)->get();
    }

    /**
     * Get guild by ID
     *
     * @param int $guildId Guild ID
     * @return Collection Collection of guild or empty collection if connection failed
     */
    public function getGuild(int $guildId)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('guild')->where('guildid', $guildId)->first();
    }
}

