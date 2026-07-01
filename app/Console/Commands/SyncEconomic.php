<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\EconomicService;
use Illuminate\Console\Command;

class SyncEconomic extends Command
{
    protected $signature = 'sync:economic {country? : Kode negara (opsional, contoh: IDN)}';

    protected $description = 'Sync data ekonomi dari World Bank API ke database';

    public function handle(EconomicService $economicService)
    {
        $countryCode = $this->argument('country');

        if ($countryCode) {
            $country = Country::where('code', strtoupper($countryCode))->first();

            if (!$country) {
                $this->error("Negara dengan kode {$countryCode} tidak ditemukan.");
                return Command::FAILURE;
            }

            $this->info("Mengambil data ekonomi untuk {$country->name}...");
            $result = $economicService->fetchEconomicDataByCountry($country);

            if ($result) {
                $this->info("Selesai! GDP: {$result->gdp}, Inflasi: {$result->inflation_rate}%, Populasi: {$result->population}");
            } else {
                $this->warn("Gagal mengambil data ekonomi untuk {$country->name}.");
            }

        } else {
            $countries = Country::whereNotNull('code2')->get();
            $total = $countries->count();
            $success = 0;

            $this->info("Mengambil data ekonomi untuk {$total} negara...");
            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($countries as $country) {
                $result = $economicService->fetchEconomicDataByCountry($country);
                if ($result) $success++;
                $bar->advance();
                usleep(200000); // delay 0.2 detik antar request
            }

            $bar->finish();
            $this->newLine();
            $this->info("Selesai! Berhasil: {$success}/{$total} negara.");
        }

        return Command::SUCCESS;
    }
}