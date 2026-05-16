<?php

namespace Modules\Example\Admin\Controllers;

use App\Http\Controllers\Controller;

class ExampleAdminController extends Controller
{
    public function index()
    {
        return view('example-admin::index');
    }
}
