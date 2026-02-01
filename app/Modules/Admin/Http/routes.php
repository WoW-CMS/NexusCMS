<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:Admin', 'web'])
    ->prefix(strtolower('acp'))
    ->group(function () {
        Route::get('/', [\Modules\Admin\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/permissions', [\Modules\Admin\Http\Controllers\PermissionController::class, 'index'])->name('admin.permissions');
        
        // Roles
        Route::get('/roles', [\Modules\Admin\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/create', [\Modules\Admin\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles', [\Modules\Admin\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/{role}/edit', [\Modules\Admin\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/roles/{role}', [\Modules\Admin\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [\Modules\Admin\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy');
        
        // Administrative Users
        Route::get('/users', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    
        // Settings
        Route::get('/settings', [\Modules\Admin\Http\Controllers\AdminSettingsController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [\Modules\Admin\Http\Controllers\AdminSettingsController::class, 'store'])->name('admin.settings.store');
    });
