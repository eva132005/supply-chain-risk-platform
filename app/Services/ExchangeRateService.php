<?php

namespace App\Services;

use App\Models\Country;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    protected string $baseUrl = 'https://open.er-api.com/v6/latest';

    public function fetchRateByCountry(Country $country): ?ExchangeRate
    {
        if (!$country->currency_code) {
            return null;
        }

        $response = Http::timeout(30)->get("{$this->baseUrl}/USD");

        if (!$response->successful()) {
            Log::error('ExchangeRate API failed', [
                'country' => $country->code,
                'status'  => $response->status(),
            ]);
            return null;
        }

        $data  = $response->json();
        $rates = $data['rates'] ?? [];

        $targetCurrency = strtoupper($country->currency_code);

        if (!isset($rates[$targetCurrency])) {
            Log::warning("Currency {$targetCurrency} not found in exchange rates");
            return null;
        }

        $exchangeRate = ExchangeRate::updateOrCreate(
            [
                'country_id'      => $country->id,
                'base_currency'   => 'USD',
                'target_currency' => $targetCurrency,
            ],
            [
                'rate'       => $rates[$targetCurrency],
                'fetched_at' => now(),
            ]
        );

        return $exchangeRate;
    }
}