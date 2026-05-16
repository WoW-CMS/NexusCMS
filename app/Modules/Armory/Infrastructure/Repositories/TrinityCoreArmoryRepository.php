<?php
namespace Modules\Armory\Infrastructure\Repositories;

use App\Enums\Professions;
use Modules\Armory\Domain\Interfaces\ArmoryRepositoryInterface;
use App\Traits\ConnectsToExternalDatabase;
use App\Enums\Achievement;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;

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
     * Get connection to world database
     *
     * @return ?Connection Connection to world database or null if connection failed
     */
    protected function getWorld(): ?Connection
    {
        if (!$this->world) {
            $this->world = $this->connectToExternalDatabase($this->realmConfig['world_database'], 'world');
        }
        return $this->world;
    }

    /**
     * Get all characters from characters database with pagination
     *
     * @return Collection Collection of characters or empty collection if connection failed
     */
    public function getAllCharacters()
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('characters')
            ->select(['guid', 'name', 'race', 'class', 'level', 'gender', 'account'])
            ->limit(500)
            ->get();
    }

    /**
     * Search characters by name
     *
     * @param string $q Search query
     * @return Collection Collection of characters matching the query or empty collection if connection failed
     */
    public function search(string $q, ?string $faction, ?string $class, ?int $minLevel)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        $query = $conn->table('characters')
            ->select(['guid', 'name', 'race', 'class', 'level', 'gender']);

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        if (!empty($faction)) {
            $races = $faction === 'horde'
                ? [2, 5, 6, 8, 9, 10]
                : [1, 3, 4, 7, 11];

            $query->whereIn('race', $races);
        }

        if ($class) {
            $query->where('class', $class);
        }
        if ($minLevel) {
            $query->where('level', '>=', $minLevel);
        }
        
        return $query->orderByDesc('level')
            ->limit(100)
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
        $characters = $this->getCharacters();
        $world = $this->getWorld();

        if (!$characters || !$world) return collect();

        $rows = $characters->table('character_inventory AS ci')
            ->where('ci.guid', $guid)
            ->whereBetween('ci.slot', [0, 18])
            ->join('item_instance AS ii', 'ii.guid', '=', 'ci.item')
            ->orderBy('ci.slot')
            ->get([
                'ci.bag',
                'ci.slot',
                'ii.itemEntry',
            ]);
        
        return $rows->map(function ($row) {
            return [
                'bag'   => $row->bag,
                'slot'  => $row->slot,
                'entry' => $row->itemEntry,
            ];
        });
    }

    /**
     * Get character achievements by GUID
     *
     * @param int $guid Character GUID
     * @return Collection Collection of character achievements or empty collection if connection failed
     */
    public function getAchievementsCharacter(int $guid)
    {
        $characters = $this->getCharacters();
        $world = $this->getWorld();

        if (!$characters || !$world) return collect();

        $rows = $characters->table('character_achievement')
            ->where('guid', $guid)
            ->whereNotIn('achievement', Achievement::BLOCK_ACHIEVEMENTS)
            ->orderByDesc('date')
            ->limit(10)
            ->get([
                'achievement',
                'date'
            ]);
        
        return $rows->map(function ($row) {
            return [
                'id' => $row->achievement,
                'date' => $row->date,
            ];
        });
    }

    /**
     * Get guild by ID
     *
     * @param int $guildId Guild ID
     * @return Collection Collection of guild or empty collection if connection failed
     */
    public function getGuildByMember(int $memberGuid)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('guild as g')
            ->join('guild_member as gm', 'gm.guildid', '=', 'g.guildid')
            ->where('gm.guid', $memberGuid)
            ->first();
    }

    /**
     * Get guild rank of member
     *
     * @param int $guildId Guild ID
     * @param int $memberGuid Member GUID
     * @return Collection Collection of guild rank or empty collection if connection failed
     */
    public function getGuildRankMember(int $guildId, int $memberGuid)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        return $conn->table('guild_member as gm')
            ->join('guild_rank as gr', 'gr.guildid', '=', 'gm.guildid')
            ->where('gm.guildid', $guildId)
            ->where('gm.guid', $memberGuid)
            ->get([
                'gr.rname',
            ])->first();
    }

    /**
     * Get Skill of character
     *
     * @param int $guid Character GUID
     * @return Collection Collection of skill or empty collection if connection failed
     */
    public function getSkillCharacter(int $guid)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        $skill = $conn->table('character_skills')
            ->where('guid', $guid) 
            ->where('professionSlot', 0)
            ->get([
                'skill',
                'max',
                'value',
            ]);
        
        return $skill->map(function ($row) {
            return [
                'id' => $row->skill,
                'name' => Professions::getName($row->skill),
                'type' => Professions::getType($row->skill),
                'icon' => Professions::getIcon($row->skill),
                'max' => $row->max,
                'value' => $row->value,
            ];
        });
    }

    /**
     * Get Arena Team of character
     *
     * @param int $guid Character GUID
     * @return Collection Collection of arena team or empty collection if connection failed
     */
    public function getArenaTeam(int $guid)
    {
        $conn = $this->getCharacters();
        if (!$conn) return collect();

        $arenaTeam = $conn->table('arena_team')
            ->join('arena_team_member as atm', 'atm.arenaTeamId', '=', 'arena_team.arenaTeamId')
            ->where('atm.guid', $guid)
            ->get([
                'arena_team.arenaTeamId',
                'arena_team.name',
                'arena_team.rating',
                'atm.personalRating',
            ]);
        
        return $arenaTeam->map(function ($row) {
            return [
                'id' => $row->arenaTeamId,
                'name' => $row->name,
                'points' => $row->rating,
                'personalRating' => $row->personalRating,
            ];
        });
    }
}
