<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\WeatherData;
use App\Models\EconomicData;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Services\RiskScoringService;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    /**
     * GET /api/risk
     * Daftar semua risk score (data terbaru per negara).
     */
    public function index()
    {
        $risks = RiskScore::with('country')->latest('calculated_at')->get();

        return response()->json([
            'success' => true,
            'total'   => $risks->count(),
            'data'    => $risks,
        ]);
    }

    /**
     * GET /api/risk/{code}
     * Ambil risk score terakhir untuk satu negara.
     */
    public function show(string $code)
    {
        $country = Country::where('code', strtoupper($code))->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }

        $risk = RiskScore::where('country_id', $country->id)->latest()->first();

        if (!$risk) {
            return response()->json(['success' => false, 'message' => 'Data risk belum tersedia, silakan hitung dulu'], 404);
        }

        return response()->json([
            'success' => true,
            'country' => $country->name,
            'data'    => $risk,
        ]);
    }

    /**
     * POST /api/risk/calculate/{code}
     * Hitung ulang risk score untuk satu negara.
     */
    public function calculate(string $code, RiskScoringService $service)
    {
        $country = Country::where('code', strtoupper($code))->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }

        $risk = $service->calculateForCountry($country);

        return response()->json([
            'success' => true,
            'message' => 'Risk score berhasil dihitung',
            'country' => $country->name,
            'data'    => $risk,
        ]);
    }

    /**
     * POST /api/risk/calculate-all
     * Hitung ulang risk score untuk semua negara sekaligus.
     */
    public function calculateAll(RiskScoringService $service)
    {
        $countries = Country::all();
        $results = [];

        foreach ($countries as $country) {
            $results[] = $service->calculateForCountry($country);
        }

        return response()->json([
            'success' => true,
            'message' => 'Risk score berhasil dihitung untuk semua negara',
            'total'   => count($results),
        ]);
    }
}