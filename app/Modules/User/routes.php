<?php

use App\Modules\News\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NewsController::class, 'index'])->name('news');