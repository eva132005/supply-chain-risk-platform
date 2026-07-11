<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $query = Port::with('country');
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('country')) {
            $country = Country::where('code', strtoupper($request->country))->first();
            if ($country) $query->where('country_id', $country->id);
        }
        $ports = $query->orderBy('name')->take(100)->get();
        return response()->json(['success' => true, 'total' => $ports->count(), 'data' => $ports]);
    }

    public function show(int $id)
    {
        $port = Port::with('country')->find($id);
        if (!$port) {
            return response()->json(['success' => false, 'message' => 'Port tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $port]);
    }

    public function byCountry(string $code)
    {
        $country = Country::where('code', strtoupper($code))->first();
        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }
        $ports = Port::where('country_id', $country->id)->orderBy('name')->get();
        return response()->json(['success' => true, 'country' => $country->name, 'total' => $ports->count(), 'data' => $ports]);
    }

    public function search(string $query)
    {
        $ports = Port::with('country')
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('port_code', 'like', '%' . $query . '%')
            ->orderBy('name')->take(50)->get();
        return response()->json(['success' => true, 'total' => $ports->count(), 'data' => $ports]);
    }
}