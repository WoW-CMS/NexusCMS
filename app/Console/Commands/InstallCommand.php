<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'nexus:install 
        {--database= : Database name}
        {--db-username= : Database username}
        {--db-password= : Database password}
        {--app-name= : Application name}
        {--app-url= : Application URL}
        {--locale= : Application locale}';

    protected $description = 'Install and configure NexusCMS';

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

        if (!File::exists($envFile) && File::exists($envExample)) {
            File::copy($envExample, $envFile);
        }

        $this->updateEnv([
            'APP_NAME' => $this->option('app-name') ?? 'NexusCMS',
            'APP_URL' => $this->option('app-url') ?? 'http://localhost',
            'APP_LOCALE' => $this->option('locale') ?? 'en',
            'DB_DATABASE' => $this->option('database') ?? 'nexuscms',
            'DB_USERNAME' => $this->option('db-username') ?? 'root',
            'DB_PASSWORD' => $this->option('db-password') ?? 'root',
        ]);
    }

    protected function updateEnv($data)
    {
        if (!count($data)) {
            return;
        }

        $envContent = File::get(base_path('.env'));

        foreach ($data as $key => $value) {
            if ($value) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            }
        }

        File::put(base_path('.env'), $envContent);
    }
}