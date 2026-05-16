<?php

namespace Modules\Armory\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ArmoryAdminController extends Controller
{
    public function index(): View
    {
        return view('armory-admin::index');
    }
}
