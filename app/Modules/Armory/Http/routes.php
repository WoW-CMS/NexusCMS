<?php

use Illuminate\Support\Facades\Route;
use Modules\Armory\Http\Controllers\ArmoryController;

Route::middleware('web')->group(function () {
    Route::prefix('armory')->group(function () {
        Route::get('/', [ArmoryController::class, 'index'])->name('armory');
        Route::get('{id}', [ArmoryController::class, 'show'])->where('id', '[0-9]+')->name('armory.show');
        Route::get('{id}/{realm?}', [ArmoryController::class, 'show'])->where('id', '[0-9]+')->where('realm', '[0-9]+')->name('armory.show.realm');
    });
});