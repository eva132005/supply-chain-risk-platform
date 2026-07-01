<?php

namespace Database\Seeders;

use App\Models\PositiveWord;
use App\Models\NegativeWord;
use Illuminate\Database\Seeder;

class SentimentWordSeeder extends Seeder
{
    public function run(): void
    {
        $positiveWords = [
            'growth', 'increase', 'profit', 'stable', 'improve',
            'boost', 'recovery', 'surplus', 'expand', 'rise',
            'gain', 'strong', 'success', 'thrive', 'positive',
            'opportunity', 'innovation', 'development', 'progress',
            'efficient', 'reliable', 'robust', 'sustainable', 'prosper',
            'investment', 'export', 'demand', 'supply', 'agreement',
            'cooperation', 'partnership', 'benefit', 'advance', 'secure',
        ];

        $negativeWords = [
            'war', 'crisis', 'inflation', 'delay', 'disaster',
            'decline', 'loss', 'risk', 'conflict', 'shortage',
            'recession', 'collapse', 'threat', 'disruption', 'sanction',
            'instability', 'corruption', 'deficit', 'unemployment', 'poverty',
            'flood', 'drought', 'storm', 'earthquake', 'pandemic',
            'ban', 'tariff', 'blockade', 'protest', 'strike',
            'decrease', 'drop', 'fall', 'weak', 'negative',
        ];

        foreach ($positiveWords as $word) {
            PositiveWord::updateOrCreate(['word' => $word]);
        }

        foreach ($negativeWords as $word) {
            NegativeWord::updateOrCreate(['word' => $word]);
        }

        $this->command->info('Sentiment words seeded successfully!');
    }
}