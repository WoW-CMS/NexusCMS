<?php

namespace Tests\Feature;

use App\Services\ModuleRegistryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ModuleRegistryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        ModuleRegistryService::flushCache();
    }

    public function test_get_all_modules_returns_manifest_with_expected_shape(): void
    {
        $modules = ModuleRegistryService::getAllModules();

        $this->assertNotEmpty($modules);

        $first = $modules[0];
        $this->assertArrayHasKey('folder', $first);
        $this->assertArrayHasKey('name', $first);
        $this->assertArrayHasKey('enabled', $first);
        $this->assertArrayHasKey('module_type', $first);
    }

    public function test_get_module_returns_null_for_blank_and_module_for_valid_name(): void
    {
        $this->assertNull(ModuleRegistryService::getModule(''));

        $forum = ModuleRegistryService::getModule('Forum');

        $this->assertIsArray($forum);
        $this->assertSame('Forum', $forum['folder']);
    }

    public function test_get_module_state_uses_fallback_and_normalizes_invalid_type(): void
    {
        $state = ModuleRegistryService::getModuleState('UnknownModule', [
            'enabled' => false,
            'module_type' => 'invalid_type',
        ]);

        $this->assertFalse($state['enabled']);
        $this->assertSame('core', $state['module_type']);
    }

    public function test_upsert_state_and_is_module_enabled_work_with_database_state(): void
    {
        ModuleRegistryService::upsertState('Forum', false, 'third_party');

        $state = ModuleRegistryService::getModuleState('Forum');

        $this->assertFalse(ModuleRegistryService::isModuleEnabled('Forum', true));
        $this->assertFalse($state['enabled']);
        $this->assertSame('third_party', $state['module_type']);

        ModuleRegistryService::upsertState('Forum', true, 'invalid_type');

        $stateAfter = ModuleRegistryService::getModuleState('Forum');
        $this->assertTrue($stateAfter['enabled']);
        $this->assertSame('core', $stateAfter['module_type']);
    }

    public function test_delete_state_removes_custom_module_state(): void
    {
        ModuleRegistryService::upsertState('Store', false, 'core');

        $this->assertFalse(ModuleRegistryService::isModuleEnabled('Store', true));

        ModuleRegistryService::deleteState('Store');

        $store = ModuleRegistryService::getModule('Store');
        $fallbackEnabled = (bool) ($store['enabled'] ?? true);
        $this->assertSame($fallbackEnabled, ModuleRegistryService::isModuleEnabled('Store', true));
    }

    public function test_get_enabled_providers_returns_unique_provider_list(): void
    {
        $providers = ModuleRegistryService::getEnabledProviders();

        $this->assertIsArray($providers);
        $this->assertSame(array_values(array_unique($providers)), $providers);

        foreach ($providers as $provider) {
            $this->assertIsString($provider);
            $this->assertTrue($provider === '' || Str::contains($provider, '\\'));
        }
    }

    public function test_sync_discovered_modules_persists_states_without_errors(): void
    {
        $modules = ModuleRegistryService::getAllModules();

        ModuleRegistryService::syncDiscoveredModules($modules);

        foreach ($modules as $module) {
            $name = $module['folder'] ?? null;
            if (is_string($name) && $name !== '') {
                $state = ModuleRegistryService::getModuleState($name);
                $this->assertArrayHasKey('enabled', $state);
                $this->assertArrayHasKey('module_type', $state);
            }
        }
    }
}
