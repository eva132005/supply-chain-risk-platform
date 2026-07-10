<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\NewsCache;
use App\Models\RiskScore;
use App\Models\WeatherData;
use App\Models\EconomicData;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCountries = Country::count();
        $totalPorts     = Port::count();
        $totalNews      = NewsCache::count();
        $totalRisks     = RiskScore::count();

        $latestRisks = RiskScore::with('country')
            ->latest('calculated_at')
            ->take(10)
            ->get();

        $highRiskCountries = RiskScore::with('country')
            ->where('risk_level', 'High')
            ->latest('calculated_at')
            ->take(5)
            ->get();

        $recentNews = NewsCache::with('country')
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalCountries', 'totalPorts', 'totalNews', 'totalRisks',
            'latestRisks', 'highRiskCountries', 'recentNews'
        ));
    }

    public function country(string $code)
    {
        $country = Country::where('code', strtoupper($code))
            ->with(['weatherData', 'economicData', 'exchangeRates', 'riskScores', 'newsCache'])
            ->firstOrFail();

        return view('country', compact('country'));
    }

    public function ports()
    {
        $ports = Port::with('country')->orderBy('name')->paginate(50);
        return view('ports', compact('ports'));
    }

    public function news()
    {
        $news = NewsCache::with('country')->latest('published_at')->paginate(20);
        return view('news', compact('news'));
    }

    public function comparison()
    {
        $countries = Country::orderBy('name')->get();
        return view('comparison', compact('countries'));
    }

 public function watchlist()
    {
        $countries = Country::orderBy('name')->get();
        return view('watchlist', compact('countries'));
    }

    public function visualization()
    {
        return view('visualization');
    }
}