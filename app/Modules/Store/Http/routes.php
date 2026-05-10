<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('store')
    ->name('store.')
    ->group(function () {
        Route::get('/', [\Modules\Store\Http\Controllers\StoreController::class, 'index'])->name('index');
        Route::post('/purchase', [\Modules\Store\Http\Controllers\StoreController::class, 'purchase'])->name('purchase');
    });