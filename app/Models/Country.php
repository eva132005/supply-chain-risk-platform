<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'code2', 'region', 'subregion', 'capital',
        'currency_code', 'currency_name', 'flag_url',
        'latitude', 'longitude', 'is_monitored',
    ];

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function weatherData()
    {
        return $this->hasMany(WeatherData::class);
    }

    public function economicData()
    {
        return $this->hasMany(EconomicData::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class);
    }

    public function newsCache()
    {
        return $this->hasMany(NewsCache::class);
    }

    public function riskScores()
    {
        return $this->hasMany(RiskScore::class);
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}