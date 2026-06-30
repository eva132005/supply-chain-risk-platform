<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->decimal('temperature', 5, 2)->nullable();       // Celsius
            $table->decimal('rainfall', 6, 2)->nullable();          // mm
            $table->decimal('wind_speed', 6, 2)->nullable();        // km/h
            $table->string('storm_risk')->nullable();               // Low/Medium/High
            $table->string('weather_condition')->nullable();        // e.g. Clear, Rain, Storm
            $table->timestamp('fetched_at')->nullable();            // kapan data diambil dari API
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_data');
    }
};