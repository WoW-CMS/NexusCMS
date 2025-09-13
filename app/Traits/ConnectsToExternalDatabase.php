<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;

trait ConnectsToExternalDatabase
{
    /**
     * Conecta temporalmente a una base de datos externa usando los datos proporcionados.
     *
     * @param array $config Configuración de la base de datos (host, database, username, password, etc.)
     * @param string $prefix Prefijo para el nombre de la conexión (ej: 'auth', 'characters')
     * @return Connection
     */
    public function connectToExternalDatabase(array $config, string $prefix = 'external'): ?Connection
    {
        $connectionName = $prefix . '_temp_' . uniqid();

        config([
            "database.connections.$connectionName" => [
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
            ]
        ]);

        try {
            $connection = DB::connection($connectionName);
            $connection->getPdo();
            return $connection;
        } catch (\Exception $e) {
            // Clean up the temporary connection silently
            config(["database.connections.$connectionName" => null]);
            return null;
        }
    }
}