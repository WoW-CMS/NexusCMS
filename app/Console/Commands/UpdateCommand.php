<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Redis\RedisManager;

class UpdateCommand extends Command
{
    protected $signature = 'nexus:update
        {--clear-cache : Clear all caches}
        {--optimize : Optimize the application}
        {--storage-link : Recreate symbolic links}';

    protected $description = 'Update and optimize NexusCMS';

    protected $files;
    protected $redis;

    public function __construct(Filesystem $files, RedisManager $redis)
    {
        parent::__construct();
        $this->files = $files;
        $this->redis = $redis;
    }

    public function handle()
    {
        $this->info('Starting NexusCMS update...');

        // Clear caches if requested
        if ($this->option('clear-cache')) {
            $this->call('config:clear');
            $this->call('cache:clear');
            $this->call('view:clear');
            $this->call('route:clear');
            $this->info('Caches cleared successfully.');
        }

        // Run pending migrations
        $this->call('migrate', ['--force' => true]);

        // Recreate symbolic links if requested
        if ($this->option('storage-link')) {
            $this->call('storage:link');
            $this->info('Symbolic links recreated.');
        }

        // Optimize if requested
        if ($this->option('optimize')) {
            $this->call('config:cache');
            $this->call('route:cache');
            $this->call('view:cache');
            $this->call('optimize');
            $this->info('Application optimized.');
        }

        // Check and update cache configuration
        $this->checkCacheConfiguration();

        $this->info('NexusCMS has been updated successfully!');
    }

    protected function checkCacheConfiguration()
    {
        $cacheStore = config('cache.default');
        
        if ($cacheStore === 'redis') {
            try {
                $this->redis->connection()->ping();
                $this->info('Redis connection verified successfully.');
            } catch (\Exception $e) {
                $this->warn('Could not connect to Redis. Switching to file cache...');
                $this->updateEnv(['CACHE_STORE' => 'file']);
            }
        }
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
