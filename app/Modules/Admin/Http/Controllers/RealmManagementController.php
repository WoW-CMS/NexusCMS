<?php

namespace Modules\Admin\Http\Controllers;

use App\Enums\Emulator;
use App\Enums\WoWConstants;
use App\Http\Controllers\Controller;
use App\Models\Realm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RealmManagementController extends Controller
{
    /**
     * Display realms listing.
     */
    public function index()
    {
        $realms = Realm::query()->latest()->paginate(12);

        return view('admin::realms.index', compact('realms'));
    }

    /**
     * Show the create form for a realm.
     */
    public function create()
    {
        return view('admin::realms.create');
    }

    /**
     * Store a newly created realm.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        Realm::create($this->realmPayload($validated));

        return redirect()
            ->route('admin.realms.index')
            ->with('success', 'Realm creado correctamente.');
    }

    /**
     * Show edit form for a realm.
     */
    public function edit(Realm $realm)
    {
        return view('admin::realms.edit', [
            'realm' => $realm,
            'authConfig' => $this->decodeDatabaseConfig($realm->auth_database),
            'charactersConfig' => $this->decodeDatabaseConfig($realm->character_database),
            'worldConfig' => $this->decodeDatabaseConfig($realm->world_database),
        ]);
    }

    /**
     * Update an existing realm.
     */
    public function update(Request $request, Realm $realm)
    {
        $validated = $request->validate($this->validationRules($realm->id));

        $realm->update($this->realmPayload($validated));

        return redirect()
            ->route('admin.realms.index')
            ->with('success', 'Realm actualizado correctamente.');
    }

    /**
     * Delete an existing realm.
     */
    public function destroy(Realm $realm)
    {
        $realm->delete();

        return redirect()
            ->route('admin.realms.index')
            ->with('success', 'Realm eliminado correctamente.');
    }

    /**
     * Shared validation rules for create and update.
     */
    private function validationRules(?int $realmId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('realms', 'name')->ignore($realmId)],
            'hostname' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'between:1,65535'],
            'expansion' => ['required', 'integer', Rule::in(array_keys(WoWConstants::EXPANSION_NAMES))],
            'emulator' => ['required', 'string', Rule::in(array_keys(Emulator::LABELS))],
            'bnet' => ['nullable', 'boolean'],

            'console_hostname' => ['required', 'string', 'max:255'],
            'console_port' => ['nullable', 'integer', 'between:1,65535'],
            'console_username' => ['required', 'string', 'max:255'],
            'console_password' => ['required', 'string', 'max:255'],
            'console_urn' => ['required', 'string', Rule::in(array_values(Emulator::URN))],

            'auth.host' => ['required', 'string', 'max:255'],
            'auth.port' => ['nullable', 'integer', 'between:1,65535'],
            'auth.database' => ['required', 'string', 'max:255'],
            'auth.username' => ['required', 'string', 'max:255'],
            'auth.password' => ['required', 'string', 'max:255'],
            'auth.charset' => ['nullable', 'string', 'max:64'],
            'auth.collation' => ['nullable', 'string', 'max:64'],

            'characters.host' => ['required', 'string', 'max:255'],
            'characters.port' => ['nullable', 'integer', 'between:1,65535'],
            'characters.database' => ['required', 'string', 'max:255'],
            'characters.username' => ['required', 'string', 'max:255'],
            'characters.password' => ['required', 'string', 'max:255'],
            'characters.charset' => ['nullable', 'string', 'max:64'],
            'characters.collation' => ['nullable', 'string', 'max:64'],

            'world.host' => ['required', 'string', 'max:255'],
            'world.port' => ['nullable', 'integer', 'between:1,65535'],
            'world.database' => ['required', 'string', 'max:255'],
            'world.username' => ['required', 'string', 'max:255'],
            'world.password' => ['required', 'string', 'max:255'],
            'world.charset' => ['nullable', 'string', 'max:64'],
            'world.collation' => ['nullable', 'string', 'max:64'],
        ];
    }

    /**
     * Build payload for Realm model.
     */
    private function realmPayload(array $validated): array
    {
        return [
            'name' => $validated['name'],
            'hostname' => $validated['hostname'],
            'port' => $validated['port'],
            'expansion' => $validated['expansion'],
            'emulator' => $validated['emulator'],
            'bnet' => (bool) ($validated['bnet'] ?? false),
            'auth_database' => json_encode($this->buildDatabaseConfig($validated['auth'])),
            'character_database' => json_encode($this->buildDatabaseConfig($validated['characters'])),
            'world_database' => json_encode($this->buildDatabaseConfig($validated['world'])),
            'console_hostname' => $validated['console_hostname'],
            'console_port' => $validated['console_port'] ?? null,
            'console_username' => $validated['console_username'],
            'console_password' => $validated['console_password'],
            'console_urn' => $validated['console_urn'],
        ];
    }

    /**
     * Build a normalized database config payload.
     */
    private function buildDatabaseConfig(array $config): array
    {
        return [
            'driver' => 'mysql',
            'host' => $config['host'],
            'port' => $config['port'] ?? 3306,
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset' => $config['charset'] ?? 'utf8mb4',
            'collation' => $config['collation'] ?? 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ];
    }

    /**
     * Decode stored JSON config into array for forms.
     */
    private function decodeDatabaseConfig(?string $config): array
    {
        $decoded = json_decode((string) $config, true);

        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }
}
