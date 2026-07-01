<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

class SyncExchangeRate extends Command
{
    protected $signature = 'sync:exchange-rate {country? : Kode negara (opsional, contoh: IDN)}';

    protected $description = 'Sync data kurs mata uang dari ExchangeRate API ke database';

    public function handle(ExchangeRateService $exchangeRateService)
    {
        $countryCode = $this->argument('country');

        if ($countryCode) {
            $country = Country::where('code', strtoupper($countryCode))->first();

            if (!$country) {
                $this->error("Negara dengan kode {$countryCode} tidak ditemukan.");
                return Command::FAILURE;
            }

            $this->info("Mengambil kurs untuk {$country->name} ({$country->currency_code})...");
            $result = $exchangeRateService->fetchRateByCountry($country);

            if ($result) {
                $this->info("Selesai! 1 USD = {$result->rate} {$result->target_currency}");
            } else {
                $this->warn("Gagal mengambil kurs untuk {$country->name}.");
            }

        } else {
            $countries = Country::whereNotNull('currency_code')->get();
            $total = $countries->count();
            $success = 0;

            $this->info("Mengambil kurs untuk {$total} negara...");
            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($countries as $country) {
                $result = $exchangeRateService->fetchRateByCountry($country);
                if ($result) $success++;
                $bar->advance();
                usleep(100000);
            }

            $bar->finish();
            $this->newLine();
            $this->info("Selesai! Berhasil: {$success}/{$total} negara.");
        }

        return Command::SUCCESS;
    }
}