<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Domain\Models\Setting;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class AdminSettingsController extends Controller
{

    protected $view = [
        'general' => 'admin::settings.general',
        'email' => 'admin::settings.email',
        'realms' => 'admin::settings.realms',
        'payment' => 'admin::settings.payment',
        'security' => 'admin::settings.security',
        'appearance' => 'admin::settings.appearance',
        'seo' => 'admin::settings.seo',
        'api' => 'admin::settings.api',
        'maintenance' => 'admin::settings.maintenance',
        'localization' => 'admin::settings.localization',
        'advanced' => 'admin::settings.advanced',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $view = $request->get('view', 'general');

        if (!array_key_exists($view, $this->view)) {
            abort(404);
        }

        $settings = Setting::all()->pluck('value', 'key');
        return view($this->view[$view], compact('settings', 'view'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $view = $request->get('view');

        if (!array_key_exists($view, $this->view)) {
            abort(404);
        }

        // Prevent storage for specific views that handle their own data or are read-only here
        if (in_array($view, ['realms'])) {
            return redirect()->route('admin.settings.index', ['view' => $view])
                ->with('warning', 'Settings for this section cannot be saved via this form.');
        }

        $data = $request->except(['_token', 'view']);

        foreach ($data as $key => $value) {
            // Only allow keys matching the pattern: lowercase letters, digits, underscores and dots
            if (!preg_match('/^[a-z][a-z0-9_.]*$/i', (string) $key)) {
                continue;
            }

            // Limit value length to prevent oversized payloads
            if (is_string($value)) {
                $value = mb_substr($value, 0, 65535);
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear settings cache
        Cache::forget('site_settings');

        return redirect()->route('admin.settings.index', ['view' => $view])
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
