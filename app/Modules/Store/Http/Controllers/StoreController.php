<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountLinked;
use App\Models\Realm;
use App\Models\User;
use GameCrypto\SoapAccountCreator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $linkedAccounts = AccountLinked::query()
            ->with('realm')
            ->where('user_id', $user->id)
            ->get()
            ->filter(static fn (AccountLinked $link) => $link->realm !== null)
            ->values();

        return view('store::store', [
            'products' => $this->getProducts(),
            'linkedAccounts' => $linkedAccounts,
            'balance' => (int) $user->dp,
        ]);
    }

    public function purchase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product' => ['required', 'string'],
            'realm_id' => ['required', 'integer', 'exists:realms,id'],
            'character_name' => ['nullable', 'string', 'min:2', 'max:12'],
        ]);

        $products = $this->getProducts();
        $productKey = $validated['product'];

        if (!isset($products[$productKey])) {
            return back()->with('error', 'Selected product is invalid.');
        }

        $product = $products[$productKey];
        $realmId = (int) $validated['realm_id'];
        $characterName = trim((string) ($validated['character_name'] ?? ''));
        $user = $request->user();

        $link = AccountLinked::query()
            ->where('user_id', $user->id)
            ->where('realm_id', $realmId)
            ->first();

        if (!$link) {
            return back()->with('error', 'You do not have a linked game account for the selected realm.');
        }

        if (($product['requires_character'] ?? false) && $characterName === '') {
            return back()->with('error', 'This product requires a character name.');
        }

        $realm = Realm::query()->find($realmId);
        if (!$realm) {
            return back()->with('error', 'Selected realm was not found.');
        }

        try {
            $soap = new SoapAccountCreator(
                (string) $realm->console_hostname,
                (int) $realm->console_port,
                (string) $realm->console_username,
                (string) $realm->console_password,
                (string) $realm->console_urn,
                false
            );

            $response = $this->executeProduct($soap, $product, $characterName);

            if ($this->responseHasError($response)) {
                throw new \RuntimeException($response);
            }

            DB::transaction(static function () use ($user, $product): void {
                $freshUser = User::query()->lockForUpdate()->findOrFail($user->id);
                $cost = (int) $product['cost'];

                if ((int) $freshUser->dp < $cost) {
                    throw new \RuntimeException('Not enough donation points for this purchase.');
                }

                $freshUser->dp = (int) $freshUser->dp - $cost;
                $freshUser->save();
            });

            return back()->with('success', 'Purchase completed successfully. Server response: ' . $response);
        } catch (\Throwable $exception) {
            return back()->with('error', 'Purchase failed: ' . $exception->getMessage());
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function getProducts(): array
    {
        return [
            'rename' => [
                'name' => 'Character Rename',
                'description' => 'Forces a character rename on next login.',
                'cost' => 600,
                'requires_character' => true,
                'type' => 'character_rename',
            ],
            'customize' => [
                'name' => 'Character Customize',
                'description' => 'Unlocks appearance customization on next login.',
                'cost' => 800,
                'requires_character' => true,
                'type' => 'character_customize',
            ],
            'race_change' => [
                'name' => 'Race Change',
                'description' => 'Unlocks race change on next login.',
                'cost' => 1200,
                'requires_character' => true,
                'type' => 'character_race_change',
            ],
            'faction_change' => [
                'name' => 'Faction Change',
                'description' => 'Unlocks faction change on next login.',
                'cost' => 1500,
                'requires_character' => true,
                'type' => 'character_faction_change',
            ],
            'starter_mail' => [
                'name' => 'Starter Mail Pack',
                'description' => 'Sends a starter pack by in-game mail.',
                'cost' => 500,
                'requires_character' => true,
                'type' => 'mail_starter_pack',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $product
     */
    private function executeProduct(SoapAccountCreator $soap, array $product, string $characterName): string
    {
        return match ((string) $product['type']) {
            'character_rename' => $soap->character()->rename($characterName),
            'character_customize' => $soap->character()->customize($characterName),
            'character_race_change' => $soap->character()->changeRace($characterName),
            'character_faction_change' => $soap->character()->changeFaction($characterName),
            'mail_starter_pack' => $soap->mail()->sendItems(
                $characterName,
                'Nexus Store - Starter Pack',
                'Thanks for your purchase. Enjoy your items and gold.',
                [6948 => 1, 17031 => 20, 33447 => 10]
            ),
            default => throw new \InvalidArgumentException('Unsupported product type.'),
        };
    }

    private function responseHasError(string $response): bool
    {
        return preg_match('/error|fail|invalid|denied/i', $response) === 1;
    }
}