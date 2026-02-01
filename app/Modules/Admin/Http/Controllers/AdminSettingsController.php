<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Domain\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{

    protected $view = [
        'general' => 'admin::settings.general',
        'email' => 'admin::settings.email',
        'database' => 'admin::settings.database',
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

        $settings = Setting::all();
        return view($this->view[$view], compact('settings', 'view'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
