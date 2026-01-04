<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix(strtolower('Donate'))
    ->group(function () {
        Route::get('/', [\Modules\Donate\Http\Controllers\DonateController::class, 'index'])->name('donate');
    });