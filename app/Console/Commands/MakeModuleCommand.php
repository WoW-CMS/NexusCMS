<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    /**
     * Nombre del comando para Artisan
     */
    protected $signature = 'make:module {name : The name of the module} {--force : Overwrite existing module if it exists}';

    /**
     * Descripción
     */
    protected $description = 'Create a new module with its folder structure and base ServiceProvider';

    /**
     * Ejecuta el comando
     */
    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $path = base_path("app/Modules/{$name}");

        if (File::exists($path) && !$this->option('force')) {
            $this->error("The module [{$name}] already exists! Use --force to overwrite.");
            return self::FAILURE;
        }

        // Estructura de carpetas
        $directories = [
            'Services',
            'Domain/Models',
            'Domain/Interfaces',
            'Infrastructure/Repositories',
            'Http/Controllers',
            'Http/Requests',
            'Providers',
            'Resources/views',
            'Infrastructure/Database/migrations',
        ];

        foreach ($directories as $dir) {
            File::makeDirectory("{$path}/{$dir}", 0755, true, true);
        }

        // module.json
        $moduleJson = [
            'name' => $name,
            'enabled' => true,
            'routes' => true,
            'migrations' => true,
            'views' => true,
            'namespace' => "Modules\\{$name}"
        ];

        File::put("{$path}/module.json", json_encode($moduleJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // routes.php
        $routes = <<<PHP
        <?php

        use Illuminate\\Support\\Facades\\Route;

        Route::middleware('web')
            ->prefix(strtolower('{$name}'))
            ->group(function () {
                Route::get('/', [\\Modules\\{$name}\\Http\\Controllers\\{$name}Controller::class, 'index']);
            });
        PHP;
        File::put("{$path}/Http/routes.php", $routes);

        // Controller base
        $controller = <<<PHP
        <?php

        namespace Modules\\{$name}\\Http\\Controllers;

        use App\\Http\\Controllers\\Controller;

        class {$name}Controller extends Controller
        {
            public function index()
            {
                return response()->json(['message' => '{$name} module is working']);
            }
        }
        PHP;
        File::put("{$path}/Http/Controllers/{$name}Controller.php", $controller);

        // ServiceProvider
        $provider = <<<PHP
        <?php

        namespace Modules\\{$name}\\Providers;

        use App\\Providers\\BaseModuleServiceProvider;

        class {$name}ServiceProvider extends BaseModuleServiceProvider
        {
            protected string \$moduleName = '{$name}';
        }
        PHP;
        File::put("{$path}/Providers/{$name}ServiceProvider.php", $provider);

        $this->info("Module [{$name}] created successfully at app/Modules/{$name}");
        return self::SUCCESS;
    }
}

