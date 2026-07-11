<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EconomicData;
use App\Services\EconomicService;
use Illuminate\Http\Request;

class EconomicController extends Controller
{
    public function index()
    {
        $data = EconomicData::with('country')->latest()->get();
        return response()->json(['success' => true, 'total' => $data->count(), 'data' => $data]);
    }

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
        return response()->json(['success' => true, 'country' => $country->name, 'data' => $economic]);
    }

    public function refresh(string $code, EconomicService $economicService)
    {
        $country = Country::where('code', strtoupper($code))->first();
        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }
        $economic = $economicService->fetchEconomicDataByCountry($country);
        if (!$economic) {
            return response()->json(['success' => false, 'message' => 'Gagal refresh data ekonomi'], 500);
        }
        return response()->json(['success' => true, 'message' => 'Data ekonomi berhasil direfresh', 'data' => $economic]);
    }

    public function topGdp()
    {
        $data = EconomicData::with('country')
            ->whereNotNull('gdp')
            ->orderByDesc('gdp')
            ->take(10)
            ->get();
        return response()->json(['success' => true, 'data' => $data]);
    }
}