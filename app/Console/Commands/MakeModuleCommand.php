<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Artisan command to scaffold a new module.
 *
 * Generates the full folder structure, base controller,
 * service provider and configuration files.
 */
class MakeModuleCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:module {name : The name of the module} {--force : Overwrite existing module if it exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module with its folder structure and base ServiceProvider';

    /**
     * Execute the console command.
     *
     * @return int Exit code: 0 on success, 1 on failure.
     */
    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $path = base_path("app/Modules/{$name}");

        if (File::exists($path) && !$this->option('force')) {
            $this->error("The module [{$name}] already exists! Use --force to overwrite.");
            return self::FAILURE;
        }

        // Ensure clean slate when --force is used
        if ($this->option('force') && File::exists($path)) {
            File::deleteDirectory($path);
        }

        // Directory structure to be created inside the module
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

        // Generate module.json configuration file
        $moduleJson = [
            'name' => $name,
            'enabled' => true,
            'module_type' => 'third_party',
            'routes' => true,
            'migrations' => true,
            'views' => true,
            'namespace' => "Modules\\{$name}"
        ];

        File::put("{$path}/module.json", json_encode($moduleJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Generate default routes file
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

        // Generate base controller
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

        // Generate module service provider
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
