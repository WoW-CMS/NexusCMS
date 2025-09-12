<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected $signature = 'nexus:install 
        {--db-connection= : Database connection}
        {--db-host= : Database host}
        {--db-port= : Database port}
        {--db-name= : Database name}
        {--db-username= : Database username}
        {--db-password= : Database password}
        {--app-name= : Application name}
        {--app-url= : Application URL}
        {--locale= : Application locale}';

    protected $description = 'Install and configure NexusCMS';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $this->info('Starting NexusCMS installation...');

        // Configure environment
        $this->configureEnvironment();

        // Run migrations
        $this->call('migrate:fresh', ['--seed' => true]);

        // Create storage link
        $this->call('storage:link');

        // Generate application key
        $this->call('key:generate');

        // Cache configurations
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        $this->call('optimize');

        $this->info('NexusCMS has been installed successfully!');
    }

    protected function configureEnvironment()
    {
        $envFile = base_path('.env');
        $envExample = base_path('.env.example');

        if (!$this->files->exists($envFile) && $this->files->exists($envExample)) {
            $this->files->copy($envExample, $envFile);
        }

        $useRedis = $this->confirm('Would you like to use Redis as cache driver?', false);

        $envConfig = [
            'APP_NAME' => $this->option('app-name') ?: $this->ask('Application name?', 'NexusCMS'),
            'APP_URL' => $this->option('app-url') ?: $this->ask('Application URL?', 'http://localhost'),
            'APP_LOCALE' => $this->option('locale') ?: $this->ask('Application locale?', 'en'),
            'DB_CONNECTION' => $this->option('db-connection') ?: $this->ask('Database connection?', 'mysql'),
            'DB_HOST' => $this->option('db-host') ?: $this->ask('Database host?', '127.0.0.1'),
            'DB_PORT' => $this->option('db-port') ?: $this->ask('Database port?', '3306'),
            'DB_DATABASE' => $this->option('db-name') ?: $this->ask('Database name?', 'nexuscms'),
            'DB_USERNAME' => $this->option('db-username') ?: $this->ask('Database username?', 'root'),
            'DB_PASSWORD' => $this->option('db-password') ?: $this->ask('Database password?', 'root'),
        ];

        if ($useRedis) {
            $envConfig['CACHE_DRIVER'] = 'redis';
            $envConfig['QUEUE_CONNECTION'] = 'redis';
            $envConfig['SESSION_DRIVER'] = 'redis';

            // Configure Redis settings
            $redisHost = $this->ask('Redis host?', '127.0.0.1');
            $redisPassword = $this->ask('Redis password? (leave empty for none)', '');
            $redisPort = $this->ask('Redis port?', '6379');
            $redisUsername = $this->ask('Redis username? (leave empty for none)', '');

            $envConfig['REDIS_HOST'] = $redisHost;
            $envConfig['REDIS_PORT'] = $redisPort;
            
            if ($redisPassword) {
                $envConfig['REDIS_PASSWORD'] = $redisPassword;
            }
            
            if ($redisUsername) {
                $envConfig['REDIS_USERNAME'] = $redisUsername;
            }
        }

        $this->updateEnv($envConfig);
    }

    protected function updateEnv($data)
    {
        if (!count($data)) {
            return;
        }

        $envContent = $this->files->get(base_path('.env'));

        foreach ($data as $key => $value) {
            if ($value) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            }
        }

        $this->files->put(base_path('.env'), $envContent);
    }
}
