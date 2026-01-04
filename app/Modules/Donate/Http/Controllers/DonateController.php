<?php

namespace Modules\Donate\Http\Controllers;

use App\Http\Controllers\Controller;

class DonateController extends Controller
{
    public function index()
    {
        return view('donate::home');
    }
}   