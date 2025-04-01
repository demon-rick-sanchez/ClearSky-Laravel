<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    public function run(): void
    {
        $sensors = [
            [
                'sensor_id' => 'SNR-A12B34',
                'name' => 'CO2 Monitor Lab 1',
                'location' => 'Research Lab 101',
                'type' => 'co2',
                'threshold_value' => 1000,
                'start_date' => '2024-01-15',
                'notes' => 'Primary CO2 monitor for main research lab',
                'status' => 'active',
            ],
            [
                'sensor_id' => 'SNR-B56C78',
                'name' => 'NO2 Sensor Zone A',
                'location' => 'Manufacturing Area',
                'type' => 'no2',
                'threshold_value' => 50,
                'start_date' => '2024-02-01',
                'notes' => 'Monitoring NO2 levels in production zone',
                'status' => 'active',
            ],
            [
                'sensor_id' => 'SNR-D90E12',
                'name' => 'PM2.5 Monitor',
                'location' => 'Office Space',
                'type' => 'pm25',
                'threshold_value' => 35,
                'start_date' => '2024-03-01',
                'notes' => 'Air quality monitoring for workspace',
                'status' => 'active',
            ],
        ];

        foreach ($sensors as $sensor) {
            Sensor::create($sensor);
        }
    }
}
