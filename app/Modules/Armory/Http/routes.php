<?php

use Illuminate\Support\Facades\Route;
use Modules\Armory\Http\Controllers\ArmoryController;

Route::middleware(['web', 'realms.exists'])->prefix('armory')->name('armory.')->group(function () {
    Route::get('/', [ArmoryController::class, 'index'])->name('index');
    Route::get('{id}', [ArmoryController::class, 'show'])->where('id', '[0-9]+')->name('show');
    Route::get('{id}/{realm?}', [ArmoryController::class, 'show'])->where('id', '[0-9]+')->where('realm', '[0-9]+')->name('show.realm');
});
