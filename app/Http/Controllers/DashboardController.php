<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\CustomAlert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function getSensors()
    {
        $sensors = Sensor::where('status', 'active')
            ->get()
            ->map(function ($sensor) {
                $readings = Cache::get("sensor_{$sensor->id}_readings", []);
                $lastReading = end($readings) ?: ['value' => 0];
                
                // Calculate trend based on last few readings
                $trend = $this->calculateTrend($readings);
                
                return [
                    'id' => $sensor->id, // Changed from sensor_id to id
                    'name' => $sensor->name,
                    'location' => $sensor->location,
                    'lat' => $sensor->latitude,
                    'lng' => $sensor->longitude,
                    'aqi' => $lastReading['value'],
                    'status' => $this->getAqiStatus($lastReading['value']),
                    'trend' => $trend
                ];
            });

        return response()->json($sensors);
    }

    public function getSensorReadings(Sensor $sensor)
    {
        // Get cached readings
        $readings = Cache::get("sensor_{$sensor->id}_readings", []);
        
        // If no readings in cache, generate some sample historical data
        if (empty($readings)) {
            $readings = $this->generateHistoricalData($sensor);
            Cache::put("sensor_{$sensor->id}_readings", $readings, now()->addHours(1));
        }
        
        return response()->json($readings);
    }

    private function generateHistoricalData($sensor)
    {
        $readings = [];
        $now = now();
        $baseValue = $sensor->type === 'co2' ? 400 : ($sensor->type === 'no2' ? 20 : 30);
        
        // Generate last 30 days of data
        for ($i = 30 * 24; $i >= 0; $i--) {
            // Add some variation to the base value
            $variation = sin($i / 12) * 10 + (mt_rand(-5, 5));
            $value = max(0, $baseValue + $variation);
            
            $readings[] = [
                'value' => round($value, 2),
                'timestamp' => $now->copy()->subHours($i)->format('Y-m-d H:i:s')
            ];
        }
        
        return $readings;
    }

    public function getAlerts()
    {
        $alerts = [];
        
        // Get sensor-based alerts
        $sensors = Sensor::where('status', 'active')->get();
        foreach ($sensors as $sensor) {
            $readings = Cache::get("sensor_{$sensor->id}_readings", []);
            $lastReading = end($readings);
            
            if ($lastReading && $lastReading['value'] > $sensor->threshold_value) {
                $alerts[] = [
                    'id' => 'sensor_' . $sensor->id,
                    'sensor_id' => $sensor->sensor_id,
                    'sensor_name' => $sensor->name,
                    'value' => $lastReading['value'],
                    'threshold' => $sensor->threshold_value,
                    'timestamp' => $lastReading['timestamp'],
                    'type' => $lastReading['value'] > $sensor->threshold_value * 1.5 ? 'critical' : 'warning'
                ];
            }
        }

        // Get custom alerts
        $customAlerts = CustomAlert::where('is_active', true)
            ->where(function($query) {
                $query->where('target_type', 'all')
                    ->orWhere(function($q) {
                        $q->where('target_type', 'area')
                            ->whereIn('area_id', auth()->user()->alert_preferences['monitored_sensors'] ?? []);
                    });
            })
            ->get()
            ->map(function($alert) {
                return [
                    'id' => 'custom_' . $alert->id,
                    'message' => $alert->message,
                    'type' => $alert->type,
                    'timestamp' => $alert->created_at->toIso8601String(),
                    'location' => $alert->sensor ? $alert->sensor->location : null
                ];
            });

        // Merge and sort all alerts
        $allAlerts = collect(array_merge($alerts, $customAlerts->toArray()))
            ->sortByDesc('timestamp')
            ->values()
            ->all();

        return response()->json($allAlerts);
    }

    public function getSimulationHistory()
    {
        $history = \App\Models\SimulationData::with('sensor')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'sensor_name' => $record->sensor->name,
                    'pattern_type' => $record->pattern_type,
                    'duration' => $record->duration,
                    'readings' => $record->readings,
                    'created_at' => $record->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    public function getSimulationRecord($id)
    {
        try {
            $record = \App\Models\SimulationData::with('sensor')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'record' => [
                    'id' => $record->id,
                    'sensor_name' => $record->sensor->name,
                    'pattern_type' => $record->pattern_type,
                    'duration' => $record->duration,
                    'readings' => $record->readings,
                    'created_at' => $record->created_at
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching simulation record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch simulation record'
            ], 500);
        }
    }

    private function calculateTrend($readings)
    {
        if (count($readings) < 2) return 'stable';
        
        $last = end($readings);
        $prev = prev($readings);
        
        if (!$last || !$prev) return 'stable';
        
        $diff = $last['value'] - $prev['value'];
        
        if ($diff > 5) return 'increasing';
        if ($diff < -5) return 'decreasing';
        return 'stable';
    }

    private function getAqiStatus($value)
    {
        if ($value <= 50) return 'good';
        if ($value <= 100) return 'moderate';
        if ($value <= 150) return 'unhealthy-sensitive';
        return 'unhealthy';
    }

    private function extractLatitude($location)
    {
        // In a real application, you would store lat/lng in the database
        // This is a simplified version that returns hardcoded values for Colombo area
        $locations = [
            'Fort' => 6.9271,
            'Pettah' => 6.9344,
            'Slave Island' => 6.9261,
            'Kollupitiya' => 6.9174,
            'Bambalapitiya' => 6.8913,
            // Add more mappings as needed
        ];
        
        return $locations[$location] ?? 6.9271; // Default to Fort coordinates
    }

    private function extractLongitude($location)
    {
        $locations = [
            'Fort' => 79.8612,
            'Pettah' => 79.8528,
            'Slave Island' => 79.8473,
            'Kollupitiya' => 79.8483,
            'Bambalapitiya' => 79.8567,
            // Add more mappings as needed
        ];
        
        return $locations[$location] ?? 79.8612; // Default to Fort coordinates
    }
}
