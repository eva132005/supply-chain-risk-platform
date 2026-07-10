<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/country/{code}', [DashboardController::class, 'country'])->name('country.show');
Route::get('/ports', [DashboardController::class, 'ports'])->name('ports');
Route::get('/news', [DashboardController::class, 'news'])->name('news');
Route::get('/comparison', [DashboardController::class, 'comparison'])->name('comparison');
Route::get('/watchlist', [DashboardController::class, 'watchlist'])->name('watchlist');
Route::get('/visualization', [DashboardController::class, 'visualization'])->name('visualization');