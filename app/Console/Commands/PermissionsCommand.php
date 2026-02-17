<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PermissionsCommand extends Command
{
    protected $signature = 'nexus:permissions';

    protected $description = 'Fix Laravel directory permissions for NexusCMS';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $this->info('Setting required directory permissions...');

        $directories = [
            storage_path(),
            storage_path('app'),
            storage_path('framework'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            if (!$directory) {
                continue;
            }

            if (!$this->files->exists($directory)) {
                $this->files->makeDirectory($directory, 0775, true, true);
            }

            $this->setPermissions($directory);
        }

        $this->info('Directory permissions updated successfully.');

        return self::SUCCESS;
    }

    protected function setPermissions(string $directory): void
    {
        if (!$this->files->isDirectory($directory)) {
            return;
        }

        $this->files->chmod($directory, 0775);

        foreach ($this->files->directories($directory) as $dir) {
            $this->setPermissions($dir);
        }

        foreach ($this->files->files($directory) as $file) {
            $this->files->chmod($file->getPathname(), 0664);
        }
    }
}

