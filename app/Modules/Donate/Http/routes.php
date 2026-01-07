<?php

use Illuminate\Support\Facades\Route;
use Modules\Donate\Http\Controllers\DonateController;

Route::middleware('web')
    ->prefix(strtolower('Donate'))
    ->group(function () {
        Route::get('/', [DonateController::class, 'index'])->name('donate');
        Route::post('/checkout', [DonateController::class, 'checkout'])->middleware('auth')->name('donate.checkout');
        Route::match(['get', 'post'], '/callback/{gateway}', [DonateController::class, 'callback'])->name('donate.callback');
        Route::post('/webhook/{gateway}', [DonateController::class, 'webhook'])->name('donate.webhook');
    });
