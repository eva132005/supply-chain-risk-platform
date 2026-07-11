<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\RiskScore;
use App\Services\RiskScoringService;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index()
    {
        $risks = RiskScore::with('country')->latest('calculated_at')->get();
        return response()->json(['success' => true, 'total' => $risks->count(), 'data' => $risks]);
    }

    public function show(string $code)
    {
        $country = Country::where('code', strtoupper($code))->first();
        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }
        $risk = RiskScore::where('country_id', $country->id)->latest()->first();
        if (!$risk) {
            return response()->json(['success' => false, 'message' => 'Risk score belum tersedia'], 404);
        }
        return response()->json(['success' => true, 'country' => $country->name, 'data' => $risk]);
    }

    public function calculate(string $code, RiskScoringService $service)
    {
        $country = Country::where('code', strtoupper($code))->first();
        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }
        $risk = $service->calculateForCountry($country);
        return response()->json(['success' => true, 'message' => 'Risk score berhasil dihitung', 'data' => $risk]);
    }

    public function calculateAll(RiskScoringService $service)
    {
        $countries = Country::all();
        $success = 0;
        foreach ($countries as $country) {
            try {
                $service->calculateForCountry($country);
                $success++;
            } catch (\Exception $e) {
                continue;
            }
        }
        return response()->json(['success' => true, 'message' => "Risk score dihitung untuk {$success} negara"]);
    }

    public function byLevel(string $level)
    {
        $risks = RiskScore::with('country')
            ->where('risk_level', ucfirst(strtolower($level)))
            ->latest('calculated_at')->get();
        return response()->json(['success' => true, 'total' => $risks->count(), 'data' => $risks]);
    }

    public function topRisk(int $limit = 10)
    {
        $limit = min($limit, 50);
        $risks = RiskScore::with('country')
            ->orderByDesc('total_risk')
            ->take($limit)->get();
        return response()->json(['success' => true, 'data' => $risks]);
    }

    public function riskSummary()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'low'    => RiskScore::where('risk_level', 'Low')->count(),
                'medium' => RiskScore::where('risk_level', 'Medium')->count(),
                'high'   => RiskScore::where('risk_level', 'High')->count(),
                'total'  => RiskScore::count(),
            ]
        ]);
    }

    public function topRiskCountries()
    {
        $risks = RiskScore::with('country')
            ->orderByDesc('total_risk')
            ->take(5)->get();
        return response()->json(['success' => true, 'data' => $risks]);
    }
}