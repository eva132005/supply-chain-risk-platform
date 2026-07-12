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
            // Economic
            'growth', 'increase', 'profit', 'stable', 'improve',
            'boost', 'recovery', 'surplus', 'expand', 'rise',
            'gain', 'strong', 'success', 'thrive', 'positive',
            'opportunity', 'innovation', 'development', 'progress',
            'efficient', 'reliable', 'robust', 'sustainable', 'prosper',
            'investment', 'export', 'demand', 'supply', 'agreement',
            'cooperation', 'partnership', 'benefit', 'advance', 'secure',
            // Trade
            'trade', 'deal', 'signing', 'alliance', 'treaty',
            'liberalization', 'open', 'access', 'reform', 'modernize',
            'upgrade', 'enhance', 'optimize', 'streamline', 'accelerate',
            // Finance
            'revenue', 'earnings', 'dividend', 'appreciation', 'rally',
            'bullish', 'upturn', 'upswing', 'rebound', 'recover',
            'stabilize', 'strengthen', 'outperform', 'exceed', 'beat',
            // Logistics
            'efficient', 'delivery', 'ontime', 'smooth', 'seamless',
            'capacity', 'throughput', 'productivity', 'reliability',
            // General positive
            'peace', 'stability', 'harmony', 'unity', 'support',
            'resolve', 'solution', 'breakthrough', 'achievement', 'victory',
        ];

        $negativeWords = [
            // Conflict
            'war', 'crisis', 'conflict', 'tension', 'dispute',
            'sanction', 'embargo', 'blockade', 'protest', 'strike',
            'attack', 'threat', 'terrorism', 'violence', 'unrest',
            // Economic
            'inflation', 'recession', 'deficit', 'debt', 'bankruptcy',
            'decline', 'loss', 'drop', 'fall', 'decrease',
            'collapse', 'crash', 'downturn', 'slowdown', 'contraction',
            'unemployment', 'poverty', 'inequality', 'corruption', 'fraud',
            // Trade
            'tariff', 'ban', 'restriction', 'barrier', 'protectionism',
            'dumping', 'penalty', 'fine', 'violation', 'breach',
            // Logistics
            'delay', 'disruption', 'shortage', 'congestion', 'bottleneck',
            'backlog', 'damage', 'loss', 'theft', 'accident',
            // Disaster
            'disaster', 'flood', 'drought', 'storm', 'earthquake',
            'hurricane', 'tsunami', 'wildfire', 'pandemic', 'epidemic',
            // General negative
            'risk', 'danger', 'warning', 'alert', 'emergency',
            'failure', 'problem', 'issue', 'concern', 'worry',
        ];

        foreach ($positiveWords as $word) {
            PositiveWord::updateOrCreate(['word' => $word]);
        }

        foreach ($negativeWords as $word) {
            NegativeWord::updateOrCreate(['word' => $word]);
        }

        $this->command->info('Sentiment words seeded: ' . count($positiveWords) . ' positive, ' . count($negativeWords) . ' negative');
    }
}