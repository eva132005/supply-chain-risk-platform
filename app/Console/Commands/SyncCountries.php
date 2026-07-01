<?php

namespace App\Console\Commands;

use App\Services\CountryService;
use Illuminate\Console\Command;

class SyncCountries extends Command
{
    protected $signature = 'sync:countries';

    protected $description = 'Sync data negara dari REST Countries API ke database';

    public function handle(CountryService $countryService)
    {
        $this->info('Mengambil data negara dari REST Countries API...');

        $result = $countryService->syncAllCountries();

        if (!$result['success']) {
            $this->error($result['message']);
            return Command::FAILURE;
        }

        $this->info("Selesai! Total: {$result['total']} negara | Baru: {$result['created']} | Diupdate: {$result['updated']}");

        return Command::SUCCESS;
    }
}