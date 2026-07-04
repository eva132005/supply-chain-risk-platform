<?php
// app/Services/RiskScoringService.php

namespace App\Services;

use App\Models\Country;
use App\Models\WeatherData;
use App\Models\EconomicData;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Models\RiskScore;
use Carbon\Carbon;

class RiskScoringService
{
    // Bobot sesuai ketentuan dosen
    protected float $weatherWeight   = 0.30;
    protected float $inflationWeight = 0.20;
    protected float $newsWeight      = 0.40;
    protected float $currencyWeight  = 0.10;

    /**
     * Hitung & simpan risk score untuk satu negara.
     */
    public function calculateForCountry(Country $country): RiskScore
    {
        $weatherRisk   = $this->weatherRisk($country);
        $inflationRisk = $this->inflationRisk($country);
        $currencyRisk  = $this->currencyRisk($country);
        $newsRisk      = $this->newsRisk($country);

        $totalRisk = round(
            ($weatherRisk   * $this->weatherWeight) +
            ($inflationRisk * $this->inflationWeight) +
            ($newsRisk      * $this->newsWeight) +
            ($currencyRisk  * $this->currencyWeight),
            2
        );

        $riskLevel = $this->riskLevel($totalRisk);

        return RiskScore::create([
            'country_id'     => $country->id,
            'weather_risk'   => $weatherRisk,
            'inflation_risk' => $inflationRisk,
            'currency_risk'  => $currencyRisk,
            'news_risk'      => $newsRisk,
            'total_risk'     => $totalRisk,
            'risk_level'     => $riskLevel,
            'calculated_at'  => now(),
        ]);
    }

    /**
     * Weather Risk (0-100)
     * Basis: storm_risk (Low/Medium/High) + penyesuaian rainfall & wind_speed
     */
    protected function weatherRisk(Country $country): float
    {
        $weather = WeatherData::where('country_id', $country->id)
            ->latest('fetched_at')
            ->first();

        if (!$weather) {
            return 0.0; // tidak ada data = tidak ada info risiko cuaca
        }

        $base = match ($weather->storm_risk) {
            'High'   => 75,
            'Medium' => 45,
            'Low'    => 15,
            default  => 30,
        };

        // penyesuaian tambahan dari rainfall (mm) & wind_speed (km/h)
        $adjustment = 0;
        if ($weather->rainfall !== null && $weather->rainfall > 50) {
            $adjustment += 10;
        }
        if ($weather->wind_speed !== null && $weather->wind_speed > 40) {
            $adjustment += 15;
        }

        return (float) min(100, $base + $adjustment);
    }

    /**
     * Inflation Risk (0-100)
     * Semakin tinggi inflasi, semakin tinggi risiko.
     * Referensi kasar: inflasi sehat ~2%, waspada >5%, bahaya >10%
     */
    protected function inflationRisk(Country $country): float
    {
        $economic = EconomicData::where('country_id', $country->id)
            ->whereNotNull('inflation_rate')
            ->latest('year')
            ->first();

        if (!$economic || $economic->inflation_rate === null) {
            return 0.0;
        }

        $inflation = (float) $economic->inflation_rate;

        // deflasi (negatif) juga dianggap ada risiko, pakai nilai absolut
        $score = abs($inflation) * 6;

        return (float) min(100, max(0, $score));
    }

    /**
     * Currency Risk (0-100)
     * Basis: volatilitas dari 2 record kurs terakhir.
     */
    protected function currencyRisk(Country $country): float
    {
        $rates = ExchangeRate::where('country_id', $country->id)
            ->latest('fetched_at')
            ->take(2)
            ->get();

        if ($rates->count() < 2) {
            return 20.0; // data belum cukup untuk hitung volatilitas -> baseline
        }

        $latest   = (float) $rates[0]->rate;
        $previous = (float) $rates[1]->rate;

        if ($previous == 0) {
            return 20.0;
        }

        $changePercent = abs(($latest - $previous) / $previous) * 100;

        return match (true) {
            $changePercent <= 1  => 10.0,
            $changePercent <= 3  => 30.0,
            $changePercent <= 5  => 50.0,
            $changePercent <= 10 => 70.0,
            default              => 90.0,
        };
    }

    /**
     * News Risk (0-100)
     * Basis: rasio sentimen negatif dari berita 30 hari terakhir.
     */
    protected function newsRisk(Country $country): float
    {
        $news = NewsCache::where('country_id', $country->id)
            ->where('fetched_at', '>=', Carbon::now()->subDays(30))
            ->get();

        if ($news->isEmpty()) {
            return 30.0; // tidak ada berita -> asumsikan risiko netral-rendah
        }

        $totalPositive = $news->sum('positive_score');
        $totalNegative = $news->sum('negative_score');
        $total = $totalPositive + $totalNegative;

        if ($total == 0) {
            return 30.0;
        }

        return (float) round(($totalNegative / $total) * 100, 2);
    }

    protected function riskLevel(float $totalRisk): string
    {
        return match (true) {
            $totalRisk <= 30 => 'Low',
            $totalRisk <= 60 => 'Medium',
            default          => 'High',
        };
    }
}