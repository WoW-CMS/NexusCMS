<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\Users\UserController;
use App\Http\Controllers\Frontend\Users\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('news.show');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class,'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class,'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class,'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rutas para users
Route::prefix('ucp')->middleware(['auth', 'role:User,GameMaster,Admin'])->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('ucp.dashboard');
    Route::get('/gameaccount', [UserController::class, 'gameAccount'])->name('ucp.gameaccount');
    Route::get('/battlepass', [UserController::class, 'battlePass'])->name('ucp.battlepass');
    Route::post('/gameaccount/create', [UserController::class, 'createGameAccount'])->name('ucp.gameaccount.create');
});
