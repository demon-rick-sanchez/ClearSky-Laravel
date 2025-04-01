<?php

namespace App\Jobs;

use App\Models\Sensor;
use App\Models\SimulationSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessSensorSimulation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sensorId;
    private $settings;

    public function __construct($sensorId, $settings)
    {
        $this->sensorId = $sensorId;
        $this->settings = $settings;
    }

    public function handle()
    {
        $sensor = Sensor::find($this->sensorId);
        if (!$sensor || !$sensor->simulationSetting->is_active) {
            return;
        }

        // Generate new reading
        $reading = $this->generateReading($sensor);

        // Store reading
        $this->storeReading($sensor->id, $reading);

        // Check for alerts
        $this->checkAlerts($sensor, $reading);

        // Schedule next run based on frequency
        $this->dispatch(self::class)
            ->delay(now()->addMinutes($this->settings['frequency']));
    }

    private function generateReading($sensor)
    {
        $settings = $sensor->simulationSetting;
        $min = $settings->min_value;
        $max = $settings->max_value;

        // Generate value based on pattern
        $value = match($settings->pattern_type) {
            'linear' => $this->generateLinearValue($min, $max),
            'cyclical' => $this->generateCyclicalValue($min, $max),
            default => $this->generateRandomValue($min, $max)
        };

        return [
            'value' => round($value, 2),
            'timestamp' => now(),
            'sensor_id' => $sensor->id
        ];
    }

    private function storeReading($sensorId, $reading)
    {
        $cacheKey = "sensor_{$sensorId}_readings";
        $readings = Cache::get($cacheKey, []);
        array_push($readings, $reading);
        
        // Keep only last 100 readings
        if (count($readings) > 100) {
            array_shift($readings);
        }

        Cache::put($cacheKey, $readings, now()->addDay());
    }

    private function checkAlerts($sensor, $reading)
    {
        if ($reading['value'] > $sensor->threshold_value) {
            // Trigger alert logic here
            event(new SensorAlertEvent($sensor, $reading));
        }
    }

    // Helper methods for value generation
    private function generateRandomValue($min, $max)
    {
        return $min + (mt_rand() / mt_getrandmax()) * ($max - $min);
    }

    private function generateLinearValue($min, $max)
    {
        // Implement linear progression logic
        $trend = Cache::get("sensor_{$this->sensorId}_trend", 0);
        $value = $min + ($max - $min) * $trend;
        
        // Update trend (0 to 1)
        $trend += 0.1;
        if ($trend > 1) $trend = 0;
        Cache::put("sensor_{$this->sensorId}_trend", $trend, now()->addDay());

        return $value;
    }

    private function generateCyclicalValue($min, $max)
    {
        $amplitude = ($max - $min) / 2;
        $center = $min + $amplitude;
        $time = time();
        
        // Create a sinusoidal pattern
        return $center + $amplitude * sin($time / 3600); // 1-hour cycle
    }
}
