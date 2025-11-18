<?php

namespace Modules\Armory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Armory\Domain\Interfaces\ArmoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Services\Parser\WowheadParserService;
use Modules\Armory\Services\ArmoryService;

class ArmoryController extends Controller
{
    protected int $perPage = 9;

    protected array $views = [
        'index' => 'armory::armory.index',
        'show'  => 'armory::armory.show',
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

    public function index(Request $request)
    {
        $q = $request->input('q');
        $faction = $request->input('faction') ?: null;
        $realm = $request->input('realm') ?: 1;
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

    public function show(int $guid, Request $request, ?int $realm = null)
    {
        $realm = $realm ?: $request->input('realm', 1);
        $request->merge(['realm' => $realm]);

        $profile = $this->armoryService->getCharacterProfile($guid);
        abort_if(empty($profile), 404);

        return view($this->views['show'], array_merge($profile, ['realm' => $realm]));
    }
}