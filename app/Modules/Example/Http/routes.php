<?php

use Illuminate\Support\Facades\Route;
use Modules\Example\Http\Controllers\ExampleController;

Route::get('/example', [ExampleController::class, 'index']);
