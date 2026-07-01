<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\WeatherService;
use Illuminate\Console\Command;

class SyncWeather extends Command
{
    protected $signature = 'sync:weather {country? : Kode negara (opsional, contoh: IDN)}';

    protected $description = 'Sync data cuaca dari Open-Meteo API ke database';

    public function handle(WeatherService $weatherService)
    {
        $countryCode = $this->argument('country');

        if ($countryCode) {
            // Fetch cuaca untuk satu negara saja
            $country = Country::where('code', strtoupper($countryCode))->first();

            if (!$country) {
                $this->error("Negara dengan kode {$countryCode} tidak ditemukan.");
                return Command::FAILURE;
            }

            $this->info("Mengambil cuaca untuk {$country->name}...");
            $result = $weatherService->fetchWeatherByCountry($country);

            if ($result) {
                $this->info("Selesai! Cuaca {$country->name}: {$result->weather_condition}, {$result->temperature}°C, Storm Risk: {$result->storm_risk}");
            } else {
                $this->warn("Gagal mengambil cuaca untuk {$country->name} (koordinat tidak tersedia).");
            }

        } else {
            // Fetch cuaca untuk semua negara
            $countries = Country::whereNotNull('latitude')->whereNotNull('longitude')->get();
            $total = $countries->count();
            $success = 0;

            $this->info("Mengambil cuaca untuk {$total} negara...");
            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($countries as $country) {
                $result = $weatherService->fetchWeatherByCountry($country);
                if ($result) $success++;
                $bar->advance();
                usleep(100000); // delay 0.1 detik antar request biar tidak kena rate limit
            }

            $bar->finish();
            $this->newLine();
            $this->info("Selesai! Berhasil: {$success}/{$total} negara.");
        }

        return Command::SUCCESS;
    }
}