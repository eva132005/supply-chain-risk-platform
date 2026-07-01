<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortService
{
    protected string $dataUrl = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/airports.dat';

    public function syncPorts(): array
    {
        $response = Http::timeout(60)->get($this->dataUrl);

        if (!$response->successful()) {
            Log::error('Port data fetch failed', ['status' => $response->status()]);
            return ['success' => false, 'message' => 'Gagal mengambil data pelabuhan'];
        }

        $lines   = explode("\n", $response->body());
        $created = 0;
        $skipped = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $row = str_getcsv($line);
            if (count($row) < 8) continue;

            // Format: id, name, city, country, iata, icao, lat, lng, ...
            $portName    = $row[1] ?? null;
            $countryName = $row[3] ?? null;
            $portCode    = $row[5] ?? null; // ICAO code
            $latitude    = $row[6] ?? null;
            $longitude   = $row[7] ?? null;

            if (!$portName || $portName === '\N') {
                $skipped++;
                continue;
            }

            $country = Country::where('name', $countryName)->first();

            Port::updateOrCreate(
                ['port_code' => $portCode],
                [
                    'name'         => $portName,
                    'country_id'   => $country?->id,
                    'country_name' => $countryName,
                    'latitude'     => is_numeric($latitude) ? $latitude : null,
                    'longitude'    => is_numeric($longitude) ? $longitude : null,
                    'harbor_type'  => 'Airport/Seaport',
                ]
            );

            $created++;
        }

        return [
            'success' => true,
            'created' => $created,
            'skipped' => $skipped,
        ];
    }
}