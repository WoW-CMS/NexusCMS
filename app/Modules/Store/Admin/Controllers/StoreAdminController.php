<?php

namespace Modules\Store\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Realm;
use Illuminate\View\View;

class StoreAdminController extends Controller
{
    private array $products = [
        'rename' => [
            'name'               => 'Character Rename',
            'description'        => 'Forces a character rename on next login.',
            'cost'               => 600,
            'requires_character' => true,
        ],
        'customize' => [
            'name'               => 'Character Customize',
            'description'        => 'Unlocks appearance customization on next login.',
            'cost'               => 800,
            'requires_character' => true,
        ],
        'race_change' => [
            'name'               => 'Race Change',
            'description'        => 'Unlocks race change on next login.',
            'cost'               => 1200,
            'requires_character' => true,
        ],
        'faction_change' => [
            'name'               => 'Faction Change',
            'description'        => 'Unlocks faction change on next login.',
            'cost'               => 1500,
            'requires_character' => true,
        ],
        'starter_mail' => [
            'name'               => 'Starter Mail Pack',
            'description'        => 'Sends a starter pack by in-game mail.',
            'cost'               => 500,
            'requires_character' => true,
        ],
    ];

    public function index(): View
    {
        $realms = Realm::query()->orderBy('name')->get();

        return view('store-admin::index', [
            'products' => $this->products,
            'realms'   => $realms,
        ]);
    }
}
