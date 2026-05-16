<?php

use Illuminate\Support\Facades\Route;
use Modules\Donate\Admin\Controllers\DonateAdminController;

Route::middleware(['auth', 'permission:access.admin.panel'])
    ->prefix('acp/donate')
    ->name('admin.donate.')
    ->group(function () {
        // Donation Plans
        Route::get('/plans', [DonateAdminController::class, 'plansIndex'])->name('plans.index');
        Route::get('/plans/create', [DonateAdminController::class, 'plansCreate'])->name('plans.create');
        Route::post('/plans', [DonateAdminController::class, 'plansStore'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [DonateAdminController::class, 'plansEdit'])->name('plans.edit');
        Route::put('/plans/{plan}', [DonateAdminController::class, 'plansUpdate'])->name('plans.update');
        Route::delete('/plans/{plan}', [DonateAdminController::class, 'plansDestroy'])->name('plans.destroy');

        // Transactions (read-only)
        Route::get('/transactions', [DonateAdminController::class, 'transactionsIndex'])->name('transactions.index');
    });
