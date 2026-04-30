<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstallerIsAvailable
{
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = file_exists(storage_path('installer.lock')) || file_exists(storage_path('installed.lock'));

        if ($isInstalled) {
            if ($request->routeIs('install.success') && $request->session()->has('install_completed')) {
                return $next($request);
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
