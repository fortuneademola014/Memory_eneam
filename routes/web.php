<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MemoirController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;

Route::get('/', [MemoirController::class, 'landing'])->name('landing');
Route::get('/catalogue', [MemoirController::class, 'index'])->name('catalogue');
Route::get('/memoires/{memoir}', [MemoirController::class, 'show'])->name('memoires.show');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->name('login.store');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/lecteur/{memoir}', [MemoirController::class, 'read'])->name('memoires.read');
    Route::post('/memoires/{memoir}/favori', [MemoirController::class, 'toggleFavorite'])->name('memoires.favorite');
    Route::get('/favoris-historique', [MemoirController::class, 'favoritesHistory'])->name('favorites.history');

    Route::get('/mes-depots', [DepositController::class, 'index'])->name('deposits.index');
    Route::get('/mes-depots/nouveau', [DepositController::class, 'create'])->name('deposits.create');
    Route::post('/mes-depots', [DepositController::class, 'store'])->name('deposits.store');

    Route::middleware('role:validateur,admin')->group(function () {
        Route::get('/validation', [ValidationController::class, 'index'])->name('validation.index');
        Route::get('/validation/{memoir}', [ValidationController::class, 'show'])->name('validation.show');
        Route::post('/validation/{memoir}', [ValidationController::class, 'decide'])->name('validation.decide');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/filieres', [AdminController::class, 'storeFiliere'])->name('admin.filieres.store');
        Route::post('/admin/annees', [AdminController::class, 'storeYear'])->name('admin.years.store');
        Route::post('/admin/utilisateurs/{user}/role', [AdminController::class, 'updateRole'])->name('admin.users.role');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/lire-tout', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});
