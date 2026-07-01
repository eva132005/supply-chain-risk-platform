<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\NewsService;
use Illuminate\Console\Command;

class SyncNews extends Command
{
    protected $signature = 'sync:news {country? : Kode negara (opsional, contoh: IDN)}';

    protected $description = 'Sync data berita dari GNews API ke database';

    public function handle(NewsService $newsService)
    {
        $countryCode = $this->argument('country');

        if ($countryCode) {
            $country = Country::where('code', strtoupper($countryCode))->first();

            if (!$country) {
                $this->error("Negara dengan kode {$countryCode} tidak ditemukan.");
                return Command::FAILURE;
            }

            $this->info("Mengambil berita untuk {$country->name}...");
            $saved = $newsService->fetchNewsByCountry($country);
            $this->info("Selesai! {$saved} berita disimpan untuk {$country->name}.");

        } else {
            $countries = Country::all();
            $total     = $countries->count();
            $success   = 0;

            $this->info("Mengambil berita untuk {$total} negara...");
            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($countries as $country) {
                $saved = $newsService->fetchNewsByCountry($country);
                if ($saved > 0) $success++;
                $bar->advance();
                sleep(1); // delay 1 detik antar request biar tidak kena rate limit
            }

            $bar->finish();
            $this->newLine();
            $this->info("Selesai! Berhasil: {$success}/{$total} negara.");
        }

        return Command::SUCCESS;
    }
}