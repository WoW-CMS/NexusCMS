<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\RealmHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\ArmoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use App\Services\Parser\WowheadParserService;

/**
 * Frontend Armory Controller
 *
 * Handles public-facing Armory pages using the Armory repository.
 */
class ArmoryController extends Controller
{
    protected int $perPage = 9;

    protected array $views = [
        'index' => 'armory.index',
        'show'  => 'armory.show',
    ];

    protected ArmoryRepositoryInterface $armoryRepo;
    protected WowheadParserService $wowheadParser;

    public function __construct(ArmoryRepositoryInterface $armoryRepo, WowheadParserService $wowheadParser)
    {
        $this->armoryRepo = $armoryRepo;
        $this->wowheadParser = $wowheadParser;
    }

    /**
     * Display a paginated list of Armory characters
     */
    public function index(Request $request)
    {
        $characters = [];
        $q = $request->input('q');
        $faction = $request->input('faction') ?: null;
        $realm = $request->input('realm') ?: 1; // Default to realm 1 if not specified
        $class = $request->input('class') ?: null;
        $minLevel = $request->input('min_level') ?: null;

        // Pass realm to the repository through the request
        $request->merge(['realm' => $realm]);

        if ($q || $faction || $class || $minLevel) {
            $characters = $this->armoryRepo->search($q, $faction, $class, $minLevel);
        }
        
        return view($this->views['index'], [
            'data' => $characters ?? [],
            'search' => $q ?? '',
            'realm' => $realm,
        ]);
    }

    /**
     * Show a single character by GUID
     */
    public function show(int $guid, Request $request, ?int $realm = null)
    {
        // Get realm from URL parameter or query string, default to 1
        $realm = $realm ?: $request->input('realm', 1);
        
        // Pass realm to the repository through the request
        $request->merge(['realm' => $realm]);

        $character = $this->armoryRepo->getCharacter($guid);

        abort_if(!$character, 404);

        $items = $this->armoryRepo->getCharacterItems($guid);
        $guild = $this->armoryRepo->getGuildByMember($character->guid);
        $memberRank = $this->armoryRepo->getGuildRankMember($guild->guildid, $character->guid);

        // Enrich items with Wowhead data
        $item = $items->map(function ($equip) {
            $data = $this->wowheadParser->parse('item', (string) $equip['entry']);
            return array_merge($equip, ['wowhead' => $data]);
        });

        // PromedItemLevel
        $itemLevels = $item->pluck('wowhead.level')->filter(fn($lvl) => is_numeric($lvl))->toArray();
        $promedItemLevel = (int) RealmHelper::calculateItemLevelPromed($itemLevels);

        $achievement = $this->armoryRepo->getAchievementsCharacter($guid);

        return view($this->views['show'], compact('character', 'item', 'achievement', 'guild', 'memberRank', 'promedItemLevel', 'realm'));
    }
}
