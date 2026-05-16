<?php

use Illuminate\Support\Facades\Route;
use Modules\Forum\Admin\Controllers\ForumAdminController;

Route::middleware(['auth', 'permission:access.admin.panel'])
    ->prefix('acp/forum')
    ->name('admin.forum.')
    ->group(function () {
        Route::get('/', [ForumAdminController::class, 'index'])->name('index');
        Route::get('/create', [ForumAdminController::class, 'create'])->name('create');
        Route::post('/', [ForumAdminController::class, 'store'])->name('store');
        Route::get('/{forum}/edit', [ForumAdminController::class, 'edit'])->name('edit');
        Route::put('/{forum}', [ForumAdminController::class, 'update'])->name('update');
        Route::delete('/{forum}', [ForumAdminController::class, 'destroy'])->name('destroy');
    });
