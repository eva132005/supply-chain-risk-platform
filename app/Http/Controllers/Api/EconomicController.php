<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EconomicData;
use App\Services\EconomicService;
use Illuminate\Http\Request;

class EconomicController extends Controller
{
    public function show(string $code, EconomicService $economicService)
    {
        $country = Country::where('code', strtoupper($code))->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }

        $economic = EconomicData::where('country_id', $country->id)->latest()->first();

        if (!$economic) {
            $economic = $economicService->fetchEconomicDataByCountry($country);
        }

        if (!$economic) {
            return response()->json(['success' => false, 'message' => 'Data ekonomi tidak tersedia'], 404);
        }

        return response()->json([
            'success' => true,
            'country' => $country->name,
            'data'    => $economic,
        ]);
    }

    public function index()
    {
        $data = EconomicData::with('country')->latest()->get();

        return response()->json([
            'success' => true,
            'total'   => $data->count(),
            'data'    => $data,
        ]);
    }
}