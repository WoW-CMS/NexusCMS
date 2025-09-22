<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\Users\UserController;
use App\Http\Controllers\Frontend\Users\AuthController;
use App\Http\Controllers\Frontend\InstallController;
use App\Http\Controllers\Frontend\ForumsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware([])->group(function () {
    if (!file_exists(storage_path('installed.lock'))) {
        Route::get('/install', [InstallController::class, 'index'])->name('install.index');
        Route::post('/install', [InstallController::class, 'install'])->name('install.run');
        Route::post('/install/test-db', [InstallController::class, 'testDb'])->name('install.testDb');
    }
});

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
    Route::get('/gameaccount/create', [UserController::class, 'createAction'])->name('ucp.gameaccount.create');
    Route::post('/gameaccount/create', [UserController::class, 'createGameAccount'])->name('ucp.gameaccount.create');
    Route::get('/manage', [UserController::class, 'manage'])->name('ucp.manageAccount');
});

// Forums routes
Route::prefix('forums')->group(function () {
    Route::get('/', [ForumsController::class, 'index'])->name('forums');
    
    // Routes that require authentication
    Route::prefix('ucp')->middleware(['auth', 'role:User,GameMaster,Admin'])->group(function () {
        Route::get('/{slug}/create', [ForumsController::class, 'createThread'])->name('forums.create_thread');
        Route::post('/{slug}/create', [ForumsController::class, 'storeThread'])->name('forums.store_thread');
        Route::post('/{forumSlug}/{threadSlug}/reply', [ForumsController::class, 'storePost'])->name('forums.store_post');
    });
    
    // These routes must be defined after the more specific routes above
    Route::get('/{forumSlug}/{threadSlug}', [ForumsController::class, 'showThread'])->name('forums.thread');
    Route::get('/{slug}', [ForumsController::class, 'showForum'])->name('forums.show');
});
