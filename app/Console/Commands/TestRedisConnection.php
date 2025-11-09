<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Throwable;

class TestRedisConnection extends Command
{
    protected $signature = 'redis:test {connection? : Redis connection name (optional)} {--ttl=10 : TTL in seconds for test key}';
    protected $description = 'Test Redis connection(s), create a test key, and dump connection details.';

    public function handle()
    {
        $this->info('🔍 Testing Redis connection(s)...');
        $this->newLine();

        $connections = config('database.redis');
        $connectionName = $this->argument('connection');
        $ttl = (int) $this->option('ttl');

        if ($connectionName) {
            if (!isset($connections[$connectionName])) {
                $this->error("❌ Connection '{$connectionName}' not found in config/database.php");
                return Command::FAILURE;
            }
            $this->testConnection($connectionName, $connections[$connectionName], $ttl);
            return Command::SUCCESS;
        }

        foreach ($connections as $name => $config) {
            $this->testConnection($name, $config, $ttl);
        }

        return Command::SUCCESS;
    }

    private function testConnection(string $name, array $config, int $ttl): void
    {
        $this->line("🔸 Testing connection: <comment>{$name}</comment>");

        // 🔧 Mostrar toda la configuración de la conexión (sin contraseñas planas)
        $debugConfig = $config;
        if (isset($debugConfig['password'])) {
            $debugConfig['password'] = str_repeat('*', strlen($debugConfig['password']));
        }

        $this->line('🧩 Connection config:');
        $this->line(json_encode($debugConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->newLine();

        try {
            $start = microtime(true);

            $redis = Redis::connection($name);

            // Clave temporal
            $key = 'redis_test_' . uniqid();
            $value = 'connected_' . now()->format('Ymd_His');

            $redis->setex($key, $ttl, $value);
            $readValue = $redis->get($key);

            $duration = round((microtime(true) - $start) * 1000, 2);

            // Información extra
            $client = $redis->client();
            $serverInfo = method_exists($client, 'getHost') ? [
                'host' => $client->getHost(),
                'port' => $client->getPort(),
                'database' => $config['database'] ?? null,
            ] : [];

            $this->info("✅ Connection OK ({$duration} ms)");
            $this->line("   🔑 Key: <comment>{$key}</comment>");
            $this->line("   📦 Value: <comment>{$value}</comment>");
            $this->line("   ⏳ TTL: {$ttl} seconds");

            if ($serverInfo) {
                $this->line("   🌐 Connected to: " . json_encode($serverInfo, JSON_UNESCAPED_SLASHES));
            }

        } catch (Throwable $e) {
            $this->error("❌ Failed: " . $e->getMessage());
        }

        $this->newLine();
    }
}
