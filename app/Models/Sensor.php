<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'name',
        'location',
        'type',
        'threshold_value',
        'start_date',
        'notes',
        'status',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'start_date' => 'date',
        'threshold_value' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function simulationSetting()
    {
        return $this->hasOne(SimulationSetting::class);
    }
}
