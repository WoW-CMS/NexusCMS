<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function index()
    {
        return view('store::store'); 
    }
}