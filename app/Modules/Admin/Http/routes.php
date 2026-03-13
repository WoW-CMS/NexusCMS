<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'permission:access.admin.panel', 'web'])
    ->prefix(strtolower('acp'))
    ->group(function () {
        Route::get('/', [\Modules\Admin\Http\Controllers\AdminController::class, 'index'])->middleware('permission:access.admin.panel')->name('admin.index');
        Route::get('/permissions', [\Modules\Admin\Http\Controllers\PermissionController::class, 'index'])->middleware('permission:manage.permissions')->name('admin.permissions');
        
        // Roles
        Route::get('/roles', [\Modules\Admin\Http\Controllers\RoleController::class, 'index'])->middleware('permission:manage.roles')->name('admin.roles.index');
        Route::get('/roles/create', [\Modules\Admin\Http\Controllers\RoleController::class, 'create'])->middleware('permission:manage.roles')->name('admin.roles.create');
        Route::post('/roles', [\Modules\Admin\Http\Controllers\RoleController::class, 'store'])->middleware('permission:manage.roles')->name('admin.roles.store');
        Route::get('/roles/{role}/edit', [\Modules\Admin\Http\Controllers\RoleController::class, 'edit'])->middleware('permission:manage.roles')->name('admin.roles.edit');
        Route::put('/roles/{role}', [\Modules\Admin\Http\Controllers\RoleController::class, 'update'])->middleware('permission:manage.roles')->name('admin.roles.update');
        Route::delete('/roles/{role}', [\Modules\Admin\Http\Controllers\RoleController::class, 'destroy'])->middleware('permission:manage.roles')->name('admin.roles.destroy');
        
        // Administrative Users
        Route::get('/users', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'index'])->middleware('permission:manage.admin.users')->name('admin.users.index');
        Route::get('/users/create', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'create'])->middleware('permission:manage.admin.users')->name('admin.users.create');
        Route::post('/users', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'store'])->middleware('permission:manage.admin.users')->name('admin.users.store');
        Route::get('/users/{user}/edit', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'edit'])->middleware('permission:manage.admin.users')->name('admin.users.edit');
        Route::put('/users/{user}', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'update'])->middleware('permission:manage.admin.users')->name('admin.users.update');
        Route::delete('/users/{user}', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'destroy'])->middleware('permission:manage.admin.users')->name('admin.users.destroy');
    
        // Logs
        Route::get('/logs', [\Modules\Admin\Http\Controllers\LogController::class, 'index'])->middleware('permission:view.logs')->name('admin.logs.index');

        // Analytics
        Route::get('/analytics', [\Modules\Admin\Http\Controllers\AnalyticsController::class, 'index'])->name('admin.analytics.index');

        // Backups
        Route::get('/backups', [\Modules\Admin\Http\Controllers\BackupController::class, 'index'])->middleware('permission:manage.backups')->name('admin.backups.index');
        Route::post('/backups', [\Modules\Admin\Http\Controllers\BackupController::class, 'create'])->middleware('permission:manage.backups')->name('admin.backups.create');
        Route::get('/backups/{filename}/download', [\Modules\Admin\Http\Controllers\BackupController::class, 'download'])->middleware('permission:manage.backups')->name('admin.backups.download');
        Route::delete('/backups/{filename}', [\Modules\Admin\Http\Controllers\BackupController::class, 'destroy'])->middleware('permission:manage.backups')->name('admin.backups.destroy');

        // Settings
        Route::get('/settings', [\Modules\Admin\Http\Controllers\AdminSettingsController::class, 'index'])->middleware('permission:manage.settings')->name('admin.settings.index');
        Route::post('/settings', [\Modules\Admin\Http\Controllers\AdminSettingsController::class, 'store'])->middleware('permission:manage.settings')->name('admin.settings.store');
    });
