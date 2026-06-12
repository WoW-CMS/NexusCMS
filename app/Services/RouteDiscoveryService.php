<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteDiscoveryService
{
    /**
     * Returns all named routes grouped by category.
     */
    public function getAllRoutes(): array
    {
        $routes = collect(Route::getRoutes()->getRoutesByName())
            ->map(function ($route) {
                $middleware = $route->middleware();
                $uri = $route->uri();

                return [
                    'name' => $route->getName(),
                    'uri' => '/' . ltrim($uri, '/'),
                    'methods' => $route->methods(),
                    'middleware' => $middleware,
                    'is_admin' => Str::startsWith($uri, 'acp/'),
                    'is_ucp' => Str::startsWith($uri, 'ucp/'),
                    'is_public' => !$this->hasAuthMiddleware($middleware),
                    'has_auth' => in_array('auth', $middleware, true),
                    'has_permission' => $this->hasPermissionMiddleware($middleware),
                    'permissions' => $this->extractPermissions($middleware),
                ];
            })
            ->filter(fn($r) => $r['name'] !== null)
            ->values();

        return [
            'public' => $routes->filter(fn($r) => $r['is_public'] && !$r['is_admin'] && !$r['is_ucp'])->values()->all(),
            'auth' => $routes->filter(fn($r) => $r['has_auth'] && !$r['has_permission'] && !$r['is_admin'] && !$r['is_ucp'])->values()->all(),
            'ucp' => $routes->filter(fn($r) => $r['is_ucp'])->values()->all(),
            'admin' => $routes->filter(fn($r) => $r['is_admin'])->values()->all(),
        ];
    }

    /**
     * Returns routes suitable for the menu picker (user-facing, non-admin).
     */
    public function getRoutesForMenuPicker(): array
    {
        $all = $this->getAllRoutes();

        return [
            'public' => $all['public'],
            'auth' => $all['auth'],
            'ucp' => $all['ucp'],
        ];
    }

    /**
     * Returns routes grouped by module name.
     */
    public function getRoutesByModule(): array
    {
        $routes = collect(Route::getRoutes()->getRoutesByName())
            ->map(fn($route) => [
                'name' => $route->getName(),
                'uri' => '/' . ltrim($route->uri(), '/'),
                'methods' => $route->methods(),
                'middleware' => $route->middleware(),
            ])
            ->filter(fn($r) => $r['name'] !== null)
            ->values();

        $prefixes = ['armory', 'donate', 'forum', 'store'];

        $byModule = [];
        foreach ($prefixes as $prefix) {
            $filtered = $routes->filter(fn($r) => Str::startsWith($r['name'], "{$prefix}."))->values()->all();
            if (count($filtered) > 0) {
                $byModule[$prefix] = $filtered;
            }
        }

        return $byModule;
    }

    private function hasAuthMiddleware(array $middleware): bool
    {
        return in_array('auth', $middleware, true)
            || in_array('auth:api', $middleware, true);
    }

    private function hasPermissionMiddleware(array $middleware): bool
    {
        return collect($middleware)->contains(fn($m) => Str::startsWith($m, 'permission:'));
    }

    private function extractPermissions(array $middleware): array
    {
        return collect($middleware)
            ->filter(fn($m) => Str::startsWith($m, 'permission:'))
            ->map(fn($m) => Str::after($m, 'permission:'))
            ->values()
            ->all();
    }
}
