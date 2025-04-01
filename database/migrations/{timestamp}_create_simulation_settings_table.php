<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->integer('frequency')->default(5); // minutes
            $table->string('pattern_type')->default('random'); // random, linear, cyclical
            $table->float('min_value')->nullable();
            $table->float('max_value')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('thresholds')->nullable();
            $table->dateTime('last_run')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_settings');
    }
};
