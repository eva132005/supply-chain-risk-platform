<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\EconomicController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PortController;
use App\Http\Controllers\Api\RiskController;

// ============ COUNTRIES (5 endpoints) ============
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{code}', [CountryController::class, 'show']);
Route::get('/countries/region/{region}', [CountryController::class, 'byRegion']);
Route::get('/countries/search/{query}', [CountryController::class, 'search']);
Route::get('/countries/{code}/summary', [CountryController::class, 'summary']);

// ============ WEATHER (4 endpoints) ============
Route::get('/weather', [WeatherController::class, 'index']);
Route::get('/weather/{code}', [WeatherController::class, 'show']);
Route::get('/weather/{code}/refresh', [WeatherController::class, 'refresh']);
Route::get('/weather/risk/{level}', [WeatherController::class, 'byRiskLevel']);

// ============ ECONOMIC (4 endpoints) ============
Route::get('/economic', [EconomicController::class, 'index']);
Route::get('/economic/{code}', [EconomicController::class, 'show']);
Route::get('/economic/{code}/refresh', [EconomicController::class, 'refresh']);
Route::get('/economic/top/gdp', [EconomicController::class, 'topGdp']);

// ============ CURRENCY (4 endpoints) ============
Route::get('/currency', [ExchangeRateController::class, 'index']);
Route::get('/currency/{code}', [ExchangeRateController::class, 'show']);
Route::get('/currency/{code}/refresh', [ExchangeRateController::class, 'refresh']);
Route::get('/currency/compare/{codeA}/{codeB}', [ExchangeRateController::class, 'compare']);

// ============ NEWS (5 endpoints) ============
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{code}', [NewsController::class, 'show']);
Route::get('/news/sentiment/{sentiment}', [NewsController::class, 'bySentiment']);
Route::get('/news/latest/{limit}', [NewsController::class, 'latest']);
Route::get('/news/{code}/refresh', [NewsController::class, 'refresh']);

// ============ PORTS (4 endpoints) ============
Route::get('/ports', [PortController::class, 'index']);
Route::get('/ports/{id}', [PortController::class, 'show']);
Route::get('/ports/country/{code}', [PortController::class, 'byCountry']);
Route::get('/ports/search/{query}', [PortController::class, 'search']);

// ============ RISK (6 endpoints) ============
Route::get('/risk', [RiskController::class, 'index']);
Route::get('/risk/{code}', [RiskController::class, 'show']);
Route::post('/risk/calculate/{code}', [RiskController::class, 'calculate']);
Route::post('/risk/calculate-all', [RiskController::class, 'calculateAll']);
Route::get('/risk/level/{level}', [RiskController::class, 'byLevel']);
Route::get('/risk/top/{limit}', [RiskController::class, 'topRisk']);

// ============ DASHBOARD STATS (4 endpoints) ============
Route::get('/stats/overview', [CountryController::class, 'overview']);
Route::get('/stats/sentiment-summary', [NewsController::class, 'sentimentSummary']);
Route::get('/stats/risk-summary', [RiskController::class, 'riskSummary']);
Route::get('/stats/top-risk-countries', [RiskController::class, 'topRiskCountries']);