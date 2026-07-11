<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

// Auth Routes
Auth::routes();

// Protected Routes (harus login dulu)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/country/{code}', [DashboardController::class, 'country'])->name('country.show');
    Route::get('/ports', [DashboardController::class, 'ports'])->name('ports');
    Route::get('/news', [DashboardController::class, 'news'])->name('news');
    Route::get('/comparison', [DashboardController::class, 'comparison'])->name('comparison');
    Route::get('/watchlist', [DashboardController::class, 'watchlist'])->name('watchlist');
    Route::get('/visualization', [DashboardController::class, 'visualization'])->name('visualization');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/articles', [AdminController::class, 'articles'])->name('articles');
    Route::post('/articles', [AdminController::class, 'storeArticle'])->name('articles.store');
    Route::delete('/articles/{article}', [AdminController::class, 'deleteArticle'])->name('articles.delete');
    Route::get('/ports', [AdminController::class, 'ports'])->name('ports');
    Route::delete('/ports/{port}', [AdminController::class, 'deletePort'])->name('ports.delete');
});