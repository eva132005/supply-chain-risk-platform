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
            if ($country) {
                $query->where('country_id', $country->id);
            }
        }

        $ports = $query->orderBy('name')->take(100)->get();

        return response()->json([
            'success' => true,
            'total'   => $ports->count(),
            'data'    => $ports,
        ]);
    }
}