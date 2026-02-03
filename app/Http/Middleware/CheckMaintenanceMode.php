<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if maintenance mode is not enabled
        if (settings('maintenance_mode') !== '1') {
            return $next($request);
        }

        // Skip for admin routes (assuming /admin prefix or Admin module routes)
        if ($request->is('acp') || $request->is('acp/*')) {
            return $next($request);
        }

        // Skip auth login routes and check role admin
        if ($request->is('auth/login') || $request->user()?->hasRole('admin')) {
            return $next($request);
        }

        // Fixed prevent not logout when maintenance mode is enabled
        if ($request->is('auth/logout')) {
            return $next($request);
        }

        // Return 503 Service Unavailable with maintenance view
        return response()->view('errors.maintenance', [
            'message' => settings('maintenance_message', 'We are currently performing scheduled maintenance. We will be back shortly.'),
            'title' => settings('site_name', 'NexusCMS') . ' - Maintenance'
        ], 503);
    }
}
