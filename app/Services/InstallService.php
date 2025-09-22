<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('storage:link');
        Artisan::call('key:generate');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        Artisan::call('optimize');

        return true;
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

    protected function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        $envContent = $this->files->get($envPath);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                if (preg_match("/^{$key}=.*/m", $envContent)) {
                    $envContent = preg_replace(
                        "/^{$key}=.*/m",
                        "{$key}=\"{$value}\"",
                        $envContent
                    );
                } else {
                    $envContent .= PHP_EOL."{$key}=\"{$value}\"";
                }
            }
        }

        $this->files->put($envPath, $envContent);
    }
}
