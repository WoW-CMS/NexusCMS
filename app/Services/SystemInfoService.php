<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class SystemInfoService
{
    public function getInfo(): array
    {
        return [
            'php' => [
                'label' => 'PHP Version',
                'value' => PHP_VERSION,
                'icon'  => 'fab fa-php',
                'color' => 'purple',
            ],
            'laravel' => [
                'label' => 'Laravel',
                'value' => app()->version(),
                'icon'  => 'fab fa-laravel',
                'color' => 'red',
            ],
            'nexuscms' => [
                'label' => 'NexusCMS',
                'value' => config('app.version', 'Unknown'),
                'icon'  => 'fas fa-cubes',
                'color' => 'blue',
            ],
            'database' => [
                'label' => 'Database',
                'value' => $this->getDatabaseInfo(),
                'icon'  => 'fas fa-database',
                'color' => 'green',
            ],
            'cache' => [
                'label' => 'Cache Driver',
                'value' => config('cache.default'),
                'icon'  => 'fas fa-bolt',
                'color' => 'yellow',
            ],
            'queue' => [
                'label' => 'Queue Driver',
                'value' => config('queue.default'),
                'icon'  => 'fas fa-clock',
                'color' => 'cyan',
            ],
            'environment' => [
                'label' => 'Environment',
                'value' => app()->environment(),
                'icon'  => 'fas fa-server',
                'color' => $this->isProduction() ? 'red' : 'green',
            ],
            'timezone' => [
                'label' => 'Timezone',
                'value' => config('app.timezone'),
                'icon'  => 'fas fa-globe',
                'color' => 'indigo',
            ],
        ];
    }

    public function getDatabaseInfo(): string
    {
        $driver = config('database.default');
        $connections = config('database.connections');

        if (isset($connections[$driver])) {
            $db = $connections[$driver]['database'] ?? 'unknown';
            return ucfirst($driver) . " ({$db})";
        }

        return ucfirst($driver);
    }

    public function isProduction(): bool
    {
        return app()->environment('production');
    }

    public function isDebugEnabled(): bool
    {
        return config('app.debug') === true;
    }

    public function getUptime(): string
    {
        if (!function_exists('shell_exec')) {
            return 'N/A';
        }

        $uptime = @shell_exec('uptime -p 2>&1');
        if ($uptime === null) {
            // Windows
            $uptime = @shell_exec('net stats srv 2>&1');
            if ($uptime && preg_match('/Statistics since (\d{1,2}\/\d{1,2}\/\d{4})/', $uptime, $m)) {
                return 'Since ' . $m[1];
            }
            return 'N/A';
        }

        return trim($uptime) ?: 'N/A';
    }

    public function getServerSoftware(): string
    {
        return $_SERVER['SERVER_SOFTWARE'] ?? $_SERVER['SERVER_SIGNATURE'] ?? 'Unknown';
    }
}