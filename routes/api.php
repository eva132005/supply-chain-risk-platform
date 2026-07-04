<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\EconomicController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PortController;
use App\Http\Controllers\Api\RiskController;

// Countries
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{code}', [CountryController::class, 'show']);

// Weather
Route::get('/weather', [WeatherController::class, 'index']);
Route::get('/weather/{code}', [WeatherController::class, 'show']);

// Economic
Route::get('/economic', [EconomicController::class, 'index']);
Route::get('/economic/{code}', [EconomicController::class, 'show']);

// Currency / Exchange Rate
Route::get('/currency', [ExchangeRateController::class, 'index']);
Route::get('/currency/{code}', [ExchangeRateController::class, 'show']);

// News
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{code}', [NewsController::class, 'show']);

// Ports
Route::get('/ports', [PortController::class, 'index']);

// Risk
Route::get('/risk', [RiskController::class, 'index']);
Route::get('/risk/{code}', [RiskController::class, 'show']);
Route::post('/risk/calculate/{code}', [RiskController::class, 'calculate']);
Route::post('/risk/calculate-all', [RiskController::class, 'calculateAll']);