<?php

namespace App\Services;

use App\Models\Realm;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class InstallService
{
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function run(array $options = [])
    {
        $this->configureEnvironment($options);

        Artisan::call('migrate:fresh', ['--seed' => true]);
        $this->createAdminAccount($options);
        $this->createInitialRealm($options);
        Artisan::call('storage:link');
        Artisan::call('key:generate');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        Artisan::call('optimize');

        return true;
    }

    protected function createAdminAccount(array $options)
    {
        $adminName = $options['admin_name'];
        $adminEmail = $options['admin_email'];
        $adminPassword = $options['admin_password'];

        $user = User::query()->firstOrCreate([
            'email' => $adminEmail,
        ], [
            'name'  => $adminName,
            'password' => bcrypt($adminPassword),
        ]);

        $user->assignRole('Admin');
    }

    protected function createInitialRealm(array $options): void
    {
        Realm::query()->firstOrCreate(
            ['name' => $options['realm_name']],
            [
                'hostname' => $options['realm_hostname'],
                'port' => $options['realm_port'],
                'expansion' => $options['realm_expansion'],
                'emulator' => $options['realm_emulator'],
                'bnet' => (bool) ($options['realm_bnet'] ?? false),
                'auth_database' => json_encode($this->buildDatabaseConfig($options['realm_auth'])),
                'character_database' => json_encode($this->buildDatabaseConfig($options['realm_characters'])),
                'world_database' => json_encode($this->buildDatabaseConfig($options['realm_world'])),
                'console_hostname' => $options['realm_console_hostname'],
                'console_port' => $options['realm_console_port'] ?? null,
                'console_username' => $options['realm_console_username'],
                'console_password' => $options['realm_console_password'],
                'console_urn' => $options['realm_console_urn'],
            ]
        );
    }

    protected function configureEnvironment(array $options)
    {
        $envFile = base_path('.env');
        $envExample = base_path('.env.example');

        if (!$this->files->exists($envFile) && $this->files->exists($envExample)) {
            $this->files->copy($envExample, $envFile);
        }

        $envConfig = [
            'APP_NAME'      => $options['app_name'] ?? 'NexusCMS',
            'APP_URL'       => $options['app_url'] ?? 'http://localhost',
            'APP_LOCALE'    => $options['locale'] ?? 'en',
            'DB_CONNECTION' => $options['db_connection'] ?? 'mysql',
            'DB_HOST'       => $options['db_host'] ?? '127.0.0.1',
            'DB_PORT'       => $options['db_port'] ?? '3306',
            'DB_DATABASE'   => $options['db_name'] ?? 'nexuscms',
            'DB_USERNAME'   => $options['db_username'] ?? 'root',
            'DB_PASSWORD'   => $options['db_password'] ?? 'root',
        ];

        if (!empty($options['use_redis'])) {
            $envConfig['CACHE_DRIVER'] = 'redis';
            $envConfig['QUEUE_CONNECTION'] = 'redis';
            $envConfig['SESSION_DRIVER'] = 'redis';
            $envConfig['REDIS_HOST'] = $options['redis_host'] ?? '127.0.0.1';
            $envConfig['REDIS_PORT'] = $options['redis_port'] ?? '6379';

            if (!empty($options['redis_password'])) {
                $envConfig['REDIS_PASSWORD'] = $options['redis_password'];
            }
            if (!empty($options['redis_username'])) {
                $envConfig['REDIS_USERNAME'] = $options['redis_username'];
            }
        }

        $this->updateEnv($envConfig);
    }

    protected function buildDatabaseConfig(array $config): array
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

    protected function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        $envContent = $this->files->get($envPath);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $sanitized = $this->sanitizeEnvValue((string) $value);

                if (preg_match("/^{$key}=.*/m", $envContent)) {
                    $envContent = preg_replace(
                        "/^{$key}=.*/m",
                        "{$key}={$sanitized}",
                        $envContent
                    );
                } else {
                    $envContent .= PHP_EOL."{$key}={$sanitized}";
                }
            }
        }

        $this->files->put($envPath, $envContent);
    }

    /**
     * Sanitize a value for safe inclusion in a .env file.
     *
     * Strips control characters (newlines, carriage returns, null bytes)
     * that could be used to inject additional environment variables,
     * then wraps the value in double quotes with internal quotes escaped.
     */
    protected function sanitizeEnvValue(string $value): string
    {
        // Remove characters that could break out of the value context
        $value = str_replace(["\n", "\r", "\0", "\x1a"], '', $value);

        // Escape any embedded double quotes
        $value = str_replace('"', '\\"', $value);

        return "\"{$value}\"";
    }
}
