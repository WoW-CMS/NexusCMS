<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix(strtolower('Armory'))
    ->group(function () {
        Route::get('/', [\Modules\Armory\Http\Controllers\ArmoryController::class, 'index']);
    });