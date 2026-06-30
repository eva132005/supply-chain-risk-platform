<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->decimal('weather_risk', 5, 2)->default(0);     // 0-100
            $table->decimal('inflation_risk', 5, 2)->default(0);   // 0-100
            $table->decimal('currency_risk', 5, 2)->default(0);    // 0-100
            $table->decimal('news_risk', 5, 2)->default(0);        // 0-100
            $table->decimal('total_risk', 5, 2)->default(0);       // hasil akhir weighted
            $table->string('risk_level')->nullable();              // Low/Medium/High
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};