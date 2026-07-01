<?php

namespace App\Services;

use App\Models\Country;
use App\Models\NewsCache;
use App\Models\PositiveWord;
use App\Models\NegativeWord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsService
{
    protected string $baseUrl = 'https://newsdata.io/api/1/news';
    protected string $apiKey  = 'pub_45d7347136524e769e345c84f227eff3';

    public function fetchNewsByCountry(Country $country): int
    {
        $response = Http::timeout(30)->get($this->baseUrl, [
            'apikey'   => $this->apiKey,
            'q'        => "{$country->name} economy trade logistics",
            'language' => 'en',
            'category' => 'business,politics',
        ]);

        if (!$response->successful()) {
            Log::error('NewsData API failed', [
                'country' => $country->code,
                'status'  => $response->status(),
            ]);
            return 0;
        }

        $data     = $response->json();
        $articles = $data['results'] ?? [];

        if (empty($articles)) {
            Log::warning('No articles returned for ' . $country->name);
            return 0;
        }

        $positiveWords = PositiveWord::pluck('word')->toArray();
        $negativeWords = NegativeWord::pluck('word')->toArray();
        $saved = 0;

        foreach ($articles as $article) {
            $title       = $article['title'] ?? '';
            $description = $article['description'] ?? '';
            $url         = $article['link'] ?? '';

            if (empty($url)) continue;

            $text  = strtolower($title . ' ' . $description);
            $words = str_word_count($text, 1);

            $positiveScore = count(array_intersect($words, $positiveWords));
            $negativeScore = count(array_intersect($words, $negativeWords));

            if ($positiveScore > $negativeScore) {
                $sentiment = 'Positive';
            } elseif ($negativeScore > $positiveScore) {
                $sentiment = 'Negative';
            } else {
                $sentiment = 'Neutral';
            }

            try {
                NewsCache::updateOrCreate(
                    ['url' => $url],
                    [
                        'country_id'     => $country->id,
                        'title'          => mb_substr($title, 0, 255),
                        'description'    => $description,
                        'source'         => $article['source_id'] ?? null,
                        'category'       => 'economy',
                        'sentiment'      => $sentiment,
                        'positive_score' => $positiveScore,
                        'negative_score' => $negativeScore,
                        'published_at'   => isset($article['pubDate']) ? date('Y-m-d H:i:s', strtotime($article['pubDate'])) : null,
                        'fetched_at'     => now(),
                    ]
                );
                $saved++;
            } catch (\Exception $e) {
                Log::error('Failed to save news: ' . $e->getMessage());
            }
        }

        return $saved;
    }
}