<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Permission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        abort(403, 'You don`t have permissions.');
    }
}