<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EconomicData extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id', 'year', 'gdp', 'inflation_rate',
        'population', 'exports_value', 'imports_value', 'fetched_at',
    ];

    protected $casts = [
        'fetched_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}