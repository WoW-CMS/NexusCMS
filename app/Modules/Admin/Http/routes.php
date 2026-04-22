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
        Route::get('/users/{user}', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'show'])->middleware('permission:manage.admin.users')->name('admin.users.show');
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

        // Realm Management
        Route::get('/realms', [\Modules\Admin\Http\Controllers\RealmManagementController::class, 'index'])->middleware('permission:manage.realms')->name('admin.realms.index');
        Route::get('/realms/create', [\Modules\Admin\Http\Controllers\RealmManagementController::class, 'create'])->middleware('permission:manage.realms')->name('admin.realms.create');
        Route::post('/realms', [\Modules\Admin\Http\Controllers\RealmManagementController::class, 'store'])->middleware('permission:manage.realms')->name('admin.realms.store');
        Route::get('/realms/{realm}/edit', [\Modules\Admin\Http\Controllers\RealmManagementController::class, 'edit'])->middleware('permission:manage.realms')->name('admin.realms.edit');
        Route::put('/realms/{realm}', [\Modules\Admin\Http\Controllers\RealmManagementController::class, 'update'])->middleware('permission:manage.realms')->name('admin.realms.update');
        Route::delete('/realms/{realm}', [\Modules\Admin\Http\Controllers\RealmManagementController::class, 'destroy'])->middleware('permission:manage.realms')->name('admin.realms.destroy');

        // Menus
        Route::get('/menus', [\Modules\Admin\Http\Controllers\MenuManagerController::class, 'index'])->middleware('permission:manage.settings')->name('admin.menus.index');
        Route::put('/menus', [\Modules\Admin\Http\Controllers\MenuManagerController::class, 'update'])->middleware('permission:manage.settings')->name('admin.menus.update');

        // Modules
        Route::get('/modules', [\Modules\Admin\Http\Controllers\ModuleManagerController::class, 'index'])->middleware('permission:manage.modules')->name('admin.modules.index');
        Route::get('/modules/{module}/edit', [\Modules\Admin\Http\Controllers\ModuleManagerController::class, 'edit'])->middleware('permission:manage.modules')->name('admin.modules.edit');
        Route::put('/modules/{module}', [\Modules\Admin\Http\Controllers\ModuleManagerController::class, 'update'])->middleware('permission:manage.modules')->name('admin.modules.update');
        Route::patch('/modules/{module}/toggle', [\Modules\Admin\Http\Controllers\ModuleManagerController::class, 'toggle'])->middleware('permission:manage.modules')->name('admin.modules.toggle');
        Route::post('/modules/{module}/migrate', [\Modules\Admin\Http\Controllers\ModuleManagerController::class, 'migrate'])->middleware('permission:manage.modules')->name('admin.modules.migrate');
        Route::delete('/modules/{module}', [\Modules\Admin\Http\Controllers\ModuleManagerController::class, 'destroy'])->middleware('permission:manage.modules')->name('admin.modules.destroy');
    });
