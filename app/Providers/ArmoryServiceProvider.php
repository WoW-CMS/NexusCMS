<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Armory\{
    TrinityCoreArmoryRepository,
    AzerothCoreArmoryRepository
};
use App\Interfaces\ArmoryRepositoryInterface;
use App\Models\Realm;
use App\Enums\Emulator;

class ArmoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArmoryRepositoryInterface::class, function ($app) {
            $realmId = request()->get('realm', 1);
            $realm = Realm::findOrFail($realmId);

            $emulator = $realm->emulator;

            $realmConfig = [
                'auth_database' => $realm->auth_database,
                'character_database' => $realm->character_database,
                'world_database' => $realm->world_database,
            ];

            return match ($emulator) {
                Emulator::TRINITYCORE => new TrinityCoreArmoryRepository($realmConfig),
                default => throw new \Exception("Unsupported emulator: $emulator")
            };
        });
    }
}
