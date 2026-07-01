<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountryService
{
    protected string $dataUrl = 'https://raw.githubusercontent.com/mledoze/countries/master/countries.json';

    public function syncAllCountries(): array
    {
        $response = Http::timeout(30)->get($this->dataUrl);

        if (!$response->successful()) {
            Log::error('Countries data fetch failed', ['status' => $response->status()]);
            return ['success' => false, 'message' => 'Gagal mengambil data negara'];
        }

        $countries = $response->json();
        $created = 0;
        $updated = 0;

        foreach ($countries as $item) {
            $currencyCode = null;
            $currencyName = null;
            if (!empty($item['currencies'])) {
                $currencyCode = array_key_first($item['currencies']);
                $currencyName = $item['currencies'][$currencyCode]['name'] ?? null;
            }

            $code = $item['cca3'] ?? null;
            if (!$code) {
                continue;
            }

            $data = [
                'name'          => $item['name']['common'] ?? 'Unknown',
                'code2'         => $item['cca2'] ?? null,
                'region'        => $item['region'] ?? null,
                'subregion'     => $item['subregion'] ?? null,
                'capital'       => $item['capital'][0] ?? null,
                'currency_code' => $currencyCode,
                'currency_name' => $currencyName,
                'flag_url'      => $item['flags']['png'] ?? null,
                'latitude'      => $item['latlng'][0] ?? null,
                'longitude'     => $item['latlng'][1] ?? null,
            ];

            $country = Country::updateOrCreate(['code' => $code], $data);
            $country->wasRecentlyCreated ? $created++ : $updated++;
        }

        return [
            'success' => true,
            'created' => $created,
            'updated' => $updated,
            'total'   => count($countries),
        ];
    }
}