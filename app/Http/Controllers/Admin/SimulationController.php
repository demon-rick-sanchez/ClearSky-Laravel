<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\SimulationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SimulationController extends Controller
{
    public function index()
    {
        $sensors = Sensor::where('status', 'active')->get();
        return view('admin.simulation', compact('sensors'));
    }

    public function generateData(Request $request)
    {
        $sensor = Sensor::findOrFail($request->sensor_id);
        $settings = $sensor->simulationSetting;
        
        if (!$settings) {
            return response()->json(['success' => false, 'message' => 'Simulation settings not found']);
        }

        $data = $this->generatePatternData(
            $settings->pattern_type,
            $settings->min_value ?? $this->getDefaultMinValue($sensor->type),
            $settings->max_value ?? $this->getDefaultMaxValue($sensor->type),
            $request->duration ?? 60
        );

        // Store last 100 readings in cache
        $cacheKey = "sensor_{$sensor->id}_readings";
        $existingReadings = Cache::get($cacheKey, []);
        $allReadings = array_merge($existingReadings, $data);
        $allReadings = array_slice($allReadings, -100); // Keep last 100 readings
        Cache::put($cacheKey, $allReadings, now()->addHours(1));

        return response()->json([
            'success' => true,
            'data' => $data,
            'sensor' => [
                'name' => $sensor->name,
                'type' => $sensor->type,
                'unit' => $this->getSensorUnit($sensor->type)
            ]
        ]);
    }

    public function updateSettings(Request $request, Sensor $sensor)
    {
        $validated = $request->validate([
            'frequency' => 'required|integer|min:1|max:60',
            'pattern_type' => 'required|in:random,linear,cyclical',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric|gt:min_value',
            'thresholds' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $settings = $sensor->simulationSetting()->updateOrCreate(
            ['sensor_id' => $sensor->id],
            $validated
        );

        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }

    public function toggleSimulation(Sensor $sensor)
    {
        $settings = $sensor->simulationSetting;
        $settings->is_active = !$settings->is_active;
        $settings->save();

        return response()->json([
            'success' => true,
            'is_active' => $settings->is_active
        ]);
    }

    public function getSimulationLogs(Sensor $sensor)
    {
        $logs = $sensor->simulationLogs()
                      ->latest()
                      ->take(100)
                      ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    private function generatePatternData($pattern, $min, $max, $duration)
    {
        $timestamp = now();
        $data = [];

        switch ($pattern) {
            case 'linear':
                $data = $this->generateLinearPattern($min, $max, $duration, $timestamp);
                break;
            case 'cyclical':
                $data = $this->generateCyclicalPattern($min, $max, $duration, $timestamp);
                break;
            default:
                $data = $this->generateRandomPattern($min, $max, $duration, $timestamp);
        }

        return $data;
    }

    private function generateRandomPattern($min, $max, $duration, $timestamp)
    {
        $data = [];
        for ($i = 0; $i < $duration; $i++) {
            $value = rand($min * 100, $max * 100) / 100; // For 2 decimal precision
            $data[] = $this->createDataPoint($value, $timestamp->copy()->addSeconds($i));
        }
        return $data;
    }

    private function generateLinearPattern($min, $max, $duration, $timestamp)
    {
        $data = [];
        $step = ($max - $min) / ($duration - 1);
        
        for ($i = 0; $i < $duration; $i++) {
            $value = $min + ($step * $i);
            // Add small random variation
            $variation = (rand(-5, 5) / 100) * $value; // ±5% variation
            $value += $variation;
            
            $data[] = $this->createDataPoint($value, $timestamp->copy()->addSeconds($i));
        }
        return $data;
    }

    private function generateCyclicalPattern($min, $max, $duration, $timestamp)
    {
        $data = [];
        $amplitude = ($max - $min) / 2;
        $center = $min + $amplitude;
        
        for ($i = 0; $i < $duration; $i++) {
            // Generate sine wave pattern
            $value = $center + $amplitude * sin(2 * pi() * $i / ($duration / 2));
            // Add small random variation
            $variation = (rand(-2, 2) / 100) * $value; // ±2% variation
            $value += $variation;
            
            $data[] = $this->createDataPoint($value, $timestamp->copy()->addSeconds($i));
        }
        return $data;
    }

    private function createDataPoint($value, $timestamp)
    {
        return [
            'timestamp' => $timestamp->format('Y-m-d H:i:s'),
            'value' => round($value, 2),
        ];
    }

    private function getDefaultMinValue($sensorType)
    {
        return match($sensorType) {
            'co2' => 350,  // Minimum CO2 level (ppm)
            'no2' => 10,   // Minimum NO2 level (ppb)
            'pm25' => 0,   // Minimum PM2.5 level (µg/m³)
            default => 0
        };
    }

    private function getDefaultMaxValue($sensorType)
    {
        return match($sensorType) {
            'co2' => 2000,  // Maximum CO2 level (ppm)
            'no2' => 100,   // Maximum NO2 level (ppb)
            'pm25' => 500,  // Maximum PM2.5 level (µg/m³)
            default => 100
        };
    }

    private function getSensorUnit($type)
    {
        return match($type) {
            'co2' => 'ppm',
            'no2' => 'ppb',
            'pm25' => 'µg/m³',
            default => 'units'
        };
    }
}
