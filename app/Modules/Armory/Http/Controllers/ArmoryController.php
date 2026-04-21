<?php

namespace Modules\Armory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Armory\Domain\Interfaces\ArmoryRepositoryInterface;
use Illuminate\Http\Request;
use Modules\Armory\Services\WowheadParserService;
use Modules\Armory\Services\ArmoryService;

class ArmoryController extends Controller
{
    /**
     * Number of characters to display per page in listings.
     *
     * @var int
     */
    protected int $perPage = 9;

    /**
     * View paths used by the controller.
     *
     * @var array<string,string>
     */
    protected array $views = [
        'index' => 'armory::index',
        'show'  => 'armory::show',
    ];

    /**
     * Armory repository instance.
     *
     * @var ArmoryRepositoryInterface
     */
    protected ArmoryRepositoryInterface $armoryRepo;

    /**
     * Wowhead parser service instance.
     *
     * @var WowheadParserService
     */
    protected WowheadParserService $wowheadParser;

    /**
     * Armory service instance.
     *
     * @var ArmoryService
     */
    protected ArmoryService $armoryService;

    /**
     * ArmoryController constructor.
     *
     * @param ArmoryRepositoryInterface $armoryRepo     Armory repository instance.
     * @param WowheadParserService      $wowheadParser  Wowhead parser service instance.
     * @param ArmoryService             $armoryService  Armory service instance.
     */
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
     * Display the character index page.
     *
     * @param Request $request Incoming request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $faction = $request->input('faction') ?: null;
        $realm = $request->input('realm') ?: false;
        $class = $request->input('class') ?: null;
        $minLevel = $request->input('min_level') ?: null;

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
     * Display the character profile page.
     *
     * @param int         $guid    Character GUID
     * @param Request     $request Incoming request
     * @param int|null    $realm   Realm ID (optional)
     * @return \Illuminate\View\View
     */
    public function show(int $guid, Request $request, ?int $realm = null)
    {
        $realm = $realm ?: $request->input('realm', 1);
        $request->merge(['realm' => $realm]);

        $profile = $this->armoryService->getCharacterProfile($guid);
        abort_if(empty($profile), 404);

        return view($this->views['show'], array_merge($profile, ['realm' => $realm]));
    }
}