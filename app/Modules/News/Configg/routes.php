<?php

use App\Modules\News\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('news.show');
});