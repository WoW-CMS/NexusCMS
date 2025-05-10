<?php

use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::prefix('news')->group(function () {
    Route::get('/', [HomeController::class, 'news'])->name('news');
    Route::get('/{slug}', [HomeController::class, 'newsDetail'])->name('news.detail');
});