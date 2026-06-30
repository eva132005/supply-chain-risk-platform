<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->year('year')->nullable();
            $table->decimal('gdp', 20, 2)->nullable();
            $table->decimal('inflation_rate', 6, 2)->nullable();    // dalam persen
            $table->bigInteger('population')->nullable();
            $table->decimal('exports_value', 20, 2)->nullable();
            $table->decimal('imports_value', 20, 2)->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_data');
    }
};