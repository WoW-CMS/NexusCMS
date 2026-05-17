<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminAdvancedController extends Controller
{
    private const ALLOWED_SEEDERS = [
        'roles_permissions' => \Database\Seeders\RolesAndPermissionsSeeder::class,
        'forums'            => \Database\Seeders\ForumsSeeder::class,
        'donation_plans'    => \Database\Seeders\DonationPlansSeeder::class,
        'all'               => \Database\Seeders\DatabaseSeeder::class,
    ];

    private const ALLOWED_CACHES = [
        'config' => 'config:clear',
        'routes' => 'route:clear',
        'views'  => 'view:clear',
        'app'    => 'cache:clear',
        'all'    => 'optimize:clear',
    ];

    public function runSeeder(Request $request)
    {
        $key = $request->input('seeder');

        if (!array_key_exists($key, self::ALLOWED_SEEDERS)) {
            return back()->with('advanced_error', __('admin::settings.advanced.seeder_invalid'));
        }

        try {
            Artisan::call('db:seed', [
                '--class' => self::ALLOWED_SEEDERS[$key],
                '--force' => true,
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.settings.index', ['view' => 'advanced'])
                ->with('advanced_error', __('admin::settings.advanced.seeder_failed', ['error' => $e->getMessage()]));
        }

        return redirect()
            ->route('admin.settings.index', ['view' => 'advanced'])
            ->with('advanced_success', __('admin::settings.advanced.seeder_success'));
    }

    public function clearCache(Request $request)
    {
        $key = $request->input('cache');

        if (!array_key_exists($key, self::ALLOWED_CACHES)) {
            return back()->with('advanced_error', __('admin::settings.advanced.cache_invalid'));
        }

        try {
            Artisan::call(self::ALLOWED_CACHES[$key]);
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.settings.index', ['view' => 'advanced'])
                ->with('advanced_error', __('admin::settings.advanced.cache_failed', ['error' => $e->getMessage()]));
        }

        return redirect()
            ->route('admin.settings.index', ['view' => 'advanced'])
            ->with('advanced_success', __('admin::settings.advanced.cache_success'));
    }
}
