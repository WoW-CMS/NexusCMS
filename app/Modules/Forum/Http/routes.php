<?php

use Illuminate\Support\Facades\Route;
use Modules\Forum\Http\Controllers\ForumController;

Route::middleware('web')
    ->prefix('forum')
    ->name('forum.')
    ->group(function () {
        Route::get('/', [ForumController::class, 'index'])->name('index');
        Route::get('/{slug}', [ForumController::class, 'forum'])->name('show');
        Route::get('/{slug}/create', [ForumController::class, 'createThread'])->name('create-thread')->middleware('auth');
        Route::post('/{slug}/create', [ForumController::class, 'storeThread'])->name('store-thread')->middleware('auth');
        Route::get('/{forumSlug}/{threadSlug}', [ForumController::class, 'thread'])->name('thread');
        Route::post('/{forumSlug}/{threadSlug}/reply', [ForumController::class, 'storeReply'])->name('store-reply')->middleware('auth');
    });