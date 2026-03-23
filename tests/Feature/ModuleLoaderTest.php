<?php

namespace Tests\Feature;

use App\Helpers\ModuleLoader;
use App\Services\ModuleRegistryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleLoaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_providers_proxies_registry_service(): void
    {
        ModuleRegistryService::flushCache();

        $expected = ModuleRegistryService::getEnabledProviders();
        $actual = ModuleLoader::getProviders();

        $this->assertSame($expected, $actual);
        $this->assertIsArray($actual);
    }
}
