<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Emulator;
use App\Enums\WoWConstants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\InstallService;
use Exception;
use Illuminate\Validation\Rule;
use PDO;

class InstallController extends Controller
{
    public function index()
    {
        $requirements = $this->systemRequirements();

        return view('install.index', [
            'expansions' => WoWConstants::EXPANSION_NAMES,
            'emulators' => Emulator::LABELS,
            'emulatorUrns' => Emulator::URN,
            'defaultExpansion' => WoWConstants::EXPANSION_CATA,
            'defaultEmulator' => Emulator::TRINITYCORE->value,
            'requirements' => $requirements,
            'allRequirementsPassed' => collect($requirements)->every(fn (array $requirement): bool => (bool) ($requirement['ok'] ?? false)),
        ]);
    }

    public function success()
    {
        return view('install.success');
    }

    public function testDb(Request $request)
    {
        $payload = $request->validate([
            'db_host' => ['required', 'string', 'max:255'],
            'db_port' => ['required', 'integer', 'between:1,65535'],
            'db_name' => ['required', 'string', 'max:255'],
            'db_username' => ['required', 'string', 'max:255'],
            'db_password' => ['nullable', 'string'],
        ]);

        try {
            new PDO(
                "mysql:host={$payload['db_host']};port={$payload['db_port']};dbname={$payload['db_name']}",
                $payload['db_username'],
                $payload['db_password'] ?? ''
            );

            return response()->json(['success' => true, 'message' => '✅ Conexión exitosa']);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Install DB test failed', [
                'host' => $payload['db_host'],
                'port' => $payload['db_port'],
                'database' => $payload['db_name'],
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => '❌ No se pudo conectar a la base de datos. Verifique las credenciales e intente de nuevo.']);
        }
    }

    public function install(Request $request)
    {
        $requirements = $this->systemRequirements();
        $hasFailedRequirement = collect($requirements)->contains(fn (array $requirement): bool => !(bool) ($requirement['ok'] ?? false));

        if ($hasFailedRequirement) {
            return back()
                ->withInput()
                ->withErrors(['requirements' => 'No se puede instalar hasta cumplir todos los requisitos del sistema.']);
        }

        $validated = $request->validate($this->validationRules());
        $service = app(InstallService::class);

        try {
            $service->run($validated);
        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('Installation failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['install' => 'No se pudo completar la instalación. Revise los logs del servidor para más detalles.']);
        }

        $lockContent = now()->toDateTimeString();

        $installerLockWritten = file_put_contents(storage_path('installer.lock'), $lockContent, LOCK_EX);
        $legacyLockWritten = file_put_contents(storage_path('installed.lock'), $lockContent, LOCK_EX);

        if ($installerLockWritten === false && $legacyLockWritten === false) {
            throw new \RuntimeException('Unable to create installation lock file.');
        }

        return redirect()
            ->route('install.success')
            ->with('install_completed', true)
            ->with('install_success_message', 'Instalacion completada correctamente.');
    }

    /**
     * Get the system requirements for the installation.
     *
     * @return array
     */
    private function systemRequirements(): array
    {
        $phpMin = '8.2.0';

        return [
            [
                'label' => "PHP >= {$phpMin}",
                'ok' => version_compare(PHP_VERSION, $phpMin, '>='),
                'current' => PHP_VERSION,
            ],
            [
                'label' => 'Extension PDO',
                'ok' => extension_loaded('pdo'),
                'current' => extension_loaded('pdo') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension pdo_mysql',
                'ok' => extension_loaded('pdo_mysql'),
                'current' => extension_loaded('pdo_mysql') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension mbstring',
                'ok' => extension_loaded('mbstring'),
                'current' => extension_loaded('mbstring') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension openssl',
                'ok' => extension_loaded('openssl'),
                'current' => extension_loaded('openssl') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension tokenizer',
                'ok' => extension_loaded('tokenizer'),
                'current' => extension_loaded('tokenizer') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension xml',
                'ok' => extension_loaded('xml'),
                'current' => extension_loaded('xml') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension ctype',
                'ok' => extension_loaded('ctype'),
                'current' => extension_loaded('ctype') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension json',
                'ok' => extension_loaded('json'),
                'current' => extension_loaded('json') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Extension fileinfo',
                'ok' => extension_loaded('fileinfo'),
                'current' => extension_loaded('fileinfo') ? 'OK' : 'Missing',
            ],
            [
                'label' => 'Storage writable',
                'ok' => is_writable(storage_path()),
                'current' => is_writable(storage_path()) ? 'Writable' : 'Not writable',
            ],
            [
                'label' => 'Bootstrap/cache writable',
                'ok' => is_writable(base_path('bootstrap/cache')),
                'current' => is_writable(base_path('bootstrap/cache')) ? 'Writable' : 'Not writable',
            ],
        ];
    }

    private function validationRules(): array
    {
        return [
            'agree_eula' => ['accepted'],
            'app_name' => ['required', 'string', 'max:100'],
            'app_url' => ['required', 'url', 'max:255'],
            'locale' => ['required', 'string', 'max:10'],

            'db_host' => ['required', 'string', 'max:255'],
            'db_port' => ['required', 'integer', 'between:1,65535'],
            'db_name' => ['required', 'string', 'max:255'],
            'db_username' => ['required', 'string', 'max:255'],
            'db_password' => ['nullable', 'string', 'max:255'],

            'admin_name' => ['required', 'string', 'max:120'],
            'admin_email' => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],

            'realm_name' => ['required', 'string', 'max:120'],
            'realm_hostname' => ['required', 'string', 'max:255'],
            'realm_port' => ['required', 'integer', 'between:1,65535'],
            'realm_expansion' => ['required', 'integer', Rule::in(array_keys(WoWConstants::EXPANSION_NAMES))],
            'realm_emulator' => ['required', 'string', Rule::in(array_keys(Emulator::LABELS))],
            'realm_bnet' => ['nullable', 'boolean'],

            'realm_console_hostname' => ['required', 'string', 'max:255'],
            'realm_console_port' => ['nullable', 'integer', 'between:1,65535'],
            'realm_console_username' => ['required', 'string', 'max:255'],
            'realm_console_password' => ['required', 'string', 'max:255'],
            'realm_console_urn' => ['required', 'string', Rule::in(array_values(Emulator::URN))],

            'realm_auth.host' => ['required', 'string', 'max:255'],
            'realm_auth.port' => ['nullable', 'integer', 'between:1,65535'],
            'realm_auth.database' => ['required', 'string', 'max:255'],
            'realm_auth.username' => ['required', 'string', 'max:255'],
            'realm_auth.password' => ['required', 'string', 'max:255'],
            'realm_auth.charset' => ['nullable', 'string', 'max:64'],
            'realm_auth.collation' => ['nullable', 'string', 'max:64'],

            'realm_characters.host' => ['required', 'string', 'max:255'],
            'realm_characters.port' => ['nullable', 'integer', 'between:1,65535'],
            'realm_characters.database' => ['required', 'string', 'max:255'],
            'realm_characters.username' => ['required', 'string', 'max:255'],
            'realm_characters.password' => ['required', 'string', 'max:255'],
            'realm_characters.charset' => ['nullable', 'string', 'max:64'],
            'realm_characters.collation' => ['nullable', 'string', 'max:64'],

            'realm_world.host' => ['required', 'string', 'max:255'],
            'realm_world.port' => ['nullable', 'integer', 'between:1,65535'],
            'realm_world.database' => ['required', 'string', 'max:255'],
            'realm_world.username' => ['required', 'string', 'max:255'],
            'realm_world.password' => ['required', 'string', 'max:255'],
            'realm_world.charset' => ['nullable', 'string', 'max:64'],
            'realm_world.collation' => ['nullable', 'string', 'max:64'],
        ];
    }
}
