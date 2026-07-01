<?php

namespace App\Services;

use App\Models\Country;
use App\Models\WeatherData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected string $baseUrl = 'https://api.open-meteo.com/v1/forecast';

    public function fetchWeatherByCountry(Country $country): ?WeatherData
    {
        if (!$country->latitude || !$country->longitude) {
            return null;
        }

        $response = Http::timeout(15)->get($this->baseUrl, [
            'latitude'       => $country->latitude,
            'longitude'      => $country->longitude,
            'current'        => 'temperature_2m,rain,wind_speed_10m,weather_code',
            'forecast_days'  => 1,
        ]);

        if (!$response->successful()) {
            Log::error('Open-Meteo API failed', [
                'country' => $country->code,
                'status'  => $response->status(),
            ]);
            return null;
        }

        $data    = $response->json();
        $current = $data['current'] ?? [];

        $temperature = $current['temperature_2m'] ?? null;
        $rainfall    = $current['rain'] ?? 0;
        $windSpeed   = $current['wind_speed_10m'] ?? 0;
        $weatherCode = $current['weather_code'] ?? 0;

        // Tentukan kondisi cuaca dari weather code
        $weatherCondition = $this->getWeatherCondition($weatherCode);

        // Tentukan storm risk berdasarkan wind speed & rainfall
        $stormRisk = $this->calculateStormRisk($windSpeed, $rainfall);

        $weatherData = WeatherData::updateOrCreate(
            ['country_id' => $country->id],
            [
                'temperature'       => $temperature,
                'rainfall'          => $rainfall,
                'wind_speed'        => $windSpeed,
                'weather_condition' => $weatherCondition,
                'storm_risk'        => $stormRisk,
                'fetched_at'        => now(),
            ]
        );

        return $weatherData;
    }

    private function getWeatherCondition(int $code): string
    {
        if ($code === 0) return 'Clear';
        if ($code <= 3) return 'Cloudy';
        if ($code <= 67) return 'Rain';
        if ($code <= 77) return 'Snow';
        if ($code <= 99) return 'Storm';
        return 'Unknown';
    }

    private function calculateStormRisk(float $windSpeed, float $rainfall): string
    {
        if ($windSpeed >= 60 || $rainfall >= 20) return 'High';
        if ($windSpeed >= 30 || $rainfall >= 10) return 'Medium';
        return 'Low';
    }
}