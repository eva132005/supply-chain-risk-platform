<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\RiskScoringService;
use Illuminate\Console\Command;

class CalculateRiskScores extends Command
{
    protected $signature = 'risk:calculate {code? : Kode negara, contoh IDN}';
    protected $description = 'Hitung risk score untuk satu negara atau semua negara';

    public function handle(RiskScoringService $service): int
    {
        $code = $this->argument('code');

        $countries = $code
            ? Country::where('code', strtoupper($code))->get()
            : Country::all();

        if ($countries->isEmpty()) {
            $this->error('Negara tidak ditemukan.');
            return self::FAILURE;
        }

        $this->info("Menghitung risk score untuk {$countries->count()} negara...");
        $bar = $this->output->createProgressBar($countries->count());

        foreach ($countries as $country) {
            $service->calculateForCountry($country);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Selesai menghitung risk score.');

        return self::SUCCESS;
    }
}