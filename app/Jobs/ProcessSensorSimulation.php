<?php

namespace App\Jobs;

use App\Models\Sensor;
use App\Models\SimulationSetting;
use App\Events\SensorAlertEvent;
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
        if ($sensor->simulationSetting->is_active) {
            self::dispatch($this->sensorId, $this->settings)
                ->delay(now()->addMinutes($this->settings['frequency']));
        }
    }

    private function generateReading($sensor)
    {
        $settings = $this->settings;
        $min = $settings['min_value'];
        $max = $settings['max_value'];

        // Generate value based on pattern
        $value = match($settings['pattern_type']) {
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
        $thresholds = $this->settings['thresholds'];
        
        if ($reading['value'] >= $thresholds['critical']) {
            // Trigger critical alert
            event(new SensorAlertEvent($sensor, $reading, 'critical'));
        } elseif ($reading['value'] >= $thresholds['warning']) {
            // Trigger warning alert
            event(new SensorAlertEvent($sensor, $reading, 'warning'));
        }
    }

    private function generateRandomValue($min, $max)
    {
        return $min + (mt_rand() / mt_getrandmax()) * ($max - $min);
    }

    private function generateLinearValue($min, $max)
    {
        // Get previous value from cache or start from min
        $cacheKey = "sensor_{$this->sensorId}_linear_progress";
        $progress = Cache::get($cacheKey, 0);
        
        // Increment progress
        $progress += 0.1; // Move 10% each time
        if ($progress > 1) $progress = 0;
        
        Cache::put($cacheKey, $progress, now()->addDay());
        
        return $min + ($max - $min) * $progress;
    }

    private function generateCyclicalValue($min, $max)
    {
        // Get current angle from cache or start from 0
        $cacheKey = "sensor_{$this->sensorId}_cycle_angle";
        $angle = Cache::get($cacheKey, 0);
        
        // Calculate value using sine wave
        $amplitude = ($max - $min) / 2;
        $center = $min + $amplitude;
        $value = $center + $amplitude * sin($angle);
        
        // Increment angle
        $angle += pi() / 8; // 22.5 degrees each time
        if ($angle >= 2 * pi()) $angle = 0;
        
        Cache::put($cacheKey, $angle, now()->addDay());
        
        return $value;
    }
}
