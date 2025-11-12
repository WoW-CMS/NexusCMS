<?php

namespace Modules\Armory\Http\Controllers;

use App\Http\Controllers\Controller;

class ArmoryController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Armory module is working']);
    }
}