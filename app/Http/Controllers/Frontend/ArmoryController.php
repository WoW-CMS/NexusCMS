<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\RealmHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\ArmoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use App\Services\Parser\WowheadParserService;
use App\Services\ArmoryService;

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
    protected ArmoryService $armoryService;

    public function __construct(
        ArmoryRepositoryInterface $armoryRepo, 
        WowheadParserService $wowheadParser,
        ArmoryService $armoryService
    ) {
        $this->armoryRepo = $armoryRepo;
        $this->wowheadParser = $wowheadParser;
        $this->armoryService = $armoryService;
    }

    /**
     * Display a paginated list of Armory characters
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $faction = $request->input('faction') ?: null;
        $realm = $request->input('realm') ?: 1; // Default to realm 1 if not specified
        $class = $request->input('class') ?: null;
        $minLevel = $request->input('min_level') ?: null;

        // Pass realm to the repository through the request
        $request->merge(['realm' => $realm]);

        $characters = ($q || $faction || $class || $minLevel) 
            ? $this->armoryService->searchCharacters($q, $faction, $class, $minLevel)
            : collect();
        
        return view($this->views['index'], [
            'data' => $characters,
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

        $profile = $this->armoryService->getCharacterProfile($guid);
        
        abort_if(empty($profile), 404);

        return view($this->views['show'], array_merge($profile, ['realm' => $realm]));
    }
}
