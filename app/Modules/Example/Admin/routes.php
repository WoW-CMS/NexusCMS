<?php

use Illuminate\Support\Facades\Route;
use Modules\Example\Admin\Controllers\ExampleAdminController;

Route::middleware(['auth', 'permission:access.admin.panel'])
    ->prefix('acp/example')
    ->name('admin.example.')
    ->group(function () {
        Route::get('/', [ExampleAdminController::class, 'index'])->name('index');
    });
