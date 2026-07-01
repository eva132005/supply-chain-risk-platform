<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id', 'title', 'description', 'url', 'source',
        'category', 'sentiment', 'positive_score', 'negative_score',
        'published_at', 'fetched_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'fetched_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}