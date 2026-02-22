<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use App\Modules\Admin\Domain\Models\Setting;

class SettingsHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_setting_value(): void
    {
        Cache::forget('site_settings');

        Setting::create([
            'key' => 'site_name',
            'value' => 'Corex Dev'
        ]);

        $this->assertEquals('Corex Dev', settings('site_name'));
    }

    public function test_returns_default_when_not_exists(): void
    {
        Cache::forget('site_settings');

        $this->assertEquals('default', settings('unknown_key', 'default'));
    }

    public function test_returns_all_settings_when_no_key_provided(): void
    {
        Cache::forget('site_settings');

        Setting::create(['key' => 'a', 'value' => '1']);
        Setting::create(['key' => 'b', 'value' => '2']);

        $all = settings();

        $this->assertCount(2, $all);
        $this->assertEquals('1', $all->get('a'));
    }
}