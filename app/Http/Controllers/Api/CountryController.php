<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Port;
use App\Models\NewsCache;
use App\Models\RiskScore;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }
        if ($request->has('region')) {
            $query->where('region', $request->region);
        }
        $countries = $query->orderBy('name')->get();
        return response()->json(['success' => true, 'total' => $countries->count(), 'data' => $countries]);
    }

    public function show(string $code)
    {
        $country = Country::where('code', strtoupper($code))
            ->with(['weatherData', 'economicData', 'exchangeRates', 'riskScores'])
            ->first();
        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $country]);
    }

    public function byRegion(string $region)
    {
        $countries = Country::where('region', 'like', '%' . $region . '%')->orderBy('name')->get();
        return response()->json(['success' => true, 'total' => $countries->count(), 'data' => $countries]);
    }

    public function search(string $query)
    {
        $countries = Country::where('name', 'like', '%' . $query . '%')
            ->orWhere('code', 'like', '%' . $query . '%')
            ->orWhere('capital', 'like', '%' . $query . '%')
            ->orderBy('name')->take(20)->get();
        return response()->json(['success' => true, 'total' => $countries->count(), 'data' => $countries]);
    }

    public function summary(string $code)
    {
        $country = Country::where('code', strtoupper($code))->first();
        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'country'   => $country,
                'weather'   => $country->weatherData()->latest()->first(),
                'economic'  => $country->economicData()->latest()->first(),
                'currency'  => $country->exchangeRates()->latest()->first(),
                'risk'      => $country->riskScores()->latest()->first(),
                'news'      => $country->newsCache()->latest()->take(5)->get(),
                'ports'     => $country->ports()->take(5)->get(),
            ]
        ]);
    }

    public function overview()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_countries' => Country::count(),
                'total_ports'     => Port::count(),
                'total_news'      => NewsCache::count(),
                'total_risks'     => RiskScore::count(),
                'regions'         => Country::select('region')->distinct()->pluck('region'),
            ]
        ]);
    }
}