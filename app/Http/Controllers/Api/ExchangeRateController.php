<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function show(string $code, ExchangeRateService $exchangeRateService)
    {
        $country = Country::where('code', strtoupper($code))->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }

        $rate = ExchangeRate::where('country_id', $country->id)->latest()->first();

        if (!$rate || $rate->fetched_at?->diffInHours(now()) >= 1) {
            $rate = $exchangeRateService->fetchRateByCountry($country);
        }

        if (!$rate) {
            return response()->json(['success' => false, 'message' => 'Data kurs tidak tersedia'], 404);
        }

        return response()->json([
            'success' => true,
            'country' => $country->name,
            'data'    => $rate,
        ]);
    }

    public function index()
    {
        $data = ExchangeRate::with('country')->latest()->get();

        return response()->json([
            'success' => true,
            'total'   => $data->count(),
            'data'    => $data,
        ]);
    }
}