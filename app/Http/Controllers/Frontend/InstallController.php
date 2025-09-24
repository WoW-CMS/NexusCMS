<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use App\Services\InstallService;
use Illuminate\Support\Facades\DB;
use Exception;

class InstallController extends Controller
{
    public function index()
    {
        return view('install.index');
    }

    public function success()
    {
        return view('install.success');
    }

    public function testDb(Request $request)
    {
        try {
            $connection = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_name}",
                $request->db_username,
                $request->db_password
            );
            return response()->json(['success' => true, 'message' => '✅ Conexión exitosa']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()]);
        }
    }

    public function install(Request $request)
    {
        $service = app(InstallService::class);

        $service->run([
            'db_connection' => 'mysql',
            'db_host' => $request->db_host,
            'db_port' => $request->db_port,
            'db_name' => $request->db_name,
            'db_username' => $request->db_username,
            'db_password' => $request->db_password,
            'app_name' => $request->app_name,
            'app_url' => $request->app_url,
            'locale' => $request->locale,
            'use_redis' => $request->use_redis ?? false,
            'admin_name' => $request->admin_name,
            'admin_email' => $request->admin_email,
            'admin_password' => $request->admin_password,
        ]);

        // lock de instalación
        file_put_contents(storage_path('installed.lock'), now());

        return view('install.success');
    }
}
