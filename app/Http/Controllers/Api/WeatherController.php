<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\WeatherData;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    // GET /api/weather/{code}
    public function show(string $code, WeatherService $weatherService)
    {
        $country = Country::where('code', strtoupper($code))->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }

        // Ambil dari database dulu
        $weather = WeatherData::where('country_id', $country->id)->latest()->first();

        // Kalau belum ada atau sudah lebih dari 1 jam, fetch ulang
        if (!$weather || $weather->fetched_at?->diffInHours(now()) >= 1) {
            $weather = $weatherService->fetchWeatherByCountry($country);
        }

        if (!$weather) {
            return response()->json(['success' => false, 'message' => 'Data cuaca tidak tersedia'], 404);
        }

        return response()->json([
            'success' => true,
            'country' => $country->name,
            'data'    => $weather,
        ]);
    }

    // GET /api/weather
    public function index()
    {
        $weather = WeatherData::with('country')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'total'   => $weather->count(),
            'data'    => $weather,
        ]);
    }
}