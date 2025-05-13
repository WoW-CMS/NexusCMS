<?php

use App\Http\Controllers\Frontend\ArmoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('news.show');
});
Route::prefix('armory')->group(function () {
    Route::get('/', [ArmoryController::class, 'index'])->name('armory');
    Route::get('{id}', [ArmoryController::class, 'show'])->where('id', '[0-9]+')->name('armory.show');
});
