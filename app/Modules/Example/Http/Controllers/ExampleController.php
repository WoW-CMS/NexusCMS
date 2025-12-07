<?php

namespace Modules\Example\Http\Controllers;

use App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Módulo Example funcionando']);
    }
}
