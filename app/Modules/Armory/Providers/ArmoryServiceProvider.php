<?php

namespace Modules\Armory\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Armory\Infrastructure\Repositories\TrinityCoreArmoryRepository;
use Modules\Armory\Domain\Interfaces\ArmoryRepositoryInterface;
use App\Models\Realm;
use App\Enums\Emulator;

class ArmoryServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Armory';


    public function register(): void
    {
        $this->app->bind(ArmoryRepositoryInterface::class, function ($app) {
            $request = $app->make('request');
            $realmId = $request->get('realm', 1);

            $realm = Realm::find($realmId);

            if (!$realm) {
                throw new \Exception("Realm $realmId not found.");
            }

            $emulator = $realm->emulator;

            $realmConfig = [
                'auth_database' => json_decode($realm->auth_database, true),
                'character_database' => json_decode($realm->character_database, true),
                'world_database' => json_decode($realm->world_database, true),
            ];

            $emulatorEnum = Emulator::from($realm->emulator);

            return match ($emulatorEnum) {
                Emulator::TRINITYCORE => new TrinityCoreArmoryRepository($realmConfig),
                default => throw new \Exception("Unsupported emulator: $emulator")
            };
        });
    } 
}