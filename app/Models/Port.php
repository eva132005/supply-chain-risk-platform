<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'country_id', 'country_name', 'port_code',
        'latitude', 'longitude', 'harbor_type',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}