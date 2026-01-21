<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix(strtolower('Store'))
    ->group(function () {
        Route::get('/', [\Modules\Store\Http\Controllers\StoreController::class, 'index']);
    });