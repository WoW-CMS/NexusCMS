<?php

use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->prefix('auth')->group(function () {
    Route::get('/login', [UserController::class,'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class,'showRegisterForm'])->name('register');
    Route::post('/register', [UserController::class,'register']);
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});