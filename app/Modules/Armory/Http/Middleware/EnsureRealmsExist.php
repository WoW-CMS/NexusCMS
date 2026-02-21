<?php

namespace Modules\Armory\Http\Middleware;

use App\Models\Realm;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureRealmsExist
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!Schema::hasTable('realms')) {
                return redirect()->route('home');
            }

            if (!Realm::query()->exists()) {
                return redirect()->route('home');
            }
        } catch (\Throwable $e) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}

