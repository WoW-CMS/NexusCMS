<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Interfaces\ArmoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function __construct(ArmoryRepositoryInterface $armoryRepo)
    {
        $this->armoryRepo = $armoryRepo;
    }

    /**
     * Display a paginated list of Armory characters
     */
    /**
     * Show list of characters, optionally filtered by search query
     */
    public function index(Request $request)
    {
        $q = $request->input('q');

        if ($q) {
            $characters = $this->armoryRepo->search($q); // <-- Método search en el repo
        }

        return view($this->views['index'], [
            'data' => $characters ?? [],
            'search' => $q,
        ]);
    }

    /**
     * Show a single character by GUID
     */
    public function show(int $guid)
    {
        $character = $this->armoryRepo->getCharacter($guid);

        abort_if(!$character, 404);

        $items = $this->armoryRepo->getCharacterItems($guid);

        return view($this->views['show'], compact('character', 'items'));
    }
}
