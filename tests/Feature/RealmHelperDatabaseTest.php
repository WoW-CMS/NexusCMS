<?php

namespace Tests\Feature;

use App\Helpers\RealmHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RealmHelperDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_returns_realms_collection(): void
    {
        DB::table('realms')->insert([
            'name' => 'Test Realm',
            'hostname' => '127.0.0.1',
            'expansion' => 2,
            'emulator' => 'Trinity',
            'port' => 8085,
            'auth_database' => json_encode(['name' => 'auth']),
            'world_database' => json_encode(['name' => 'world']),
            'console_hostname' => '127.0.0.1',
            'console_port' => 3443,
            'console_username' => 'admin',
            'console_password' => 'pass',
            'console_urn' => 'urn:test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $all = RealmHelper::all();

        $this->assertCount(1, $all);
        $this->assertSame('Test Realm', $all->first()->name);
    }

    public function test_find_returns_realm_or_null(): void
    {
        $id = DB::table('realms')->insertGetId([
            'name' => 'Find Realm',
            'hostname' => '127.0.0.2',
            'expansion' => 3,
            'emulator' => 'AzerothCore',
            'port' => 8086,
            'auth_database' => json_encode(['name' => 'auth2']),
            'world_database' => json_encode(['name' => 'world2']),
            'console_hostname' => '127.0.0.2',
            'console_port' => 3444,
            'console_username' => 'admin2',
            'console_password' => 'pass2',
            'console_urn' => 'urn:test2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $realm = RealmHelper::find($id);

        $this->assertNotNull($realm);
        $this->assertSame('Find Realm', $realm->name);
        $this->assertNull(RealmHelper::find(999999));
    }
}
