<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix(strtolower('Forum'))
    ->group(function () {
        Route::get('/', [\Modules\Forum\Http\Controllers\ForumController::class, 'index']);
        Route::get('/{slug}', [\Modules\Forum\Http\Controllers\ForumController::class, 'forum'])->name('forum.show');
    });