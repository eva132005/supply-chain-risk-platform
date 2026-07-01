<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    // GET /api/countries
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

        return response()->json([
            'success' => true,
            'total'   => $countries->count(),
            'data'    => $countries,
        ]);
    }

    // GET /api/countries/{code}
    public function show(string $code)
    {
        $country = Country::where('code', strtoupper($code))
            ->with(['weatherData', 'economicData', 'exchangeRates', 'riskScores'])
            ->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $country,
        ]);
    }
}