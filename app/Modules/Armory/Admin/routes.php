<?php

use Illuminate\Support\Facades\Route;
use Modules\Armory\Admin\Controllers\ArmoryAdminController;

Route::middleware(['auth', 'permission:access.admin.panel'])
    ->prefix('acp/armory')
    ->name('admin.armory.')
    ->group(function () {
        Route::get('/', [ArmoryAdminController::class, 'index'])->name('index');
    });
