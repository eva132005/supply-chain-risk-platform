<?php

namespace App\Services;

use App\Models\Country;
use App\Models\EconomicData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EconomicService
{
    protected string $baseUrl = 'https://api.worldbank.org/v2';

    private array $indicators = [
        'gdp'            => 'NY.GDP.MKTP.CD',
        'inflation_rate' => 'FP.CPI.TOTL.ZG',
        'population'     => 'SP.POP.TOTL',
        'exports_value'  => 'NE.EXP.GNFS.CD',
        'imports_value'  => 'NE.IMP.GNFS.CD',
    ];

    public function fetchEconomicDataByCountry(Country $country): ?EconomicData
    {
        if (!$country->code2) {
            return null;
        }

        $data = [];

        foreach ($this->indicators as $field => $indicator) {
            $value = $this->fetchIndicator($country->code2, $indicator);
            $data[$field] = $value['value'] ?? null;
            $data['year'] = $value['year'] ?? null;
        }

        $economicData = EconomicData::updateOrCreate(
            ['country_id' => $country->id],
            array_merge($data, ['fetched_at' => now()])
        );

        return $economicData;
    }

    private function fetchIndicator(string $countryCode2, string $indicator): array
    {
        $response = Http::timeout(60)->get("{$this->baseUrl}/country/{$countryCode2}/indicator/{$indicator}", [
            'format'   => 'json',
            'mrv'      => 1,
            'per_page' => 1,
        ]);

        if (!$response->successful()) {
            Log::warning("World Bank API failed for {$countryCode2}/{$indicator}");
            return [];
        }

        $json = $response->json();

        if (!isset($json[1][0])) {
            return [];
        }

        return [
            'value' => $json[1][0]['value'],
            'year'  => $json[1][0]['date'],
        ];
    }
}