<?php

namespace App\Models;

use App\Enums\ItemQuality;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Race;
use App\Enums\CharacterClass;

class Armory extends Model
{
    /**
     * Different database connection for characters
     * @var string
     */
    protected $connection = 'mysql_characters';
    protected string $worldConnection = 'mysql_world';

    /**
     * Defines the table name
     * @var string
     */
    protected $table = 'characters';

    /**
     * Defines the primary key of the table
     * @var string
     */
    protected $primaryKey = 'guid';

    /**
     * Specifies the type of the primary key
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Summary of casts
     * @var array
     */
    protected $casts = [
        'class' => CharacterClass::class,
        'race' => Race::class,
        'quality' => ItemQuality::class,
    ];

    // We want to expose this virtual attribute:
    protected $appends = [
        'equipment',
        'arena_rating',
        'guild',
    ];

    /**
     * Scope to search by name
     */
    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $term = mb_strtolower($term, 'UTF-8');
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
        }
        return $query;
    }

    /**
     * Scope to add stats via JOIN
     */
    public function scopeWithStats($query)
    {
        return $query
            ->leftJoin('character_stats as cs', 'cs.guid', '=', 'characters.guid')
            ->addSelect([
                'characters.*',
                'cs.strength',
                'cs.agility',
                'cs.intellect',
                'cs.stamina',
            ]);
    }

    /**
     * Accessor: gets the equipped inventory and adds name and displayid
     */
    public function getEquipmentAttribute()
    {
        // 1) Get from the characters DB the instance GUIDs and slots
        $rows = DB::connection('mysql_characters')
            ->table('character_inventory AS ci')
            ->where('ci.guid', $this->guid)
            ->whereBetween('ci.slot', [0, 18])
            ->join('item_instance AS ii', 'ii.GUID', '=', 'ci.item')
            ->orderBy('ci.slot')
            ->get([
                'ci.slot',
                'ii.itemEntry',
            ]);

        if ($rows->isEmpty()) {
            return collect();
        }

        // 2) Extract the unique entries
        $entries = $rows->pluck('itemEntry')->unique()->all();

        // 3) From the world DB, get templates for those entries
        $templates = DB::connection('mysql_world')
            ->table('item_template')
            ->whereIn('entry', $entries)
            ->get(['entry', 'name', 'displayid', 'ItemLevel'])
            ->keyBy('entry');

        // 4) Combine rows + templates
        return $rows->map(function ($row) use ($templates) {
            $tpl = $templates->get($row->itemEntry);
            return (object) [
                'slot' => $row->slot,
                'entry' => $row->itemEntry,
                'name' => $tpl->name ?? 'Unknown',
                'displayid' => $tpl->displayid ?? null,
                'itemLevel' => $tpl->ItemLevel ?? 'Unknown',
            ];
        });
    }

    /**
     * Returns the highest arena rating of all
     * the teams the character belongs to.
     */
    public function getArenaRatingAttribute(): int
    {
        return (int) DB::connection('mysql_characters')
            ->table('arena_team_member as atm')
            ->join('arena_team as at', 'at.arenaTeamId', '=', 'atm.arenaTeamId')
            ->where('atm.guid', $this->guid)
            ->max('at.rating');
    }

    /**
     * Returns the guild information for the character.
     */
    public function getGuildAttribute(): ?object
    {
        static $cache = [];

        if (isset($cache[$this->guid])) {
            return $cache[$this->guid];
        }

        $row = DB::connection($this->connection)
            ->table('guild_member as gm')
            ->join('guild as g', 'g.guildid', '=', 'gm.guildid')
            ->join('guild_rank as gr', 'gr.rid', '=', 'gm.rank')
            ->where('gm.guid', $this->guid)
            ->first([
                'g.guildid as guildid',
                'g.name   as guild_name',
                'gr.rname  as rank_name',
            ]);

        return $cache[$this->guid] = $row
            ? (object) [
                'guildid' => $row->guildid,
                'guild_name' => $row->guild_name,
                'rank_name' => $row->rank_name,
            ]
            : null;
    }
}
