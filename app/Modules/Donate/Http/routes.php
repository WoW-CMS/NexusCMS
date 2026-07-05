<?php

use Illuminate\Support\Facades\Route;
use Modules\Donate\Http\Controllers\DonateController;

Route::middleware('web')
    ->prefix(strtolower('Donate'))
    ->group(function () {
        Route::get('/', [DonateController::class, 'index'])->name('donate');

        // ─── Checkout ─────────────────────────────────────────────────────
        // Throttled at 5 attempts per minute per user. Protects against
        // scripted donation abuse and accidental double-submits.
        Route::post('/checkout', [DonateController::class, 'checkout'])
            ->middleware(['auth', 'throttle:donate-checkout'])
            ->name('donate.checkout');

        // ─── Gateway callbacks / webhooks ────────────────────────────────
        // GET + POST for callback (gateways vary). Webhook is POST-only.
        Route::match(['get', 'post'], '/callback/{gateway}', [DonateController::class, 'callback'])
            ->name('donate.callback');

        // Webhooks from payment providers are throttled more aggressively
        // because a compromised/duplicated webhook must never reach the
        // business logic faster than a legitimate one.
        Route::post('/webhook/{gateway}', [DonateController::class, 'webhook'])
            ->middleware('throttle:donate-webhook')
            ->name('donate.webhook');

        // ─── Receipts ────────────────────────────────────────────────────
        Route::get('/receipt/{id}', [DonateController::class, 'receipt'])
            ->middleware('auth')
            ->where('id', '[0-9]+')
            ->name('donate.receipt');

        Route::get('/receipt/{id}/pdf', [DonateController::class, 'receiptPdf'])
            ->middleware(['auth', 'throttle:donate-receipt'])
            ->where('id', '[0-9]+')
            ->name('donate.receipt.pdf');
    });
