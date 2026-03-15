<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\Users\UserController;
use App\Http\Controllers\Frontend\Users\AuthController;
use App\Http\Controllers\Frontend\InstallController;
use App\Http\Controllers\Frontend\ForumsController;
use App\Http\Controllers\Frontend\SubscriptionController;
use App\Http\Controllers\Frontend\CommentController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['track.analytics'])->name('home');
Route::get('/howtoplay', [HomeController::class, 'howToPlay'])->middleware(['track.analytics'])->name('howtoplay');

Route::get('/test-redis', function() {
    try {
        $pong = Redis::ping();
        $keys = Redis::keys('*');
        return response()->json([
            'connected' => $pong === '+PONG',
            'keys_count' => count($keys),
            'keys' => $keys
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'connected' => false,
            'error' => $e->getMessage()
        ]);
    }
});

Route::middleware([])->group(function () {
    if (!file_exists(storage_path('installed.lock'))) {
        Route::get('/install', [InstallController::class, 'index'])->name('install.index');
        Route::get('/install/success', [InstallController::class, 'success'])->name('install.success');
        Route::post('/install', [InstallController::class, 'install'])->name('install.run');
        Route::post('/install/test-db', [InstallController::class, 'testDb'])->name('install.testDb');
    }
});

Route::prefix('news')->middleware(['track.analytics'])->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::post('/{slug}/comment', [CommentController::class, 'store'])->name('news.comment.store');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('news.comment.destroy');
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/confirm-subscription/{token}', [SubscriptionController::class, 'confirmSubscription'])->name('confirm.subscription');
    Route::get('/unsubscribe/{token}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class,'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class,'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class,'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('ucp')->middleware(['auth', 'permission:access.ucp', 'track.analytics'])->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('ucp.dashboard');
    Route::get('/gameaccount', [UserController::class, 'gameAccount'])->name('ucp.gameaccount');
    Route::get('/transactions', [UserController::class, 'transaction'])->name('ucp.transaction');
    Route::get('/gameaccount/create', [UserController::class, 'createAction'])->name('ucp.gameaccount.create');
    Route::post('/gameaccount/create', [UserController::class, 'createGameAccount'])->name('ucp.gameaccount.store');
    Route::get('/manage', [UserController::class, 'manage'])->middleware('permission:manage.own.account')->name('ucp.manageAccount');
});