<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\NewsCache;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsCache::with('country');

        if ($request->has('country')) {
            $country = Country::where('code', strtoupper($request->country))->first();
            if ($country) {
                $query->where('country_id', $country->id);
            }
        }

        if ($request->has('sentiment')) {
            $query->where('sentiment', $request->sentiment);
        }

        $news = $query->latest('published_at')->take(50)->get();

        return response()->json([
            'success' => true,
            'total'   => $news->count(),
            'data'    => $news,
        ]);
    }

    public function show(string $code, NewsService $newsService)
    {
        $country = Country::where('code', strtoupper($code))->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Negara tidak ditemukan'], 404);
        }

        $news = NewsCache::where('country_id', $country->id)
            ->latest('published_at')
            ->take(10)
            ->get();

        if ($news->isEmpty()) {
            $newsService->fetchNewsByCountry($country);
            $news = NewsCache::where('country_id', $country->id)
                ->latest('published_at')
                ->take(10)
                ->get();
        }

        return response()->json([
            'success' => true,
            'country' => $country->name,
            'total'   => $news->count(),
            'data'    => $news,
        ]);
    }
}