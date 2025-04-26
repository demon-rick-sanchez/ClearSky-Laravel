<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\SimulationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SimulationController extends Controller
{
    public function index()
    {
        $sensors = Sensor::where('status', 'active')->get();
        return view('admin.simulation', compact('sensors'));
    }

    public function generateData(Request $request)
    {
        try {
            $sensor = Sensor::findOrFail($request->sensor_id);
            $settings = $sensor->simulationSetting;
            
            if (!$settings) {
                return response()->json(['success' => false, 'message' => 'Simulation settings not found'], 400);
            }

            $data = $this->generatePatternData(
                $settings->pattern_type,
                $settings->min_value ?? $this->getDefaultMinValue($sensor->type),
                $settings->max_value ?? $this->getDefaultMaxValue($sensor->type),
                $request->duration ?? 60
            );

            $cacheKey = "sensor_{$sensor->id}_readings";
            $existingReadings = Cache::get($cacheKey, []);
            $allReadings = array_merge($existingReadings, $data);
            $allReadings = array_slice($allReadings, -100);
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
        } catch (\Exception $e) {
            Log::error('Error generating simulation data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate simulation data'
            ], 500);
        }
    }

    public function updateSettings(Request $request, Sensor $sensor)
    {
        try {
            $validated = $request->validate([
                'frequency' => 'required|integer|min:1|max:60',
                'pattern_type' => 'required|in:random,linear,cyclical',
                'min_value' => 'required|numeric',
                'max_value' => 'required|numeric|gt:min_value',
                'thresholds' => 'required|array',
                'thresholds.warning' => 'required|numeric|gt:min_value',
                'thresholds.critical' => 'required|numeric|gt:thresholds.warning'
            ]);

            $settings = $sensor->simulationSetting()->updateOrCreate(
                ['sensor_id' => $sensor->id],
                $validated
            );

            return response()->json([
                'success' => true,
                'settings' => $settings->fresh()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in updateSettings: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating simulation settings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update simulation settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleSimulation(Sensor $sensor)
    {
        try {
            $settings = $sensor->simulationSetting;
            if (!$settings) {
                // Create default settings if none exist
                $settings = $sensor->simulationSetting()->create([
                    'frequency' => 5,
                    'pattern_type' => 'random',
                    'min_value' => $this->getDefaultMinValue($sensor->type),
                    'max_value' => $this->getDefaultMaxValue($sensor->type),
                    'thresholds' => [
                        'warning' => $sensor->threshold_value,
                        'critical' => $sensor->threshold_value * 1.5
                    ],
                    'is_active' => false
                ]);
            }

            $settings->is_active = !$settings->is_active;
            $settings->save();

            if ($settings->is_active) {
                dispatch(new \App\Jobs\ProcessSensorSimulation($sensor->id, [
                    'frequency' => $settings->frequency,
                    'pattern_type' => $settings->pattern_type,
                    'min_value' => $settings->min_value,
                    'max_value' => $settings->max_value,
                    'thresholds' => $settings->thresholds
                ]));
            }

            return response()->json([
                'success' => true,
                'is_active' => $settings->is_active,
                'settings' => $settings->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling simulation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle simulation: ' . $e->getMessage()
            ], 500);
        }
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

    public function getSimulationSettings(Sensor $sensor)
    {
        $settings = $sensor->simulationSetting;
        
        if (!$settings) {
            $settings = [
                'frequency' => 5,
                'pattern_type' => 'random',
                'min_value' => $this->getDefaultMinValue($sensor->type),
                'max_value' => $this->getDefaultMaxValue($sensor->type),
                'thresholds' => [
                    'warning' => $sensor->threshold_value,
                    'critical' => $sensor->threshold_value * 1.5
                ],
                'is_active' => false
            ];
        }

        return response()->json([
            'success' => true,
            'settings' => $settings
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
