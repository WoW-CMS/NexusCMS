<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Admin\Controllers\StoreAdminController;

Route::middleware(['auth', 'permission:access.admin.panel'])
    ->prefix('acp/store')
    ->name('admin.store.')
    ->group(function () {
        Route::get('/', [StoreAdminController::class, 'index'])->name('index');
    });
