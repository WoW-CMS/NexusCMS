<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:Admin', 'web'])
    ->prefix(strtolower('acp'))
    ->group(function () {
        Route::get('/', [\Modules\Admin\Http\Controllers\AdminController::class, 'index']);
        Route::get('/permissions', [\Modules\Admin\Http\Controllers\PermissionController::class, 'index'])->name('admin.permissions');
        
        // Roles
        Route::get('/roles', [\Modules\Admin\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/create', [\Modules\Admin\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles', [\Modules\Admin\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/{role}/edit', [\Modules\Admin\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/roles/{role}', [\Modules\Admin\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update');
    });
