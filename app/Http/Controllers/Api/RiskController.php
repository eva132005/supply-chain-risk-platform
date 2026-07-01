<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\WeatherData;
use App\Models\EconomicData;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index()
    {
        $risks = RiskScore::with('country')->latest('calculated_at')->get();

        return response()->json([
            'success' => true,
            'total'   => $risks->count(),
            'data'    => $risks,
        ]);
    }

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
}